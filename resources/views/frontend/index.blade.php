<!doctype html>
<html class="{{ app()->getLocale() }}" lang="{{ app()->getLocale() }}">

<head>
    @php
        $frontendSiteName = \App\Models\Setting::getSettingByKey('site_name');

        if (empty($frontendSiteName)) {
            $frontendSiteName = app()->getLocale() === 'en' ? 'Hanoi Investment Map' : 'Bản đồ đầu tư Hà Nội';
        }

        if (app()->getLocale() === 'en') {
            $frontendSiteAlternateNames = ['Hanoi Investment Map', 'Dau tu Ha Noi'];
        } else {
            $frontendSiteAlternateNames = ['Đầu tư Hà Nội', 'Dau tu Ha Noi'];
        }
    @endphp
    {!! $setting['tracking_code_head'] !!}
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <![endif]-->

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $setting['meta_title'] }}">
    <meta property="og:description" content="{{ $setting['meta_description'] }}">
    <meta property="og:type" content="website">
    @if(!empty($setting['og_image']))
        <meta property="og:image" content="{{ url($setting['og_image']) }}">
    @endif
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <link rel="icon" href="{{ url($setting['favicon'] ?? '') }}" type="image/x-icon" />

    <script>var baseUrl = "{{ url('/') }}";</script>
    <script>var current_locale = "{{ app()->getLocale() }}";</script>

    <title>{{ app()->getLocale() === 'en' ? 'Hanoi Investment Map' : 'Bản đồ đầu tư Hà Nội' }}</title>
    <meta name="keywords" content="{{ $setting['meta_keywords'] }}">
    <meta name="description" content="{{ $setting['meta_description'] }}">
    @if($setting['noindex'])
        <meta name="robots" content="noindex" />
    @else
        <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large" />
    @endif
    <link rel="canonical" href="{{ url()->current() }}" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $setting['meta_title'] }}" />
    <meta property="og:description" content="{{ $setting['meta_description'] }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ $frontendSiteName }}" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $setting['meta_title'] }}" />
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:site" content="{{ $frontendSiteName }}" >
    @if(!empty($setting['og_image']))
        <meta name="twitter:image" content="{{ url($setting['og_image']) }}">
    @endif
    <meta name="twitter:description" content="{{ $setting['meta_description'] }}" />
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@type": "WebSite",
            "name": @json($frontendSiteName),
            "alternateName": @json($frontendSiteAlternateNames),
            "url": @json(url('/'))
        }
    </script>
    <!-- Styles-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/libs.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/fontawesome-pro/css/all.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}?v={{ filemtime(public_path('css/custom.css')) }}" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light.css" />
    <link rel="stylesheet" href="{{ asset('backend_assets/vendor/select2/select2.min.css') }}" />
    <!-- Scripts-->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script src="{{ asset('js/libs.js') }}" defer="defer"></script>
    <script src="{{ asset('js/app.js') }}" defer="defer"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('backend_assets/vendor/select2/select2.min.js') }}"></script>
    <style>
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #000 !important;
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
            border-radius: 0 !important;
            min-height: 38px !important;
            padding: 2px 0 !important;
            background-color: transparent !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-bottom: 2px solid #0d6efd !important;
            outline: 0;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #e0edff !important;
            border: 1px solid #b6d4fe !important;
            color: #0d6efd !important;
            border-radius: 4px !important;
            padding: 2px 8px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            margin-top: 4px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #0d6efd !important;
            margin-right: 5px !important;
            font-weight: bold !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #dc3545 !important;
        }
        .select2-dropdown {
            z-index: 1065 !important;
            border: 1px solid #ced4da !important;
            border-radius: 4px !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
        }
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #0d6efd !important;
            color: white !important;
        }
        .select2-container {
            width: 100% !important;
        }
    </style>

</head>

<body>
    <div class="page">

        {!! $setting['tracking_code_body'] !!}

        @include('frontend.header')

        @yield('content')

        @if(!request()->routeIs('home_page'))
            @include('frontend.footer')
        @endif
    </div>
    <button class="btn-movetop" type="button" data-target="body"><i class="fa fa-arrow-up"></i></button>
    <!-- ================== LOGIN POPUP ================== -->
    <div class="md-form modal fade" id="md-sign-in" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="modal-body md-form__body" id="form-login" action="{{route('auth_login')}}" method="post">
                    @csrf
                    <button class="md-form__close" type="button" data-bs-dismiss="modal"><i
                            class="far fa-lg fa-times"></i></button>
                    <div class="md-form__banner"><img src="{{ asset('/images/banner-login.jpg') }}" alt="" /></div>
                    <div class="md-form__content">
                        <a class="md-form__logo" href="#!"><img src="{{ asset('/images/logo_sce.png') }}" alt="" /></a>
                        <div class="md-form__title">{{ __('app.login') }}</div>

                        <div class="md-form__group">
                            <label class="form-label mb-0">{{ __('app.email_address') }}</label>
                            <input class="form-control" type="email" name="email" id="login-email" required />
                            <div class="text-danger mt-1 error-email"></div>
                        </div>

                        <div class="md-form__group">
                            <label class="form-label mb-0">{{ __('app.password') }}</label>
                            <input class="form-control" type="password" name="password" required />
                            <div class="text-danger mt-1 error-password"></div>
                        </div>

                        <div class="md-form__btns">
                            <button class="md-form__btn" type="submit">{{ __('app.login') }}</button>
                            <a class="md-form__btn-2" href="{{route('google_login')}}"><i
                                    class="fab fa-google me-3"></i><span>{{ __('app.login_with_goolge') }}</span></a>
                        </div>

                        <div class="md-form__footer">
                            <div class="d-flex justify-content-center">
                                <label class="checkbox-styled">
                                    <input class="checkbox-styled__input" type="checkbox" name="remember" value="1" />
                                    <span class="checkbox-styled__icon"></span><span>{{ __('app.remember_me') }}</span>
                                </label>
                            </div>
                            <div class="mt-2 text-center">
                                <a class="d-inline-block text-primary fw-700 js-switch-modal" href="#md-forgot-password">
                                    {{ __('app.forgot_password') }}
                                </a>
                            </div>
                            <div class="mt-2 text-center" id="resend-verification-container" style="display: none;">
                                <a href="javascript:void(0)" class="text-warning fw-600 js-resend-verification" style="font-size: 13px;">
                                    <i class="far fa-envelope me-1"></i> Gửi lại email xác thực
                                </a>
                            </div>
                            <div class="mt-2 text-center">
                                {{ __('app.no_account') }}
                                <a class="d-inline-block text-primary fw-700 js-switch-modal" href="#md-sign-up">
                                    {{ __('app.sign_up') }}
                                </a>
                            </div>                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================== FORGOT PASSWORD POPUP ================== -->
    <div class="md-form modal fade" id="md-forgot-password" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="modal-body md-form__body" id="form-forgot-password" action="{{ route('auth_forgot_password') }}" method="post">
                    @csrf
                    <button class="md-form__close" type="button" data-bs-dismiss="modal"><i
                            class="far fa-lg fa-times"></i></button>
                    <div class="md-form__banner"><img src="{{ asset('/images/banner-login.jpg') }}" alt="" /></div>
                    <div class="md-form__content">
                        <a class="md-form__logo" href="#!"><img src="{{ asset('/images/logo_sce.png') }}" alt="" /></a>
                        <div class="md-form__title">{{ __('app.forgot_password') }}</div>
                        <div class="md-form__desc">{{ __('app.forgot_password_desc') }} (Liên kết có hiệu lực trong 24 giờ)</div>

                        <div class="md-form__group">
                            <label class="form-label mb-0">{{ __('app.email_address') }}</label>
                            <input class="form-control" type="email" name="email" required />
                            <div class="text-danger mt-1 error-email"></div>
                        </div>

                        <div class="md-form__btns">
                            <button class="md-form__btn" type="submit">{{ __('app.send_reset_link') }}</button>
                        </div>

                        <div class="md-form__footer">
                            <div class="mt-2 text-center">
                                <a class="d-inline-block text-primary fw-700 js-switch-modal" href="#md-sign-in">
                                    {{ __('app.back_to_login') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================== REGISTER POPUP ================== -->
    <div class="md-form modal fade" id="md-sign-up" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form class="modal-body md-form__body" id="form-register" action="{{route('auth_register')}}" method="post">
                    @csrf
                    <button class="md-form__close" type="button" data-bs-dismiss="modal"><i
                            class="far fa-lg fa-times"></i></button>
                    <div class="md-form__banner"><img src="{{ asset('/images/banner-login.jpg') }}" alt="" /></div>
                    <div class="md-form__content" style="max-height: 85vh; overflow-y: auto; padding-right: 15px;">
                        <a class="md-form__logo" href="#!"><img src="{{ asset('/images/logo_sce.png') }}" alt="" /></a>
                        <div class="md-form__title">{{ __('app.sign_up') }}</div>
                        <div class="md-form__desc">Đăng ký tài khoản nhà đầu tư trên hệ thống</div>

                        <!-- Loại nhà đầu tư -->
                        <div class="md-form__group mt-3">
                            <label class="form-label mb-1 fw-bold text-dark">Loại nhà đầu tư <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-4 mt-1">
                                <label class="d-inline-flex align-items-center gap-2 cursor-pointer">
                                    <input type="radio" name="investor_type" value="individual" checked class="js-investor-type" />
                                    <span>Cá nhân</span>
                                </label>
                                <label class="d-inline-flex align-items-center gap-2 cursor-pointer">
                                    <input type="radio" name="investor_type" value="organization" class="js-investor-type" />
                                    <span>Tổ chức</span>
                                </label>
                                <label class="d-inline-flex align-items-center gap-2 cursor-pointer">
                                    <input type="radio" name="investor_type" value="fdi" class="js-investor-type" />
                                    <span>FDI</span>
                                </label>
                            </div>
                            <div class="text-danger mt-1 error-investor_type"></div>
                        </div>

                        <!-- Trường bổ sung cho Tổ chức & FDI -->
                        <div id="org-extra-fields" style="display: none; background: #f8f9fa; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 12px; margin: 12px 0;">
                            <div class="md-form__group mb-2">
                                <label class="form-label mb-0 fw-bold">Tên tổ chức / Doanh nghiệp <span class="text-danger">*</span></label>
                                <input class="form-control" name="organization_name" type="text" placeholder="Nhập tên tổ chức / doanh nghiệp" />
                                <div class="text-danger mt-1 error-organization_name"></div>
                            </div>
                            <div class="md-form__group mb-0">
                                <label class="form-label mb-0 fw-bold">Mã số thuế <span class="text-danger">*</span></label>
                                <input class="form-control" name="tax_code" type="text" placeholder="Nhập mã số thuế doanh nghiệp" />
                                <div class="text-danger mt-1 error-tax_code"></div>
                            </div>
                        </div>

                        <div class="md-form__group">
                            <label class="form-label mb-0">{{ __('app.full_name') }} <span class="text-danger">*</span></label>
                            <input class="form-control" name="name" type="text" id="fullNameInput" placeholder="Nhập họ và tên" required />
                            <div class="text-danger mt-1 error-name"></div>
                        </div>

                        <div class="md-form__group">
                            <label class="form-label mb-0">{{ __('app.email_address') }} <span class="text-danger">*</span></label>
                            <input class="form-control" name="email" type="email" placeholder="example@email.com" required />
                            <div class="text-danger mt-1 error-email"></div>
                        </div>

                        <div class="md-form__group">
                            <label class="form-label mb-0">Số điện thoại <span class="text-danger">*</span></label>
                            <input class="form-control" name="phone" type="text" placeholder="Nhập số điện thoại liên hệ" required />
                            <div class="text-danger mt-1 error-phone"></div>
                        </div>

                        <div class="md-form__group">
                            <label class="form-label mb-0">{{ __('app.password') }} <span class="text-danger">*</span></label>
                            <input class="form-control" name="password" type="password" placeholder="Tối thiểu 6 ký tự" required />
                            <div class="text-danger mt-1 error-password"></div>
                        </div>

                        <!-- Lĩnh vực quan tâm (chọn nhiều & có tìm kiếm) -->
                        <div class="md-form__group" style="position: relative;">
                            <label class="form-label mb-1 fw-bold text-dark">Lĩnh vực quan tâm (chọn nhiều)</label>
                            
                            <div class="position-relative">
                                <div id="selected-tags-interested_sectors"
                                    class="form-control d-flex flex-wrap align-items-center px-2 py-2 mb-1"
                                    onclick="toggleDropdown_interested_sectors()"
                                    style="min-height: fit-content; cursor: pointer; gap: 5px">
                                    <span class="text-muted small">Tìm kiếm và chọn lĩnh vực quan tâm...</span>
                                </div>
                                <div id="dropdown_interested_sectors" class="border rounded shadow bg-white position-absolute w-100"
                                    style="z-index: 9999; display: none; max-height: 220px; overflow-y: auto;">
                                    <div class="mb-2 px-2 pt-2">
                                        <input type="text" class="form-control form-control-sm" placeholder="Tìm kiếm..."
                                            oninput="filterOptions_interested_sectors(this.value)">
                                    </div>
                                    <div id="dropdown-options-interested_sectors" class="px-2 pb-2 text-start">
                                        @if(isset($project_industries) && count($project_industries) > 0)
                                            @foreach ($project_industries as $industry)
                                                <div class="form-check text-start d-flex align-items-center mb-1">
                                                    <input type="checkbox" class="form-check-input custom-large me-2 flex-shrink-0" name="interested_sectors[]"
                                                        value="{{ $industry->id }}" id="industry_{{ $industry->id }}"
                                                        onchange="updateTags_interested_sectors()" style="margin-top: 0;">
                                                    <label class="form-check-label"
                                                        for="industry_{{ $industry->id }}">{{ $industry->name }}</label>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-danger mt-1 error-interested_sectors"></div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="md-form__group">
                                    <label class="form-label mb-0">{{ __('app.nationality') }}</label>
                                    <select class="form-control" name="nation_id">
                                        <option value="">{{ __('app.choice_nationality') }}</option>
                                        @if(isset($nations))
                                            @foreach ($nations as $n)
                                                <option value="{{ $n->id }}">{{ $n->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="text-danger mt-1 error-nation_id"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="md-form__group">
                                    <label class="form-label mb-0">{{ __('app.vneid_or_passport') }}</label>
                                    <input class="form-control" name="identification_number" id="identification_number" type="text" />
                                    <div class="text-danger mt-1 error-identification_number"></div>
                                </div>
                            </div>
                        </div>

                        <div class="md-form__btns mt-3">
                            <button class="md-form__btn" type="submit">{{ __('app.sign_up') }}</button>
                            <a class="md-form__btn-2" href="{{route('google_login')}}"><i
                                    class="fab fa-google me-3"></i><span>{{ __('app.register_with_goolge') }}</span></a>
                        </div>

                        <div class="md-form__footer mt-3">
                            <div class="text-center">{{ __('app.have_account') }}
                                <a class="d-inline-block text-primary fw-700 js-switch-modal" href="#md-sign-in">{{ __('app.login') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @stack('bottom')

    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v19.0&appId={{ $setting['facebook_app_id'] ?? '' }}"
        nonce="uWFE6azL"></script>
    <script>
        window.customTawkIconUrl = "{{ asset('images/ha_noi_icon.png') }}";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {!! $setting['tracking_code_bottom'] !!}
    
    @include('frontend.partials.chatbot-ui')
</body>

</html>

<script>
    // let scrollTimeout;
    // let lastScrollTop = 0;

    // document.addEventListener("scroll", () => {
    //     clearTimeout(scrollTimeout);

    //     scrollTimeout = setTimeout(() => {
    //         const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    //         const isScrollingDown = scrollTop > lastScrollTop; // check hướng scroll

    //         lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // tránh âm

    //         // if (!isScrollingDown) {
    //         //     // Nếu kéo lên thì thôi, không snap
    //         //     return;
    //         // }

    //         const sections = document.querySelectorAll(".section");
    //         let closestSection = null;
    //         let minDistance = window.innerHeight;

    //         sections.forEach(section => {
    //             const rect = section.getBoundingClientRect();
    //             const distance = Math.abs(rect.top);

    //             if (distance < minDistance) {
    //                 minDistance = distance;
    //                 closestSection = section;
    //             }
    //         });

    //         // Chỉ snap xuống khi còn cách section < 150px
    //         if (closestSection && minDistance < 150) {
    //             closestSection.scrollIntoView({ behavior: "smooth" });
    //         }
    //     }, 200); // chờ user dừng kéo 200ms
    // });

    document.addEventListener("DOMContentLoaded", function () {
        tippy('[data-tippy-content]', {
            theme: 'light',
            animation: 'scale',
            duration: [200, 200],
            delay: [100, 100],
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const avatarInput = document.getElementById('avatarInput');
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarHidden = document.getElementById('avatarHidden');

        if (!avatarInput) return;

        avatarInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                avatarPreview.src = e.target.result;
                avatarHidden.value = e.target.result;
            }
            reader.readAsDataURL(file);
        });
    });

    $(document).ready(function () {
        // LĨNH VỰC QUAN TÂM CUSTOM JS
        window.toggleDropdown_interested_sectors = function() {
            const el = document.getElementById('dropdown_interested_sectors');
            el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }

        window.updateTags_interested_sectors = function() {
            const checkboxes = document.querySelectorAll('#dropdown-options-interested_sectors .form-check input[type="checkbox"]');
            const container = document.getElementById('selected-tags-interested_sectors');
            container.innerHTML = '';

            let selectedCount = 0;
            const selectedLabels = [];

            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    selectedCount++;
                    const label = document.querySelector('label[for="' + checkbox.id + '"]').innerText;
                    selectedLabels.push({ value: checkbox.value, label: label });
                }
            });

            if (selectedCount === 0) {
                const placeholder = document.createElement('span');
                placeholder.className = 'text-muted small';
                placeholder.innerText = 'Tìm kiếm và chọn lĩnh vực quan tâm...';
                container.appendChild(placeholder);
            } else {
                selectedLabels.forEach(item => {
                    const span = document.createElement('span');
                    span.className = 'badge bg-primary text-white d-flex align-items-center px-2 py-2 rounded-pill';
                    span.style.maxWidth = '100%';
                    span.innerHTML = `
                        <span class="text-truncate" style="max-width: 100%;">${item.label}</span>
                        <button type="button" class="ms-2 flex-shrink-0 bg-transparent border-0 text-white" style="font-size: 1rem; line-height: 1;"
                            onclick="removeTag_interested_sectors('${item.value}', event)">
                            &times;
                        </button>
                    `;
                    container.appendChild(span);
                });
            }
        }

        window.removeTag_interested_sectors = function(value, event) {
            event.stopPropagation();
            const checkbox = Array.from(document.querySelectorAll('#dropdown-options-interested_sectors .form-check input[type="checkbox"]'))
                .find(cb => cb.value === value);
            if (checkbox) {
                checkbox.checked = false;
                updateTags_interested_sectors();
            }
        }

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('dropdown_interested_sectors');
            const tags = document.getElementById('selected-tags-interested_sectors');
            if (dropdown && tags && !dropdown.contains(e.target) && !tags.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });

        window.filterOptions_interested_sectors = function(keyword) {
            const lowerKeyword = keyword.toLowerCase();
            const options = document.querySelectorAll('#dropdown-options-interested_sectors .form-check');
            options.forEach(option => {
                const label = option.querySelector('label').innerText.toLowerCase();
                option.style.display = label.includes(lowerKeyword) ? 'block' : 'none';
            });
        }

        // TOGGLE EXTRA ORG FIELDS
        $('.js-investor-type').on('change', function () {
            const val = $(this).val();
            if (val === 'organization' || val === 'fdi') {
                $('#org-extra-fields').slideDown(250);
            } else {
                $('#org-extra-fields').slideUp(250);
            }
        });

        // LOGIN AJAX
        $('#form-login, form[action="{{route('guest_login')}}"], form[action="{{route('auth_login')}}"]').on('submit', function (e) {
            e.preventDefault();
            const form = $(this);
            form.find('.text-danger').text('');

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function (res) {
                    Swal.fire({
                        icon: 'success',
                        title: res.message || 'Đăng nhập thành công!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = res.redirect || '/';
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if (errors.email) {
                            form.find('.error-email').text(errors.email[0]);
                            const emailMsg = errors.email[0];
                            if (emailMsg.includes('chưa được xác thực')) {
                                $('#resend-verification-container').slideDown();
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Chưa xác thực email',
                                    text: emailMsg,
                                    confirmButtonText: 'Đã hiểu'
                                });
                            } else if (emailMsg.includes('tạm khóa') || emailMsg.includes('khóa')) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Tài khoản bị khóa',
                                    text: emailMsg,
                                    confirmButtonText: 'Đóng'
                                });
                            } else if (emailMsg.includes('Chờ xác thực')) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Đang chờ phê duyệt',
                                    text: emailMsg,
                                    confirmButtonText: 'Đóng'
                                });
                            }
                        }
                        if (errors.password) {
                            form.find('.error-password').text(errors.password[0]);
                            if (errors.password[0].includes('khóa')) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Cảnh báo đăng nhập',
                                    text: errors.password[0],
                                    confirmButtonText: 'Đóng'
                                });
                            }
                        }
                    } else if (xhr.status === 401) {
                        form.find('.error-password').text('Thông tin đăng nhập không chính xác');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('app.error') ?? 'Lỗi!' }}',
                            text: xhr.responseJSON?.message || 'Có lỗi xảy ra, vui lòng thử lại!'
                        });
                    }
                }
            });
        });

        // RESEND VERIFICATION EMAIL AJAX
        $(document).on('click', '.js-resend-verification', function () {
            const email = $('#login-email').val();
            if (!email) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Thông báo',
                    text: 'Vui lòng nhập địa chỉ email của bạn ở ô trên trước khi yêu cầu gửi lại liên kết.'
                });
                return;
            }

            Swal.fire({
                title: 'Đang gửi...',
                text: 'Vui lòng đợi giây lát',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('auth_resend_verification') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    email: email
                },
                success: function (res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã gửi!',
                        text: res.message || 'Vui lòng kiểm tra hộp thư email của bạn.',
                        confirmButtonText: 'Đồng ý'
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: xhr.responseJSON?.message || 'Không thể gửi lại liên kết xác thực. Vui lòng thử lại.'
                    });
                }
            });
        });

        // FORGOT PASSWORD AJAX
        $('#form-forgot-password, form[action="{{route('guest_password_email')}}"], form[action="{{route('auth_forgot_password')}}"]').on('submit', function (e) {
            e.preventDefault();
            const form = $(this);
            form.find('.text-danger').text('');

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function (res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đã gửi liên kết!',
                        text: res.message || 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn và có hiệu lực trong 24 giờ.',
                        showConfirmButton: true
                    }).then(() => {
                        $('#md-forgot-password').modal('hide');
                        $('#md-sign-in').modal('show');
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if (errors.email) form.find('.error-email').text(errors.email[0]);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('app.error') ?? 'Lỗi!' }}',
                            text: '{{ __('app.generic_error') }}'
                        });
                    }
                }
            });
        });

        // REGISTER AJAX
        $('#form-register, form[action="{{route('guest_register')}}"], form[action="{{route('auth_register')}}"]').on('submit', function (e) {
            e.preventDefault();
            const form = $(this);
            form.find('.text-danger').text('');

            Swal.fire({
                title: 'Đang xử lý...',
                text: 'Hệ thống đang khởi tạo tài khoản và gửi liên kết xác thực',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function (res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Đăng ký thành công!',
                        text: res.message || 'Vui lòng kiểm tra email để xác thực tài khoản.',
                        confirmButtonText: 'Đến trang đăng nhập'
                    }).then(() => {
                        $('#md-sign-up').modal('hide');
                        $('#md-sign-in').modal('show');
                        if (res.email) {
                            $('#login-email').val(res.email);
                        }
                    });
                },
                error: function (xhr) {
                    Swal.close();
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        for (let key in errors) {
                            // Xử lý key dạng array hoặc bình thường
                            const sanitizedKey = key.replace('.', '_');
                            form.find('.error-' + sanitizedKey).text(errors[key][0]);
                            form.find('.error-' + key).text(errors[key][0]);
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('app.error') ?? 'Lỗi!' }}',
                            text: xhr.responseJSON?.message || 'Có lỗi xảy ra, vui lòng thử lại!'
                        });
                    }
                }
            });
        });

    });

    @if(session('success'))
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Thành công',
                text: @json(session('success')),
                confirmButtonColor: '#0d6efd'
            });
        });
    @endif

    @if(isset($errors) && $errors->has('msg'))
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'warning',
                title: 'Thông báo',
                text: @json($errors->first('msg')),
                confirmButtonColor: '#0d6efd'
            });
        });
    @endif

    function toggleInterest($btn) {
    let id = $btn.data('id');
    let type = $btn.data('type');
    let $icon = $btn.find('i');

    $.ajax({
        url: "{{ route('interest') }}",
        type: "POST",
        data: {
            interestable_id: id,
            interestable_type: type,
            _token: "{{ csrf_token() }}"
        },
        success: function (res) {
            if (res.message) {
                let wasActive = $icon.hasClass('text-danger');
                $icon.toggleClass('text-danger');

                if ((window.location.pathname === '/account') && wasActive) {
                    // Xoá DOM slide tương ứng
                    let $slide = $btn.closest('.swiper-slide');
                    $slide.remove();
                }

                Swal.fire({
                    icon: 'success',
                    title: res.message,
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        },
        error: function (xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: xhr.responseJSON?.message || 'Có lỗi xảy ra, vui lòng thử lại!',
            });
        }
    });
}

// Gán sự kiện
$('.project__like').click(e => toggleInterest($(e.currentTarget)));
$('.news__like').click(e => toggleInterest($(e.currentTarget)));

    $(function () {
        $('#project-interest-search').on('input', function () {
            const keyword = $(this).val().toLowerCase();

            $('.project-slide').each(function () {
                const name = $(this).data('name');
                if (name.includes(keyword)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            if ($('.project-slide:visible').length === 0) {
                if ($('#no-project-message').length === 0) {
                    $('#project-interest .swiper-wrapper').append('<p id="no-project-message" class="text-center w-100 mt-4">Không tìm thấy dự án nào</p>');
                }
            } else {
                $('#no-project-message').remove();
            }

            // Update swiper nếu đang dùng swiper.js
            if (typeof swiper !== 'undefined') {
                swiper.update();
            }
        });
    });

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('fullNameInput');

    if (!nameInput) return;

    nameInput.addEventListener('input', function () {
        this.value = this.value.replace(/[^a-zA-ZÀ-ỹ\s]/g, '');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const idInput = document.getElementById('identification_number');
    if (!idInput) return;

    idInput.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>
