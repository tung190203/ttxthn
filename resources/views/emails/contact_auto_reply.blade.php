<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận thông tin liên hệ</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, Helvetica, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding:30px 0;">
    <tr>
        <td align="center">
            <!-- Container -->
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 6px 20px rgba(0,0,0,0.08);">
                
                <!-- Header -->
                <tr>
                    <td style="background:#5422C6; padding:24px;">
                        <h2 style="margin:0; color:#ffffff; font-size:22px;">
                            Xác nhận thông tin liên hệ
                        </h2>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:30px; font-size:15px; color:#333; line-height:1.6;">
                        <p>Chào <strong>{{ $contact->name }}</strong>,</p>
                        
                        @php
                            $setting = \App\Models\Setting::getAllSetting();
                            $locale = app()->getLocale();
                            $autoReplyMessage = $setting['contact_auto_reply_message'][$locale] ?? 'Hệ thống đã ghi nhận yêu cầu của bạn, chuyển cho bộ phận chuyên môn phụ trách và sẽ trả lời bạn trong thời gian sớm nhất.';
                        @endphp
                        
                        <div>{!! $autoReplyMessage !!}</div>
                        
                        <p>Dưới đây là thông tin bạn đã gửi:</p>
                        
                        <div style="background:#f8f9fb; border:1px solid #e6e8ec; padding:16px; border-radius:6px; margin-top: 15px; margin-bottom: 15px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                <li><strong>Họ tên:</strong> {{ $contact->name }}</li>
                                <li><strong>Email:</strong> {{ $contact->email }}</li>
                                @if(!empty($contact->phone))
                                <li><strong>Số điện thoại:</strong> {{ $contact->phone }}</li>
                                @endif
                                <li><strong>Lĩnh vực quan tâm:</strong> {{ optional($contact->projectIndustry)->name ?? 'Không xác định' }}</li>
                                <li><strong>Nội dung:</strong> {{ $contact->message }}</li>
                            </ul>
                        </div>
                        
                        <p>Xin trân trọng cảm ơn,</p>
                        <p><strong>Ban Quản Trị Hệ Thống</strong></p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#fafafa; padding:18px 30px; font-size:13px; color:#777;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    Thời gian gửi: <strong>{{ now()->format('d/m/Y H:i') }}</strong>
                                </td>
                                <td align="right" style="color:#999;">
                                    © {{ date('Y') }} hanoiinvestment
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
