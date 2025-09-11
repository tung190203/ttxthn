<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class BaseLoginController extends Controller
{
    public function showLogin()
    {
        return view('base-login');
    }

//     public function checkLogin(Request $request)
// {
//     $request->validate([
//         'password' => 'required'
//     ]);

//     $fixedPassword = env('BASE_LOGIN_PASSWORD');

//     if ($request->password === $fixedPassword) {
//         Session::put('base_logged_in', true);
//         $redirectUrl = Session::pull('redirect_after_login', '/');
//         return redirect($redirectUrl);
//     }

//     return back()->withErrors(['password' => 'Sai mật khẩu']);
// }


    public function checkLogin(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        $currentPassword = Cache::get('base_login_password');

        if ($request->password === $currentPassword) {
            // login thành công
            Session::put('base_logged_in', true);

            // xoá mật khẩu cũ để bắt buộc phải tạo mới
            Cache::forget('base_login_password');
            $redirectUrl = Session::pull('redirect_after_login', '/');

            return redirect($redirectUrl);
        }

        return back()->withErrors(['password' => 'Sai mật khẩu']);
    }

    public function generatePassword()
    {
        $currentPassword = Cache::get('base_login_password');
    
        if ($currentPassword) {
            $newPassword = $currentPassword;
        } else {
            $newPassword = Str::random(12);
            Cache::put('base_login_password', $newPassword, now()->addDays(3));
        }
    
        return view('show-password', ['password' => $newPassword]);
    }
}
