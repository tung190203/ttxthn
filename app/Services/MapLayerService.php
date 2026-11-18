<?php

namespace App\Services;

use App\Models\MapLayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class MapLayerService
{
    /**
     * Kiểm tra kết nối tới URL endpoint của nguồn dữ liệu
     */
    public static function testConnection(string $url, string $type = 'GeoJSON', ?string $wmsLayers = null): array
    {
        $url = trim($url);

        if (empty($url)) {
            return [
                'success' => false,
                'code' => 'EMPTY_URL',
                'message' => 'Địa chỉ nguồn dữ liệu không được để trống.'
            ];
        }

        // Kiểm tra xem URL có phải là tài nguyên nội bộ trên cùng server không
        $isLocal = str_starts_with($url, '/') ||
            (filter_var($url, FILTER_VALIDATE_URL) && in_array(parse_url($url, PHP_URL_HOST), ['localhost', '127.0.0.1', request()->getHost()]));

        if ($isLocal) {
            $path = str_starts_with($url, '/') ? $url : (parse_url($url, PHP_URL_PATH) ?: '/');
            $query = parse_url($url, PHP_URL_QUERY);
            $fullPath = $path . ($query ? '?' . $query : '');

            // 1. Kiểm tra nếu là file tĩnh lưu trong public hoặc storage
            $cleanPath = ltrim(parse_url($url, PHP_URL_PATH), '/');
            $publicFilePath = public_path($cleanPath);
            $storageFilePath = storage_path('app/public/' . preg_replace('#^storage/#', '', $cleanPath));

            $targetFile = null;
            if (File::exists($publicFilePath) && !is_dir($publicFilePath)) {
                $targetFile = $publicFilePath;
            } elseif (File::exists($storageFilePath) && !is_dir($storageFilePath)) {
                $targetFile = $storageFilePath;
            }

            if ($targetFile) {
                if (str_ends_with(strtolower($targetFile), '.zip')) {
                    return [
                        'success' => true,
                        'code' => 200,
                        'count' => 1,
                        'feature_count' => 1,
                        'message' => 'Tệp Shapefile (.zip) nội bộ hợp lệ, sẵn sàng kết xuất bản đồ.'
                    ];
                }

                $jsonContent = File::get($targetFile);
                $data = json_decode($jsonContent, true);
                if (is_array($data)) {
                    $count = isset($data['features']) ? count($data['features']) : count($data);
                    return [
                        'success' => true,
                        'code' => 200,
                        'count' => $count,
                        'feature_count' => $count,
                        'message' => "Kết nối thành công! Đã lấy được {$count} đối tượng không gian (features)."
                    ];
                }
            }

            // 2. Chạy internal sub-request (tránh timeout trên php artisan serve single-thread)
            try {
                $subRequest = Request::create($fullPath, 'GET');
                $subResponse = app()->handle($subRequest);

                if ($subResponse->getStatusCode() === 200) {
                    $data = json_decode($subResponse->getContent(), true);
                    $count = 0;
                    if (isset($data['type']) && $data['type'] === 'FeatureCollection' && isset($data['features'])) {
                        $count = count($data['features']);
                    } elseif (is_array($data)) {
                        $count = count($data);
                    }

                    return [
                        'success' => true,
                        'code' => 200,
                        'count' => $count,
                        'feature_count' => $count,
                        'message' => "Kết nối thành công! Đã lấy được {$count} đối tượng không gian (features)."
                    ];
                } else {
                    return [
                        'success' => false,
                        'code' => $subResponse->getStatusCode(),
                        'message' => "Không kết nối được tới nguồn dữ liệu nội bộ. Mã lỗi: {$subResponse->getStatusCode()}."
                    ];
                }
            } catch (\Exception $e) {
                // Tiếp tục thử bằng HTTP nếu sub-request có lỗi
            }
        }

        // Với URL external / bên ngoài
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return [
                'success' => false,
                'code' => 'INVALID_URL',
                'message' => 'Địa chỉ nguồn dữ liệu không hợp lệ.'
            ];
        }

        try {
            if ($type === MapLayer::TYPE_GEOJSON || $type === MapLayer::TYPE_WFS) {
                $response = Http::timeout(8)->withOptions(['verify' => false])->get($url);

                if (!$response->successful()) {
                    return [
                        'success' => false,
                        'code' => $response->status(),
                        'message' => "Không kết nối được tới nguồn dữ liệu. Mã lỗi: {$response->status()}. Kiểm tra lại địa chỉ hoặc quyền truy cập."
                    ];
                }

                $data = $response->json();
                if (!$data || !is_array($data)) {
                    return [
                        'success' => false,
                        'code' => 'INVALID_GEOJSON',
                        'message' => 'Không kết nối được tới nguồn dữ liệu. Định dạng dữ liệu không phải GeoJSON hợp lệ.'
                    ];
                }

                $count = 0;
                if (isset($data['type']) && $data['type'] === 'FeatureCollection' && isset($data['features'])) {
                    $count = count($data['features']);
                } elseif (isset($data['type']) && $data['type'] === 'Feature') {
                    $count = 1;
                } else {
                    $count = count($data);
                }

                return [
                    'success' => true,
                    'code' => 200,
                    'count' => $count,
                    'feature_count' => $count,
                    'message' => "Kết nối thành công! Đã lấy được {$count} đối tượng không gian (features)."
                ];

            } elseif ($type === MapLayer::TYPE_WMS) {
                $client = Http::timeout(10)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    ])
                    ->withOptions(['verify' => false]);

                $capabilitiesUrl = $url;
                if (!str_contains($capabilitiesUrl, 'GetCapabilities')) {
                    $sep = str_contains($capabilitiesUrl, '?') ? '&' : '?';
                    $capabilitiesUrl .= $sep . 'SERVICE=WMS&REQUEST=GetCapabilities';
                }

                $response = null;
                try {
                    $response = $client->get($capabilitiesUrl);
                } catch (\Exception $e) {
                    // Thử fallback tiếp theo
                }

                // Nếu GetCapabilities không thành công hoặc bị chặn, thử lấy 1 tile mẫu bằng GetMap hoặc HEAD
                if (!$response || !$response->successful()) {
                    $testLayerName = !empty($wmsLayers) ? trim($wmsLayers) : (str_contains($url, 'terrestris') ? 'OSM-WMS' : '');
                    $sep = str_contains($url, '?') ? '&' : '?';
                    $sampleTileUrl = $url . $sep . 'SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&LAYERS=' . urlencode($testLayerName) . '&STYLES=&BBOX=105,20,106,21&WIDTH=256&HEIGHT=256&FORMAT=image/png&SRS=EPSG:4326';

                    try {
                        $tileRes = $client->get($sampleTileUrl);
                        if ($tileRes->successful()) {
                            return [
                                'success' => true,
                                'code' => 200,
                                'count' => 1,
                                'feature_count' => 1,
                                'message' => 'Kết nối thành công! Đã lấy được tile ảnh mẫu từ máy chủ WMS.'
                            ];
                        }
                    } catch (\Exception $e) {
                        // Thử HEAD
                    }

                    try {
                        $headRes = $client->head($url);
                        if ($headRes->successful()) {
                            return [
                                'success' => true,
                                'code' => 200,
                                'count' => 1,
                                'feature_count' => 1,
                                'message' => 'Kết nối thành công! Máy chủ WMS phản hồi khả dụng.'
                            ];
                        }
                    } catch (\Exception $e) {
                        // Bỏ qua
                    }

                    $statusCode = $response ? $response->status() : 'TIMEOUT';
                    return [
                        'success' => false,
                        'code' => $statusCode,
                        'message' => "Không kết nối được tới nguồn dữ liệu WMS. Mã lỗi: {$statusCode}. Kiểm tra lại địa chỉ hoặc quyền truy cập."
                    ];
                }

                $body = $response->body();
                preg_match_all('/<Layer[^>]*>[\s\S]*?<Name>([^<]+)<\/Name>/i', $body, $matches);
                $availableLayers = array_unique(array_filter($matches[1] ?? []));

                if (!empty($wmsLayers) && !empty($availableLayers)) {
                    $trimmedWms = trim($wmsLayers);
                    if (!in_array($trimmedWms, $availableLayers)) {
                        $layerListStr = implode(', ', array_slice($availableLayers, 0, 5));
                        return [
                            'success' => false,
                            'code' => 'LAYER_NOT_FOUND',
                            'message' => "Kết nối máy chủ WMS thành công nhưng lớp '{$trimmedWms}' không tồn tại. Các lớp khả dụng trên máy chủ: {$layerListStr}..."
                        ];
                    }
                }

                $foundMsg = !empty($availableLayers) ? ' (Các lớp tìm thấy: ' . implode(', ', array_slice($availableLayers, 0, 4)) . ')' : '';
                return [
                    'success' => true,
                    'code' => 200,
                    'count' => count($availableLayers) ?: 1,
                    'feature_count' => count($availableLayers) ?: 1,
                    'message' => 'Kết nối thành công! Máy chủ WMS phản hồi hợp lệ, sẵn sàng kết xuất bản đồ đa lớp.' . $foundMsg
                ];

            } else {
                // Shapefile
                $response = Http::timeout(8)->withOptions(['verify' => false])->head($url);
                if (!$response->successful()) {
                    $response = Http::timeout(8)->withOptions(['verify' => false])->get($url);
                }

                if (!$response->successful()) {
                    return [
                        'success' => false,
                        'code' => $response->status(),
                        'message' => "Không kết nối được tới nguồn dữ liệu. Mã lỗi: {$response->status()}."
                    ];
                }

                return [
                    'success' => true,
                    'code' => 200,
                    'count' => 1,
                    'feature_count' => 1,
                    'message' => 'Kết nối thành công! Tệp Shapefile sẵn sàng nạp lên hệ thống.'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'code' => 'EXCEPTION',
                'message' => "Không kết nối được tới nguồn dữ liệu. Mã lỗi: " . ($e->getCode() ?: 'TIMEOUT') . ". Kiểm tra lại địa chỉ hoặc quyền truy cập."
            ];
        }
    }
}
