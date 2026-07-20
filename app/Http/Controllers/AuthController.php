<?php

namespace App\Http\Controllers;

use App\Http\Requests\Guest\LoginRequest;
use App\Http\Requests\Guest\RegisterRequest;
use App\Http\Requests\Guest\UpdateRequest;
use App\Models\Guest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'redirect' => url()->previous() ?: url('/'),
        ]);
    }    

    public function register(RegisterRequest $request)
    {
        Guest::create([
            'name' => $request->input('name'),
            'identification_number' => $request->input('identification_number'),
            'email' => $request->input('email'),
            'nation_id' => $request->input('nation_id'),
            'password' => bcrypt($request->input('password')),
        ]);

        // Redirect to the intended page or home page
        return response()->json([
            'success' => true,
            'redirect' => url()->previous() ?: url('/'),
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => __('validation.fields.email.required'),
            'email.email' => __('validation.fields.email.email'),
        ]);

        $status = Password::broker('guests')->sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => __($status),
            ]);
        }

        return response()->json([
            'success' => false,
            'errors' => [
                'email' => [__($status)],
            ],
        ], 422);
    }

    public function showResetPasswordForm(Request $request, string $token)
    {
        return view('frontend.auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.required' => __('validation.fields.email.required'),
            'email.email' => __('validation.fields.email.email'),
            'password.required' => __('validation.fields.password.required'),
            'password.string' => __('validation.fields.password.string'),
            'password.min' => __('validation.fields.password.min', ['min' => 6]),
            'password.confirmed' => __('validation.fields.password.confirmed'),
        ]);

        $status = Password::broker('guests')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Guest $guest, string $password) {
                $guest->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('home_page')
                ->with('success', __($status));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }

    public function logout(Request $request)
    {
        Auth::guard('guest')->logout();
        $request->session()->forget('guest');
        $request->session()->regenerateToken();
        return redirect()->intended(url()->previous() ?: '/');
    }

    public function updateAccount(UpdateRequest $request)
    {
        $guest = Auth::guard('guest')->user();
        // Update guest details
        $guest->name = $request->input('name');
        if($request->filled('identification_number')) {
            $guest->identification_number = $request->input('identification_number');
        }
        $guest->email = $request->input('email');
        $guest->nation_id = $request->input('nation_id');
        $guest->phone = $request->input('phone');
        $guest->address = $request->input('address');
        if($request->filled('password')) {
            $guest->password = Hash::make($request->input('password'));
        }
        if ($request->filled('avatar')) {
            $avatarData = $request->input('avatar');
    
            // format: "data:image/png;base64,xxxx"
            if (preg_match('/^data:image\/(\w+);base64,/', $avatarData, $type)) {
                $avatarData = substr($avatarData, strpos($avatarData, ',') + 1);
                $type = strtolower($type[1]); // png, jpg, jpeg
    
                $avatarData = base64_decode($avatarData);
                if ($avatarData === false) {
                    return back()->withErrors(['avatar' => 'Invalid image data']);
                }
    
                // Xóa avatar cũ
                if ($guest->avatar && Storage::disk('public')->exists($guest->avatar)) {
                    Storage::disk('public')->delete($guest->avatar);
                }
    
                $fileName = 'avatars/' . uniqid() . '.' . $type;
                Storage::disk('public')->put($fileName, $avatarData);
                $guest->avatar = $fileName;
            }
        }
        $guest->save();

        return redirect()->back()->with('success', 'Chỉnh sửa thông tin thành công');
    }
    public function redirectToGoogle()
    {
        $host = request()->getHost();
        $redirectUrl = ($host === 'localhost') 
            ? env('APP_URL') . ':' . env('APP_PORT', 80) . '/guest/auth/google/callback'
            : 'https://' . $host . '/guest/auth/google/callback';

        return Socialite::driver('google')->redirectUrl($redirectUrl)->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $host = request()->getHost();
            $redirectUrl = ($host === 'localhost') 
                ? env('APP_URL') . ':' . env('APP_PORT', 80) . '/guest/auth/google/callback'
                : 'https://' . $host . '/guest/auth/google/callback';
            $googleUser = Socialite::driver('google')->redirectUrl($redirectUrl)->user();
    
            $avatarPath = null;
    
            // Nếu Google có avatar thì tải về
            if ($googleUser->getAvatar()) {
                try {
                    $avatarUrl = $googleUser->getAvatar();
                    // Sử dụng Http client với timeout 5s để tránh treo web nếu máy chủ chặn tải file
                    $response = \Illuminate\Support\Facades\Http::timeout(5)->get($avatarUrl);
                    if ($response->successful()) {
                        $fileName = 'avatars/' . uniqid() . '.jpg';
                        Storage::disk('public')->put($fileName, $response->body());
                        $avatarPath = $fileName;
                    }
                } catch (\Exception $avatarEx) {
                    // Nếu không tải được ảnh (do tường lửa server chặn, mạng lỗi,...), ghi log và bỏ qua, vẫn cho phép đăng nhập
                    \Illuminate\Support\Facades\Log::warning('Không thể tải avatar từ Google: ' . $avatarEx->getMessage());
                }
            }
    
            $guest = Guest::updateOrCreate(
                [ 'email' => $googleUser->getEmail() ], // tìm theo email
                [
                    'name'     => $googleUser->getName(),
                    'avatar'   => $avatarPath,
                    'password' => bcrypt(str()->random(16)),
                ]
            );
    
            if (!$guest->avatar && $avatarPath) {
                $guest->avatar = $avatarPath;
                $guest->save();
            }
    
            Auth::guard('guest')->login($guest, true);
    
            return redirect()->intended('/');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Login Error: ' . $e->getMessage() . ' - File: ' . $e->getFile() . ' - Line: ' . $e->getLine());
            return redirect()->route('home_page')->withErrors(['msg' => 'Đăng nhập Google thất bại.']);
        }
    }
}
