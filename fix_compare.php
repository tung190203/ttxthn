<?php

// 1. Fix placeholder in compare.blade.php
$file = 'resources/views/frontend/guest/compare.blade.php';
$content = file_get_contents($file);
$content = str_replace('Nhập ghi chú cá nhân...', "{{ __('app.enter_personal_notes') }}", $content);
// 2. Fix JS highlighting
$content = str_replace("highlightBestWorst('.price-cell', false);", "highlightBestWorst('.price-cell', true);", $content);
file_put_contents($file, $content);

// 3. Fix dashboard.blade.php
$file2 = 'resources/views/frontend/guest/dashboard.blade.php';
$content2 = file_get_contents($file2);
$content2 = str_replace("'Phiên so sánh ' . \$session->created_at", "__('app.compare_session') . ' ' . \$session->created_at", $content2);
file_put_contents($file2, $content2);

// 4. Fix GuestDashboardController.php
$file3 = 'app/Http/Controllers/GuestDashboardController.php';
$content3 = file_get_contents($file3);
$content3 = str_replace("'Phiên so sánh ' . now()", "__('app.compare_session') . ' ' . now()", $content3);
file_put_contents($file3, $content3);

// 5. Add translation to app.php
$vi = [
    'enter_personal_notes' => 'Nhập ghi chú cá nhân...',
];

$en = [
    'enter_personal_notes' => 'Enter personal notes...',
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
echo "Done fixing compare";
