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
                'dashboard' => [
                    'icon' => 'fas fa-tachometer-alt',
                    'route' => 'backend_dashboard',
                    'title' => 'Dashboard',
                ],
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
                // 'widget' => [
                //     'icon' => 'fas fa-puzzle-piece',
                //     'route' => 'backend_widget',
                //     'title' => 'Widgets',
                // ],
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
                'popup' => [
                    'icon' => 'fas fa-window-restore',
                    'route' => 'backend_popup',
                    'title' => 'Popup',
                ],
                'guest' => [
                    'icon' => 'fas fa-user',
                    'route' => 'backend_guest',
                    'title' => 'Người dùng',
                ],
               'vr_tour' => [
                   'icon' => 'fas fa-vr-cardboard',
                   'title' => 'VrTour',
                   'items' => [
                       'skin' => [
                           'title' => 'Skin',
                           'route' => 'backend_vrtour_skin_index',
                       ],
                       'hotspot' => [
                           'title' => 'Hotspot',
                           'route' => 'backend_vrtour_hotspot_index',
                       ],
                       'content' => [
                           'title' => 'Nội dung',
                           'route' => 'backend_vrtour_content_index',
                       ]
                   ]
               ],
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
