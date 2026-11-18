<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MapLayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class MapLayerApiController extends Controller
{
    /**
     * Danh sách layer công khai đang hoạt động (có cache, đảm bảo phản ánh < 60s)
     */
    public function index()
    {
        $layers = Cache::remember('public_map_layers', 60, function () {
            return MapLayer::active()
                ->ordered()
                ->select([
                    'id',
                    'name',
                    'type',
                    'url_endpoint',
                    'file_path',
                    'description',
                    'display_order',
                    'layer_options'
                ])
                ->get();
        });

        return response()->json([
            'status' => 'success',
            'data' => $layers
        ]);
    }

    /**
     * Endpoint cung cấp dữ liệu GeoJSON cho Ranh giới hành chính Thủ đô Hà Nội
     */
    public function getBoundaryGeoJson()
    {
        $cacheKey = 'layer_geojson_boundary';
        $geoJson = Cache::remember($cacheKey, 3600, function () {
            $filePath = public_path('js/boundaries.js');
            if (!File::exists($filePath)) {
                return ['type' => 'FeatureCollection', 'features' => []];
            }

            $content = File::get($filePath);
            if (preg_match('/let boundaries\s*=\s*(\{[\s\S]*\});?/', $content, $matches)) {
                $jsonStr = $matches[1];
                $jsonStr = preg_replace('/,\s*([\]\}])/', '$1', $jsonStr);
                $boundaries = json_decode($jsonStr, true);

                if (is_array($boundaries)) {
                    $features = [];
                    foreach ($boundaries as $name => $coords) {
                        // Leaflet coords are [lat, lng], standard GeoJSON Polygon coords are [lng, lat]
                        $polyCoords = [];
                        foreach ($coords as $pt) {
                            if (is_array($pt) && count($pt) >= 2) {
                                $polyCoords[] = [(float)$pt[1], (float)$pt[0]];
                            }
                        }
                        // Close ring if not closed
                        if (!empty($polyCoords) && $polyCoords[0] !== end($polyCoords)) {
                            $polyCoords[] = $polyCoords[0];
                        }

                        $features[] = [
                            'type' => 'Feature',
                            'properties' => [
                                'name' => $name,
                                'type' => 'boundary',
                            ],
                            'geometry' => [
                                'type' => 'Polygon',
                                'coordinates' => [$polyCoords]
                            ]
                        ];
                    }

                    return [
                        'type' => 'FeatureCollection',
                        'features' => $features
                    ];
                }
            }

            return ['type' => 'FeatureCollection', 'features' => []];
        });

        return response()->json($geoJson);
    }

    /**
     * Endpoint cung cấp dữ liệu GeoJSON cho Tuyến đường sắt đô thị Hà Nội
     */
    public function getRailwayGeoJson()
    {
        $cacheKey = 'layer_geojson_railway';
        $geoJson = Cache::remember($cacheKey, 3600, function () {
            $filePath = public_path('js/railways/railways.js');
            if (!File::exists($filePath)) {
                return ['type' => 'FeatureCollection', 'features' => []];
            }

            $content = File::get($filePath);
            if (preg_match('/const railways\s*=\s*(\{[\s\S]*\});?/', $content, $matches)) {
                $jsonStr = $matches[1];
                $jsonStr = preg_replace('/,\s*([\]\}])/', '$1', $jsonStr);
                $railways = json_decode($jsonStr, true);

                if (is_array($railways)) {
                    $features = [];
                    foreach ($railways as $name => $lineData) {
                        $rawCoords = $lineData['coords'] ?? [];
                        $lines = [];

                        // Coordinates can be nested arrays of [lat, lng]
                        if (isset($rawCoords[0]) && is_array($rawCoords[0])) {
                            if (isset($rawCoords[0][0]) && is_array($rawCoords[0][0])) {
                                // MultiLineString
                                foreach ($rawCoords as $subLine) {
                                    $subPts = [];
                                    foreach ($subLine as $pt) {
                                        if (is_array($pt) && count($pt) >= 2) {
                                            $subPts[] = [(float)$pt[1], (float)$pt[0]];
                                        }
                                    }
                                    if (!empty($subPts)) {
                                        $lines[] = $subPts;
                                    }
                                }
                                $geometry = [
                                    'type' => 'MultiLineString',
                                    'coordinates' => $lines
                                ];
                            } else {
                                // LineString
                                $linePts = [];
                                foreach ($rawCoords as $pt) {
                                    if (is_array($pt) && count($pt) >= 2) {
                                        $linePts[] = [(float)$pt[1], (float)$pt[0]];
                                    }
                                }
                                $geometry = [
                                    'type' => 'LineString',
                                    'coordinates' => $linePts
                                ];
                            }
                        } else {
                            continue;
                        }

                        $features[] = [
                            'type' => 'Feature',
                            'properties' => [
                                'name' => $name,
                                'color' => $lineData['color'] ?? '#0000FF',
                                'type' => 'railway',
                            ],
                            'geometry' => $geometry
                        ];
                    }

                    return [
                        'type' => 'FeatureCollection',
                        'features' => $features
                    ];
                }
            }

            return ['type' => 'FeatureCollection', 'features' => []];
        });

        return response()->json($geoJson);
    }

    /**
     * API kiểm tra kết nối nguồn dữ liệu (Test Connection)
     * Đáp ứng yêu cầu spec:
     * - Trước khi lưu, hệ thống tự gọi thử tới URL endpoint để kiểm tra kết nối.
     * - Kết quả trả về: Thành công (số feature hoặc số tile mẫu lấy được) hoặc Lỗi (mã lỗi và không cho lưu).
     */
    public function testConnection(Request $request)
    {
        $url = trim($request->input('url_endpoint', ''));
        $type = $request->input('type', 'GeoJSON');

        $result = \App\Services\MapLayerService::testConnection($url, $type);
        $status = $result['success'] ? 200 : ($result['code'] === 'EMPTY_URL' || $result['code'] === 'INVALID_URL' ? 422 : 400);

        return response()->json($result, $status);
    }
}
