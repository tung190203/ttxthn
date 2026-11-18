<?php

namespace App\Http\Controllers;

use App\Http\Requests\Guest\LoginRequest;
use App\Http\Requests\Guest\RegisterRequest;
use App\Http\Requests\Guest\UpdateRequest;
use App\Mail\AccountLockedAlertMail;
use App\Mail\VerifyEmailMail;
use App\Models\Guest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

        $guest = Auth::guard('guest')->user();
        $isFdi = $guest && $guest->investor_type === 'fdi';

        $previousUrl = url()->previous() ?: url('/');
        $redirectUrl = $this->getRedirectUrlForGuest($previousUrl, $guest);

        return response()->json([
            'success' => true,
            'message' => $isFdi ? 'Logged in successfully!' : 'Đăng nhập thành công!',
            'redirect' => $redirectUrl,
        ]);
    }

    /**
     * Tự động chuyển URL sang /en nếu NĐT là tài khoản FDI và đang ở trang tiếng Việt
     */
    protected function getRedirectUrlForGuest(?string $targetUrl, ?Guest $guest): string
    {
        $targetUrl = $targetUrl ?: url('/');

        // Tránh chuyển hướng ngược lại các trang auth/login
        if (str_contains($targetUrl, '/auth/login') || str_contains($targetUrl, '/guest/login')) {
            $targetUrl = url('/');
        }

        if ($guest && $guest->investor_type === 'fdi') {
            $parsed = parse_url($targetUrl);
            $path = $parsed['path'] ?? '/';
            $path = trim($path, '/');

            $availableLocales = config('app.available_locales', ['en', 'vn', 'vi']);

            $segments = $path !== '' ? explode('/', $path) : [];
            $firstSegment = $segments[0] ?? '';

            // Nếu đã ở tiếng Anh (/en) thì giữ nguyên
            if ($firstSegment === 'en') {
                return $targetUrl;
            }

            // Nếu URL đang có tiền tố ngôn ngữ khác (vn, vi) thì loại bỏ
            if (in_array($firstSegment, $availableLocales, true)) {
                array_shift($segments);
            }

            // Đưa tiền tố 'en' lên đầu
            array_unshift($segments, 'en');
            $newPath = '/' . implode('/', $segments);

            $scheme = isset($parsed['scheme']) ? $parsed['scheme'] . '://' : '';
            $host = $parsed['host'] ?? '';
            $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';
            $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
            $fragment = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';

            if ($host) {
                return $scheme . $host . $port . $newPath . $query . $fragment;
            }

            return url($newPath) . $query . $fragment;
        }

        return $targetUrl;
    }    

    public function register(RegisterRequest $request)
    {
        $verificationToken = Str::random(64);

        $guest = Guest::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => bcrypt($request->input('password')),
            'investor_type' => $request->input('investor_type', 'individual'),
            'interested_sectors' => $request->input('interested_sectors', []),
            'organization_name' => $request->input('organization_name'),
            'tax_code' => $request->input('tax_code'),
            'identification_number' => $request->input('identification_number'),
            'nation_id' => $request->input('nation_id'),
            'status' => 'pending',
            'verification_token' => $verificationToken,
            'email_verified_at' => null,
            'failed_login_attempts' => 0,
        ]);

        // Gửi email xác thực
        $verifyUrl = route('auth_verify_email', [
            'id' => $guest->id,
            'token' => $verificationToken,
        ]);

        try {
            Mail::to($guest->email)->send(new VerifyEmailMail($guest, $verifyUrl));
        } catch (\Exception $e) {
            Log::error('Gửi email xác thực đăng ký thất bại: ' . $e->getMessage());
        }

        $isOrg = in_array($guest->investor_type, ['organization', 'fdi']);
        $message = 'Đăng ký thành công! Vui lòng kiểm tra hộp thư email (' . $guest->email . ') để xác thực và kích hoạt tài khoản.';
        if ($isOrg) {
            $message .= ' Sau khi xác thực email, tài khoản sẽ ở trạng thái "Chờ xác thực" và cần Quản trị hệ thống phê duyệt.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'require_verification' => true,
            'email' => $guest->email,
        ]);
    }

    public function verifyEmail(Request $request, $id, $token)
    {
        $guest = Guest::where('id', $id)->first();

        if (!$guest || empty($guest->verification_token) || !hash_equals($guest->verification_token, (string)$token)) {
            return redirect()->route('home_page')->withErrors([
                'msg' => 'Liên kết đã hết hiệu lực. Vui lòng yêu cầu gửi lại.',
            ]);
        }

        $guest->email_verified_at = now();
        $guest->verification_token = null;

        if ($guest->investor_type === 'individual') {
            $guest->status = 'active';
            $msg = 'Xác thực email thành công! Tài khoản cá nhân của bạn đã được kích hoạt. Hãy đăng nhập để tiếp tục.';
        } else {
            $guest->status = 'pending';
            $typeLabel = $guest->investor_type === 'fdi' ? 'FDI' : 'Tổ chức';
            $msg = "Xác thực email thành công! Tài khoản {$typeLabel} của bạn hiện ở trạng thái \"Chờ xác thực\" và đang chờ Quản trị hệ thống phê duyệt.";
        }

        $guest->save();

        return redirect()->route('home_page')->with('success', $msg);
    }

    public function verifyEmailApi(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'token' => 'required|string',
        ]);

        $guest = Guest::where('id', $request->input('id'))->first();

        if (!$guest || empty($guest->verification_token) || !hash_equals($guest->verification_token, (string)$request->input('token'))) {
            return response()->json([
                'success' => false,
                'message' => 'Liên kết đã hết hiệu lực. Vui lòng yêu cầu gửi lại.',
            ], 422);
        }

        $guest->email_verified_at = now();
        $guest->verification_token = null;

        if ($guest->investor_type === 'individual') {
            $guest->status = 'active';
            $msg = 'Xác thực email thành công! Tài khoản cá nhân của bạn đã hoạt động.';
        } else {
            $guest->status = 'pending';
            $msg = 'Xác thực email thành công! Tài khoản đang ở trạng thái "Chờ xác thực" và chờ Quản trị hệ thống phê duyệt.';
        }

        $guest->save();

        return response()->json([
            'success' => true,
            'message' => $msg,
            'status' => $guest->status,
        ]);
    }

    public function resendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:guests,email',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.exists' => 'Không tìm thấy tài khoản với email này.',
        ]);

        $guest = Guest::where('email', $request->input('email'))->first();

        if ($guest->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Địa chỉ email này đã được xác thực trước đó. Vui lòng đăng nhập.',
            ], 422);
        }

        $token = Str::random(64);
        $guest->verification_token = $token;
        $guest->save();

        $verifyUrl = route('auth_verify_email', ['id' => $guest->id, 'token' => $token]);

        try {
            Mail::to($guest->email)->send(new VerifyEmailMail($guest, $verifyUrl));
        } catch (\Exception $e) {
            Log::error('Gửi lại email xác thực thất bại: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã gửi lại liên kết xác thực tới email của bạn. Vui lòng kiểm tra hộp thư.',
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => __('validation.fields.email.required') ?: 'Vui lòng nhập địa chỉ email.',
            'email.email' => __('validation.fields.email.email') ?: 'Địa chỉ email không hợp lệ.',
        ]);

        $status = Password::broker('guests')->sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn và có hiệu lực trong 24 giờ.',
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
        $guest->organization_name = $request->input('organization_name');
        $guest->tax_code = $request->input('tax_code');
        $guest->interested_sectors = $request->input('interested_sectors', []);
        
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
                    $response = \Illuminate\Support\Facades\Http::timeout(5)->get($avatarUrl);
                    if ($response->successful()) {
                        $fileName = 'avatars/' . uniqid() . '.jpg';
                        Storage::disk('public')->put($fileName, $response->body());
                        $avatarPath = $fileName;
                    }
                } catch (\Exception $avatarEx) {
                    \Illuminate\Support\Facades\Log::warning('Không thể tải avatar từ Google: ' . $avatarEx->getMessage());
                }
            }
    
            $guest = Guest::where('email', $googleUser->getEmail())->first();

            if (!$guest) {
                // Đăng ký mới qua Google: yêu cầu xác thực email theo đặc tả
                $token = Str::random(64);
                $guest = Guest::create([
                    'email'    => $googleUser->getEmail(),
                    'name'     => $googleUser->getName(),
                    'avatar'   => $avatarPath,
                    'password' => bcrypt(Str::random(16)),
                    'investor_type' => 'individual',
                    'status'   => 'pending',
                    'verification_token' => $token,
                    'email_verified_at' => null,
                    'failed_login_attempts' => 0,
                ]);

                // Gửi email xác thực
                $verifyUrl = route('auth_verify_email', ['id' => $guest->id, 'token' => $token]);
                try {
                    Mail::to($guest->email)->send(new VerifyEmailMail($guest, $verifyUrl));
                } catch (\Exception $ex) {
                    Log::error('Gửi email xác thực Google thất bại: ' . $ex->getMessage());
                }

                return redirect()->route('home_page')->with('success', 'Đăng ký tài khoản qua Google thành công! Vui lòng kiểm tra hộp thư email (' . $guest->email . ') để xác thực trước khi đăng nhập.');
            }

            // Tài khoản đã tồn tại: kiểm tra xác thực email
            if (!$guest->email_verified_at) {
                return redirect()->route('home_page')->withErrors(['msg' => 'Địa chỉ email này chưa được xác thực. Vui lòng kiểm tra hộp thư để kích hoạt tài khoản.']);
            }

            // Kiểm tra trạng thái khóa
            if ($guest->status === 'locked' || ($guest->locked_until && $guest->locked_until->isFuture())) {
                return redirect()->route('home_page')->withErrors(['msg' => 'Tài khoản tạm khóa do phát hiện đăng nhập bất thường. Liên hệ quản trị viên.']);
            }

            // Kiểm tra trạng thái Chờ xác thực đối với tổ chức/FDI
            if ($guest->status === 'pending') {
                return redirect()->route('home_page')->withErrors(['msg' => 'Tài khoản của bạn đang ở trạng thái "Chờ xác thực". Vui lòng chờ Quản trị hệ thống phê duyệt.']);
            }

            // Kiểm tra IP bất thường
            $currentIp = request()->ip();
            if ($guest->last_login_ip && $guest->last_login_ip !== $currentIp) {
                $guest->update([
                    'status' => 'locked',
                    'locked_until' => now()->addDays(7),
                ]);

                try {
                    $adminEmail = env('ADMIN_NOTIFICATION_EMAIL', 'ducnh241@gmail.com');
                    Mail::to($adminEmail)->send(new AccountLockedAlertMail($guest, "Đăng nhập Google từ IP lạ: {$currentIp} (Địa chỉ IP trước đó: {$guest->last_login_ip})", $currentIp));
                } catch (\Exception $ex) {
                    Log::error('Gửi cảnh báo Google IP lạ thất bại: ' . $ex->getMessage());
                }

                return redirect()->route('home_page')->withErrors(['msg' => 'Tài khoản tạm khóa do phát hiện đăng nhập bất thường. Liên hệ quản trị viên.']);
            }

            // Đăng nhập thành công
            $guest->last_login_ip = $currentIp;
            $guest->failed_login_attempts = 0;
            if (!$guest->avatar && $avatarPath) {
                $guest->avatar = $avatarPath;
            }
            $guest->save();
    
            Auth::guard('guest')->login($guest, true);
    
            $targetUrl = redirect()->intended('/')->getTargetUrl();
            return redirect($this->getRedirectUrlForGuest($targetUrl, $guest));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Login Error: ' . $e->getMessage() . ' - File: ' . $e->getFile() . ' - Line: ' . $e->getLine());
            return redirect()->route('home_page')->withErrors(['msg' => 'Đăng nhập Google thất bại.']);
        }
    }
}
