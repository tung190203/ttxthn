<?php

// 1. Update project_detail.blade.php
$file1 = '/Users/thanhtung/Desktop/trungtamxttmhn/resources/views/frontend/home/project_detail.blade.php';
$content1 = file_get_contents($file1);

$search1 = '<a href="{{ asset($file) }}" target="_blank" rel="noopener noreferrer" style="color: #1A73E8; font-weight: 600; font-size: 14px; text-decoration: none;">';
$replace1 = <<<HTML
@php
    \$isAllowed = false;
    if (auth('guest')->check()) {
        \$invType = auth('guest')->user()->investor_type;
        if (in_array(\$invType, ['organization', 'fdi'])) {
            \$isAllowed = true;
        }
    }
@endphp
                                @if(\$isAllowed)
                                <a href="{{ asset(\$file) }}" target="_blank" rel="noopener noreferrer" style="color: #1A73E8; font-weight: 600; font-size: 14px; text-decoration: none;">
HTML;

$content1 = str_replace($search1, $replace1, $content1);

$search2 = 'Xem chi tiết &nbsp;<i class="far fa-arrow-right"></i>
                                </a>';
$replace2 = <<<HTML
Xem chi tiết &nbsp;<i class="far fa-arrow-right"></i>
                                </a>
                                @else
                                <a href="#" onclick="Swal.fire('Truy cập bị từ chối', 'Văn bản này thuộc mức Nội bộ. Chỉ tài khoản Tổ chức hoặc FDI mới được phép tải/xem tài liệu này.', 'warning'); return false;" style="color: #999; font-weight: 600; font-size: 14px; text-decoration: none; cursor: not-allowed;">
                                    <i class="far fa-lock"></i> Xem chi tiết
                                </a>
                                @endif
HTML;

$content1 = str_replace($search2, $replace2, $content1);
file_put_contents($file1, $content1);
echo "Updated project_detail.blade.php\n";


// 2. Update AIChatController.php
$file2 = '/Users/thanhtung/Desktop/trungtamxttmhn/app/Http/Controllers/AIChatController.php';
$content2 = file_get_contents($file2);

$search3 = 'public function chat(Request $request)
    {';
$replace3 = <<<PHP
public function chat(Request \$request)
    {
        if (!auth('guest')->check() && !auth('web')->check()) {
            \$ip = request()->ip();
            \$cacheKey = 'ai_chat_limit_' . \$ip;
            \$attempts = cache()->get(\$cacheKey, 0);
            \$limit = 5; 
            if (\$attempts >= \$limit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã hết lượt hỏi miễn phí ('.\$limit.' lượt/ngày). Vui lòng đăng nhập để tiếp tục sử dụng Trợ lý ảo AI không giới hạn.',
                    'limit_reached' => true
                ], 403);
            }
            cache()->put(\$cacheKey, \$attempts + 1, now()->endOfDay());
        }
PHP;

$content2 = str_replace($search3, $replace3, $content2);
file_put_contents($file2, $content2);
echo "Updated AIChatController.php\n";

