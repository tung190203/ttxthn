<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông báo cập nhật dự án</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, Helvetica, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding:30px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 6px 20px rgba(0,0,0,0.08);">

                <!-- Header -->
                <tr>
                    <td style="background:#0d6efd; padding:24px; text-align:center;">
                        <h2 style="margin:0; color:#ffffff; font-size:22px;">
                            Dự án quan tâm vừa được cập nhật!
                        </h2>
                        <p style="margin:6px 0 0; color:#e9ecef; font-size:14px;">
                            Bản đồ Đầu tư Hà Nội
                        </p>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:30px; line-height: 1.6; color:#333333; font-size:15px;">
                        <p>Xin chào <strong>{{ $guest->name }}</strong>,</p>

                        <p>Chúng tôi xin thông báo rằng dự án <strong>{{ $project->name }}</strong> mà bạn đang quan tâm <strong>{{ $updateMessage }}</strong>.</p>

                        <div style="margin:20px 0; padding:16px; background:#e9ecef; border-left:4px solid #0d6efd; border-radius:4px; font-size:14px;">
                            <strong>Thông tin cập nhật:</strong><br>
                            • Tên dự án: {{ $project->name }}<br>
                            • Trạng thái: {{ $project->status === 'approved' ? 'Đã phê duyệt' : 'Chờ phê duyệt' }}<br>
                        </div>

                        <p>Vui lòng nhấp vào nút dưới đây để xem thông tin chi tiết về các cập nhật mới nhất của dự án này.</p>

                        <div style="text-align: center; margin: 30px 0;">
                            <a href="{{ $url }}" style="background-color: #0d6efd; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);">
                                Xem chi tiết dự án
                            </a>
                        </div>

                        <p style="margin-top: 25px; font-size: 13px; color: #999;">
                            Nếu bạn không muốn nhận thông báo này nữa, bạn có thể đăng nhập vào hệ thống và huỷ quan tâm (bỏ yêu thích) dự án này.
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#fafafa; padding:18px 30px; font-size:13px; color:#777; border-top: 1px solid #eeeeee;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    Thời gian: <strong>{{ now()->format('d/m/Y H:i') }}</strong>
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
