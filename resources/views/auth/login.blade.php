<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('cms.name') }} | Log in</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('backend_assets/vendor/fontawesome/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend_assets/vendor/bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend_assets/css/adminlte.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,800&display=swap" rel="stylesheet">

    <style>
        body.login-page {
            align-items: center;
            background: #eaf0f7;
            color: #273247;
            font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            justify-content: center;
            min-height: 100vh;
            padding: 24px 24px 56px;
        }

        .hmg-login-wrap {
            align-items: center;
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .hmg-login {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 18px 48px rgba(22, 39, 64, 0.1);
            max-width: 440px;
            overflow: hidden;
            width: 100%;
        }

        .hmg-login__brand {
            align-items: center;
            background: #14263f;
            display: flex;
            height: 185px;
            justify-content: center;
            padding: 28px 48px;
        }

        .hmg-login__brand img {
            display: block;
            height: auto;
            max-height: 122px;
            max-width: 100%;
            object-fit: contain;
            width: 350px;
        }

        .hmg-login__body {
            padding: 34px 32px 36px;
        }

        .hmg-login__title {
            color: #263247;
            font-size: 20px;
            font-weight: 800;
            line-height: 1.2;
            margin: 0 0 28px;
            text-align: center;
        }

        .hmg-login__alert {
            border-radius: 8px;
            font-size: 13px;
            margin: -14px 0 20px;
        }

        .hmg-login__field {
            margin-bottom: 16px;
        }

        .hmg-login__input-group {
            align-items: center;
            border: 1px solid #cfd6df;
            border-radius: 4px;
            display: flex;
            height: 44px;
            padding: 0 14px;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .hmg-login__input-group:focus-within {
            border-color: #1683f7;
            box-shadow: 0 0 0 4px rgba(22, 131, 247, 0.12);
        }

        .hmg-login__input {
            border: 0;
            color: #273247;
            flex: 1;
            font-size: 16px;
            font-weight: 600;
            height: 100%;
            min-width: 0;
            outline: 0;
            padding: 0;
        }

        .hmg-login__input::placeholder {
            color: #929ba8;
            opacity: 1;
        }

        .hmg-login__icon {
            color: #777;
            flex: 0 0 auto;
            font-size: 16px;
            margin-left: 12px;
        }

        .hmg-login__error {
            display: block;
            font-size: 13px;
            margin-top: 8px;
        }

        .hmg-login__actions {
            align-items: center;
            display: grid;
            gap: 16px;
            grid-template-columns: minmax(0, 1fr) 116px;
            margin-top: 4px;
        }

        .hmg-login__remember {
            align-items: center;
            color: #5b5b5b;
            cursor: pointer;
            display: inline-flex;
            font-size: 18px;
            font-weight: 800;
            line-height: 1;
            margin: 0;
        }

        .hmg-login__remember input {
            appearance: none;
            background: #fff;
            border: 2px solid #d2d2d2;
            border-radius: 0;
            height: 24px;
            margin: 0 9px 0 0;
            position: relative;
            width: 24px;
        }

        .hmg-login__remember input:checked {
            background: #1683f7;
            border-color: #1683f7;
        }

        .hmg-login__remember input:checked::after {
            border: solid #fff;
            border-width: 0 3px 3px 0;
            content: "";
            height: 12px;
            left: 7px;
            position: absolute;
            top: 3px;
            transform: rotate(45deg);
            width: 7px;
        }

        .hmg-login__submit {
            background: #1683f7;
            border: 0;
            border-radius: 5px;
            color: #fff;
            font-size: 18px;
            font-weight: 800;
            height: 44px;
            line-height: 1;
            width: 100%;
        }

        .hmg-login__submit:hover,
        .hmg-login__submit:focus {
            background: #0876ed;
            color: #fff;
        }

        .hmg-login-platform {
            bottom: 18px;
            color: #737d88;
            font-size: 15px;
            font-weight: 600;
            left: 50%;
            position: fixed;
            text-align: center;
            transform: translateX(-50%);
            width: calc(100% - 32px);
        }

        .hmg-login-platform i {
            color: #087cff;
            margin-right: 6px;
        }

        .hmg-login-platform a {
            color: #087cff;
            font-weight: 800;
            margin-left: 4px;
        }

        @media (max-width: 767.98px) {
            body.login-page {
                padding: 24px 12px 56px;
            }

            .hmg-login {
                border-radius: 12px;
            }

            .hmg-login__brand {
                height: 170px;
                padding: 28px 24px;
            }

            .hmg-login__body {
                padding: 32px 22px 36px;
            }

            .hmg-login__title {
                font-size: 24px;
                margin-bottom: 26px;
            }

            .hmg-login__input-group,
            .hmg-login__submit {
                height: 54px;
            }

            .hmg-login__input,
            .hmg-login__icon,
            .hmg-login__remember,
            .hmg-login__submit {
                font-size: 20px;
            }

            .hmg-login__actions {
                grid-template-columns: 1fr;
            }

            .hmg-login__remember input {
                height: 34px;
                width: 34px;
            }

            .hmg-login-platform {
                bottom: 14px;
                font-size: 13px;
            }
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="hmg-login-wrap">
        <main class="hmg-login">
            <div class="hmg-login__brand">
                <img src="{{ asset('backend_assets/images/hmglogo.png') }}" alt="{{ config('cms.name') }}">
            </div>

            <div class="hmg-login__body">
                <h1 class="hmg-login__title">Sign in to your workspace</h1>

                @if (session('error'))
                    <div class="alert alert-danger hmg-login__alert">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="post">
                    @csrf

                    <div class="hmg-login__field">
                        <label class="sr-only" for="email">Email</label>
                        <div class="hmg-login__input-group">
                            <input id="email" type="text" name="email" value="{{ old('email') }}"
                                class="hmg-login__input" placeholder="Email" autocomplete="email" autofocus>
                            <span class="fas fa-envelope hmg-login__icon"></span>
                        </div>
                        @if($errors->has('email'))
                            <span class="text-danger hmg-login__error">{{ $errors->first('email') }}</span>
                        @endif
                    </div>

                    <div class="hmg-login__field">
                        <label class="sr-only" for="password">Password</label>
                        <div class="hmg-login__input-group">
                            <input id="password" type="password" name="password" class="hmg-login__input"
                                placeholder="Password" autocomplete="current-password">
                            <span class="fas fa-lock hmg-login__icon"></span>
                        </div>
                        @if($errors->has('password'))
                            <span class="text-danger hmg-login__error">{{ $errors->first('password') }}</span>
                        @endif
                    </div>

                    <div class="hmg-login__actions">
                        <label class="hmg-login__remember" for="remember">
                            <input type="checkbox" id="remember" name="remember">
                            <span>Remember Me</span>
                        </label>

                        <button type="submit" class="hmg-login__submit">Sign In</button>
                    </div>
                </form>
            </div>
        </main>

    </div>

    <div class="hmg-login-platform">
        <i class="fas fa-layer-group"></i>
        Sản phẩm xây dựng trên nền tảng
        <a href="https://hm360.vn/" target="_blank" rel="noopener noreferrer">HM360</a>
    </div>

    <script src="{{ asset('backend_assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('backend_assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

</body>

</html>
