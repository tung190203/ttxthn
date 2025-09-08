<?php

return [
    'prefix_admin' => env('PREFIX_ADMIN', 'backend'),
    'logo' => [
        'lg' => '<b>Pro</b>CMS',
        'mini' => '<b>CMS</b>',
    ],
    'name' => 'ProCMS',
    'version' => '4.0',
    'backend_module' => [
        'contents' => [
            'title' => 'Content',
            'items' => [
                'category' => [
                    'icon' => 'fas fa-th',
                    'route' => 'backend_category',
                    'title' => 'Danh mục',
                ],
                'post' => [
                    'icon' => 'far fa-newspaper',
                    'route' => 'backend_post',
                    'title' => 'Tin tức',
                ],
                'investment_guide' => [
                    'icon' => 'fas fa-book',
                    'route' => 'backend_investment_guide',
                    'title' => 'Cẩm nang đầu tư',
                ],
                'widget' => [
                    'icon' => 'fas fa-puzzle-piece',
                    'route' => 'backend_widget',
                    'title' => 'Widgets',
                ],
                'menu' => [
                    'icon' => 'fas fa-bars',
                    'route' => 'backend_menu',
                    'title' => 'Navigation',
                ],
                'project' => [
                    'icon' => 'fas fa-project-diagram',
                    'route' => 'backend_project',
                    'title' => 'Dự án',
                ],
//                'member' => [
//                    'icon' => 'fas fa-motorcycle',
//                    'route' => 'backend_member',
//                    'title' => 'Đại lý/cửa hàng',
//                ],
                // 'page' => [
                //     'icon' => 'far fa-file',
                //     'route' => 'backend_page',
                //     'title' => 'Trang nội dung',
                // ],
//                'landing_page' => [
//                    'icon' => 'fas fa-palette',
//                    'title' => 'Lading page',
//                    'items' => [
//                        'home' => [
//                            'title' => 'Trang chủ',
//                            'route' => 'backend_landing_page_home',
//                        ],
//                        'job' => [
//                            'title' => 'Trang công việc',
//                            'route' => 'backend_landing_page_job',
//                        ]
//                    ]
//                ],
            ]
        ],
        'systems' => [
            'title' => 'Systems',
            'items' => [
                'file_manager' => [
                    'icon' => 'fas fa-file-archive',
                    'route' => 'backend_file_manager',
                    'title' => 'Files',
                ],
                'user' => [
                    'icon' => 'fas fa-users',
                    'title' => 'Quản lý user',
                    'items' => [
                        'user' => [
                            'title' => 'User',
                            'route' => 'backend_user'
                        ],
                        'group' => [
                            'title' => 'Group',
                            'route' => 'backend_group'
                        ]
                    ]
                ],
                'setting' => [
                    'icon' => 'fas fa-cogs',
                    'title' => 'Cài đặt hệ thống',
                    'items' => [
                        'general' => [
                            'title' => 'Cài đặt chung',
                            'route' => 'backend_setting_general'
                        ],
                        'seo' => [
                            'title' => 'SEO',
                            'route' => 'backend_setting_seo'
                        ],
                        'social' => [
                            'title' => 'Mạng xã hội',
                            'route' => 'backend_setting_social'
                        ]
                    ]
                ],
            ]
        ]
    ]
];
