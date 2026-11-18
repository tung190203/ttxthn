<?php

$file = 'resources/views/frontend/guest/compare.blade.php';
$content = file_get_contents($file);

$replacements = [
    'So sánh Dự án Song song' => "{{ __('app.compare_parallel_projects') }}",
    'Trở lại Bảng điều khiển' => "{{ __('app.back_to_dashboard') }}",
    'Xuất PDF' => "{{ __('app.export_pdf') }}",
    '7 Tiêu chí chuẩn' => "{{ __('app.standard_criteria_7') }}",
    '1. Địa bàn & kết nối vùng' => "1. {{ __('app.criteria_1') }}",
    'Địa bàn & kết nối vùng' => "{{ __('app.criteria_1') }}",
    '2. Quy mô diện tích' => "2. {{ __('app.criteria_2') }}",
    'Quy mô diện tích' => "{{ __('app.criteria_2') }}",
    '3. Tính chất pháp lý' => "3. {{ __('app.criteria_3') }}",
    'Tính chất pháp lý' => "{{ __('app.criteria_3') }}",
    '4. Tổng vốn đầu tư' => "4. {{ __('app.criteria_4') }}",
    'Tổng vốn đầu tư' => "{{ __('app.criteria_4') }}",
    '5. Ngành nghề ưu tiên' => "5. {{ __('app.criteria_5') }}",
    '6. Tỷ lệ lấp đầy' => "6. {{ __('app.criteria_6') }}",
    '7. Loại dự án' => "7. {{ __('app.criteria_7') }}",
    'Ghi chú của tôi' => "{{ __('app.my_notes') }}",
    'Hiện tất cả' => "{{ __('app.show_all') }}",
    'Lưu tên phiên' => "{{ __('app.save_session_name') }}",
    'Lưu phiên so sánh' => "{{ __('app.save_compare_session') }}",
    'Chi tiết tiêu chí' => "{{ __('app.criteria_detail') }}",
    'Tải tài liệu ' => "{{ __('app.download_doc') }} "
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

file_put_contents($file, $content);

$file2 = 'resources/views/frontend/guest/dashboard.blade.php';
$content2 = file_get_contents($file2);

$replacements2 = [
    'Bảng điều khiển cá nhân' => "{{ __('app.personal_dashboard') }}",
    'TỔNG QUAN' => "{{ __('app.overview') }}",
    'LỊCH SỬ TRA CỨU' => "{{ __('app.search_history') }}",
    'PHIÊN SO SÁNH' => "{{ __('app.compare_session') }}",
    'Dự án quan tâm' => "{{ __('app.interested_projects') }}",
    'Tin tức quan tâm' => "{{ __('app.interested_news') }}",
    'Dự án cập nhật mới' => "{{ __('app.new_updated_projects') }}",
    'Lịch sử tra cứu' => "{{ __('app.search_history') }}",
    'Mục yêu thích' => "{{ __('app.favorites') }}",
    'Cập nhật mới' => "{{ __('app.new_updates') }}",
    'Gợi ý dự án đầu tư' => "{{ __('app.investment_project_suggestions') }}",
    'Chưa có dữ liệu.' => "{{ __('app.no_data') }}",
    'Ngày lưu:' => "{{ __('app.saved_date') }}",
    '>Dự án<' => ">{{ __('app.project') }}<",
    '>Tin tức<' => ">{{ __('app.news') }}<",
    'Ghi chú:' => "{{ __('app.notes') }}",
    'Thời gian cập nhật:' => "{{ __('app.updated_time') }}",
    'Tên dự án' => "{{ __('app.project_name') }}",
    'Thời gian<' => "{{ __('app.time') }}<",
    'Thao tác<' => "{{ __('app.action') }}<",
    'Tên phiên so sánh' => "{{ __('app.compare_session_name') }}",
    'Số lượng dự án' => "{{ __('app.number_of_projects') }}",
    'Lưu lần cuối' => "{{ __('app.last_saved') }}",
    'Xem lại' => "{{ __('app.review') }}",
    'Tải PDF' => "{{ __('app.download_pdf') }}",
    'Chưa có phiên so sánh nào được lưu' => "{{ __('app.no_saved_compare_session') }}"
];

foreach ($replacements2 as $search => $replace) {
    $content2 = str_replace($search, $replace, $content2);
}

file_put_contents($file2, $content2);

echo "Done translating";
