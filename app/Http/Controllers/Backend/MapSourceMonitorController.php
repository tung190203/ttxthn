<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MapLayer;
use App\Models\MapLayerAuditLog;
use App\Models\MapSourceMonitor;
use App\Services\MapLayerMonitoringService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MapSourceMonitorController extends Controller
{
    public function __construct(protected MapLayerMonitoringService $monitoringService)
    {
        $this->selectedMainMenu = 'map';

        parent::__construct();

        $this->selectedSubMenu('map_monitor');

        // Phân quyền cho Quản trị hệ thống (QTHT)
        if (!Gate::allows('map') && !Gate::allows('map/map_monitor')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
    }

    /**
     * Màn hình chính Giám sát Layer Realtime
     */
    public function index(Request $request)
    {
        $search = trim($request->get('search', ''));
        $statusFilter = $request->get('status', 'all');
        $typeFilter = $request->get('type', 'all');

        $query = MapLayer::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('url_endpoint', 'like', "%{$search}%");
            });
        }

        if ($statusFilter !== 'all' && in_array($statusFilter, ['healthy', 'warning', 'error'])) {
            $query->where('last_status', $statusFilter);
        }

        if ($typeFilter !== 'all' && array_key_exists($typeFilter, MapLayer::$types)) {
            $query->where('type', $typeFilter);
        }

        // Ưu tiên hiển thị layer lỗi/cảnh báo lên trước, sau đó theo display_order
        $layers = $query->orderByRaw("CASE 
                WHEN last_status = 'error' THEN 1 
                WHEN last_status = 'warning' THEN 2 
                ELSE 3 
            END ASC")
            ->orderBy('display_order', 'asc')
            ->get();

        // Gắn thêm chỉ số uptime 30 ngày cho từng layer
        foreach ($layers as $layer) {
            $layer->uptime_30d = MapSourceMonitor::calculateUptimePercentage($layer->id, 30);
        }

        // Thống kê tổng quan hệ thống
        $stats = $this->monitoringService->getSystemOverviewStats();

        return view('backend.map_source_monitor.index', [
            'layers' => $layers,
            'stats' => $stats,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'typeFilter' => $typeFilter,
            'types' => MapLayer::$types,
        ]);
    }

    /**
     * Màn hình chi tiết nguồn: Lịch sử Uptime 30 ngày, Biểu đồ độ trễ, Log lỗi
     */
    public function detail(Request $request, $id)
    {
        $layer = MapLayer::findOrFail($id);

        $logFilter = $request->get('log_filter', 'all'); // 'all' hoặc 'error'
        $hours = (int)$request->get('hours', 24);
        if ($hours <= 0 || $hours > 168) {
            $hours = 24;
        }

        // Tỷ lệ Uptime 30 ngày
        $uptime30d = MapSourceMonitor::calculateUptimePercentage($layer->id, 30);

        // Lịch sử uptime 30 ngày theo từng ngày
        $uptimeDaily = $this->monitoringService->getUptimeDailyBreakdown($layer, 30);

        // Biểu đồ độ trễ phản hồi trong N giờ qua (mặc định 24h)
        $latencyChartData = $this->monitoringService->getLatencyChartData($layer, $hours);

        // Danh sách log kiểm tra & lỗi
        $logsQuery = MapSourceMonitor::where('map_layer_id', $layer->id)->latest('checked_at');
        if ($logFilter === 'error') {
            $logsQuery->whereIn('status', [MapSourceMonitor::STATUS_ERROR, MapSourceMonitor::STATUS_WARNING]);
        }
        $logs = $logsQuery->paginate(20)->withQueryString();

        return view('backend.map_source_monitor.detail', [
            'layer' => $layer,
            'uptime30d' => $uptime30d,
            'uptimeDaily' => $uptimeDaily,
            'latencyChartData' => $latencyChartData,
            'logs' => $logs,
            'logFilter' => $logFilter,
            'hours' => $hours,
        ]);
    }

    /**
     * Nút [Kiểm tra ngay] - Kiểm tra kết nối thủ công tức thì cho một Layer
     */
    public function checkNow(Request $request, $id)
    {
        $layer = MapLayer::findOrFail($id);

        try {
            $result = $this->monitoringService->checkLayer($layer);

            $statusText = match ($result['status']) {
                MapSourceMonitor::STATUS_HEALTHY => 'Hoạt động tốt',
                MapSourceMonitor::STATUS_WARNING => 'Cảnh báo (độ trễ cao)',
                MapSourceMonitor::STATUS_ERROR => 'Lỗi kết nối / Gián đoạn',
                default => 'Không xác định'
            };

            $message = "Đã kiểm tra nguồn \"{$layer->name}\": {$statusText} ({$result['latency_ms']}ms)";

            return response()->json([
                'success' => true,
                'data' => [
                    'layer_id' => $layer->id,
                    'status' => $result['status'],
                    'status_text' => $statusText,
                    'latency_ms' => $result['latency_ms'],
                    'status_code' => $result['status_code'],
                    'error_message' => $result['error_message'],
                    'checked_at' => Carbon::now()->format('d/m/Y H:i:s'),
                    'consecutive_failures' => $result['consecutive_failures'],
                ],
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi kiểm tra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kiểm tra tất cả các nguồn đang hoạt động
     */
    public function checkAllNow(Request $request)
    {
        try {
            $results = $this->monitoringService->checkAllActiveLayers();
            $total = count($results);

            return response()->json([
                'success' => true,
                'total' => $total,
                'results' => $results,
                'message' => "Đã hoàn thành kiểm tra kết nối cho {$total} nguồn dữ liệu.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi kiểm tra toàn bộ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cấu hình ngưỡng cảnh báo riêng cho từng nguồn
     */
    public function updateThreshold(Request $request, $id)
    {
        $layer = MapLayer::findOrFail($id);

        $request->validate([
            'max_downtime_minutes' => 'required|integer|min:1|max:1440',
            'max_latency_ms' => 'required|integer|min:50|max:60000',
        ], [
            'max_downtime_minutes.required' => 'Vui lòng nhập thời gian gián đoạn tối đa.',
            'max_downtime_minutes.min' => 'Thời gian gián đoạn tối thiểu là 1 phút.',
            'max_latency_ms.required' => 'Vui lòng nhập độ trễ phản hồi giới hạn.',
            'max_latency_ms.min' => 'Độ trễ tối thiểu là 50ms.',
        ]);

        $oldValues = [
            'max_downtime_minutes' => $layer->max_downtime_minutes,
            'max_latency_ms' => $layer->max_latency_ms,
        ];

        $layer->max_downtime_minutes = (int)$request->input('max_downtime_minutes');
        $layer->max_latency_ms = (int)$request->input('max_latency_ms');
        $layer->updated_by = auth('web')->id();
        $layer->save();

        // Ghi Audit Log
        MapLayerAuditLog::record(
            $layer->id,
            $layer->name,
            MapLayerAuditLog::ACTION_UPDATE,
            "Cập nhật ngưỡng cảnh báo giám sát: Downtime tối đa {$layer->max_downtime_minutes} phút, Độ trễ giới hạn {$layer->max_latency_ms}ms",
            $oldValues,
            [
                'max_downtime_minutes' => $layer->max_downtime_minutes,
                'max_latency_ms' => $layer->max_latency_ms,
            ]
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Đã lưu cấu hình ngưỡng cảnh báo cho \"{$layer->name}\" thành công!"
            ]);
        }

        return redirect()->back()->with('success', "Đã lưu cấu hình ngưỡng cảnh báo cho \"{$layer->name}\" thành công!");
    }
}

