<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tài khoản nhà đầu tư đã được phê duyệt</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, Helvetica, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding:30px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 6px 20px rgba(0,0,0,0.08);">

                <!-- Header -->
                <tr>
                    <td style="background:#198754; padding:24px; text-align:center;">
                        <h2 style="margin:0; color:#ffffff; font-size:22px;">
                            Tài khoản đã được phê duyệt
                        </h2>
                        <p style="margin:6px 0 0; color:#d1e7dd; font-size:14px;">
                            Bản đồ Đầu tư Hà Nội
                        </p>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:30px; line-height: 1.6; color:#333333; font-size:15px;">
                        <p>Xin chào <strong>{{ $guest->name }}</strong>,</p>

                        <p>Chúng tôi vui mừng thông báo rằng tài khoản nhà đầu tư của bạn trên hệ thống <strong>Bản đồ Đầu tư Hà Nội</strong> đã được Quản trị hệ thống xem xét và <strong style="color:#198754;">phê duyệt thành công</strong>.</p>

                        <div style="margin:20px 0; padding:16px; background:#d1e7dd; border-left:4px solid #198754; border-radius:4px; font-size:14px;">
                            <strong>Thông tin tài khoản:</strong><br>
                            • Họ tên: {{ $guest->name }}<br>
                            • Email: {{ $guest->email }}<br>
                            • Loại tài khoản: {{ $guest->investor_type === 'fdi' ? 'FDI' : 'Tổ chức' }}<br>
                            @if($guest->organization_name)
                            • Tên tổ chức: {{ $guest->organization_name }}<br>
                            @endif
                            • Trạng thái: <strong>Hoạt động</strong>
                        </div>

                        <p>Bạn có thể đăng nhập ngay vào hệ thống để sử dụng đầy đủ các tính năng dành cho nhà đầu tư.</p>

                        <div style="text-align: center; margin: 30px 0;">
                            <a href="{{ url('/') }}" style="background-color: #198754; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3);">
                                Đăng nhập ngay
                            </a>
                        </div>

                        <p style="margin-top: 25px; font-size: 13px; color: #999;">
                            Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email hoặc điện thoại.
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#fafafa; padding:18px 30px; font-size:13px; color:#777; border-top: 1px solid #eeeeee;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    Ngày phê duyệt: <strong>{{ now()->format('d/m/Y H:i') }}</strong>
                                </td>
                                <td align="right" style="color:#999;">
                                    © {{ date('Y') }} Hanoi Investment
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
