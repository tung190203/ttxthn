<?php

namespace App\Services;

use App\Models\MapLayer;
use App\Models\MapSourceMonitor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MapLayerMonitoringService
{
    /**
     * Thực hiện kiểm tra kết nối và đo độ trễ cho một Layer
     */
    public function checkLayer(MapLayer $layer): array
    {
        $startTime = microtime(true);
        $wmsLayers = $layer->layer_options['wms_layers'] ?? null;

        // Gọi testConnection từ MapLayerService
        $testResult = MapLayerService::testConnection($layer->url_endpoint, $layer->type, $wmsLayers);
        $durationMs = (int)round((microtime(true) - $startTime) * 1000);

        // Xác định trạng thái kết nối
        $maxLatency = $layer->max_latency_ms ?: 2000;
        $maxDowntimeMin = $layer->max_downtime_minutes ?: 5;

        if (!$testResult['success']) {
            $status = MapSourceMonitor::STATUS_ERROR;
            $statusCode = (string)($testResult['code'] ?? 'ERROR');
            $errorMessage = $testResult['message'] ?? 'Kết nối tới nguồn dữ liệu thất bại.';
            $layer->consecutive_failures = ($layer->consecutive_failures ?? 0) + 1;
        } else {
            if ($durationMs > $maxLatency) {
                $status = MapSourceMonitor::STATUS_WARNING;
                $errorMessage = "Độ trễ phản hồi ({$durationMs}ms) vượt quá ngưỡng cho phép ({$maxLatency}ms).";
            } else {
                $status = MapSourceMonitor::STATUS_HEALTHY;
                $errorMessage = null;
            }
            $statusCode = (string)($testResult['code'] ?? '200');
            $layer->consecutive_failures = 0;
        }

        // Lưu bản ghi vào bảng nhật ký giám sát (NhatKyGiamSatNguon)
        $monitorLog = MapSourceMonitor::create([
            'map_layer_id' => $layer->id,
            'status' => $status,
            'response_time_ms' => $durationMs,
            'http_status_code' => $statusCode,
            'error_message' => $errorMessage,
            'checked_at' => Carbon::now(),
        ]);

        // Cập nhật trạng thái mới nhất cho Layer
        $layer->last_status = $status;
        $layer->last_checked_at = Carbon::now();
        $layer->last_latency_ms = $durationMs;

        // Kiểm tra ngưỡng cảnh báo gián đoạn (> 5 phút hoặc số phút cấu hình)
        $shouldAlert = false;
        if ($layer->consecutive_failures >= $maxDowntimeMin) {
            $lastAlert = $layer->last_alert_sent_at;
            // Gửi cảnh báo nếu chưa gửi hoặc lần gửi gần nhất đã quá 30 phút
            if (!$lastAlert || Carbon::parse($lastAlert)->diffInMinutes(Carbon::now()) >= 30) {
                $shouldAlert = true;
                $this->sendDowntimeAlert($layer, $errorMessage, $statusCode);
                $layer->last_alert_sent_at = Carbon::now();
            }
        }

        $layer->save();

        return [
            'success' => $testResult['success'],
            'status' => $status,
            'latency_ms' => $durationMs,
            'status_code' => $statusCode,
            'error_message' => $errorMessage,
            'checked_at' => Carbon::now()->toIso8601String(),
            'should_alert' => $shouldAlert,
            'consecutive_failures' => $layer->consecutive_failures,
        ];
    }

    /**
     * Quét kiểm tra tất cả các Layer đang hoạt động
     */
    public function checkAllActiveLayers(): array
    {
        $layers = MapLayer::where('is_active', true)->get();
        $results = [];

        foreach ($layers as $layer) {
            try {
                $results[$layer->id] = $this->checkLayer($layer);
            } catch (\Exception $e) {
                Log::error("Lỗi khi giám sát layer {$layer->id}: " . $e->getMessage());
                $results[$layer->id] = [
                    'success' => false,
                    'status' => MapSourceMonitor::STATUS_ERROR,
                    'error_message' => $e->getMessage(),
                ];
            }
        }

        // Tự động dọn dẹp các bản ghi cũ hơn 90 ngày để tối ưu dung lượng DB
        MapSourceMonitor::where('checked_at', '<', Carbon::now()->subDays(90))->delete();

        return $results;
    }

    /**
     * Gửi cảnh báo email / log tới Quản trị hệ thống khi nguồn gián đoạn
     */
    protected function sendDowntimeAlert(MapLayer $layer, ?string $errorMsg, ?string $code): void
    {
        $adminEmails = User::where('is_super_admin', true)
            ->whereNotNull('email')
            ->pluck('email')
            ->toArray();

        $subject = "[CẢNH BÁO GIÁM SÁT LAYER] Nguồn dữ liệu \"{$layer->name}\" đang bị gián đoạn!";
        $body = "Kính gửi Quản trị hệ thống,\n\n"
            . "Hệ thống giám sát nguồn dữ liệu không gian phát hiện nguồn dữ liệu của layer [{$layer->name}] đang gặp sự cố gián đoạn liên tục quá {$layer->max_downtime_minutes} phút.\n\n"
            . "- Mã layer: #{$layer->id}\n"
            . "- Tên layer: {$layer->name}\n"
            . "- Loại nguồn: {$layer->type}\n"
            . "- URL endpoint: {$layer->url_endpoint}\n"
            . "- Mã phản hồi: {$code}\n"
            . "- Chi tiết lỗi: {$errorMsg}\n"
            . "- Thời gian phát hiện: " . Carbon::now()->format('d/m/Y H:i:s') . "\n\n"
            . "Gợi ý xử lý:\n"
            . "1. Kiểm tra lại đường truyền mạng hoặc quyền truy cập của máy chủ cung cấp dữ liệu GIS.\n"
            . "2. Đăng nhập vào trang Quản trị → Bản đồ số → Giám sát Layer để kiểm tra trực tiếp.\n"
            . "3. Nếu máy chủ nguồn bảo trì kéo dài, có thể tạm ẩn layer để tránh gián đoạn trải nghiệm người dùng.\n\n"
            . "Trân trọng,\nHệ thống Bản đồ số đa lớp.";

        Log::warning($subject . " - Lỗi: {$errorMsg} (Mã: {$code})");

        // Gửi email nếu hệ thống đã cấu hình mail server
        if (!empty($adminEmails) && config('mail.mailers.smtp.host')) {
            try {
                Mail::raw($body, function ($message) use ($adminEmails, $subject) {
                    $message->to($adminEmails)->subject($subject);
                });
            } catch (\Exception $e) {
                Log::error("Không thể gửi email cảnh báo gián đoạn layer: " . $e->getMessage());
            }
        }
    }

    /**
     * Lấy dữ liệu biểu đồ độ trễ phản hồi trong 24 giờ qua (Chart.js)
     */
    public function getLatencyChartData(MapLayer $layer, int $hours = 24): array
    {
        $since = Carbon::now()->subHours($hours);

        $logs = MapSourceMonitor::where('map_layer_id', $layer->id)
            ->where('checked_at', '>=', $since)
            ->orderBy('checked_at', 'asc')
            ->select(['response_time_ms', 'status', 'checked_at'])
            ->get();

        $labels = [];
        $latencies = [];
        $pointColors = [];

        foreach ($logs as $log) {
            $labels[] = $log->checked_at->format('H:i');
            $latencies[] = $log->response_time_ms;
            $pointColors[] = match ($log->status) {
                'healthy' => '#28a745',
                'warning' => '#ffc107',
                'error' => '#dc3545',
                default => '#6c757d'
            };
        }

        return [
            'labels' => $labels,
            'latencies' => $latencies,
            'pointColors' => $pointColors,
            'count' => count($labels),
        ];
    }

    /**
     * Lấy lịch sử tỷ lệ uptime từng ngày trong 30 ngày gần nhất
     */
    public function getUptimeDailyBreakdown(MapLayer $layer, int $days = 30): array
    {
        $breakdown = [];
        $today = Carbon::today();

        for ($i = $days - 1; $i >= 0; $i--) {
            $dayDate = (clone $today)->subDays($i);
            $startOfDay = (clone $dayDate)->startOfDay();
            $endOfDay = (clone $dayDate)->endOfDay();

            $total = MapSourceMonitor::where('map_layer_id', $layer->id)
                ->whereBetween('checked_at', [$startOfDay, $endOfDay])
                ->count();

            if ($total === 0) {
                $rate = 100.0;
                $status = 'healthy';
            } else {
                $success = MapSourceMonitor::where('map_layer_id', $layer->id)
                    ->whereBetween('checked_at', [$startOfDay, $endOfDay])
                    ->whereIn('status', [MapSourceMonitor::STATUS_HEALTHY, MapSourceMonitor::STATUS_WARNING])
                    ->count();

                $rate = round(($success / $total) * 100, 1);
                $status = $rate >= 99 ? 'healthy' : ($rate >= 90 ? 'warning' : 'error');
            }

            $breakdown[] = [
                'date' => $dayDate->format('d/m'),
                'full_date' => $dayDate->format('d/m/Y'),
                'rate' => $rate,
                'status' => $status,
                'checks_count' => $total,
            ];
        }

        return $breakdown;
    }

    /**
     * Lấy thống kê tổng quan toàn hệ thống về giám sát layer
     */
    public function getSystemOverviewStats(): array
    {
        $totalLayers = MapLayer::count();
        $activeLayers = MapLayer::where('is_active', true)->count();
        $healthyCount = MapLayer::where('is_active', true)->where('last_status', MapSourceMonitor::STATUS_HEALTHY)->count();
        $warningCount = MapLayer::where('is_active', true)->where('last_status', MapSourceMonitor::STATUS_WARNING)->count();
        $errorCount = MapLayer::where('is_active', true)->where('last_status', MapSourceMonitor::STATUS_ERROR)->count();

        // Tỷ lệ uptime trung bình 30 ngày qua trên toàn bộ hệ thống
        $since30d = Carbon::now()->subDays(30);
        $totalChecks = MapSourceMonitor::where('checked_at', '>=', $since30d)->count();
        if ($totalChecks > 0) {
            $successfulChecks = MapSourceMonitor::where('checked_at', '>=', $since30d)
                ->whereIn('status', [MapSourceMonitor::STATUS_HEALTHY, MapSourceMonitor::STATUS_WARNING])
                ->count();
            $systemUptime = round(($successfulChecks / $totalChecks) * 100, 2);
        } else {
            $systemUptime = 100.0;
        }

        return [
            'total' => $totalLayers,
            'active' => $activeLayers,
            'healthy' => $healthyCount,
            'warning' => $warningCount,
            'error' => $errorCount,
            'uptime_30d' => $systemUptime,
        ];
    }
}
