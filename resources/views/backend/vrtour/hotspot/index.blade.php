@extends('backend.index')

@section('title')
    Quản lý hotspot
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Hotspot</li>
@endsection

@section('content')
    <hr class="mt-0">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

                        {{-- Bên trái: bộ lọc --}}
                        <form action="" method="GET" class="form-filter-top-index d-flex flex-wrap ">
                            <div class="mr-2" style="min-width:220px">
                                <x-forms.select2 name="slt_vrtour" id="slt_vrtour" :options="$vrtour"
                                    placeholder="-- Chọn dự án --" :selected="old('slt_vrtour')" />
                            </div>
                            <div class="mr-2" style="min-width:160px">
                                <select class="form-control" id="slt_vrtour_type" style="height: 38px">
                                    {{-- <option value="0">-- Hotspot chính --</option> --}}
                                    <option value="2">-- Lô đất --</option>
                                    {{-- <option value="1">-- Toàn bộ --</option> --}}
                                </select>
                            </div>
                            <div class="mr-2" style="min-width:200px">
                                <select class="form-control" id="slt_status" style="height: 38px">
                                    <option value="">-- Tất cả trạng thái --</option>
                                    @foreach ($investmentStatuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                    <option value="none">Chưa có trạng thái</option>
                                </select>
                            </div>
                            @if (auth()->user()->is_super_admin)
                                <button type="button" class="btn btn-danger btn-sm" id="btn_reset"
                                    style="display:none; height: 38px;">Reset</button>
                            @endif
                            <button type="button" class="btn btn-success btn-sm ml-2" id="btn_export"
                                style="display:none; height: 38px;">
                                <i class="fas fa-file-excel"></i> Xuất Excel
                            </button>
                        </form>

                        {{-- Bên phải: chú thích lô đất --}}
                        <div class="d-flex flex-wrap align-items-center ml-auto">
                            <span class="font-weight-bold mr-3">
                                <i class="fas fa-info-circle text-info"></i> Chú thích lô đất:
                            </span>
                            @foreach ($investmentStatuses as $status)
                                <span class="d-inline-flex align-items-center ml-3">
                                    <span class="d-inline-block mr-2"
                                        style="width:16px;height:16px;border-radius:3px;background-color: {{ $status->color_code }}"></span>
                                    {{ $status->name }}
                                </span>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="bulkBar" class="d-none mb-3">
                        <div class="alert alert-info d-flex flex-wrap align-items-center mb-0">
                            <span class="mr-3">Đã chọn <strong id="bulkCount">0</strong> lô</span>
                            <select class="form-control mr-2" id="bulk_status" style="width:220px; height:38px">
                                <option value="">-- Chọn trạng thái mới --</option>
                                @foreach ($investmentStatuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary btn-sm mr-2" id="btn_bulk_apply"
                                style="height:38px">
                                Áp dụng
                            </button>
                            <button type="button" class="btn btn-primary btn-sm mr-2" style="height:38px"
                                id="btn_bulk_clear">Bỏ chọn</button>
                        </div>
                    </div>
                    <div id="bulkResult" class="alert alert-warning d-none"></div>
                    <div id="messageBox" class="alert alert-warning text-center" style="display: none;"></div>
                    <div class="card" id="listCard" style="display: none;">
                        <div class="card-body table-responsive p-0">

                            <table class="table table-hover table-grid-admin text-center">
                                <thead id="tableHeader" style="display: none;">
                                    <tr>
                                        <th style="width:3%" class="grid_header"><input type="checkbox" id="chk_all"></th>
                                        <th style="width:3%" class="grid_header">STT</th>
                                        <th style="width:15%" class="grid_header">Ảnh</th>
                                        <th style="width:10%" class="grid_header">Vị trí</th>
                                        <th style="width:10%" class="grid_header">Trạng thái</th>
                                        <th style="width:10%" class="grid_header">Tình trạng lô đất</th>
                                        <th style="width:10%" class="grid_header">Giá gốc</th>
                                        <th style="width:10%" class="grid_header">Giá thuê</th>
                                        <th style="width:10%" class="grid_header">Giá bán</th>
                                        <th style="width:10%" class="grid_header">Liên hệ</th>
                                        <th style="width:5%" class="grid_header1">Hiển thị</th>
                                        <th style="width:10%" class="grid_header1"></th>
                                    </tr>
                                </thead>
                                <tbody id="dataGrid">
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex justify-content-end" id="pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('backend_vrtour_hotspot_export') }}" method="POST" id="exportForm">
                    @csrf
                    <input type="hidden" name="vrtour_id" id="exp_vrtour_id">
                    <input type="hidden" name="type" id="exp_type">
                    <input type="hidden" name="status" id="exp_status">

                    <div class="modal-header">
                        <h5 class="modal-title">Chọn trường cần xuất</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="custom-control custom-checkbox mb-2 border-bottom pb-2">
                            <input type="checkbox" class="custom-control-input" id="exp_all">
                            <label class="custom-control-label font-weight-bold" for="exp_all">Chọn tất cả</label>
                        </div>
                        @foreach (\App\Exports\HotspotExport::FIELDS as $key => $label)
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" class="custom-control-input exp-field"
                                    id="exp_{{ $key }}" name="fields[]" value="{{ $key }}"
                                    @checked(in_array($key, \App\Exports\HotspotExport::DEFAULT_FIELDS))>
                                <label class="custom-control-label"
                                    for="exp_{{ $key }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-download"></i> Xuất file
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var lastUrl = null;

        function getParam(name, url = null) {
            let params;
            if (url) {
                // Nếu truyền url cụ thể
                params = new URL(url).searchParams;
            } else {
                // Nếu không truyền thì lấy từ window.location
                params = new URLSearchParams(window.location.search);
            }
            return params.get(name);
        }
        if (getParam("vrtour") != null && getParam("type") != null) {
            $('#slt_vrtour').val(getParam("vrtour")).change();
            $('#slt_vrtour_type').val(getParam("type")).change();
            renderTable(false);
        }

        $(document).on('click', '#btn_reset', function() {
            if (confirm("Bạn có chắc chắn muốn reset toàn bộ Hotspot của dự án này không?")) {
                renderTable(true, true);
            }
        });

        $('#slt_vrtour').on('change', function() {
            renderTable();
        });

        $('#slt_vrtour_type').on('change', function() {
            renderTable();
        });

        function fetchHotspots(url, notify = false) {
            lastUrl = url;
            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    if (response.success) {
                        $('#listCard').show();
                        $('#tableHeader').show();
                        $('#messageBox').hide();
                        $('#dataGrid').html(response.data);
                        $('#pagination').html(response.pagination);
                        updateBulkBar();
                        $('#btn_export').show();
                        if (notify) {
                            toastr["success"]('Lấy dữ liệu thành công', 'Success');
                        }
                        if (response.notice) toastr.warning(response.notice);
                    } else {
                        $('#listCard').hide();
                        $('#tableHeader').hide();
                        $('#dataGrid').html('');
                        $('#pagination').html('');
                        $('#btn_export').hide();
                        $('#messageBox').text(response.message).show();
                        toastr["warning"](response.message, 'Warning');
                    }
                    $('#btn_reset').show();
                },
                error: function() {
                    $('#listCard').hide();
                    $('#btn_reset').hide();
                    $('#dataGrid').html('');
                    $('#pagination').html('');
                    $('#btn_export').hide();
                    toastr["error"]('Có lỗi xảy ra ! Vui lòng thử lại', 'Error');
                }
            });
        }

        function renderTable(notify = true, reset = false) {
            var vrtour = $('#slt_vrtour').val();
            var type = $('#slt_vrtour_type').val();
            var status = $('#slt_status').val();
            if (vrtour && vrtour != 'all') {
                fetchHotspots(
                    assetUrl + 'vrtour/hotspot/get-hotspot/' + vrtour +
                    '?type=' + type +
                    '&status=' + encodeURIComponent(status) +
                    '&reset=' + reset,
                    notify
                );
            } else {
                $('#listCard').hide();
                $('#messageBox').hide();
                $('#btn_reset').hide();
                $('#dataGrid').html('');
                $('#pagination').html('');
                $('#btn_export').hide();
            }
        }

        $('#slt_status').on('change', function() {
            renderTable();
        });

        // Bấm số trang: gọi lại AJAX bằng chính link của paginator
        $(document).on('click', '#pagination a', function(e) {
            e.preventDefault();
            fetchHotspots($(this).attr('href'), false);
        });


        function updateBulkBar() {
            var n = $('.chk-row:checked').length;
            var selectable = $('.chk-row:not(:disabled)').length;
            $('#bulkCount').text(n);
            $('#bulkBar').toggleClass('d-none', n === 0);
            $('#chk_all').prop('checked', n > 0 && n === selectable);
        }

        $(document).on('change', '.chk-row', updateBulkBar);

        $(document).on('change', '#chk_all', function() {
            $('.chk-row:not(:disabled)').prop('checked', this.checked);
            updateBulkBar();
        });

        $('#btn_bulk_clear').on('click', function() {
            $('.chk-row, #chk_all').prop('checked', false);
            updateBulkBar();
        });

        function showBulkResult(res) {
            var $box = $('#bulkResult').empty();
            if (!res.skipped || !res.skipped.length) {
                $box.addClass('d-none');
                return;
            }
            var $ul = $('<ul class="mb-0 pl-3"></ul>');
            res.skipped.forEach(function(s) {
                $ul.append($('<li></li>').text((s.position ? s.position + ': ' : '') + s.reason));
            });
            $box.append($('<strong></strong>').text('Các mục bị bỏ qua:'), $ul).removeClass('d-none');
        }

        $('#btn_bulk_apply').on('click', function() {
            var ids = $('.chk-row:checked').map(function() {
                return this.value;
            }).get();
            var statusId = $('#bulk_status').val();
            if (!statusId) {
                toastr['warning']('Vui lòng chọn trạng thái mới', 'Warning');
                return;
            }
            var statusName = $('#bulk_status option:selected').text();
            if (!confirm('Đổi ' + ids.length + ' lô đất sang trạng thái "' + statusName + '"?')) {
                return;
            }

            var $btn = $(this).prop('disabled', true);
            $.ajax({
                url: assetUrl + 'vrtour/hotspot/bulk-status',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    vrtour_id: $('#slt_vrtour').val(),
                    ids: ids,
                    status_id: statusId
                },
                success: function(res) {
                    var clean = res.success && (!res.skipped || !res.skipped.length);
                    toastr[clean ? 'success' : 'warning'](res.message, clean ? 'Success' : 'Warning');
                    showBulkResult(res);
                    if (res.notice) toastr.warning(res.notice);
                    if (res.success) {
                        if (lastUrl) {
                            fetchHotspots(lastUrl.replace('reset=true', 'reset=false'), false);
                        } else {
                            renderTable(false);
                        }
                    }
                },
                error: function(xhr) {
                    var msg = (xhr.responseJSON && xhr.responseJSON.message) ||
                        'Có lỗi xảy ra ! Vui lòng thử lại';
                    toastr['error'](msg, 'Error');
                },
                complete: function() {
                    $btn.prop('disabled', false);
                }
            });
        });

        // xuất excel
        $('#btn_export').on('click', function() {
            $('#exportModal').modal('show');
        });

        // Lấy đúng bộ lọc đang chọn lúc mở popup
        $('#exportModal').on('show.bs.modal', function() {
            $('#exp_vrtour_id').val($('#slt_vrtour').val());
            $('#exp_type').val($('#slt_vrtour_type').val());
            $('#exp_status').val($('#slt_status').val());
            syncExportAll();
        });

        function syncExportAll() {
            var total = $('.exp-field').length;
            $('#exp_all').prop('checked', $('.exp-field:checked').length === total);
        }

        $('#exp_all').on('change', function() {
            $('.exp-field').prop('checked', this.checked);
        });
        $(document).on('change', '.exp-field', syncExportAll);

        $('#exportForm').on('submit', function(e) {
            if (!$('.exp-field:checked').length) {
                e.preventDefault();
                toastr['warning']('Vui lòng chọn ít nhất một trường cần xuất', 'Warning');
                return;
            }
            // Form tải file nên trang không đổi, chỉ đóng popup
            setTimeout(function() {
                $('#exportModal').modal('hide');
            }, 300);
        });
    </script>
@endsection
