@extends('backend.index')

@section('title')
    Giám sát nguồn dữ liệu không gian
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#!">Bản đồ số</a></li>
    <li class="breadcrumb-item active">Giám sát Layer</li>
@endsection

@section('css')
<style>
    .status-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 6px;
    }
    .status-dot.healthy {
        background-color: #28a745;
        box-shadow: 0 0 8px rgba(40, 167, 69, 0.6);
    }
    .status-dot.warning {
        background-color: #ffc107;
        box-shadow: 0 0 8px rgba(255, 193, 7, 0.6);
        animation: pulse-warning 1.8s infinite;
    }
    .status-dot.error {
        background-color: #dc3545;
        box-shadow: 0 0 10px rgba(220, 53, 69, 0.8);
        animation: pulse-error 1.2s infinite;
    }
    @keyframes pulse-error {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(220, 53, 69, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
    @keyframes pulse-warning {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
        70% { transform: scale(1.1); box-shadow: 0 0 0 6px rgba(255, 193, 7, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }
    .metric-card {
        border-radius: 8px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .latency-bar-container {
        width: 100%;
        max-width: 90px;
        height: 6px;
        background: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
        display: inline-block;
        vertical-align: middle;
        margin-left: 6px;
    }
    .latency-bar-fill {
        height: 100%;
        border-radius: 3px;
    }
    .table thead th {
        white-space: nowrap !important;
        vertical-align: middle !important;
    }
    .table td, .table th {
        vertical-align: middle;
    }
    .action-cell {
        width: 140px !important;
        white-space: nowrap !important;
        text-align: center !important;
    }
    .btn-action-group {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
    }
    .btn-action-group .btn {
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
</style>
@endsection

@section('content')
<hr class="mt-0">
<section class="content">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- THẺ THỐNG KÊ TỔNG QUAN HỆ THỐNG -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card metric-card shadow-sm border-left-primary h-100 py-2 border-primary" style="border-left: 4px solid #007bff !important;">
                    <div class="card-body py-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng nguồn giám sát</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }} <small class="text-muted" style="font-size: 13px;">({{ $stats['active'] }} đang kích hoạt)</small></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-satellite text-primary fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card metric-card shadow-sm h-100 py-2" style="border-left: 4px solid #28a745 !important;">
                    <div class="card-body py-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hoạt động bình thường</div>
                                <div class="h5 mb-0 font-weight-bold text-success">{{ $stats['healthy'] }} <small class="text-muted" style="font-size: 13px;">nguồn</small></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle text-success fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-3">
                <div class="card metric-card shadow-sm h-100 py-2" style="border-left: 4px solid #ffc107 !important;">
                    <div class="card-body py-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Cảnh báo chậm</div>
                                <div class="h5 mb-0 font-weight-bold text-warning">{{ $stats['warning'] }} <small class="text-muted" style="font-size: 13px;">nguồn</small></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle text-warning fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-3">
                <div class="card metric-card shadow-sm h-100 py-2" style="border-left: 4px solid #dc3545 !important;">
                    <div class="card-body py-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Gián đoạn / Lỗi</div>
                                <div class="h5 mb-0 font-weight-bold text-danger">{{ $stats['error'] }} <small class="text-muted" style="font-size: 13px;">nguồn</small></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle text-danger fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 mb-3">
                <div class="card metric-card shadow-sm h-100 py-2" style="border-left: 4px solid #17a2b8 !important;">
                    <div class="card-body py-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Uptime 30 ngày</div>
                                <div class="h5 mb-0 font-weight-bold text-info">{{ $stats['uptime_30d'] }}%</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line text-info fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- THANH CÔNG CỤ: TÌM KIẾM, BỘ LỌC VÀ NÚT HÀNH ĐỘNG -->
        <div class="card card-default shadow-sm mb-3">
            <div class="card-body py-2 px-3">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <form action="{{ route('backend_map_monitor') }}" method="GET" class="form-inline">
                            <div class="form-group mr-2 mb-2">
                                <input type="text" name="search" class="form-control form-control-sm" 
                                       placeholder="Tìm tên hoặc URL endpoint..." value="{{ $search }}" style="min-width: 220px;">
                            </div>
                            <div class="form-group mr-2 mb-2">
                                <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>-- Tất cả trạng thái --</option>
                                    <option value="healthy" {{ $statusFilter === 'healthy' ? 'selected' : '' }}>Hoạt động tốt</option>
                                    <option value="warning" {{ $statusFilter === 'warning' ? 'selected' : '' }}>Cảnh báo chậm</option>
                                    <option value="error" {{ $statusFilter === 'error' ? 'selected' : '' }}>Gián đoạn / Lỗi</option>
                                </select>
                            </div>
                            <div class="form-group mr-2 mb-2">
                                <select name="type" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="all" {{ $typeFilter === 'all' ? 'selected' : '' }}>-- Loại nguồn --</option>
                                    @foreach($types as $k => $label)
                                        <option value="{{ $k }}" {{ $typeFilter === $k ? 'selected' : '' }}>{{ $k }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm mb-2 mr-1">
                                <i class="fas fa-filter mr-1"></i> Lọc
                            </button>
                            @if($search || $statusFilter !== 'all' || $typeFilter !== 'all')
                                <a href="{{ route('backend_map_monitor') }}" class="btn btn-default btn-sm mb-2" title="Bỏ lọc">
                                    <i class="fas fa-undo"></i>
                                </a>
                            @endif
                        </form>
                    </div>

                    <div class="col-lg-4 text-right">
                        <button type="button" class="btn btn-outline-info btn-sm mb-2 mr-1" id="btnCheckAll">
                            <i class="fas fa-sync-alt mr-1"></i> Kiểm tra tất cả nguồn
                        </button>
                        <div class="custom-control custom-switch d-inline-block align-middle mb-2 ml-1" title="Tự động cập nhật mỗi 30 giây">
                            <input type="checkbox" class="custom-control-input" id="autoRefreshSwitch">
                            <label class="custom-control-label text-muted" for="autoRefreshSwitch" style="font-size: 13px; cursor: pointer;">
                                Tự động làm mới (30s)
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BẢNG DANH SÁCH GIÁM SÁT REALTIME -->
        <div class="card card-default shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="thead-light">
                            <tr class="text-center text-nowrap">
                                <th style="width: 50px;" class="text-nowrap">ID</th>
                                <th class="text-left text-nowrap" style="min-width: 220px;">Nguồn dữ liệu</th>
                                <th style="width: 100px;" class="text-nowrap">Loại</th>
                                <th class="text-left text-nowrap" style="min-width: 240px;">URL Endpoint</th>
                                <th style="width: 130px;" class="text-nowrap">Trạng thái</th>
                                <th style="width: 130px;" class="text-nowrap">Độ trễ</th>
                                <th style="width: 120px;" class="text-nowrap">Uptime 30d</th>
                                <th style="width: 140px;" class="text-nowrap">Lần test cuối</th>
                                <th style="width: 140px;" class="text-nowrap">Ngưỡng cảnh báo</th>
                                <th class="action-cell text-nowrap">Thao tác nhanh</th>
                            </tr>
                        </thead>
                        <tbody id="monitorTableBody">
                            @forelse($layers as $layer)
                                @php
                                    $st = $layer->last_status ?: 'healthy';
                                    $statusBadge = match($st) {
                                        'healthy' => '<span class="badge badge-success px-2 py-1">Hoạt động</span>',
                                        'warning' => '<span class="badge badge-warning px-2 py-1">Cảnh báo</span>',
                                        'error' => '<span class="badge badge-danger px-2 py-1">Gián đoạn</span>',
                                        default => '<span class="badge badge-secondary px-2 py-1">Chưa test</span>'
                                    };

                                    $latencyMs = $layer->last_latency_ms ?? 0;
                                    $maxLat = $layer->max_latency_ms ?: 2000;
                                    $latencyPct = min(100, round(($latencyMs / $maxLat) * 100));
                                    $barColor = $latencyMs > $maxLat ? '#dc3545' : ($latencyMs > ($maxLat * 0.7) ? '#ffc107' : '#28a745');
                                @endphp
                                <tr id="row-layer-{{ $layer->id }}" data-id="{{ $layer->id }}">
                                    <!-- ID -->
                                    <td class="text-center font-weight-bold text-muted">
                                        {{ $layer->id }}
                                    </td>
                                    
                                    <!-- TÊN LAYER -->
                                    <td>
                                        <a href="{{ route('backend_map_monitor_detail', $layer->id) }}" class="font-weight-bold text-dark">
                                            {{ $layer->name }}
                                        </a>
                                        @if(!$layer->is_active)
                                            <span class="badge badge-secondary ml-1 font-weight-normal" title="Layer đang ẩn trên bản đồ công khai">Đang ẩn</span>
                                        @endif
                                        @if($layer->consecutive_failures > 0)
                                            <small class="d-block text-danger font-weight-bold">
                                                <i class="fas fa-exclamation-circle mr-1"></i>Lỗi liên tục: {{ $layer->consecutive_failures }} lần
                                            </small>
                                        @endif
                                    </td>

                                    <!-- LOẠI NGUỒN -->
                                    <td class="text-center">
                                        <span class="badge badge-light border font-weight-normal px-2 py-1">
                                            {{ $layer->type }}
                                        </span>
                                    </td>

                                    <!-- URL ENDPOINT -->
                                    <td>
                                        <div class="text-truncate" style="max-width: 260px;" title="{{ $layer->url_endpoint }}">
                                            <a href="{{ $layer->url_endpoint }}" target="_blank" class="text-primary" style="font-size: 13px;">
                                                <i class="fas fa-link mr-1"></i>{{ $layer->url_endpoint }}
                                            </a>
                                        </div>
                                    </td>

                                    <!-- TRẠNG THÁI -->
                                    <td class="text-center status-col">
                                        {!! $statusBadge !!}
                                    </td>

                                    <!-- ĐỘ TRỄ -->
                                    <td class="text-center latency-col">
                                        @if($layer->last_latency_ms !== null)
                                            <span class="font-weight-bold">{{ $layer->last_latency_ms }}ms</span>
                                            <div class="latency-bar-container" title="Độ trễ so với giới hạn {{ $maxLat }}ms">
                                                <div class="latency-bar-fill" style="width: {{ $latencyPct }}%; background-color: {{ $barColor }};"></div>
                                            </div>
                                        @else
                                            <span class="text-muted">--</span>
                                        @endif
                                    </td>

                                    <!-- UPTIME 30 NGÀY -->
                                    <td class="text-center font-weight-bold">
                                        @php
                                            $upRate = $layer->uptime_30d ?? 100.0;
                                            $upColor = $upRate >= 99 ? 'text-success' : ($upRate >= 95 ? 'text-warning' : 'text-danger');
                                        @endphp
                                        <span class="{{ $upColor }}">{{ $upRate }}%</span>
                                    </td>

                                    <!-- LẦN TEST CUỐI -->
                                    <td class="text-center last-checked-col" style="font-size: 13px;">
                                        @if($layer->last_checked_at)
                                            <span title="{{ $layer->last_checked_at->format('d/m/Y H:i:s') }}">
                                                {{ $layer->last_checked_at->diffForHumans() }}
                                            </span>
                                        @else
                                            <span class="text-muted">Chưa kiểm tra</span>
                                        @endif
                                    </td>

                                    <!-- NGƯỠNG CẢNH BÁO -->
                                    <td class="text-center" style="font-size: 12.5px;">
                                        <span class="badge badge-light border" title="Thời gian gián đoạn tối đa trước khi gửi email">
                                            > {{ $layer->max_downtime_minutes }}p
                                        </span>
                                        <span class="badge badge-light border ml-1" title="Giới hạn độ trễ cảnh báo">
                                            < {{ $layer->max_latency_ms }}ms
                                        </span>
                                    </td>

                                    <!-- THAO TÁC -->
                                    <td class="action-cell">
                                        <div class="btn-action-group">
                                            <!-- Nút Kiểm tra ngay -->
                                            <button type="button" class="btn btn-sm btn-info btn-check-now" 
                                                    data-id="{{ $layer->id }}" 
                                                    data-name="{{ $layer->name }}" 
                                                    title="Kiểm tra ngay kết nối">
                                                <i class="fas fa-play"></i>
                                            </button>

                                            <!-- Nút Xem chi tiết -->
                                            <a href="{{ route('backend_map_monitor_detail', $layer->id) }}" class="btn btn-sm btn-primary" title="Xem biểu đồ & Lịch sử uptime 30 ngày">
                                                <i class="fas fa-chart-area"></i>
                                            </a>

                                            <!-- Nút Cài đặt ngưỡng -->
                                            <button type="button" class="btn btn-sm btn-secondary btn-edit-threshold" 
                                                    data-id="{{ $layer->id }}" 
                                                    data-name="{{ $layer->name }}"
                                                    data-downtime="{{ $layer->max_downtime_minutes }}"
                                                    data-latency="{{ $layer->max_latency_ms }}"
                                                    title="Cấu hình ngưỡng cảnh báo">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">
                                        Chưa có nguồn dữ liệu layer nào phù hợp với bộ lọc.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- MODAL CẤU HÌNH NGƯỠNG CẢNH BÁO RIÊNG CHO TỪNG NGUỒN -->
<div class="modal fade" id="thresholdModal" tabindex="-1" role="dialog" aria-labelledby="thresholdModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light py-2">
                <h5 class="modal-title font-weight-bold" id="thresholdModalLabel" style="font-size: 16px;">
                    <i class="fas fa-sliders-h text-primary mr-1"></i> Cấu hình ngưỡng cảnh báo
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="thresholdForm" method="POST">
                @csrf
                <div class="modal-body py-3">
                    <div class="alert alert-info py-2" style="font-size: 13px;">
                        Thiết lập ngưỡng giám sát riêng cho layer: <strong id="modalLayerName"></strong>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold" for="inputDowntime">
                            Thời gian gián đoạn tối đa (phút) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="inputDowntime" name="max_downtime_minutes" min="1" max="1440" required>
                            <div class="input-group-append">
                                <span class="input-group-text">phút</span>
                            </div>
                        </div>
                        <small class="text-muted">Khi nguồn bị gián đoạn liên tục quá số phút này, hệ thống sẽ tự động gửi email cảnh báo tới QTHT. (Mặc định: 5 phút)</small>
                    </div>

                    <div class="form-group mb-2">
                        <label class="font-weight-bold" for="inputLatency">
                            Độ trễ phản hồi giới hạn (ms) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="inputLatency" name="max_latency_ms" min="50" max="60000" step="50" required>
                            <div class="input-group-append">
                                <span class="input-group-text">mili-giây (ms)</span>
                            </div>
                        </div>
                        <small class="text-muted">Nếu thời gian phản hồi vượt quá mức này, layer sẽ chuyển sang trạng thái "Cảnh báo chậm". (Mặc định: 2000 ms)</small>
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary btn-sm font-weight-bold" id="btnSaveThreshold">
                        <i class="fas fa-save mr-1"></i> Lưu cấu hình
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    const checkNowUrlTemplate = "{{ route('backend_map_monitor_check_now', ['id' => ':id']) }}";
    const thresholdUrlTemplate = "{{ route('backend_map_monitor_threshold', ['id' => ':id']) }}";
    const csrfToken = "{{ csrf_token() }}";

    // 1. Xử lý nút [Kiểm tra ngay] (Nút Play) cho từng layer bằng AJAX
    $(document).on('click', '.btn-check-now', function() {
        const btn = $(this);
        const layerId = btn.data('id');
        const layerName = btn.data('name');
        const row = $('#row-layer-' + layerId);

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        const ajaxUrl = checkNowUrlTemplate.replace(':id', layerId);

        $.ajax({
            url: ajaxUrl,
            type: "POST",
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: {
                _token: csrfToken
            },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-play"></i>');
                if (res.success && res.data) {
                    const d = res.data;
                    toastr.success(res.message);

                    // Cập nhật trạng thái badge trên dòng
                    let badgeHtml = '';
                    if (d.status === 'healthy') {
                        badgeHtml = '<span class="badge badge-success px-2 py-1">Hoạt động</span>';
                    } else if (d.status === 'warning') {
                        badgeHtml = '<span class="badge badge-warning px-2 py-1">Cảnh báo</span>';
                    } else {
                        badgeHtml = '<span class="badge badge-danger px-2 py-1">Gián đoạn</span>';
                    }
                    row.find('.status-col').html(badgeHtml);

                    // Cập nhật độ trễ
                    row.find('.latency-col').html(
                        '<span class="font-weight-bold">' + d.latency_ms + 'ms</span>'
                    );

                    // Cập nhật thời gian check cuối
                    row.find('.last-checked-col').html(
                        '<span>Vừa xong</span>'
                    );
                } else {
                    toastr.error(res.message || 'Kiểm tra thất bại.');
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-play"></i>');
                const err = xhr.responseJSON ? xhr.responseJSON.message : 'Lỗi kết nối máy chủ.';
                toastr.error(err);
            }
        });
    });

    // 2. Xử lý nút [Kiểm tra tất cả nguồn]
    $('#btnCheckAll').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Đang kiểm tra toàn bộ...');

        $.ajax({
            url: "{{ route('backend_map_monitor_check_all') }}",
            type: "POST",
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: {
                _token: csrfToken
            },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt mr-1"></i> Kiểm tra tất cả nguồn');
                toastr.success(res.message);
                setTimeout(function() {
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt mr-1"></i> Kiểm tra tất cả nguồn');
                toastr.error('Có lỗi xảy ra khi kiểm tra toàn bộ.');
            }
        });
    });

    // 3. Mở Modal Cấu hình ngưỡng cảnh báo (Nút Setting / Bánh răng)
    $(document).on('click', '.btn-edit-threshold', function(e) {
        e.preventDefault();
        const btn = $(this);
        const layerId = btn.data('id');
        const layerName = btn.data('name');
        const downtime = btn.data('downtime');
        const latency = btn.data('latency');

        $('#modalLayerName').text(layerName);
        $('#inputDowntime').val(downtime);
        $('#inputLatency').val(latency);
        
        const formAction = thresholdUrlTemplate.replace(':id', layerId);
        $('#thresholdForm').attr('action', formAction);

        $('#thresholdModal').modal('show');
    });

    // 4. Submit form cấu hình ngưỡng qua AJAX
    $('#thresholdForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const submitBtn = $('#btnSaveThreshold');

        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Đang lưu...');

        $.ajax({
            url: url,
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: form.serialize(),
            success: function(res) {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Lưu cấu hình');
                $('#thresholdModal').modal('hide');
                toastr.success(res.message || 'Đã lưu cấu hình ngưỡng thành công.');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Lưu cấu hình');
                const err = xhr.responseJSON ? xhr.responseJSON.message : 'Lỗi khi lưu cấu hình.';
                toastr.error(err);
            }
        });
    });

    // 5. Tự động làm mới realtime (mỗi 30s)
    let autoRefreshTimer = null;
    $('#autoRefreshSwitch').on('change', function() {
        if ($(this).is(':checked')) {
            toastr.info('Đã bật chế độ tự động làm mới mỗi 30 giây.');
            autoRefreshTimer = setInterval(function() {
                location.reload();
            }, 30000);
        } else {
            if (autoRefreshTimer) {
                clearInterval(autoRefreshTimer);
                autoRefreshTimer = null;
                toastr.info('Đã tắt tự động làm mới.');
            }
        }
    });
});
</script>
@endsection

