<?php
// proxy.php
if (!isset($_GET['url'])) {
    http_response_code(400);
    exit('Thiếu URL');
}
$url = $_GET['url'];

// BẢO MẬT: Chỉ cho phép URL dạng http:// hoặc https:// (chống đọc file hệ thống như /etc/passwd hoặc .env)
if (!preg_match('/^https?:\/\//i', $url)) {
    http_response_code(403);
    exit('URL không hợp lệ');
}

// BẢO MẬT: Chống SSRF (chặn request vào server nội bộ)
$host = parse_url($url, PHP_URL_HOST);
if (preg_match('/^(localhost|127\.0\.0\.1|10\.|172\.(1[6-9]|2[0-9]|3[0-1])\.|192\.168\.)/i', $host)) {
    http_response_code(403);
    exit('Yêu cầu bị từ chối');
}

// header CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: audio/mpeg"); // Fix mime type

// Tắt lỗi để không lộ đường dẫn, trả lại file mp3
@readfile($url);