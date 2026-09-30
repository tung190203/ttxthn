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
            'email' => 'required|email|unique:guests,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'investor_type' => 'required|in:individual,organization,fdi',
            'interested_sectors' => 'nullable|array',
            'organization_name' => 'required_if:investor_type,organization,fdi|nullable|string|max:255',
            'tax_code' => 'required_if:investor_type,organization,fdi|nullable|string|max:50',
            'identification_number' => 'nullable|max:20|min:6',
            'nation_id' => 'nullable|exists:nations,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.fields.name.required') ?: 'Vui lòng nhập họ tên.',
            'name.string' => __('validation.fields.name.string'),
            'name.max' => __('validation.fields.name.max'),
            'email.required' => __('validation.fields.email.required') ?: 'Vui lòng nhập địa chỉ email.',
            'email.email' => __('validation.fields.email.email') ?: 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Địa chỉ thư điện tử này đã được đăng ký.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'password.required' => __('validation.fields.password.required') ?: 'Vui lòng nhập mật khẩu.',
            'password.string' => __('validation.fields.password.string'),
            'password.min' => __('validation.fields.password.min', ['min' => 6]) ?: 'Mật khẩu phải có ít nhất 6 ký tự.',
            'investor_type.required' => 'Vui lòng chọn loại nhà đầu tư.',
            'investor_type.in' => 'Loại nhà đầu tư không hợp lệ.',
            'organization_name.required_if' => 'Vui lòng nhập tên tổ chức/doanh nghiệp.',
            'tax_code.required_if' => 'Vui lòng nhập mã số thuế.',
        ];
    }
}
