<!doctype html>
<html class="{{ app()->getLocale() }}" lang="{{ app()->getLocale() }}">
<head>
    {!! $setting['tracking_code_head'] !!}
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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

    <link rel="icon" href="{{ url($setting['favicon'] ?? '') }}" type="image/x-icon"/>

    <script>var baseUrl = "{{ url('/') }}";</script>
    <script>var current_locale = "{{ app()->getLocale() }}";</script>

    <title>{{ $setting['meta_title'] }}</title>
    <meta name="keywords" content="{{ $setting['meta_keywords'] }}">
    <meta name="description" content="{{ $setting['meta_description'] }}">
    @if($setting['noindex'])
        <meta name="robots" content="noindex"/>
    @else
        <meta name="robots" content="follow, index, max-snippet:-1, max-video-preview:-1, max-image-preview:large"/>
    @endif
    <link rel="canonical" href="{{ url()->current() }}"/>
    <meta property="og:locale" content="en_US"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="{{ $setting['meta_title'] }}"/>
    <meta property="og:description" content="{{ $setting['meta_description'] }}"/>
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:site_name" content="{{ $setting['site_name'] }}"/>

    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="{{ $setting['meta_title'] }}"/>
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:site" content="{{ $setting['site_name'] }}">
    @if(!empty($setting['og_image']))
        <meta name="twitter:image" content="{{ url($setting['og_image']) }}">
    @endif
    <meta name="twitter:description" content="{{ $setting['meta_description'] }}"/>

    <title>Title</title>
    <meta charset="utf-8"/>
    <meta name="description" content="Description"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <!-- Styles-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/libs.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/fontawesome-pro/css/all.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}"/>
    <!-- Scripts-->
    <script src="{{ asset('js/libs.js') }}" defer="defer"></script>
    <script src="{{ asset('js/app.js') }}" defer="defer"></script>

</head>
<body>
<div class="page">

    {!! $setting['tracking_code_body'] !!}

    @include('frontend.header')

    @yield('content')

    @include('frontend.footer')
</div>
<button class="btn-movetop" type="button" data-target="body"><i class="fa fa-arrow-up"></i></button>
<div class="md-form modal fade" id="md-sign-in" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="modal-body md-form__body" action="#!">
                <button class="md-form__close" type="button" data-bs-dismiss="modal"><i class="far fa-lg fa-times"></i>
                </button>
                <div class="md-form__banner"><img src="./images/banner-login.jpg" alt=""/></div>
                <div class="md-form__content"><a class="md-form__logo" href="#!"><img src="./images/logo.png"
                                                                                      alt=""/></a>
                    <div class="md-form__title">Đăng nhập</div>
                    <div class="md-form__group">
                        <label class="form-label mb-0">Địa chỉ Email</label>
                        <input class="form-control" type="email"/>
                    </div>
                    <div class="md-form__group">
                        <label class="form-label mb-0">Mật khẩu</label>
                        <input class="form-control" type="password"/>
                    </div>
                    <div class="md-form__btns">
                        <button class="md-form__btn" type="submit">Đăng nhập</button>
                        <a class="md-form__btn-2" href="#!"><i class="fab fa-google me-3"></i><span>Đăng nhập bằng tài khoản Google</span></a>
                    </div>
                    <div class="md-form__footer">
                        <div class="d-flex justify-content-center">
                            <label class="checkbox-styled">
                                <input class="checkbox-styled__input" type="checkbox" name="checkbox"/><span
                                        class="checkbox-styled__icon"></span><span>Lưu thông tin đăng nhập</span>
                            </label>
                        </div>
                        <div class="mt-2 text-center">Bạn chưa có tài khoản? <a
                                    class="d-inline-block text-primary fw-700 js-switch-modal" href="#md-sign-up">Đăng
                                ký tài khoản</a></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="md-form modal fade" id="md-sign-up" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="modal-body md-form__body" action="#!">
                <button class="md-form__close" type="button" data-bs-dismiss="modal"><i class="far fa-lg fa-times"></i>
                </button>
                <div class="md-form__banner"><img src="./images/banner-login.jpg" alt=""/></div>
                <div class="md-form__content"><a class="md-form__logo" href="#!"><img src="./images/logo.png"
                                                                                      alt=""/></a>
                    <div class="md-form__title">Đăng ký tài khoản</div>
                    <div class="md-form__desc">Tài khoản dùng thử hoàn toàn miễn phí</div>
                    <div class="md-form__group">
                        <label class="form-label mb-0">Họ và Tên</label>
                        <input class="form-control" type="text"/>
                    </div>
                    <div class="md-form__group">
                        <label class="form-label mb-0">Địa chỉ Email</label>
                        <input class="form-control" type="email"/>
                    </div>
                    <div class="md-form__group">
                        <label class="form-label mb-0">Mật khẩu</label>
                        <input class="form-control" type="password"/>
                    </div>
                    <div class="md-form__btns">
                        <button class="md-form__btn" type="submit">Đăng ký tài khoản</button>
                        <a class="md-form__btn-2" href="#!"><i class="fab fa-google me-3"></i><span>Đăng ký bằng tài khoản Google</span></a>
                    </div>
                    <div class="md-form__footer">
                        <div class="d-flex justify-content-center">
                            <label class="checkbox-styled">
                                <input class="checkbox-styled__input" type="checkbox" name="checkbox"/><span
                                        class="checkbox-styled__icon"></span><span>Đăng ký nhận thông tin các dự án</span>
                            </label>
                        </div>
                        <div class="mt-2 text-center">Bạn đã có tài khoản? <a
                                    class="d-inline-block text-primary fw-700 js-switch-modal" href="#md-sign-in">Đăng
                                nhập</a></div>
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
{!! $setting['tracking_code_bottom'] !!}
</body>
</html>
