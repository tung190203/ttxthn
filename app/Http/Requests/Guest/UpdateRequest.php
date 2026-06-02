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
            'email' => 'required|email:rfc,dns|unique:guests,email,' . $guestId,
            'nation_id' => 'nullable|exists:nations,id',
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.fields.name.required'),
            'name.string' => __('validation.fields.name.string'),
            'name.max' => __('validation.fields.name.max'),
    
            'email.required' => __('validation.fields.email.required'),
            'email.email' => __('validation.fields.email.email'),
            'email.unique' => __('validation.fields.email.unique'),
    
            'password.required' => __('validation.fields.password.required'),
            'password.string' => __('validation.fields.password.string'),
            'password.min' => __('validation.fields.password.min'),
            'password.confirmed' => __('validation.fields.password.confirmed'),
    
            'identification_number.required' => __('validation.fields.identification_number.required'),
            'identification_number.string' => __('validation.fields.identification_number.string'),
            'identification_number.max' => __('validation.fields.identification_number.max'),
            'identification_number.unique' => __('validation.fields.identification_number.unique'),
    
            'nation_id.required' => __('validation.fields.nation_id.required'),
            'nation_id.exists' => __('validation.fields.nation_id.exists'),
    
            'phone.numeric' => __('validation.fields.phone.numeric'),
            'phone.max' => __('validation.fields.phone.max'),
    
            'address.string' => __('validation.fields.address.string'),
            'address.max' => __('validation.fields.address.max'),
    
            'avatar.string' => __('validation.fields.avatar.string'),
        ];
    }    
}
