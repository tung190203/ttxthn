<!DOCTYPE html>
<html>
<head>
    <title>Mật khẩu mới</title>
    <style>
        body { font-family: sans-serif; background: #eef2f7; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .box { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .password { font-size: 1.5rem; color: #333; background: #f8f9fa; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="box">
        <h3>Mật khẩu hiện tại</h3>
        <div class="password">{{ $password }}</div>
        <p style="color:gray;">Sử dụng mật khẩu này để login. Sau khi login, mật khẩu này sẽ không còn hiệu lực.</p>
    </div>
</body>
</html>
