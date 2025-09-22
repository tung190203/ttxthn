<?php

namespace App\Http\Requests\Guest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // ép user() luôn lấy từ guard guest
    public function user($guard = null)
    {
        return auth('guest')->user();
    }

    public function rules(): array
    {
        $guestId = $this->user()?->id; // hoặc auth('guest')->id()

        return [
            'name' => 'required|string|max:255',
            'identification_number' => 'nullable|string|max:20|unique:guests,identification_number,' . $guestId,
            'email' => 'required|email|unique:guests,email,' . $guestId,
            'nation_id' => 'nullable|exists:nations,id',
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập họ và tên',
            'name.string' => 'Họ và tên không hợp lệ',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự',
            'identification_number.string' => 'Số VNeID / Passport không hợp lệ',
            'identification_number.max' => 'Số VNeID / Passport không được vượt quá 20 ký tự',
            'identification_number.unique' => 'Số VNeID / Passport đã được sử dụng',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'nation_id.required' => 'Vui lòng chọn quốc tịch',
            'nation_id.exists' => 'Quốc tịch không hợp lệ',
            'password.string' => 'Mật khẩu không hợp lệ',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'phone.string' => 'Số điện thoại không hợp lệ',
            'phone.max' => 'Số điện thoại không được vượt quá 15 ký tự',
            'address.string' => 'Địa chỉ không hợp lệ',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự',
            'avatar.string' => 'Avatar không hợp lệ',
        ];
    }
}
