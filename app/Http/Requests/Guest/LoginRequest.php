<?php

namespace App\Http\Requests\Guest;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    private const MAX_ATTEMPTS = 5;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'remember' => 'sometimes|boolean',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('validation.fields.email.required'),
            'email.email' => __('validation.fields.email.email'),
            'password.required' => __('validation.fields.password.required'),
            'password.string' => __('validation.fields.password.string'),
            'password.min' => __('validation.fields.password.min', ['min' => 6]),
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $guest = \App\Models\Guest::where('email', $this->email)->first();

        if ($guest) {
            // Check if locked
            if ($guest->status === 'locked' || ($guest->locked_until && $guest->locked_until->isFuture())) {
                throw ValidationException::withMessages([
                    'email' => 'Tài khoản tạm khóa do phát hiện đăng nhập bất thường. Liên hệ quản trị viên.',
                ]);
            }
        }

        if (! Auth::guard('guest')->validate($this->only('email', 'password'))) {
            if ($guest) {
                $guest->increment('failed_login_attempts');
                if ($guest->failed_login_attempts >= 5) {
                    $guest->update([
                        'status' => 'locked',
                        'locked_until' => now()->addDays(7),
                    ]);

                    $this->sendAlertToAdmin($guest, 'Nhập sai mật khẩu liên tiếp từ 5 lần trở lên');

                    throw ValidationException::withMessages([
                        'email' => 'Tài khoản tạm khóa do phát hiện đăng nhập bất thường. Liên hệ quản trị viên.',
                    ]);
                }
            }

            $remaining = $guest ? max(0, 5 - $guest->failed_login_attempts) : 0;
            $msg = 'Thông tin đăng nhập không chính xác.';
            if ($guest && $remaining > 0) {
                $msg .= " Còn {$remaining} lần thử trước khi tài khoản bị khóa.";
            }

            throw ValidationException::withMessages([
                'password' => $msg,
            ]);
        }

        // Password matched! Check email verification
        if (! $guest->email_verified_at) {
            throw ValidationException::withMessages([
                'email' => 'Địa chỉ email này chưa được xác thực. Vui lòng kiểm tra hộp thư để kích hoạt tài khoản.',
            ]);
        }

        // Check status for Organization and FDI
        if ($guest->status === 'pending') {
            throw ValidationException::withMessages([
                'email' => 'Tài khoản của bạn đang ở trạng thái "Chờ xác thực". Vui lòng chờ Quản trị hệ thống phê duyệt.',
            ]);
        }

        // Check strange IP
        $currentIp = $this->ip();
        if ($guest->last_login_ip && $guest->last_login_ip !== $currentIp) {
            $guest->update([
                'status' => 'locked',
                'locked_until' => now()->addDays(7),
            ]);

            $this->sendAlertToAdmin($guest, "Phát hiện đăng nhập từ địa chỉ IP lạ: {$currentIp} (Địa chỉ IP trước đó: {$guest->last_login_ip})");

            throw ValidationException::withMessages([
                'email' => 'Tài khoản tạm khóa do phát hiện đăng nhập bất thường. Liên hệ quản trị viên.',
            ]);
        }

        // Successful login
        $guest->update([
            'failed_login_attempts' => 0,
            'last_login_ip' => $currentIp,
            'locked_until' => null,
        ]);

        Auth::guard('guest')->login($guest, $this->boolean('remember'));
    }

    protected function sendAlertToAdmin(\App\Models\Guest $guest, string $reason): void
    {
        try {
            $adminEmail = env('ADMIN_NOTIFICATION_EMAIL', 'ducnh241@gmail.com');
            \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\AccountLockedAlertMail($guest, $reason, $this->ip()));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gửi email cảnh báo khóa tài khoản lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), self::MAX_ATTEMPTS)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
