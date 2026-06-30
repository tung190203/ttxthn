<?php
header('Access-Control-Allow-Origin: *');
if (!isset($_GET['image_path'])) {
    exit();
}

$output_width = $_GET['output_width'];
$output_height = $_GET['output_height'];
$image_path = $_GET['image_path'];

// BẢO MẬT: Kiểm tra tính hợp lệ của đường dẫn ảnh
$is_valid_url = filter_var($image_path, FILTER_VALIDATE_URL) && preg_match('/^https?:\/\//i', $image_path);
// Đảm bảo nếu là file nội bộ thì phải nằm trong thư mục uploads
$real_base = realpath(__DIR__ . '/../../public/uploads');
$real_path = realpath($image_path);
$is_local_upload = ($real_base && $real_path && strpos($real_path, $real_base) === 0);

if (!$is_valid_url && !$is_local_upload) {
    http_response_code(403);
    exit('Đường dẫn ảnh không hợp lệ');
}

if ($is_valid_url) {
    // Ngăn chặn SSRF nếu là URL
    $host = parse_url($image_path, PHP_URL_HOST);
    if (preg_match('/^(localhost|127\.0\.0\.1|10\.|172\.(1[6-9]|2[0-9]|3[0-1])\.|192\.168\.)/i', $host)) {
        http_response_code(403);
        exit('Truy cập bị từ chối');
    }
}

function get_image_mime_type(string $image_path): ?string
{
    if (file_exists($image_path)) {
        return image_type_to_mime_type(exif_imagetype($image_path));
    } else {
        return NULL;
    }
}

$image_mime_type = get_image_mime_type($image_path);

if ($image_mime_type == 'image/png') {
    $imagecreatefrom_x  = 'imagecreatefrompng';
    $image_display      = 'imagepng';
} elseif ($image_mime_type == 'image/jpeg') {
    $imagecreatefrom_x = 'imagecreatefromjpeg';
    $image_display      = 'imagejpeg';
} else {
    $imagecreatefrom_x = 'imagecreatefromjpeg';
    $image_display      = 'imagejpeg';
    $image_mime_type    = 'image/jpeg';
    $image_path         = '../uploads/images/default-thumbnail.jpg';
}


// Get new dimensions
list($image_width, $image_height) = getimagesize($image_path);

$image_ratio = $image_width / $image_height;
$scale_x = $image_width / $output_width;
$scale_y = $image_height / $output_height;
if ($scale_x > $scale_y) {
    $new_width = $output_width;
    $new_height = $new_width / $image_ratio;
    $left = 0;
    $top = ($output_height - $new_height) / 2;
} else {
    $new_height = $output_height;
    $new_width = $new_height * $image_ratio;
    $left = ($output_width - $new_width) / 2;
    $top = 0;
}

$image_p = imagecreatetruecolor($output_width, $output_height);

$trans_colour = imagecolorallocatealpha($image_p, 0, 0, 0, 127);
imagefill($image_p, 0, 0, $trans_colour);

imagealphablending($image_p, false);
imagesavealpha($image_p, true);

$image = $imagecreatefrom_x($image_path);
imagecopyresampled($image_p, $image, (int)$left, (int)$top, 0, 0, (int)$new_width, (int)$new_height, $image_width, $image_height);

// Output
header('Content-type: image/png');
imagepng($image_p);
imagedestroy($image);
imagedestroy($image_p);
