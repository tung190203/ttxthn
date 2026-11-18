@extends('backend.index')

@section('title')
    Quản lý danh mục Layer bản đồ
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#!">Bản đồ số</a></li>
    <li class="breadcrumb-item active">Quản lý Layer</li>
@endsection

@section('css')
<style>
    .drag-handle {
        cursor: move;
        color: #999;
    }
    .drag-handle:hover {
        color: #333;
    }
    .sortable-ghost {
        background-color: #f4f6f9 !important;
        opacity: 0.6;
    }
    .table thead th {
        white-space: nowrap !important;
        vertical-align: middle !important;
    }
    .table td, .table th {
        vertical-align: middle;
    }
    .action-cell {
        width: 100px !important;
        min-width: 100px !important;
        white-space: nowrap !important;
        text-align: center !important;
    }
    .btn-action-group {
        display: inline-flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
    }
    .btn-action-group .btn {
        flex-shrink: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
        font-size: 13px !important;
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

        <!-- TOP BAR: TÌM KIẾM, BỘ LỌC VÀ NÚT THÊM MỚI -->
        <div class="row align-items-center mb-3">
            <div class="col-xl-8 col-lg-8">
                <form action="{{ route('backend_map_layer') }}" method="GET" class="form-filter-top-index">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <input type="text" name="search" class="form-control form-control-sm" 
                                   placeholder="Tìm kiếm theo tên layer..." value="{{ $search }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <select name="type" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="">-- Loại nguồn --</option>
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}" {{ $typeFilter === $key ? 'selected' : '' }}>{{ $key }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="">-- Trạng thái --</option>
                                <option value="1" {{ (string)$statusFilter === '1' ? 'selected' : '' }}>Đang Hiện</option>
                                <option value="0" {{ (string)$statusFilter === '0' ? 'selected' : '' }}>Đang Ẩn</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2 text-left">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-search mr-1"></i> Tìm
                            </button>
                            @if($search || $typeFilter || ($statusFilter !== null && $statusFilter !== ''))
                                <a href="{{ route('backend_map_layer') }}" class="btn btn-default btn-sm ml-1" title="Bỏ lọc">
                                    <i class="fas fa-undo"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xl-4 col-lg-4 text-right mb-2">
                <a href="{{ route('backend_map_monitor') }}" class="btn btn-outline-primary btn-sm mr-1">
                    <i class="fas fa-satellite mr-1"></i> Giám sát Layer
                </a>
                <a href="{{ route('backend_map_layer_create') }}" class="btn btn-info btn-sm">
                    <i class="fa fa-plus mr-1"></i> Thêm mới Layer
                </a>
            </div>
        </div>

        <!-- TABS ĐIỀU HƯỚNG -->
        <ul class="nav nav-tabs mb-3" id="layerNavTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'layers' ? 'active' : '' }}" id="tab-layers-link" data-toggle="pill" href="#tab-layers" role="tab">
                    <i class="fas fa-layer-group mr-1 text-primary"></i> Danh sách Layer ({{ count($layers) }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'history' ? 'active' : '' }}" id="tab-history-link" data-toggle="pill" href="#tab-history" role="tab">
                    <i class="fas fa-history mr-1 text-info"></i> Nhật ký thay đổi (Audit Log)
                </a>
            </li>
        </ul>

        <div class="tab-content" id="layerTabsContent">
            
            <!-- ═════════ TAB 1: DANH SÁCH LAYER ═════════ -->
            <div class="tab-pane fade {{ $activeTab === 'layers' ? 'show active' : '' }}" id="tab-layers" role="tabpanel">
                
                <div class="alert alert-light border py-2 px-3 text-muted mb-3" style="font-size: 13px;">
                    <i class="fas fa-info-circle text-info mr-1"></i>
                    <strong>Gợi ý:</strong> Bạn có thể kéo-thả chuột tại cột <strong>Kéo</strong> để thay đổi thứ tự hiển thị của các layer trên bản đồ. Thay đổi sẽ cập nhật ngay lập tức ra ngoài bản đồ công khai.
                </div>

                <div class="card card-default shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0" id="sortableLayersTable">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th style="width: 60px;">Kéo</th>
                                        <th style="width: 60px;">ID</th>
                                        <th class="text-left" style="min-width: 220px;">Tên Layer</th>
                                        <th style="width: 120px;">Loại nguồn</th>
                                        <th class="text-left" style="min-width: 250px;">URL Endpoint / Tệp</th>
                                        <th style="width: 110px;">Dự án dùng</th>
                                        <th style="width: 90px;">Thứ tự</th>
                                        <th style="width: 120px;" class="text-center text-nowrap">Trạng thái</th>
                                        <th class="action-cell" nowrap>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="sortableLayersBody">
                                    @forelse($layers as $layer)
                                        <tr data-id="{{ $layer->id }}">
                                            <!-- CỘT KÉO THẢ -->
                                            <td class="text-center">
                                                <span class="drag-handle" title="Bấm giữ để kéo thả">
                                                    <i class="fas fa-arrows-alt"></i>
                                                </span>
                                            </td>
                                            <!-- ID -->
                                            <td class="text-center text-muted font-weight-bold">
                                                {{ $layer->id }}
                                            </td>
                                            <!-- TÊN LAYER -->
                                            <td>
                                                <a href="{{ route('backend_map_layer_edit', $layer->id) }}" class="font-weight-bold text-dark text-decoration-none">
                                                    {{ $layer->name }}
                                                </a>
                                                @if($layer->description)
                                                    <small class="text-muted d-block text-truncate" style="max-width: 350px;">
                                                        {{ $layer->description }}
                                                    </small>
                                                @endif
                                            </td>
                                            <!-- LOẠI NGUỒN -->
                                            <td class="text-center">
                                                @php
                                                    $badgeClass = match($layer->type) {
                                                        'GeoJSON' => 'badge-success',
                                                        'WMS' => 'badge-primary',
                                                        'WFS' => 'badge-info',
                                                        'Shapefile' => 'badge-warning',
                                                        default => 'badge-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }} px-2 py-1 font-weight-normal">
                                                    {{ $layer->type }}
                                                </span>
                                            </td>
                                            <!-- URL ENDPOINT -->
                                            <td>
                                                <div class="text-truncate" style="max-width: 280px;" title="{{ $layer->url_endpoint }}">
                                                    <a href="{{ $layer->url_endpoint }}" target="_blank" class="text-primary">
                                                        <i class="fas fa-link mr-1" style="font-size: 11px;"></i>{{ $layer->url_endpoint }}
                                                    </a>
                                                </div>
                                                @if($layer->file_path)
                                                    <small class="text-success"><i class="fas fa-file-alt mr-1"></i>Tệp lưu trên server</small>
                                                @endif
                                            </td>
                                            <!-- DỰ ÁN THAM CHIẾU -->
                                            <td class="text-center">
                                                @if($layer->projects_count > 0)
                                                    <span class="badge badge-warning px-2 py-1 font-weight-normal" title="Có {{ $layer->projects_count }} dự án đang liên kết">
                                                        <i class="fas fa-project-diagram mr-1"></i>{{ $layer->projects_count }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">0</span>
                                                @endif
                                            </td>
                                            <!-- THỨ TỰ -->
                                            <td class="text-center">
                                                <span class="badge badge-light border px-2 py-1 order-display font-weight-bold">
                                                    {{ $layer->display_order }}
                                                </span>
                                            </td>
                                            <!-- TRẠNG THÁI -->
                                            <td class="text-center text-nowrap">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input toggle-status-switch" 
                                                           id="statusSwitch_{{ $layer->id }}" 
                                                           data-id="{{ $layer->id }}" 
                                                           {{ $layer->is_active ? 'checked' : '' }}>
                                                    <label class="custom-control-label font-weight-normal status-label" for="statusSwitch_{{ $layer->id }}" style="cursor: pointer;">
                                                        {{ $layer->is_active ? 'Hiện' : 'Ẩn' }}
                                                    </label>
                                                </div>
                                            </td>
                                            <!-- THAO TÁC -->
                                            <td class="action-cell" nowrap>
                                                <div class="btn-action-group">
                                                    <a href="{{ route('backend_map_layer_edit', $layer->id) }}" class="btn btn-info" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-delete-layer" 
                                                            data-id="{{ $layer->id }}" 
                                                            data-name="{{ $layer->name }}"
                                                            data-projects-count="{{ $layer->projects_count }}"
                                                            title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4 text-muted">
                                                Chưa có layer dữ liệu nào phù hợp với bộ lọc.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ═════════ TAB 2: NHẬT KÝ THAY ĐỔI (AUDIT LOG) ═════════ -->
            <div class="tab-pane fade {{ $activeTab === 'history' ? 'show active' : '' }}" id="tab-history" role="tabpanel">
                
                <div class="row align-items-center mb-3">
                    <div class="col-md-8">
                        <form action="{{ route('backend_map_layer') }}" method="GET" class="form-inline">
                            <input type="hidden" name="tab" value="history">
                            <div class="form-group mr-2 mb-2">
                                <select name="log_action" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="">-- Tất cả thao tác --</option>
                                    @foreach($actions as $actKey => $actLabel)
                                        <option value="{{ $actKey }}" {{ $logActionFilter === $actKey ? 'selected' : '' }}>{{ $actLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mr-2 mb-2">
                                <select name="log_user" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="">-- Tất cả người thực hiện (QTHT) --</option>
                                    @foreach($adminUsers as $adm)
                                        <option value="{{ $adm->id }}" {{ $logUserFilter == $adm->id ? 'selected' : '' }}>
                                            {{ $adm->name }} ({{ $adm->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if($logActionFilter || $logUserFilter)
                                <a href="{{ route('backend_map_layer', ['tab' => 'history']) }}" class="btn btn-default btn-sm mb-2" title="Bỏ lọc">
                                    <i class="fas fa-undo mr-1"></i> Bỏ lọc
                                </a>
                            @endif
                        </form>
                    </div>
                    <div class="col-md-4 text-right mb-2 text-muted" style="font-size: 13px;">
                        <i class="fas fa-shield-alt mr-1"></i> Lưu trữ nhật ký tối thiểu 90 ngày
                    </div>
                </div>

                <div class="card card-default shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0" style="font-size: 13.5px;">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th style="width: 160px;" class="text-nowrap">Thời gian</th>
                                        <th class="text-left" style="width: 200px;">Người thực hiện (QTHT)</th>
                                        <th style="width: 160px;" class="text-nowrap">Loại thao tác</th>
                                        <th class="text-left" style="min-width: 220px;">Tên Layer</th>
                                        <th style="width: 130px;" class="text-nowrap">Địa chỉ IP</th>
                                        <th style="width: 90px;" class="text-nowrap">Chi tiết</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($auditLogs as $log)
                                        <tr>
                                            <td class="text-center font-weight-bold text-muted">
                                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                                            </td>
                                            <td>
                                                <i class="fas fa-user-circle text-secondary mr-1"></i>
                                                <strong>{{ $log->user_name }}</strong>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $actionBadge = match($log->action) {
                                                        'CREATE' => 'badge-success',
                                                        'UPDATE' => 'badge-primary',
                                                        'DELETE' => 'badge-danger',
                                                        'TOGGLE_STATUS' => 'badge-info',
                                                        'REORDER' => 'badge-warning',
                                                        default => 'badge-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $actionBadge }} px-2 py-1 font-weight-normal">
                                                    {{ $log->action_label ?? $log->action }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $log->layer_name }}</strong>
                                            </td>
                                            <td class="text-center text-muted font-monospace text-nowrap" style="font-size: 12px;">
                                                {{ $log->ip_address }}
                                            </td>
                                            <td class="text-center text-nowrap">
                                                <button type="button" class="btn btn-xs btn-outline-info px-2 py-1 btn-view-log" 
                                                        data-log='@json($log)'>
                                                    <i class="fas fa-eye mr-1"></i> Xem
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                Chưa có lịch sử thao tác nào.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    {{ $auditLogs->links() }}
                </div>

            </div>

        </div>

    </div>
</section>

<!-- MODAL XEM CHI TIẾT AUDIT LOG -->
<div class="modal fade" id="logDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow-sm">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-clipboard-list mr-1 text-primary"></i> Chi tiết thay đổi Layer
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3 bg-light p-2 rounded mx-0" style="font-size: 13.5px;">
                    <div class="col-md-6 mb-1"><strong>Người thực hiện:</strong> <span id="modalLogUser" class="text-primary font-weight-bold"></span></div>
                    <div class="col-md-6 mb-1"><strong>Thời gian:</strong> <span id="modalLogTime" class="text-muted"></span></div>
                    <div class="col-md-6"><strong>Thao tác:</strong> <span id="modalLogAction" class="badge badge-info px-2 py-1"></span></div>
                    <div class="col-md-6"><strong>Địa chỉ IP:</strong> <span id="modalLogIp" class="text-muted font-monospace"></span></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <h6 class="font-weight-bold text-danger"><i class="fas fa-minus-circle mr-1"></i> Giá trị trước thay đổi</h6>
                        <pre id="modalOldValues" class="bg-light p-2 border rounded" style="max-height: 250px; overflow-y: auto; font-size: 12px;"></pre>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-success"><i class="fas fa-plus-circle mr-1"></i> Giá trị sau thay đổi</h6>
                        <pre id="modalNewValues" class="bg-light p-2 border rounded" style="max-height: 250px; overflow-y: auto; font-size: 12px;"></pre>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL XÁC NHẬN XÓA (CẢNH BÁO THAM CHIẾU DỰ ÁN 2 LẦN) -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-sm">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Xác nhận xóa Layer
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="projectReferenceAlert" class="alert alert-warning d-none">
                    <i class="fas fa-radiation-alt fa-lg text-danger mr-2"></i>
                    <strong class="text-danger">CẢNH BÁO:</strong>
                    <p class="mb-0 mt-1" id="projectReferenceText">
                        Layer này đang được <strong><span id="refProjectCount">0</span></strong> dự án sử dụng. Xóa sẽ làm mất hiển thị của các dự án đó. Bạn có chắc chắn?
                    </p>
                </div>

                <p id="deleteMessageNormal" class="mb-0">
                    Bạn có chắc chắn muốn xóa layer <strong id="deleteLayerName"></strong>?
                </p>

                <div id="doubleConfirmWrapper" class="custom-control custom-checkbox mt-3 p-2 bg-light border border-warning rounded d-none">
                    <input type="checkbox" class="custom-control-input" id="doubleConfirmCheckbox">
                    <label class="custom-control-label text-danger font-weight-bold" for="doubleConfirmCheckbox" style="font-size: 13px; cursor: pointer;">
                        Tôi hiểu rõ và xác nhận xóa layer này cùng với liên kết của các dự án!
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Hủy bỏ</button>
                <button type="button" class="btn btn-danger btn-sm font-weight-bold" id="btnConfirmDelete">
                    <i class="fas fa-trash mr-1"></i> Xóa
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // 1. KÉO THẢ SẮP XẾP THỨ TỰ (SORTABLE)
    const sortableContainer = document.getElementById('sortableLayersBody');
    if (sortableContainer) {
        new Sortable(sortableContainer, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function() {
                const orders = [];
                $('#sortableLayersBody tr').each(function(index) {
                    const id = $(this).data('id');
                    const newOrder = index + 1;
                    $(this).find('.order-display').text(newOrder);
                    orders.push({ id: id, order: newOrder });
                });

                $.ajax({
                    url: "{{ route('backend_map_layer_reorder') }}",
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    data: { orders: orders },
                    success: function(res) {
                        toastr.success(res.message || 'Đã cập nhật thứ tự hiển thị!');
                    },
                    error: function() {
                        toastr.error('Lỗi khi sắp xếp thứ tự.');
                    }
                });
            }
        });
    }

    // 2. CÔNG TẮC BẬT/TẮT TRẠNG THÁI (AJAX SWITCH)
    $('.toggle-status-switch').on('change', function() {
        const switchEl = $(this);
        const layerId = switchEl.data('id');
        const labelEl = switchEl.closest('td').find('.status-label');

        $.ajax({
            url: "{{ route('backend_map_layer_toggle_status') }}",
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { id: layerId },
            success: function(res) {
                if (res.success) {
                    labelEl.text(res.is_active ? 'Hiện' : 'Ẩn');
                    toastr.success(res.message || 'Đã cập nhật trạng thái.');
                }
            },
            error: function() {
                switchEl.prop('checked', !switchEl.prop('checked'));
                toastr.error('Lỗi khi cập nhật trạng thái.');
            }
        });
    });

    // 3. XÓA LAYER (XÁC NHẬN LẦN HAI KHI CÓ DỰ ÁN DÙNG)
    let currentDeleteId = null;
    let currentProjectsCount = 0;

    $('.btn-delete-layer').on('click', function() {
        currentDeleteId = $(this).data('id');
        const name = $(this).data('name');
        currentProjectsCount = parseInt($(this).data('projects-count') || 0);

        $('#deleteLayerName').text('"' + name + '"');

        if (currentProjectsCount > 0) {
            $('#refProjectCount').text(currentProjectsCount);
            $('#projectReferenceAlert').removeClass('d-none');
            $('#doubleConfirmWrapper').removeClass('d-none');
            $('#doubleConfirmCheckbox').prop('checked', false);
            $('#btnConfirmDelete').prop('disabled', true);
        } else {
            $('#projectReferenceAlert').addClass('d-none');
            $('#doubleConfirmWrapper').addClass('d-none');
            $('#btnConfirmDelete').prop('disabled', false);
        }

        $('#deleteConfirmModal').modal('show');
    });

    $('#doubleConfirmCheckbox').on('change', function() {
        $('#btnConfirmDelete').prop('disabled', !$(this).is(':checked'));
    });

    $('#btnConfirmDelete').on('click', function() {
        if (!currentDeleteId) return;

        const isDoubleConfirmed = currentProjectsCount > 0 ? $('#doubleConfirmCheckbox').is(':checked') : true;

        $.ajax({
            url: baseUrl + "/backend/map-layers/delete/" + currentDeleteId,
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { confirmed: isDoubleConfirmed ? 1 : 0 },
            success: function(res) {
                $('#deleteConfirmModal').modal('hide');
                if (res.success) {
                    toastr.success(res.message);
                    setTimeout(function() {
                        location.reload();
                    }, 800);
                } else if (res.require_double_confirmation) {
                    toastr.warning(res.message);
                }
            },
            error: function() {
                toastr.error('Lỗi khi xóa layer!');
            }
        });
    });

    // 4. XEM CHI TIẾT AUDIT LOG
    $('.btn-view-log').on('click', function() {
        const log = $(this).data('log');
        $('#modalLogUser').text(log.user_name || 'Hệ Thống');
        $('#modalLogTime').text(log.created_at);
        $('#modalLogAction').text(log.action_label || log.action);
        $('#modalLogIp').text(log.ip_address || '127.0.0.1');

        $('#modalOldValues').text(log.old_values ? JSON.stringify(log.old_values, null, 2) : '(Không có)');
        $('#modalNewValues').text(log.new_values ? JSON.stringify(log.new_values, null, 2) : '(Không có)');

        $('#logDetailModal').modal('show');
    });
});
</script>
@endsection
