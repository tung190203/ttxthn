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
            'identification_number' => 'required|max:20|min:6|unique:guests,identification_number',
            'email' => 'required|email|unique:guests,email',
            'nation_id' => 'required|exists:nations,id',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.fields.name.required'),
            'name.string' => __('validation.fields.name.string'),
            'name.max' => __('validation.fields.name.max'),
            'identification_number.required' => __('validation.fields.identification_number.required'),
            'identification_number.string' => __('validation.fields.identification_number.string'),
            'identification_number.max' => __('validation.fields.identification_number.max'),
            'identification_number.unique' => __('validation.fields.identification_number.unique'),
            'email.required' => __('validation.fields.email.required'),
            'email.email' => __('validation.fields.email.email'),
            'email.unique' => __('validation.fields.email.unique'),
            'nation_id.required' => __('validation.fields.nation_id.required'),
            'nation_id.exists' => __('validation.fields.nation_id.exists'),
            'password.required' => __('validation.fields.password.required'),
            'password.string' => __('validation.fields.password.string'),
            'password.min' => __('validation.fields.password.min'),
        ];
    }
}
