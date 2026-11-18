<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MapLayer;
use App\Models\MapLayerAuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MapLayerController extends Controller
{
    public function __construct()
    {
        $this->selectedMainMenu = 'map';

        parent::__construct();

        $this->selectedSubMenu('map_layer');

        // Check permission
        if (!Gate::allows('map') && !Gate::allows('map/map_layer')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
    }

    /**
     * Màn hình danh sách layer & Tab lịch sử
     */
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'layers');

        // Bộ lọc danh sách Layer
        $search = trim($request->get('search') ?? '');
        $typeFilter = $request->get('type', '');
        $statusFilter = $request->get('status');

        $query = MapLayer::withCount('projects')->ordered();

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        if (!empty($typeFilter) && array_key_exists($typeFilter, MapLayer::$types)) {
            $query->where('type', $typeFilter);
        }

        if ($statusFilter !== null && $statusFilter !== '') {
            $query->where('is_active', (int)$statusFilter === 1);
        }

        $layers = $query->get();

        // Dữ liệu cho Tab Lịch sử (Audit Logs)
        $logActionFilter = $request->get('log_action');
        $logUserFilter = $request->get('log_user');

        $logQuery = MapLayerAuditLog::latest();

        if (!empty($logActionFilter)) {
            $logQuery->where('action', $logActionFilter);
        }

        if (!empty($logUserFilter)) {
            $logQuery->where('user_id', $logUserFilter);
        }

        $auditLogs = $logQuery->paginate(20)->withQueryString();
        $adminUsers = User::where('is_super_admin', true)
            ->orWhereNotNull('group_id')
            ->select('id', 'name', 'email')
            ->get();

        if ($adminUsers->isEmpty()) {
            $adminUsers = User::select('id', 'name', 'email')->limit(20)->get();
        }

        return view('backend.map_layer.index', [
            'layers' => $layers,
            'auditLogs' => $auditLogs,
            'adminUsers' => $adminUsers,
            'search' => $search,
            'typeFilter' => $typeFilter,
            'statusFilter' => $statusFilter,
            'logActionFilter' => $logActionFilter,
            'logUserFilter' => $logUserFilter,
            'activeTab' => $activeTab,
            'types' => MapLayer::$types,
            'actions' => MapLayerAuditLog::$actions,
        ]);
    }

    /**
     * Màn hình thêm mới layer
     */
    public function create()
    {
        $layer = new MapLayer();
        $layer->display_order = (MapLayer::max('display_order') ?? 0) + 1;
        $layer->is_active = true;

        return view('backend.map_layer.create', [
            'layer' => $layer,
            'types' => MapLayer::$types,
        ]);
    }

    /**
     * Màn hình sửa layer
     */
    public function edit(MapLayer $layer)
    {
        return view('backend.map_layer.create', [
            'layer' => $layer,
            'types' => MapLayer::$types,
        ]);
    }

    /**
     * API kiểm tra kết nối tới URL endpoint trước khi lưu
     */
    public function testConnection(Request $request)
    {
        $url = trim($request->input('url_endpoint', ''));
        $type = $request->input('type', 'GeoJSON');

        $wmsLayers = $request->input('wms_layers');
        $result = \App\Services\MapLayerService::testConnection($url, $type, $wmsLayers);
        $status = $result['success'] ? 200 : ($result['code'] === 'EMPTY_URL' || $result['code'] === 'INVALID_URL' ? 422 : 400);

        return response()->json($result, $status);
    }

    /**
     * Lưu thêm mới hoặc cập nhật layer
     */
    public function save(Request $request, ?MapLayer $layer = null)
    {
        $isNew = !$layer || !$layer->exists;
        if ($isNew) {
            $layer = new MapLayer();
        }

        // Validate cơ bản
        $request->validate([
            'name' => 'required|string|max:200',
            'type' => 'required|in:WMS,WFS,GeoJSON,Shapefile',
            'display_order' => 'required|integer',
            'is_active' => 'required|boolean',
            'file_upload' => 'nullable|file|max:51200', // max 50MB (GeoJSON hoặc zip Shapefile)
        ], [
            'name.required' => 'Vui lòng nhập tên layer.',
            'name.max' => 'Tên layer không được vượt quá 200 ký tự.',
            'type.required' => 'Vui lòng chọn loại nguồn.',
            'display_order.required' => 'Vui lòng nhập thứ tự hiển thị.',
        ]);

        $url = trim($request->input('url_endpoint', ''));

        // Xử lý upload file từ máy tính nếu người dùng tải file lên
        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $extension = strtolower($file->getClientOriginalExtension());

            if (!in_array($extension, ['geojson', 'json', 'zip'])) {
                return back()->withInput()->withErrors([
                    'file_upload' => 'Tập tin tải lên phải có định dạng .geojson, .json hoặc .zip (Shapefile).'
                ]);
            }

            $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $extension;
            $savePath = $file->storeAs('layers', $fileName, 'public');

            $layer->file_path = $savePath;
            $url = Storage::disk('public')->url($savePath);
        }

        if (empty($url)) {
            return back()->withInput()->withErrors([
                'url_endpoint' => 'Vui lòng nhập URL endpoint của nguồn dữ liệu hoặc tải file lên từ máy tính.'
            ]);
        }

        // Kiểm tra định dạng URL
        if (!str_starts_with($url, '/') && !filter_var($url, FILTER_VALIDATE_URL)) {
            return back()->withInput()->withErrors([
                'url_endpoint' => 'Địa chỉ nguồn dữ liệu không hợp lệ.'
            ]);
        }

        // QUY TẮC BẮT BUỘC: Kiểm tra kết nối trước khi lưu, thất bại không cho lưu
        $testReq = new Request([
            'url_endpoint' => $url,
            'type' => $request->input('type'),
            'wms_layers' => $request->input('wms_layers'),
        ]);
        $testResult = $this->testConnection($testReq);
        $testData = $testResult->getData();

        if (empty($testData->success)) {
            $errorMsg = $testData->message ?? 'Không kết nối được tới nguồn dữ liệu. Kiểm tra lại địa chỉ hoặc quyền truy cập.';
            return back()->withInput()->withErrors([
                'url_endpoint' => $errorMsg
            ]);
        }

        $oldValues = $isNew ? null : $layer->toArray();

        $layer->name = $request->input('name');
        $layer->type = $request->input('type');
        $layer->url_endpoint = $url;
        $layer->description = $request->input('description');
        $layer->display_order = (int)$request->input('display_order');
        $layer->is_active = (bool)$request->input('is_active');
        $layer->layer_options = [
            'color' => $request->input('layer_color', '#1a6fc4'),
            'fillColor' => $request->input('layer_fill_color', '#4a9ede'),
            'opacity' => (float)$request->input('layer_opacity', 0.8),
            'wms_layers' => $request->input('wms_layers', ''),
        ];

        if ($request->filled('max_downtime_minutes')) {
            $layer->max_downtime_minutes = (int)$request->input('max_downtime_minutes');
        }
        if ($request->filled('max_latency_ms')) {
            $layer->max_latency_ms = (int)$request->input('max_latency_ms');
        }

        $currentUserId = auth('web')->id();
        if ($isNew) {
            $layer->created_by = $currentUserId;
        }
        $layer->updated_by = $currentUserId;

        $layer->save();

        // Ghi nhận nhật ký thay đổi (Audit log)
        MapLayerAuditLog::record(
            $layer->id,
            $layer->name,
            $isNew ? MapLayerAuditLog::ACTION_CREATE : MapLayerAuditLog::ACTION_UPDATE,
            $isNew ? 'Thêm mới layer' : 'Cập nhật thông số kỹ thuật layer',
            $oldValues,
            $layer->toArray()
        );

        // Xóa cache ngay lập tức
        MapLayer::clearPublicCache();

        $message = $isNew ? 'Thêm mới layer bản đồ thành công!' : 'Cập nhật layer bản đồ thành công!';
        return redirect()->route('backend_map_layer')->with('success', $message);
    }

    /**
     * Bật/Tắt trạng thái Ẩn/Hiện bằng công tắc trên danh sách (AJAX)
     */
    public function toggleStatus(Request $request)
    {
        $id = $request->input('id');
        $layer = MapLayer::findOrFail($id);

        $oldStatus = $layer->is_active;
        $layer->is_active = !$oldStatus;
        $layer->updated_by = auth('web')->id();
        $layer->save();

        // Ghi audit log
        MapLayerAuditLog::record(
            $layer->id,
            $layer->name,
            MapLayerAuditLog::ACTION_TOGGLE_STATUS,
            $layer->is_active ? 'Chuyển sang trạng thái Hiện' : 'Chuyển sang trạng thái Ẩn',
            ['is_active' => $oldStatus],
            ['is_active' => $layer->is_active]
        );

        MapLayer::clearPublicCache();

        return response()->json([
            'success' => true,
            'is_active' => $layer->is_active,
            'message' => 'Đã cập nhật trạng thái layer thành công.'
        ]);
    }

    /**
     * Sắp xếp thứ tự hiển thị các layer bằng kéo-thả (AJAX)
     */
    public function reorder(Request $request)
    {
        $orders = $request->input('orders', []); // Mảng [id => order]

        if (!is_array($orders) || empty($orders)) {
            return response()->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ.'], 400);
        }

        foreach ($orders as $item) {
            if (isset($item['id']) && isset($item['order'])) {
                MapLayer::where('id', $item['id'])->update(['display_order' => (int)$item['order']]);
            }
        }

        MapLayerAuditLog::record(
            null,
            'Nhiều layer',
            MapLayerAuditLog::ACTION_REORDER,
            'Sắp xếp lại thứ tự hiển thị bằng kéo thả',
            null,
            $orders
        );

        MapLayer::clearPublicCache();

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật thứ tự hiển thị thành công.'
        ]);
    }

    /**
     * Xóa layer
     * Quy tắc bắt buộc:
     * - Nếu layer đang được ít nhất một dự án tham chiếu: hiện cảnh báo nêu rõ số dự án đang dùng và yêu cầu xác nhận lần hai.
     */
    public function delete(Request $request, $id)
    {
        $layer = MapLayer::withCount('projects')->findOrFail($id);
        $projectCount = $layer->projects_count;
        $confirmed = $request->input('confirmed', false);

        // Nếu có dự án tham chiếu mà chưa xác nhận lần 2
        if ($projectCount > 0 && !$confirmed) {
            return response()->json([
                'success' => false,
                'require_double_confirmation' => true,
                'project_count' => $projectCount,
                'message' => "Layer này đang được {$projectCount} dự án sử dụng. Xóa sẽ làm mất hiển thị của các dự án đó. Bạn có chắc chắn?"
            ]);
        }

        $oldData = $layer->toArray();
        $layerName = $layer->name;

        // Xóa file upload nếu có
        if ($layer->file_path && Storage::disk('public')->exists($layer->file_path)) {
            Storage::disk('public')->delete($layer->file_path);
        }

        // Hủy liên kết dự án
        $layer->projects()->detach();
        $layer->delete();

        // Ghi audit log
        MapLayerAuditLog::record(
            $id,
            $layerName,
            MapLayerAuditLog::ACTION_DELETE,
            'Xóa lớp dữ liệu bản đồ' . ($projectCount > 0 ? " (có {$projectCount} dự án liên kết)" : ''),
            $oldData,
            null
        );

        MapLayer::clearPublicCache();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Đã xóa layer \"{$layerName}\" thành công!"
            ]);
        }

        return redirect()->route('backend_map_layer')->with('success', "Đã xóa layer \"{$layerName}\" thành công!");
    }
}
