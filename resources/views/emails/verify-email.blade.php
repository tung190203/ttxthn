<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác thực địa chỉ email - Trung tâm Xúc tiến Đầu tư Hà Nội</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, Helvetica, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding:30px 0;">
    <tr>
        <td align="center">
            <!-- Container -->
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 6px 20px rgba(0,0,0,0.08);">
                
                <!-- Header -->
                <tr>
                    <td style="background:#0d6efd; padding:24px; text-align:center;">
                        <h2 style="margin:0; color:#ffffff; font-size:22px;">
                            Xác thực tài khoản nhà đầu tư
                        </h2>
                        <p style="margin:6px 0 0; color:#e0edff; font-size:14px;">
                            Bản đồ Đầu tư Hà Nội
                        </p>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:30px; line-height: 1.6; color:#333333; font-size:15px;">
                        <p>Xin chào <strong>{{ $guest->name }}</strong>,</p>
                        
                        <p>Cảm ơn bạn đã đăng ký tài khoản nhà đầu tư trên hệ thống Bản đồ Đầu tư Hà Nội. Để hoàn tất đăng ký và kích hoạt tài khoản, vui lòng nhấn vào nút bên dưới để xác thực địa chỉ email của bạn:</p>

                        <div style="text-align: center; margin: 30px 0;">
                            <a href="{{ $verifyUrl }}" style="background-color: #0d6efd; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);">
                                Xác thực Email ngay
                            </a>
                        </div>

                        <p style="font-size: 13px; color: #6c757d;">
                            Nếu nút trên không hoạt động, bạn có thể sao chép và dán liên kết sau vào trình duyệt:<br>
                            <a href="{{ $verifyUrl }}" style="color: #0d6efd; word-break: break-all;">{{ $verifyUrl }}</a>
                        </p>

                        @if($guest->investor_type !== 'individual')
                        <div style="margin-top: 20px; padding: 12px; background-color: #fff3cd; border-left: 4px solid #ffc107; font-size: 14px; color: #856404;">
                            <strong>Lưu ý:</strong> Tài khoản của bạn thuộc loại <em>{{ $guest->investor_type === 'fdi' ? 'FDI' : 'Tổ chức' }}</em>. Sau khi xác thực email, tài khoản sẽ được chuyển sang trạng thái <strong>"Chờ xác thực"</strong> để Quản trị hệ thống xem xét và phê duyệt trước khi có thể sử dụng đầy đủ quyền hạn.
                        </div>
                        @endif

                        <p style="margin-top: 25px; font-size: 13px; color: #999;">
                            Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#fafafa; padding:18px 30px; font-size:13px; color:#777; border-top: 1px solid #eeeeee;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    Thời gian tạo: <strong>{{ now()->format('d/m/Y H:i') }}</strong>
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
