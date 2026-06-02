<?php

return [
    'accepted' => 'The :attribute field must be accepted.',
    'array' => 'The :attribute field must be an array.',
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'email' => 'The :attribute field must be a valid email address.',
    'exists' => 'The selected :attribute is invalid.',
    'integer' => 'The :attribute field must be an integer.',
    'max' => [
        'numeric' => 'The :attribute field must not be greater than :max.',
        'string' => 'The :attribute field must not be greater than :max characters.',
        'array' => 'The :attribute field must not have more than :max items.',
    ],
    'min' => [
        'numeric' => 'The :attribute field must be at least :min.',
        'string' => 'The :attribute field must be at least :min characters.',
        'array' => 'The :attribute field must have at least :min items.',
    ],
    'numeric' => 'The :attribute field must be a number.',
    'required' => 'The :attribute field is required.',
    'string' => 'The :attribute field must be a string.',
    'unique' => 'The :attribute has already been taken.',
    'url' => 'The :attribute field must be a valid URL.',

    'fields' => [
        'name' => [
            'required' => 'Please enter your full name',
            'string' => 'Full name is invalid',
            'max' => 'Full name must not exceed 255 characters',
        ],
        'email' => [
            'required' => 'Please enter your email',
            'email' => 'Email is not valid',
            'unique' => 'This email has already been taken',
        ],
        'password' => [
            'required' => 'Password is required',
            'string' => 'Password is invalid',
            'min' => 'Password must be at least :min characters',
            'confirmed' => 'Password confirmation does not match',
        ],
        'identification_number' => [
            'required' => 'Please enter your VNeID/Passport number',
            'string' => 'VNeID / Passport number is invalid',
            'regex' => 'VNeID / Passport number must contain only numbers',
            'min' => 'VNeID / Passport number must be at least :min characters',
            'max' => 'VNeID / Passport number must not exceed 20 characters',
            'unique' => 'This VNeID / Passport number has already been used',
        ],
        'nation_id' => [
            'required' => 'Please select your nationality',
            'exists' => 'Selected nationality is invalid',
        ],
        'phone' => [
            'numeric' => 'Phone number is invalid',
            'max' => 'Phone number must not exceed 15 characters',
        ],
        'address' => [
            'string' => 'Address is invalid',
            'max' => 'Address must not exceed 255 characters',
        ],
        'avatar' => [
            'string' => 'Avatar is invalid',
        ],
    ],
];
