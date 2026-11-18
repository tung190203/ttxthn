<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cảnh báo: Tài khoản nhà đầu tư bị tạm khóa</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, Helvetica, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding:30px 0;">
    <tr>
        <td align="center">
            <!-- Container -->
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 6px 20px rgba(0,0,0,0.08);">
                
                <!-- Header -->
                <tr>
                    <td style="background:#dc3545; padding:24px; text-align:center;">
                        <h2 style="margin:0; color:#ffffff; font-size:22px;">
                            CẢNH BÁO AN NINH HỆ THỐNG
                        </h2>
                        <p style="margin:6px 0 0; color:#ffebee; font-size:14px;">
                            Phát hiện đăng nhập bất thường hoặc sai mật khẩu nhiều lần
                        </p>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:30px; line-height: 1.6; color:#333333; font-size:15px;">
                        <p>Kính gửi <strong>Quản trị viên hệ thống</strong>,</p>
                        
                        <p>Hệ thống vừa tự động tạm khóa một tài khoản nhà đầu tư do phát hiện dấu hiệu bất thường:</p>

                        <table width="100%" cellpadding="8" cellspacing="0" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; margin: 20px 0; font-size: 14px;">
                            <tr>
                                <td style="width: 160px; font-weight: bold; border-bottom: 1px solid #dee2e6;">Họ tên NĐT:</td>
                                <td style="border-bottom: 1px solid #dee2e6;">{{ $guest->name }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; border-bottom: 1px solid #dee2e6;">Email tài khoản:</td>
                                <td style="border-bottom: 1px solid #dee2e6;">{{ $guest->email }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; border-bottom: 1px solid #dee2e6;">Số điện thoại:</td>
                                <td style="border-bottom: 1px solid #dee2e6;">{{ $guest->phone ?? 'Chưa cập nhật' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; border-bottom: 1px solid #dee2e6;">Loại tài khoản:</td>
                                <td style="border-bottom: 1px solid #dee2e6;">{{ $guest->investor_type }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; border-bottom: 1px solid #dee2e6;">Lý do khóa:</td>
                                <td style="border-bottom: 1px solid #dee2e6; color: #dc3545; font-weight: bold;">{{ $reason }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold; border-bottom: 1px solid #dee2e6;">Địa chỉ IP truy cập:</td>
                                <td style="border-bottom: 1px solid #dee2e6;"><code>{{ $ip }}</code></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Thời điểm phát hiện:</td>
                                <td>{{ now()->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        </table>

                        <p>Tài khoản đã được chuyển sang trạng thái <strong>Tạm khóa (locked)</strong> để bảo vệ dữ liệu. Vui lòng kiểm tra trên giao diện Quản trị hệ thống.</p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#fafafa; padding:18px 30px; font-size:13px; color:#777; border-top: 1px solid #eeeeee;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    Hệ thống Cảnh báo Bảo mật Tự động
                                </td>
                                <td align="right" style="color:#999;">
                                    © {{ date('Y') }} Hanoi Investment
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
            <!-- End Container -->
        </td>
    </tr>
</table>
</body>
</html>
