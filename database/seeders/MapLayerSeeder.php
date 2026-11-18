<?php

namespace Database\Seeders;

use App\Models\MapLayer;
use App\Models\MapLayerAuditLog;
use App\Models\Project;
use Illuminate\Database\Seeder;

class MapLayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Layer Ranh giới hành chính
        $layerBoundary = MapLayer::updateOrCreate(
            ['name' => 'Ranh giới hành chính Thủ đô Hà Nội'],
            [
                'type' => MapLayer::TYPE_GEOJSON,
                'url_endpoint' => '/api/layers/data/boundary',
                'description' => 'Lớp hiển thị ranh giới địa giới hành chính các quận, huyện, thị xã trên địa bàn thành phố Hà Nội.',
                'display_order' => 1,
                'is_active' => true,
                'layer_options' => [
                    'color' => '#1a6fc4',
                    'weight' => 1.5,
                    'fillColor' => '#4a9ede',
                    'fillOpacity' => 0.08,
                    'is_system_default' => true,
                ],
            ]
        );

        // 2. Layer Bản đồ đường sắt đô thị
        $layerRailway = MapLayer::updateOrCreate(
            ['name' => 'Bản đồ tuyến đường sắt đô thị'],
            [
                'type' => MapLayer::TYPE_GEOJSON,
                'url_endpoint' => '/api/layers/data/railway',
                'description' => 'Lớp hiển thị hướng tuyến, các ga và depot của các tuyến đường sắt đô thị quy hoạch Thủ đô Hà Nội.',
                'display_order' => 2,
                'is_active' => true,
                'layer_options' => [
                    'weight' => 4,
                    'opacity' => 0.85,
                    'is_system_default' => true,
                ],
            ]
        );

        // Ghi nhận nhật ký audit ban đầu
        MapLayerAuditLog::firstOrCreate(
            [
                'map_layer_id' => $layerBoundary->id,
                'action' => MapLayerAuditLog::ACTION_CREATE,
            ],
            [
                'layer_name' => $layerBoundary->name,
                'user_name' => 'Hệ Thống (Khởi tạo)',
                'action_label' => 'Khởi tạo lớp dữ liệu mặc định',
                'new_values' => $layerBoundary->toArray(),
                'ip_address' => '127.0.0.1',
            ]
        );

        MapLayerAuditLog::firstOrCreate(
            [
                'map_layer_id' => $layerRailway->id,
                'action' => MapLayerAuditLog::ACTION_CREATE,
            ],
            [
                'layer_name' => $layerRailway->name,
                'user_name' => 'Hệ Thống (Khởi tạo)',
                'action_label' => 'Khởi tạo lớp dữ liệu mặc định',
                'new_values' => $layerRailway->toArray(),
                'ip_address' => '127.0.0.1',
            ]
        );

        // Gắn thử 1 dự án tham chiếu tới layer Railway để kiểm tra cảnh báo xóa (nếu có dự án)
        $firstProject = Project::first();
        if ($firstProject) {
            $layerRailway->projects()->syncWithoutDetaching([$firstProject->id]);
        }
    }
}
