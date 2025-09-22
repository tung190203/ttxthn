<?php

namespace App\Http\Requests\Guest;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
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
            'name' => 'required|string|max:255',
            'identification_number' => 'required|string|max:20|unique:guests,identification_number',
            'email' => 'required|email|unique:guests,email',
            'nation_id' => 'required|exists:nations,id',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập họ và tên',
            'name.string' => 'Họ và tên không hợp lệ',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự',
            'identification_number.required' => 'Vui lòng nhập số VNeID/Passport',
            'identification_number.string' => 'Số VNeID / Passport không hợp lệ',
            'identification_number.max' => 'Số VNeID / Passport không được vượt quá 20 ký tự',
            'identification_number.unique' => 'Số VNeID / Passport đã được sử dụng',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'nation_id.required' => 'Vui lòng chọn quốc tịch',
            'nation_id.exists' => 'Quốc tịch không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.string' => 'Mật khẩu không hợp lệ',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ];
    }
}
