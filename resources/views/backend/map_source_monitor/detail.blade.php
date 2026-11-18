@extends('backend.index')

@section('title')
    Giám sát chi tiết: {{ $layer->name }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend_map_monitor') }}">Giám sát Layer</a></li>
    <li class="breadcrumb-item active">{{ $layer->name }}</li>
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
    .status-dot.healthy { background-color: #28a745; box-shadow: 0 0 8px rgba(40, 167, 69, 0.6); }
    .status-dot.warning { background-color: #ffc107; box-shadow: 0 0 8px rgba(255, 193, 7, 0.6); }
    .status-dot.error { background-color: #dc3545; box-shadow: 0 0 10px rgba(220, 53, 69, 0.8); }

    /* Thanh dải Uptime 30 ngày */
    .uptime-bar-wrapper {
        display: flex;
        align-items: stretch;
        gap: 3px;
        height: 38px;
        background: #f8f9fa;
        padding: 5px;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }
    .uptime-day-col {
        flex: 1;
        min-width: 4px;
        border-radius: 3px;
        cursor: pointer;
        transition: transform 0.15s ease, opacity 0.15s ease;
    }
    .uptime-day-col:hover {
        transform: scaleY(1.15);
        opacity: 0.85;
    }
    .uptime-day-col.healthy { background-color: #28a745; }
    .uptime-day-col.warning { background-color: #ffc107; }
    .uptime-day-col.error { background-color: #dc3545; }
    .uptime-day-col.empty { background-color: #e9ecef; }

    .chart-container {
        position: relative;
        height: 260px;
        width: 100%;
    }
    .table thead th {
        white-space: nowrap !important;
        vertical-align: middle !important;
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

        <!-- HEADER THANH ĐIỀU HƯỚNG VÀ NÚT HÀNH ĐỘNG -->
        <div class="card card-default shadow-sm mb-3">
            <div class="card-body py-2 px-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('backend_map_monitor') }}" class="btn btn-default btn-sm mr-2" title="Quay lại danh sách giám sát">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <div>
                                <h4 class="mb-0 font-weight-bold" style="font-size: 18px;">
                                    {{ $layer->name }}
                                    <span class="badge badge-light border ml-1 font-weight-normal" style="font-size: 13px;">{{ $layer->type }}</span>
                                </h4>
                                <small class="text-muted">
                                    <i class="fas fa-link mr-1"></i>
                                    <a href="{{ $layer->url_endpoint }}" target="_blank" class="text-muted text-truncate d-inline-block" style="max-width: 480px; vertical-align: bottom;">
                                        {{ $layer->url_endpoint }}
                                    </a>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 text-right mt-2 mt-md-0">
                        <button type="button" class="btn btn-info btn-sm mr-1" id="btnDetailCheckNow" data-id="{{ $layer->id }}">
                            <i class="fas fa-play mr-1"></i> Kiểm tra ngay
                        </button>
                        <a href="{{ route('backend_map_layer_edit', $layer->id) }}" class="btn btn-default btn-sm mr-1">
                            <i class="fas fa-edit mr-1"></i> Sửa cấu hình
                        </a>
                        <a href="{{ route('backend_map_monitor') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-list mr-1"></i> Danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- THẺ THỐNG KÊ NHANH CHO LAYER NÀY -->
        <div class="row">
            <!-- 1. Trạng thái hiện tại -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card shadow-sm h-100 py-2" style="border-left: 4px solid @if($layer->last_status == 'healthy') #28a745 @elseif($layer->last_status == 'warning') #ffc107 @else #dc3545 @endif !important;">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted">Trạng thái hiện tại</div>
                        <div class="h5 mb-0 font-weight-bold" id="badgeCurrentStatus">
                            @if($layer->last_status === 'healthy')
                                <span class="text-success">Hoạt động tốt</span>
                            @elseif($layer->last_status === 'warning')
                                <span class="text-warning">Cảnh báo chậm</span>
                            @else
                                <span class="text-danger">Lỗi kết nối</span>
                            @endif
                        </div>
                        <small class="text-muted" id="textLastChecked">
                            @if($layer->last_checked_at)
                                Test cuối: {{ $layer->last_checked_at->diffForHumans() }}
                            @else
                                Chưa kiểm tra
                            @endif
                        </small>
                    </div>
                </div>
            </div>

            <!-- 2. Uptime 30 ngày -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card shadow-sm h-100 py-2" style="border-left: 4px solid #17a2b8 !important;">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tỷ lệ Uptime 30 ngày</div>
                        <div class="h5 mb-0 font-weight-bold @if($uptime30d >= 99) text-success @elseif($uptime30d >= 95) text-warning @else text-danger @endif">
                            {{ $uptime30d }}%
                        </div>
                        <small class="text-muted">Mục tiêu SLA: ≥ 99.0%</small>
                    </div>
                </div>
            </div>

            <!-- 3. Độ trễ phản hồi gần nhất -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card shadow-sm h-100 py-2" style="border-left: 4px solid #6f42c1 !important;">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Độ trễ gần nhất</div>
                        <div class="h5 mb-0 font-weight-bold text-dark" id="valCurrentLatency">
                            {{ $layer->last_latency_ms !== null ? $layer->last_latency_ms . ' ms' : '--' }}
                        </div>
                        <small class="text-muted">Ngưỡng giới hạn: {{ $layer->max_latency_ms }} ms</small>
                    </div>
                </div>
            </div>

            <!-- 4. Lỗi liên tiếp / Cảnh báo email -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card shadow-sm h-100 py-2" style="border-left: 4px solid #fd7e14 !important;">
                    <div class="card-body py-2">
                        <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Gián đoạn liên tục</div>
                        <div class="h5 mb-0 font-weight-bold @if($layer->consecutive_failures > 0) text-danger @else text-success @endif" id="valConsecutiveFailures">
                            {{ $layer->consecutive_failures ?? 0 }} lần
                        </div>
                        <small class="text-muted">Gửi email khi: ≥ {{ $layer->max_downtime_minutes }} lần (phút)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- PHẦN 1: LỊCH SỬ UPTIME 30 NGÀY QUA DẢI THANH -->
        <div class="card card-default shadow-sm mb-3">
            <div class="card-header py-2 bg-light">
                <h5 class="card-title font-weight-bold mb-0" style="font-size: 15px;">
                    <i class="fas fa-calendar-alt text-primary mr-1"></i> Lịch sử Uptime 30 ngày gần nhất
                </h5>
                <div class="card-tools">
                    <span class="badge badge-light border font-weight-normal px-2 py-1">
                        Trung bình: <strong>{{ $uptime30d }}%</strong>
                    </span>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="d-flex justify-content-between text-muted mb-1" style="font-size: 12px;">
                    <span>30 ngày trước</span>
                    <span class="font-weight-bold">Tỷ lệ khả dụng theo từng ngày</span>
                    <span>Hôm nay</span>
                </div>

                <!-- Dải 30 thanh biểu diễn 30 ngày -->
                <div class="uptime-bar-wrapper mb-2">
                    @foreach($uptimeDaily as $day)
                        @php
                            $colClass = $day['status'];
                            $tooltipText = "{$day['full_date']}: Uptime {$day['rate']}% ({$day['checks_count']} lần kiểm tra)";
                        @endphp
                        <div class="uptime-day-col {{ $colClass }}" 
                             data-toggle="tooltip" 
                             data-placement="top" 
                             title="{{ $tooltipText }}"></div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between align-items-center text-muted mt-2" style="font-size: 12.5px;">
                    <div>
                        <span class="mr-3"><span class="status-dot healthy"></span> ≥ 99% (Bình thường)</span>
                        <span class="mr-3"><span class="status-dot warning"></span> 90% - 98.9% (Chậm)</span>
                        <span><span class="status-dot error"></span> < 90% (Gián đoạn)</span>
                    </div>
                    <div>
                        <span>Tự động lưu trữ tối thiểu <strong>30 ngày</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- PHẦN 2: BIỂU ĐỒ ĐỘ TRỄ PHẢN HỒI (CHART.JS) -->
            <div class="col-lg-8 mb-3">
                <div class="card card-default shadow-sm h-100">
                    <div class="card-header py-2 bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title font-weight-bold mb-0" style="font-size: 15px;">
                                <i class="fas fa-chart-line text-info mr-1"></i> Biểu đồ độ trễ phản hồi (Response Latency)
                            </h5>
                            <form action="{{ route('backend_map_monitor_detail', $layer->id) }}" method="GET" class="form-inline m-0">
                                <input type="hidden" name="log_filter" value="{{ $logFilter }}">
                                <select name="hours" class="form-control form-control-sm" onchange="this.form.submit()" style="font-size: 12.5px;">
                                    <option value="12" {{ $hours == 12 ? 'selected' : '' }}>12 giờ qua</option>
                                    <option value="24" {{ $hours == 24 ? 'selected' : '' }}>24 giờ qua</option>
                                    <option value="48" {{ $hours == 48 ? 'selected' : '' }}>48 giờ qua</option>
                                    <option value="168" {{ $hours == 168 ? 'selected' : '' }}>7 ngày qua</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart-container">
                            <canvas id="latencyChart"></canvas>
                        </div>
                        <div class="text-center text-muted mt-2" style="font-size: 12px;">
                            <span class="mr-3"><i class="fas fa-square text-success mr-1"></i>Hoạt động</span>
                            <span class="mr-3"><i class="fas fa-square text-warning mr-1"></i>Độ trễ cao</span>
                            <span class="mr-3"><i class="fas fa-square text-danger mr-1"></i>Lỗi kết nối</span>
                            <span><i class="fas fa-minus text-danger mr-1"></i>Ngưỡng cảnh báo: {{ $layer->max_latency_ms }} ms</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PHẦN 3: FORM CẤU HÌNH NGƯỠNG CẢNH BÁO RIÊNG -->
            <div class="col-lg-4 mb-3">
                <div class="card card-default shadow-sm h-100">
                    <div class="card-header py-2 bg-light">
                        <h5 class="card-title font-weight-bold mb-0" style="font-size: 15px;">
                            <i class="fas fa-sliders-h text-primary mr-1"></i> Cấu hình ngưỡng cảnh báo
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <form action="{{ route('backend_map_monitor_threshold', $layer->id) }}" method="POST">
                            @csrf
                            <div class="alert alert-light border py-2 mb-3 text-muted" style="font-size: 12.5px;">
                                <i class="fas fa-info-circle text-info mr-1"></i>
                                Cấu hình ngưỡng giám sát riêng áp dụng cho nguồn này.
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold" style="font-size: 13px;">
                                    Thời gian downtime tối đa
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="max_downtime_minutes" class="form-control" 
                                           value="{{ old('max_downtime_minutes', $layer->max_downtime_minutes) }}" 
                                           min="1" max="1440" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">phút</span>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1">Khi gián đoạn liên tục vượt số phút này, hệ thống sẽ gửi email tới QTHT.</small>
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold" style="font-size: 13px;">
                                    Độ trễ phản hồi giới hạn
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="max_latency_ms" class="form-control" 
                                           value="{{ old('max_latency_ms', $layer->max_latency_ms) }}" 
                                           min="50" max="60000" step="50" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">ms</span>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1">Nếu thời gian phản hồi vượt mức này, hệ thống báo cảnh báo chậm.</small>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm btn-block font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Lưu cấu hình ngưỡng
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- PHẦN 4: BẢNG NHẬT KÝ GIÁM SÁT & LOG LỖI GẦN ĐÂY -->
        <div class="card card-default shadow-sm mb-4">
            <div class="card-header py-2 bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0" style="font-size: 15px;">
                        <i class="fas fa-history text-secondary mr-1"></i> Nhật ký kiểm tra & Log lỗi
                    </h5>
                    <div>
                        <a href="{{ route('backend_map_monitor_detail', ['id' => $layer->id, 'hours' => $hours, 'log_filter' => 'all']) }}" 
                           class="btn btn-sm {{ $logFilter === 'all' ? 'btn-primary' : 'btn-default' }} py-0 px-2" style="font-size: 12px;">
                            Tất cả ({{ $logs->total() }})
                        </a>
                        <a href="{{ route('backend_map_monitor_detail', ['id' => $layer->id, 'hours' => $hours, 'log_filter' => 'error']) }}" 
                           class="btn btn-sm {{ $logFilter === 'error' ? 'btn-danger' : 'btn-default' }} py-0 px-2 ml-1" style="font-size: 12px;">
                            Chỉ Lỗi & Cảnh báo
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="thead-light">
                            <tr class="text-center text-nowrap" style="font-size: 13px;">
                                <th style="width: 60px;">STT</th>
                                <th style="width: 170px;">Thời gian kiểm tra</th>
                                <th style="width: 140px;">Trạng thái</th>
                                <th style="width: 130px;">Độ trễ</th>
                                <th style="width: 110px;">Mã HTTP</th>
                                <th class="text-left">Chi tiết thông báo / Log lỗi</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 13px;">
                            @forelse($logs as $index => $log)
                                @php
                                    $stBadge = match($log->status) {
                                        'healthy' => '<span class="badge badge-success px-2 py-1">Hoạt động</span>',
                                        'warning' => '<span class="badge badge-warning px-2 py-1">Cảnh báo</span>',
                                        'error' => '<span class="badge badge-danger px-2 py-1">Lỗi kết nối</span>',
                                        default => '<span class="badge badge-secondary px-2 py-1">--</span>'
                                    };
                                @endphp
                                <tr>
                                    <td class="text-center text-muted font-weight-bold">
                                        {{ $logs->firstItem() + $index }}
                                    </td>
                                    <td class="text-center">
                                        {{ $log->checked_at->format('d/m/Y H:i:s') }}
                                        <small class="text-muted d-block">({{ $log->checked_at->diffForHumans() }})</small>
                                    </td>
                                    <td class="text-center">
                                        {!! $stBadge !!}
                                    </td>
                                    <td class="text-center font-weight-bold">
                                        {{ $log->response_time_ms }} ms
                                    </td>
                                    <td class="text-center">
                                        @if($log->http_status_code === '200')
                                            <span class="badge badge-light border text-success">200 OK</span>
                                        @else
                                            <span class="badge badge-light border text-danger font-weight-bold">{{ $log->http_status_code ?: 'ERR' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->error_message)
                                            <span class="text-danger"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $log->error_message }}</span>
                                        @else
                                            <span class="text-muted"><i class="fas fa-check text-success mr-1"></i>Kết nối thành công, tải dữ liệu ổn định.</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Chưa có bản ghi nhật ký kiểm tra nào phù hợp.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="px-3 py-2 bg-light border-top d-flex justify-content-end">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</section>
@endsection

@section('script')
<script src="{{ asset('backend_assets/vendor/chart/Chart.min.js') }}"></script>
<script>
$(document).ready(function() {
    const checkNowUrlTemplate = "{{ route('backend_map_monitor_check_now', ['id' => ':id']) }}";
    const csrfToken = "{{ csrf_token() }}";

    // Khởi tạo Bootstrap tooltips cho dải Uptime
    $('[data-toggle="tooltip"]').tooltip();

    // Xử lý nút [Kiểm tra ngay] trên trang chi tiết
    $('#btnDetailCheckNow').on('click', function() {
        const btn = $(this);
        const layerId = btn.data('id');

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Đang kiểm tra...');

        const ajaxUrl = checkNowUrlTemplate.replace(':id', layerId);

        $.ajax({
            url: ajaxUrl,
            type: "POST",
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: {
                _token: csrfToken
            },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-play mr-1"></i> Kiểm tra ngay');
                if (res.success && res.data) {
                    toastr.success(res.message);
                    setTimeout(function() {
                        location.reload();
                    }, 800);
                } else {
                    toastr.error(res.message || 'Kiểm tra thất bại.');
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-play mr-1"></i> Kiểm tra ngay');
                const err = xhr.responseJSON ? xhr.responseJSON.message : 'Lỗi kết nối máy chủ.';
                toastr.error(err);
            }
        });
    });

    // Vẽ biểu đồ độ trễ phản hồi với Chart.js
    const chartData = @json($latencyChartData);
    const maxLatencyThreshold = {{ $layer->max_latency_ms }};
    const ctx = document.getElementById('latencyChart');

    if (ctx && chartData.labels && chartData.labels.length > 0) {
        // Tạo đường ngưỡng cảnh báo
        const thresholdLine = chartData.labels.map(() => maxLatencyThreshold);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Độ trễ phản hồi (ms)',
                        data: chartData.latencies,
                        borderColor: '#17a2b8',
                        backgroundColor: 'rgba(23, 162, 184, 0.08)',
                        pointBackgroundColor: chartData.pointColors,
                        pointBorderColor: '#fff',
                        pointHoverRadius: 6,
                        pointRadius: 4,
                        borderWidth: 2,
                        tension: 0.25,
                        fill: true
                    },
                    {
                        label: 'Ngưỡng cảnh báo (' + maxLatencyThreshold + ' ms)',
                        data: thresholdLine,
                        borderColor: '#dc3545',
                        borderDash: [5, 5],
                        borderWidth: 1.5,
                        pointRadius: 0,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            maxTicksLimit: 12,
                            fontSize: 11
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontSize: 11,
                            callback: function(value) {
                                return value + ' ms';
                            }
                        },
                        gridLines: {
                            color: '#f0f0f0'
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            return data.datasets[tooltipItem.datasetIndex].label + ': ' + tooltipItem.yLabel + ' ms';
                        }
                    }
                }
            }
        });
    } else if (ctx) {
        // Chưa có dữ liệu biểu đồ
        const parent = ctx.parentElement;
        parent.innerHTML = '<div class="text-center py-5 text-muted"><i class="fas fa-chart-line fa-3x mb-2 text-muted opacity-50"></i><p>Chưa có dữ liệu kiểm tra trong khoảng thời gian này.<br>Bấm <strong>[Kiểm tra ngay]</strong> để nạp bản ghi đầu tiên.</p></div>';
    }
});
</script>
@endsection

