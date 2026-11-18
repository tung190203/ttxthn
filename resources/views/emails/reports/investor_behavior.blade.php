<x-mail::message>
# Báo cáo Hành vi Nhà đầu tư tuần này

Kính gửi Ban Lãnh Đạo,

Dưới đây là tóm tắt điều hành về hành vi của Nhà đầu tư trong tuần vừa qua:

- **Nhà đầu tư đăng ký mới**: {{ $data['newGuestsCount'] }}
- **Lượt quan tâm (Bookmark) mới**: {{ $data['totalBookmarks'] }}
- **Tỷ lệ chuyển đổi**: {{ $data['conversionRate'] }}%

Vui lòng truy cập hệ thống quản trị để xem biểu đồ và số liệu chi tiết.

<x-mail::button :url="config('app.url') . '/backend/reports/investor-behavior'">
Xem Báo cáo Chi tiết
</x-mail::button>

Trân trọng,<br>
{{ config('app.name') }}
</x-mail::message>
