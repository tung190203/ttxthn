<?php

$filesToUpdate = [
    'resources/views/frontend/guest/compare.blade.php',
    'resources/views/frontend/guest/compare_pdf.blade.php',
    'resources/views/frontend/guest/dashboard.blade.php',
    'resources/views/frontend/home/partials/search_results_ajax.blade.php'
];

foreach ($filesToUpdate as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Fix hardcoded number_format for price and area
    // number_format($p->price, 0, ',', '.') -> formatDecimalByLocale($p->price, null, 0)
    $content = preg_replace("/number_format\(\s*\\$([^,]+)\s*,\s*0\s*,\s*','\s*,\s*'\.'\s*\)/", "formatDecimalByLocale($$1, null, 0)", $content);
    // number_format($project->investment_capital, 0, ',', '.')
    $content = preg_replace("/number_format\(\s*\\$([^,]+)\s*,\s*0\s*,\s*','\s*,\s*'\.'\s*\)/", "formatDecimalByLocale($$1, null, 0)", $content);
    // number_format($item['price'], 0, ',','.' )
    $content = preg_replace("/number_format\(\s*\\$([^,]+)\s*,\s*0\s*,\s*','\s*,\s*'\.'\s*\)/", "formatDecimalByLocale($$1, null, 0)", $content);
    
    // Same for 2 decimal places: number_format($project->land_area, 2, ',', '.')
    $content = preg_replace("/number_format\(\s*\\$([^,]+)\s*,\s*2\s*,\s*','\s*,\s*'\.'\s*\)/", "formatDecimalByLocale($$1)", $content);

    // Replace ' tỷ VNĐ' with ' ' . __('app.billion_vnd')
    $content = str_replace("' tỷ VNĐ'", "' ' . __('app.billion_vnd')", $content);
    $content = str_replace(" tỷ VNĐ", " {{ __('app.billion_vnd') }}", $content);

    // Replace 'Đang cập nhật'
    $content = str_replace("'Đang cập nhật'", "__('app.updating')", $content);
    
    // Save file
    file_put_contents($file, $content);
}

// Translations to add
$vi = [
    'personal_dashboard' => 'Bảng điều khiển cá nhân',
    'overview' => 'TỔNG QUAN',
    'search_history' => 'LỊCH SỬ TRA CỨU',
    'compare_session' => 'PHIÊN SO SÁNH',
    'new_updated_projects' => 'Dự án cập nhật mới',
    'favorites' => 'Mục yêu thích',
    'new_updates' => 'Cập nhật mới',
    'investment_project_suggestions' => 'Gợi ý dự án đầu tư',
    'no_data' => 'Chưa có dữ liệu.',
    'saved_date' => 'Ngày lưu:',
    'project' => 'Dự án',
    'news' => 'Tin tức',
    'notes' => 'Ghi chú:',
    'updated_time' => 'Thời gian cập nhật:',
    'project_name' => 'Tên dự án',
    'time' => 'Thời gian',
    'action' => 'Thao tác',
    'compare_session_name' => 'Tên phiên so sánh',
    'number_of_projects' => 'Số lượng dự án',
    'last_saved' => 'Lưu lần cuối',
    'review' => 'Xem lại',
    'download_pdf' => 'Tải PDF',
    'no_saved_compare_session' => 'Chưa có phiên so sánh nào được lưu',
    'compare_parallel_projects' => 'So sánh Dự án Song song',
    'back_to_dashboard' => 'Trở lại Bảng điều khiển',
    'export_pdf' => 'Xuất PDF',
    'standard_criteria_7' => '7 Tiêu chí chuẩn',
    'criteria_1' => 'Địa bàn & kết nối vùng',
    'criteria_2' => 'Quy mô diện tích',
    'criteria_3' => 'Tính chất pháp lý',
    'criteria_4' => 'Tổng vốn đầu tư',
    'criteria_5' => 'Ngành nghề ưu tiên',
    'criteria_6' => 'Tỷ lệ lấp đầy',
    'criteria_7' => 'Loại dự án',
    'my_notes' => 'Ghi chú của tôi',
    'show_all' => 'Hiện tất cả',
    'save_session_name' => 'Lưu tên phiên',
    'save_compare_session' => 'Lưu phiên so sánh',
    'criteria_detail' => 'Chi tiết tiêu chí',
    'download_doc' => 'Tải tài liệu',
];

$en = [
    'personal_dashboard' => 'Personal Dashboard',
    'overview' => 'OVERVIEW',
    'search_history' => 'SEARCH HISTORY',
    'compare_session' => 'COMPARE SESSIONS',
    'new_updated_projects' => 'Newly Updated Projects',
    'favorites' => 'Favorites',
    'new_updates' => 'New Updates',
    'investment_project_suggestions' => 'Investment Project Suggestions',
    'no_data' => 'No data available.',
    'saved_date' => 'Saved Date:',
    'project' => 'Project',
    'news' => 'News',
    'notes' => 'Notes:',
    'updated_time' => 'Updated Time:',
    'project_name' => 'Project Name',
    'time' => 'Time',
    'action' => 'Action',
    'compare_session_name' => 'Compare Session Name',
    'number_of_projects' => 'Number of Projects',
    'last_saved' => 'Last Saved',
    'review' => 'Review',
    'download_pdf' => 'Download PDF',
    'no_saved_compare_session' => 'No saved compare sessions',
    'compare_parallel_projects' => 'Compare Parallel Projects',
    'back_to_dashboard' => 'Back to Dashboard',
    'export_pdf' => 'Export PDF',
    'standard_criteria_7' => '7 Standard Criteria',
    'criteria_1' => 'Location & Regional Connectivity',
    'criteria_2' => 'Area Scale',
    'criteria_3' => 'Legal Status',
    'criteria_4' => 'Total Investment Capital',
    'criteria_5' => 'Priority Industries',
    'criteria_6' => 'Occupancy Rate',
    'criteria_7' => 'Project Type',
    'my_notes' => 'My Notes',
    'show_all' => 'Show All',
    'save_session_name' => 'Save Session Name',
    'save_compare_session' => 'Save Compare Session',
    'criteria_detail' => 'Criteria Detail',
    'download_doc' => 'Download Document',
];

foreach (['vi' => $vi, 'en' => $en] as $lang => $trans) {
    $file = "resources/lang/{$lang}/app.php";
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $insert = "";
        foreach ($trans as $k => $v) {
            if (!str_contains($content, "'$k'")) {
                $insert .= "    '$k' => '$v',\n";
            }
        }
        if ($insert) {
            $content = preg_replace("/(return\s*\[)/", "$1\n" . $insert, $content);
            file_put_contents($file, $content);
        }
    }
}
echo "Done";
