<x-mail::message>
# Yêu cầu đặt lại mật khẩu

Chào {{ $guest->name }},

Ban Quản trị HPA đã gửi yêu cầu đặt lại mật khẩu cho tài khoản nhà đầu tư của bạn.

Vui lòng nhấp vào nút bên dưới để thiết lập mật khẩu mới. Liên kết này sẽ có hiệu lực trong vòng **24 giờ**.

<x-mail::button :url="$url" color="primary">
Đặt lại mật khẩu
</x-mail::button>

Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.

Trân trọng,<br>
Ban Quản trị HPA
</x-mail::message>
