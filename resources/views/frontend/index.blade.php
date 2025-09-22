<!doctype html>
<html class="{{ app()->getLocale() }}" lang="{{ app()->getLocale() }}">

<head>
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

    <title>{{ $setting['meta_title'] }}</title>
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
    <meta property="og:site_name" content="{{ $setting['site_name'] }}" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $setting['meta_title'] }}" />
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:site" content="{{ $setting['site_name'] }}">
    @if(!empty($setting['og_image']))
        <meta name="twitter:image" content="{{ url($setting['og_image']) }}">
    @endif
    <meta name="twitter:description" content="{{ $setting['meta_description'] }}" />

    <title>Title</title>
    <meta charset="utf-8" />
    <meta name="description" content="Description" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <!-- Styles-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/libs.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/fontawesome-pro/css/all.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}?v={{ filemtime(public_path('css/custom.css')) }}" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light.css" />
    <!-- Scripts-->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script src="{{ asset('js/libs.js') }}" defer="defer"></script>
    <script src="{{ asset('js/app.js') }}" defer="defer"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <div class="page">

        {!! $setting['tracking_code_body'] !!}

        @include('frontend.header')

        @yield('content')

        @include('frontend.footer')
    </div>
    <button class="btn-movetop" type="button" data-target="body"><i class="fa fa-arrow-up"></i></button>
    <!-- ================== LOGIN POPUP ================== -->
    <div class="md-form modal fade" id="md-sign-in" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="modal-body md-form__body" action="{{route('guest_login')}}" method="post">
                    @csrf
                    <button class="md-form__close" type="button" data-bs-dismiss="modal"><i
                            class="far fa-lg fa-times"></i></button>
                    <div class="md-form__banner"><img src="./images/banner-login.jpg" alt="" /></div>
                    <div class="md-form__content">
                        <a class="md-form__logo" href="#!"><img src="./images/logo_sce.png" alt="" /></a>
                        <div class="md-form__title">Đăng nhập</div>

                        <div class="md-form__group">
                            <label class="form-label mb-0">Địa chỉ Email</label>
                            <input class="form-control" type="email" name="email" />
                            <div class="text-danger mt-1 error-email"></div>
                        </div>

                        <div class="md-form__group">
                            <label class="form-label mb-0">Mật khẩu</label>
                            <input class="form-control" type="password" name="password" />
                            <div class="text-danger mt-1 error-password"></div>
                        </div>

                        <div class="md-form__btns">
                            <button class="md-form__btn" type="submit">Đăng nhập</button>
                            <a class="md-form__btn-2" href="#!"><i class="fab fa-google me-3"></i><span>Đăng nhập bằng
                                    tài khoản Google</span></a>
                        </div>

                        <div class="md-form__footer">
                            <div class="d-flex justify-content-center">
                                <label class="checkbox-styled">
                                    <input class="checkbox-styled__input" type="checkbox" name="remember" value="1" />
                                    <span class="checkbox-styled__icon"></span><span>Lưu thông tin đăng nhập</span>
                                </label>
                            </div>
                            <div class="mt-2 text-center">Bạn chưa có tài khoản?
                                <a class="d-inline-block text-primary fw-700 js-switch-modal" href="#md-sign-up">Đăng ký
                                    tài khoản</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================== REGISTER POPUP ================== -->
    <div class="md-form modal fade" id="md-sign-up" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="modal-body md-form__body" action="{{route('guest_register')}}" method="post">
                    @csrf
                    <button class="md-form__close" type="button" data-bs-dismiss="modal"><i
                            class="far fa-lg fa-times"></i></button>
                    <div class="md-form__banner"><img src="./images/banner-login.jpg" alt="" /></div>
                    <div class="md-form__content">
                        <a class="md-form__logo" href="#!"><img src="./images/logo_sce.png" alt="" /></a>
                        <div class="md-form__title">Đăng ký tài khoản</div>
                        <div class="md-form__desc">Tài khoản dùng thử hoàn toàn miễn phí</div>

                        <div class="md-form__group">
                            <label class="form-label mb-0">Họ và Tên</label>
                            <input class="form-control" name="name" type="text" />
                            <div class="text-danger mt-1 error-name"></div>
                        </div>

                        <div class="md-form__group">
                            <label class="form-label mb-0">VNeID / Passport</label>
                            <input class="form-control" name="identification_number" type="text" />
                            <div class="text-danger mt-1 error-identification_number"></div>
                        </div>

                        <div class="md-form__group">
                            <label class="form-label mb-0">Địa chỉ Email</label>
                            <input class="form-control" name="email" type="email" />
                            <div class="text-danger mt-1 error-email"></div>
                        </div>

                        <div class="md-form__group">
                            <label class="form-label mb-0">Quốc tịch</label>
                            <select class="form-control" name="nation_id" id="">
                                <option value="">Chọn quốc tịch</option>
                                @foreach ($nations as $n)
                                    <option value="{{ $n->id }}">{{ $n->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger mt-1 error-nation_id"></div>
                        </div>

                        <div class="md-form__group">
                            <label class="form-label mb-0">Mật khẩu</label>
                            <input class="form-control" name="password" type="password" />
                            <div class="text-danger mt-1 error-password"></div>
                        </div>

                        <div class="md-form__btns">
                            <button class="md-form__btn" type="submit">Đăng ký tài khoản</button>
                            <a class="md-form__btn-2" href="#!"><i class="fab fa-google me-3"></i><span>Đăng ký bằng tài
                                    khoản Google</span></a>
                        </div>

                        <div class="md-form__footer">
                            <div class="d-flex justify-content-center">
                                <label class="checkbox-styled">
                                    <input class="checkbox-styled__input" type="checkbox" name="checkbox" />
                                    <span class="checkbox-styled__icon"></span><span>Đăng ký nhận thông tin các dự
                                        án</span>
                                </label>
                            </div>
                            <div class="mt-2 text-center">Bạn đã có tài khoản?
                                <a class="d-inline-block text-primary fw-700 js-switch-modal" href="#md-sign-in">Đăng
                                    nhập</a>
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
</body>

</html>

<script>
    let scrollTimeout;
    let lastScrollTop = 0;

    document.addEventListener("scroll", () => {
        clearTimeout(scrollTimeout);

        scrollTimeout = setTimeout(() => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const isScrollingDown = scrollTop > lastScrollTop; // check hướng scroll

            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // tránh âm

            // if (!isScrollingDown) {
            //     // Nếu kéo lên thì thôi, không snap
            //     return;
            // }

            const sections = document.querySelectorAll(".section");
            let closestSection = null;
            let minDistance = window.innerHeight;

            sections.forEach(section => {
                const rect = section.getBoundingClientRect();
                const distance = Math.abs(rect.top);

                if (distance < minDistance) {
                    minDistance = distance;
                    closestSection = section;
                }
            });

            // Chỉ snap xuống khi còn cách section < 150px
            if (closestSection && minDistance < 150) {
                closestSection.scrollIntoView({ behavior: "smooth" });
            }
        }, 200); // chờ user dừng kéo 200ms
    });

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
        // LOGIN AJAX
        $('form[action="{{route('guest_login')}}"]').on('submit', function (e) {
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
                        title: 'Đăng nhập thành công!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = res.redirect || '/';
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if (errors.email) form.find('.error-email').text(errors.email[0]);
                        if (errors.password) form.find('.error-password').text(errors.password[0]);
                    } else if (xhr.status === 401) {
                        form.find('.error-password').text('Thông tin đăng nhập không chính xác');
                    } else {
                        alert('Có lỗi xảy ra, vui lòng thử lại!');
                    }
                }
            });
        });

        // REGISTER AJAX
        $('form[action="{{route('guest_register')}}"]').on('submit', function (e) {
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
                        title: 'Đăng ký thành công!',
                        text: 'Bạn có thể đăng nhập ngay bây giờ.',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = res.redirect || '/';
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        for (let key in errors) {
                            form.find('.error-' + key).text(errors[key][0]);
                        }
                    } else {
                        alert('Có lỗi xảy ra, vui lòng thử lại!');
                    }
                }
            });
        });

    });
</script>