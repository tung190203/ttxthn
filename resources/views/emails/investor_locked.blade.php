<x-mail::message>
# Thông báo khóa tài khoản

Chào {{ $guest->name }},

Tài khoản nhà đầu tư của bạn trên hệ thống Trung tâm Xúc tiến Đầu tư, Thương mại, Du lịch thành phố Hà Nội (HPA) đã bị tạm khóa.

**Lý do khóa:**
<x-mail::panel>
{{ $reason }}
</x-mail::panel>

Nếu bạn cho rằng đây là sự nhầm lẫn hoặc cần được hỗ trợ, vui lòng liên hệ với Ban Quản trị HPA để được giải quyết.

Trân trọng,<br>
Ban Quản trị HPA
</x-mail::message>
