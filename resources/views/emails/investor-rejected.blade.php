<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông báo kết quả xét duyệt tài khoản</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, Helvetica, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding:30px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 6px 20px rgba(0,0,0,0.08);">

                <!-- Header -->
                <tr>
                    <td style="background:#dc3545; padding:24px; text-align:center;">
                        <h2 style="margin:0; color:#ffffff; font-size:22px;">
                            Thông báo kết quả xét duyệt tài khoản
                        </h2>
                        <p style="margin:6px 0 0; color:#f8d7da; font-size:14px;">
                            Bản đồ Đầu tư Hà Nội
                        </p>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:30px; line-height: 1.6; color:#333333; font-size:15px;">
                        <p>Xin chào <strong>{{ $guest->name }}</strong>,</p>

                        <p>Sau khi xem xét hồ sơ đăng ký tài khoản nhà đầu tư của bạn trên hệ thống <strong>Bản đồ Đầu tư Hà Nội</strong>, Quản trị hệ thống rất tiếc phải thông báo rằng tài khoản của bạn <strong style="color:#dc3545;">chưa được phê duyệt</strong> vào thời điểm này.</p>

                        <div style="margin:20px 0; padding:16px; background:#f8d7da; border-left:4px solid #dc3545; border-radius:4px; font-size:14px;">
                            <strong>Thông tin yêu cầu:</strong><br>
                            • Họ tên: {{ $guest->name }}<br>
                            • Email: {{ $guest->email }}<br>
                            • Loại tài khoản: {{ $guest->investor_type === 'fdi' ? 'FDI' : 'Tổ chức' }}<br>
                            @if($guest->organization_name)
                            • Tên tổ chức: {{ $guest->organization_name }}
                            @endif
                        </div>

                        @if($reason)
                        <div style="margin:20px 0; padding:16px; background:#fff3cd; border-left:4px solid #ffc107; border-radius:4px; font-size:14px;">
                            <strong>Lý do:</strong><br>
                            {{ $reason }}
                        </div>
                        @endif

                        <p>Nếu bạn cho rằng có sự nhầm lẫn hoặc muốn cung cấp thêm thông tin, vui lòng liên hệ với chúng tôi để được hỗ trợ.</p>

                        <p style="margin-top: 25px; font-size: 13px; color: #999;">
                            Email này được gửi tự động từ hệ thống. Vui lòng không trả lời trực tiếp email này.
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#fafafa; padding:18px 30px; font-size:13px; color:#777; border-top: 1px solid #eeeeee;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    Ngày thông báo: <strong>{{ now()->format('d/m/Y H:i') }}</strong>
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
