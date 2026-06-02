<?php

return [
    'accepted' => 'Trường :attribute phải được chấp nhận.',
    'array' => 'Trường :attribute phải là một mảng.',
    'boolean' => 'Trường :attribute phải là đúng hoặc sai.',
    'confirmed' => 'Xác nhận :attribute không khớp.',
    'email' => 'Trường :attribute phải là email hợp lệ.',
    'exists' => ':attribute đã chọn không hợp lệ.',
    'integer' => 'Trường :attribute phải là số nguyên.',
    'max' => [
        'numeric' => 'Trường :attribute không được lớn hơn :max.',
        'string' => 'Trường :attribute không được vượt quá :max ký tự.',
        'array' => 'Trường :attribute không được có nhiều hơn :max mục.',
    ],
    'min' => [
        'numeric' => 'Trường :attribute phải tối thiểu :min.',
        'string' => 'Trường :attribute phải có ít nhất :min ký tự.',
        'array' => 'Trường :attribute phải có ít nhất :min mục.',
    ],
    'numeric' => 'Trường :attribute phải là số.',
    'required' => 'Vui lòng nhập :attribute.',
    'string' => 'Trường :attribute không hợp lệ.',
    'unique' => ':attribute đã được sử dụng.',
    'url' => 'Trường :attribute phải là URL hợp lệ.',

    'fields' => [
        'name' => [
            'required' => 'Vui lòng nhập họ và tên',
            'string' => 'Họ và tên không hợp lệ',
            'max' => 'Họ và tên không được vượt quá 255 ký tự',
        ],
        'email' => [
            'required' => 'Vui lòng nhập email',
            'email' => 'Email không hợp lệ',
            'unique' => 'Email đã được sử dụng',
        ],
        'password' => [
            'required' => 'Mật khẩu là bắt buộc',
            'string' => 'Mật khẩu không hợp lệ',
            'min' => 'Mật khẩu phải có ít nhất :min ký tự',
            'confirmed' => 'Xác nhận mật khẩu không khớp',
        ],
        'identification_number' => [
            'required' => 'Vui lòng nhập số VNeID/Passport',
            'string' => 'Số VNeID / Passport không hợp lệ',
            'regex' => 'Số VNeID / Passport chỉ chấp nhận số',
            'min' => 'Số VNeID / Passport không được ít hơn :min ký tự',
            'max' => 'Số VNeID / Passport không được vượt quá 20 ký tự',
            'unique' => 'Số VNeID / Passport đã được sử dụng',
        ],
        'nation_id' => [
            'required' => 'Vui lòng chọn quốc tịch',
            'exists' => 'Quốc tịch không hợp lệ',
        ],
        'phone' => [
            'numeric' => 'Số điện thoại không hợp lệ',
            'max' => 'Số điện thoại không được vượt quá 15 ký tự',
        ],
        'address' => [
            'string' => 'Địa chỉ không hợp lệ',
            'max' => 'Địa chỉ không được vượt quá 255 ký tự',
        ],
        'avatar' => [
            'string' => 'Avatar không hợp lệ',
        ],
    ],
];
