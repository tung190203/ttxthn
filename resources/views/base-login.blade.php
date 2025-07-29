<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Base Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #f3f4f6;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333333;
        }

        .login-box input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 15px;
            border: 1px solid #cccccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .login-box button:hover {
            background-color: #0056b3;
        }

        .error-message {
            background-color: #ffe0e0;
            color: #cc0000;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>🔒 Nhập mật khẩu để vào website</h2>

        @if ($errors->any())
            <div class="error-message">{{ $errors->first('password') }}</div>
        @endif

        <form method="POST" action="{{ route('base_login_post') }}">
            @csrf
            <input type="password" name="password" placeholder="Mật khẩu" required autofocus>
            <button type="submit">Vào hệ thống</button>
        </form>
    </div>
</body>
</html>
