@extends('backend.index')

@section('title')
    {{ $layer->exists ? 'Sửa Layer bản đồ' : 'Thêm mới Layer bản đồ' }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend_map_layer') }}">Quản lý Layer</a></li>
    <li class="breadcrumb-item active">{{ $layer->exists ? 'Sửa' : 'Thêm mới' }}</li>
@endsection

@section('content')
<hr class="mt-0">
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0 pl-3">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">
                            {{ $layer->exists ? 'Sửa cấu hình Layer: ' . $layer->name : 'Thông tin Layer bản đồ mới' }}
                        </h3>
                    </div>

                    <form id="layerForm" action="{{ route('backend_map_layer_save', $layer->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">

                            <!-- TÊN LAYER -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label required">
                                    Tên Layer <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" id="layerName" class="form-control" 
                                           placeholder="Ví dụ: Quy hoạch phân khu sông Hồng, Tuyến đường sắt đô thị..." 
                                           maxlength="200" required
                                           value="{{ old('name', $layer->name) }}">
                                    <small class="text-muted">Tối đa 200 ký tự. Hiển thị trên hộp chọn lớp ngoài trang chủ.</small>
                                </div>
                            </div>

                            <!-- LOẠI NGUỒN -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label required">
                                    Loại nguồn dữ liệu <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <select name="type" id="layerType" class="form-control" required>
                                        @foreach($types as $key => $label)
                                            <option value="{{ $key }}" {{ old('type', $layer->type) === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- PHƯƠNG THỨC: DÁN LINK HOẶC UPLOAD -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">
                                    Phương thức cung cấp <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-secondary active btn-sm" id="labelSourceUrl" onclick="switchSourceMode('url')">
                                            <input type="radio" name="source_mode" checked> <i class="fas fa-link mr-1"></i> Dán liên kết URL Endpoint
                                        </label>
                                        <label class="btn btn-outline-secondary btn-sm" id="labelSourceFile" onclick="switchSourceMode('file')">
                                            <input type="radio" name="source_mode"> <i class="fas fa-upload mr-1"></i> Tải tệp từ máy tính
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- 1. URL ENDPOINT -->
                            <div class="form-group row" id="sectionUrlEndpoint">
                                <label class="col-sm-3 col-form-label required">
                                    URL Endpoint <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" name="url_endpoint" id="urlEndpoint" class="form-control" 
                                               placeholder="Ví dụ: /api/layers/data/boundary hoặc https://geoserver.example.vn/wms" 
                                               value="{{ old('url_endpoint', $layer->url_endpoint) }}">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-info btn-sm" id="btnTestConnection">
                                                <i class="fas fa-plug mr-1"></i> Kiểm tra kết nối
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1">Đường dẫn nguồn dữ liệu. Bắt buộc kiểm tra kết nối thành công trước khi lưu.</small>

                                    <!-- KẾT QUẢ TEST CONNECTION -->
                                    <div id="testConnResultBox" class="alert d-none mt-2 mb-0 py-2" style="font-size: 13.5px;"></div>
                                </div>
                            </div>

                            <!-- 2. UPLOAD FILE -->
                            <div class="form-group row d-none" id="sectionFileUpload">
                                <label class="col-sm-3 col-form-label">
                                    Chọn tệp từ máy tính
                                </label>
                                <div class="col-sm-9">
                                    <div class="custom-file">
                                        <input type="file" name="file_upload" class="custom-file-input" id="customFile" accept=".geojson,.json,.zip">
                                        <label class="custom-file-label" for="customFile">Chọn file (.geojson, .json hoặc file nén .zip Shapefile)...</label>
                                    </div>
                                    <small class="text-muted d-block mt-1">Hệ thống sẽ lưu trữ và tự động sinh URL endpoint nội bộ.</small>
                                    @if($layer->file_path)
                                        <div class="mt-2 text-success font-weight-bold" style="font-size: 13px;">
                                            <i class="fas fa-file mr-1"></i> Đang dùng file: <code>{{ basename($layer->file_path) }}</code>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- THAM SỐ WMS NẾU CHỌN WMS -->
                            <div class="form-group row d-none" id="sectionWmsOptions">
                                <label class="col-sm-3 col-form-label">
                                    Tên Layer WMS (layers param)
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" name="wms_layers" id="wmsLayers" class="form-control" 
                                           placeholder="Ví dụ: hanoi:quyhoach_chung" 
                                           value="{{ old('wms_layers', $layer->layer_options['wms_layers'] ?? '') }}">
                                </div>
                            </div>

                            <!-- MÔ TẢ -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">
                                    Mô tả layer
                                </label>
                                <div class="col-sm-9">
                                    <textarea name="description" class="form-control" rows="3" 
                                              placeholder="Mô tả nội dung lớp dữ liệu...">{{ old('description', $layer->description) }}</textarea>
                                </div>
                            </div>

                            <!-- THỨ TỰ HIỂN THỊ -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label required">
                                    Thứ tự hiển thị <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9">
                                    <input type="number" name="display_order" class="form-control" style="max-width: 120px;" 
                                           value="{{ old('display_order', $layer->display_order) }}" required min="0">
                                    <small class="text-muted">Số nguyên quyết định layer nào nằm trên.</small>
                                </div>
                            </div>

                            <!-- TRẠNG THÁI HIỆN / ẨN -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label required">
                                    Trạng thái <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-9 pt-1">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="statusActive" name="is_active" class="custom-control-input" value="1" 
                                               {{ old('is_active', $layer->is_active ?? true) ? 'checked' : '' }}>
                                        <label class="custom-control-label font-weight-normal text-success" for="statusActive">
                                            Hiện trên bản đồ
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="statusInactive" name="is_active" class="custom-control-input" value="0" 
                                               {{ !old('is_active', $layer->is_active ?? true) ? 'checked' : '' }}>
                                        <label class="custom-control-label font-weight-normal text-muted" for="statusInactive">
                                            Ẩn
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- TÙY CHỈNH MÀU SẮC -->
                            <div class="card card-outline card-secondary mt-3">
                                <div class="card-header py-2">
                                    <h5 class="card-title" style="font-size: 14px; font-weight: 600;">
                                        Tùy chỉnh màu sắc hiển thị (GeoJSON)
                                    </h5>
                                </div>
                                <div class="card-body py-2">
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <label class="text-muted" style="font-size: 13px;">Màu đường viền:</label>
                                            <input type="color" name="layer_color" class="form-control" style="height: 38px;" 
                                                   value="{{ old('layer_color', $layer->layer_options['color'] ?? '#1a6fc4') }}">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label class="text-muted" style="font-size: 13px;">Màu nền polygon:</label>
                                            <input type="color" name="layer_fill_color" class="form-control" style="height: 38px;" 
                                                   value="{{ old('layer_fill_color', $layer->layer_options['fillColor'] ?? '#4a9ede') }}">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label class="text-muted" style="font-size: 13px;">Độ mờ (0.1 - 1.0):</label>
                                            <input type="number" step="0.05" min="0.1" max="1.0" name="layer_opacity" class="form-control" 
                                                   value="{{ old('layer_opacity', $layer->layer_options['opacity'] ?? 0.8) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- CẤU HÌNH NGƯỠNG GIÁM SÁT NGUỒN (USE CASE A3) -->
                            <div class="card card-outline card-info mt-3">
                                <div class="card-header py-2">
                                    <h5 class="card-title" style="font-size: 14px; font-weight: 600;">
                                        <i class="fas fa-satellite mr-1"></i> Ngưỡng cảnh báo giám sát nguồn dữ liệu (Use Case A3)
                                    </h5>
                                </div>
                                <div class="card-body py-2">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="text-muted" style="font-size: 13px;">Downtime tối đa trước khi gửi cảnh báo email (phút):</label>
                                            <div class="input-group input-group-sm">
                                                <input type="number" name="max_downtime_minutes" class="form-control" min="1" max="1440" 
                                                       value="{{ old('max_downtime_minutes', $layer->max_downtime_minutes ?: 5) }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">phút</span>
                                                </div>
                                            </div>
                                            <small class="text-muted">Mặc định: 5 phút gián đoạn liên tục.</small>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="text-muted" style="font-size: 13px;">Độ trễ giới hạn (ms) báo cảnh báo chậm:</label>
                                            <div class="input-group input-group-sm">
                                                <input type="number" name="max_latency_ms" class="form-control" min="50" max="60000" step="50" 
                                                       value="{{ old('max_latency_ms', $layer->max_latency_ms ?: 2000) }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">ms</span>
                                                </div>
                                            </div>
                                            <small class="text-muted">Mặc định: 2000 ms (2 giây).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('backend_map_layer') }}" class="btn btn-default mr-2">
                                Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-primary" id="btnSaveLayer">
                                <i class="fas fa-save mr-1"></i> Lưu thông tin
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
let lastTestedUrl = null;
let lastTestSuccess = false;
const csrfToken = $('meta[name="csrf-token"]').attr('content');

function switchSourceMode(mode) {
    if (mode === 'url') {
        $('#sectionUrlEndpoint').removeClass('d-none');
        $('#sectionFileUpload').addClass('d-none');
    } else {
        $('#sectionFileUpload').removeClass('d-none');
    }
}

$(document).ready(function() {
    $('#customFile').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            $(this).next('.custom-file-label').html(file.name);
            if (!$('#layerName').val().trim()) {
                const autoName = file.name.replace(/\.[^/.]+$/, "").replace(/[-_]/g, ' ');
                $('#layerName').val(autoName.charAt(0).toUpperCase() + autoName.slice(1));
            }
        }
    });

    $('#layerType').on('change', function() {
        if ($(this).val() === 'WMS') {
            $('#sectionWmsOptions').removeClass('d-none');
        } else {
            $('#sectionWmsOptions').addClass('d-none');
        }
    }).trigger('change');

    // NÚT TEST KẾT NỐI
    $('#btnTestConnection').on('click', function() {
        const url = $('#urlEndpoint').val().trim();
        const type = $('#layerType').val();

        if (!url) {
            toastr.warning('Vui lòng nhập địa chỉ URL endpoint.');
            $('#urlEndpoint').focus();
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Đang kiểm tra...');

        $('#testConnResultBox').removeClass('d-none alert-success alert-danger')
            .addClass('alert-info')
            .html('<i class="fas fa-spinner fa-spin mr-1"></i> Đang kết nối tới URL endpoint...');

        $.ajax({
            url: "{{ route('backend_map_layer_test_connection') }}",
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            data: { url_endpoint: url, type: type, wms_layers: $('#wmsLayers').val() },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-plug mr-1"></i> Kiểm tra kết nối');

                if (res.success) {
                    lastTestedUrl = url;
                    lastTestSuccess = true;
                    $('#testConnResultBox')
                        .removeClass('alert-info alert-danger')
                        .addClass('alert-success')
                        .html('<i class="fas fa-check-circle mr-1"></i> <strong>Thành công:</strong> ' + res.message);
                    toastr.success('Kết nối thành công!');
                } else {
                    lastTestSuccess = false;
                    $('#testConnResultBox')
                        .removeClass('alert-info alert-success')
                        .addClass('alert-danger')
                        .html('<i class="fas fa-times-circle mr-1"></i> <strong>Thất bại:</strong> ' + res.message);
                    toastr.error(res.message);
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-plug mr-1"></i> Kiểm tra kết nối');
                lastTestSuccess = false;

                let msg = 'Không kết nối được tới nguồn dữ liệu. Kiểm tra lại địa chỉ hoặc quyền truy cập.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }

                $('#testConnResultBox')
                    .removeClass('alert-info alert-success')
                    .addClass('alert-danger')
                    .html('<i class="fas fa-times-circle mr-1"></i> <strong>Lỗi:</strong> ' + msg);
                toastr.error(msg);
            }
        });
    });

    // SUBMIT FORM: BẮT BUỘC KIỂM TRA KẾT NỐI
    $('#layerForm').on('submit', function(e) {
        const hasFile = $('#customFile')[0].files && $('#customFile')[0].files.length > 0;
        const currentUrl = $('#urlEndpoint').val().trim();

        if (hasFile) {
            return true;
        }

        if (lastTestSuccess && lastTestedUrl === currentUrl) {
            return true;
        }

        e.preventDefault();
        toastr.info('Đang kiểm tra kết nối tới nguồn dữ liệu trước khi lưu...');
        $('#btnTestConnection').trigger('click');

        const checkInterval = setInterval(function() {
            if (!$('#btnTestConnection').prop('disabled')) {
                clearInterval(checkInterval);
                if (lastTestSuccess) {
                    $('#layerForm')[0].submit();
                } else {
                    toastr.error('Không thể lưu layer vì kiểm tra kết nối thất bại!');
                }
            }
        }, 300);
    });
});
</script>
@endsection
