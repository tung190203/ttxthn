@extends('backend.index')

@section('title')
{{ $guest->exists ? 'Edit Investor Account' : 'Add Investor Account' }}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('backend_guest') }}">Investor Accounts</a></li>
<li class="breadcrumb-item active">{{ $guest->exists ? 'Edit' : 'Add New' }}</li>
@endsection

@section('content')

<script src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>
<script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('backend_assets/js/globals.js') }}"></script>
<script>
    CKFinder.config({
        connectorPath: '/ckfinder/connector'
    });
</script>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-edit mr-1"></i>
                            {{ $guest->exists ? 'Investor: ' . $guest->name : 'New Investor Account' }}
                        </h3>
                        <div class="card-tools">
                            @can('guest')
                            {{-- Quick action buttons for existing accounts --}}
                            @if($guest->exists)
                                @if($guest->status === 'pending' && in_array($guest->investor_type, ['organization', 'fdi']))
                                <button type="button" class="btn btn-success btn-sm mr-1 js-approve-btn"
                                    data-id="{{ $guest->id }}" data-name="{{ $guest->name }}">
                                    <i class="fa fa-check mr-1"></i> Approve
                                </button>
                                <button type="button" class="btn btn-warning btn-sm mr-1 js-reject-btn"
                                    data-id="{{ $guest->id }}" data-name="{{ $guest->name }}">
                                    <i class="fa fa-times mr-1"></i> Reject
                                </button>
                                @endif
                                @if($guest->status === 'locked')
                                <button type="button" class="btn btn-info btn-sm mr-1 js-unlock-btn"
                                    data-id="{{ $guest->id }}" data-name="{{ $guest->name }}">
                                    <i class="fa fa-unlock mr-1"></i> Unlock
                                </button>
                                @elseif($guest->status === 'active')
                                <button type="button" class="btn btn-danger btn-sm mr-1 js-lock-btn"
                                    data-id="{{ $guest->id }}" data-name="{{ $guest->name }}">
                                    <i class="fa fa-lock mr-1"></i> Lock
                                </button>
                                @endif
                            @endif
                            <x-forms.button-save />
                            @endcan
                            @if($guest->exists)
                            @can('guest')
                            <x-forms.button-url title="Add New" class="btn-info" icon="fa fa-plus"
                                url="{{ route('backend_guest_create') }}" />
                            @endcan
                            @can('guest/delete')
                            <x-forms.button-url title="Delete" class="btn-danger" icon="fa fa-trash"
                                url="{{ route('backend_guest_delete', $guest->id) }}" />
                            @endcan
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('backend_guest_save', $guest) }}" method="post"
                        enctype="multipart/form-data"
                        class="form-horizontal" id="formDataGrid">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <!-- Left: Personal Info -->
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <h5 class="text-primary border-bottom pb-2">
                                                <i class="fas fa-id-card mr-1"></i> Basic Information
                                            </h5>
                                        </div>
                                    </div>

                                    {{-- Investor Type --}}
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label font-weight-bold">Loại nhà đầu tư</label>
                                        <div class="col-sm-9">
                                            <select name="investor_type" id="investor_type_select" class="form-control" onchange="document.getElementById('org-fields').style.display = (this.value === 'organization' || this.value === 'fdi') ? 'block' : 'none';">
                                                <option value="individual" {{ (old('investor_type') ?: $guest->investor_type) === 'individual' ? 'selected' : '' }}>Cá nhân</option>
                                                <option value="organization" {{ (old('investor_type') ?: $guest->investor_type) === 'organization' ? 'selected' : '' }}>Tổ chức</option>
                                                <option value="fdi" {{ (old('investor_type') ?: $guest->investor_type) === 'fdi' ? 'selected' : '' }}>Doanh nghiệp FDI</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Organization fields --}}
                                    <div id="org-fields" style="{{ in_array(old('investor_type') ?: $guest->investor_type, ['organization', 'fdi']) ? '' : 'display:none;' }}">
                                        <x-forms.input name="organization_name" value="{{ old('organization_name') ?: $guest->organization_name }}"
                                            label="Tên tổ chức/Công ty" :messages="$errors->get('organization_name')" />
                                        <x-forms.input name="tax_code" value="{{ old('tax_code') ?: $guest->tax_code }}"
                                            label="Mã số thuế" :messages="$errors->get('tax_code')" />
                                    </div>

                                    <x-forms.input name="name" value="{{ old('name') ?: $guest->name }}"
                                        label="<i class='fas fa-user mr-1'></i> Họ và tên" required="true" :messages="$errors->get('name')" />

                                    <x-forms.input name="email" value="{{ old('email') ?: $guest->email }}"
                                        label="<i class='fas fa-envelope mr-1'></i> Email" type="email" required="true" :messages="$errors->get('email')" />

                                    <x-forms.input name="phone" value="{{ old('phone') ?: $guest->phone }}"
                                        label="<i class='fas fa-phone mr-1'></i> Số điện thoại" :messages="$errors->get('phone')" />

                                    <x-forms.input name="identification_number" value="{{ old('identification_number') ?: $guest->identification_number }}"
                                        label="<i class='fas fa-id-badge mr-1'></i> CMND/CCCD/HC" :messages="$errors->get('identification_number')" />

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"><i class="fas fa-globe mr-1"></i> Quốc tịch</label>
                                        <div class="col-sm-9">
                                            <select name="nation_id" class="form-control select2 shadow-sm">
                                                <option value="">Chọn quốc tịch</option>
                                                @foreach($nations as $nation)
                                                <option value="{{ $nation->id }}" {{ (old('nation_id') ?: $guest->nation_id) == $nation->id ? 'selected' : '' }}>
                                                    {{ $nation->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('nation_id'))
                                            <span class="text-danger small">{{ $errors->first('nation_id') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <x-forms.input name="password" value=""
                                        label="<i class='fas fa-lock mr-1'></i> {{ $guest->exists ? 'Đổi mật khẩu' : 'Mật khẩu' }}"
                                        type="password" :required="!$guest->exists" :messages="$errors->get('password')"
                                        help="{{ $guest->exists ? 'Để trống nếu không muốn đổi mật khẩu' : '' }}" />

                                    {{-- Status (admin can override) --}}
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label font-weight-bold">Trạng thái</label>
                                        <div class="col-sm-9">
                                            <select name="status" class="form-control">
                                                <option value="pending" {{ (old('status') ?: $guest->status) === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                                <option value="active" {{ (old('status') ?: $guest->status) === 'active' ? 'selected' : '' }}>Hoạt động</option>
                                                <option value="locked" {{ (old('status') ?: $guest->status) === 'locked' ? 'selected' : '' }}>Đã khóa</option>
                                                <option value="rejected" {{ (old('status') ?: $guest->status) === 'rejected' ? 'selected' : '' }}>Từ chối</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right: Avatar & Address -->
                                <div class="col-md-5">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <h5 class="text-primary border-bottom pb-2">
                                                <i class="fas fa-image mr-1"></i> Avatar & Address
                                            </h5>
                                        </div>
                                    </div>

                                    <div class="card bg-light mb-4 shadow-sm border-0">
                                        <div class="card-body p-3">
                                            <x-forms.upload name="avatar" value="{{ old('avatar') ?: $guest->avatar }}"
                                                label="<i class='fas fa-camera mr-1'></i> Ảnh đại diện"
                                                type="image" :messages="$errors->get('avatar')"
                                                class_label="col-sm-4" class_input="col-sm-8" />

                                            <p class="text-muted small mt-2 ml-md-auto col-md-8 px-0">
                                                <i class="fas fa-info-circle mr-1"></i> JPG, PNG, GIF. Tối đa 2MB.
                                            </p>
                                        </div>
                                    </div>

                                    <x-forms.textarea name="address" value="{{ old('address') ?: $guest->address }}"
                                        label="<i class='fas fa-map-marker-alt mr-1'></i> Địa chỉ" :messages="$errors->get('address')"
                                        rows="4" class_label="col-sm-3" class_input="col-sm-9" />

                                    {{-- Account info (read-only) --}}
                                    @if($guest->exists)
                                    <div class="card bg-light shadow-sm border-0 mt-3">
                                        <div class="card-body p-3">
                                            <h6 class="font-weight-bold mb-2"><i class="fa fa-info-circle mr-1"></i> Account Info</h6>
                                            <small class="text-muted">
                                                <strong>Email verified:</strong>
                                                {{ $guest->email_verified_at ? $guest->email_verified_at->format('d/m/Y H:i') : 'Not verified' }}<br>
                                                <strong>Last login IP:</strong> {{ $guest->last_login_ip ?? 'N/A' }}<br>
                                                <strong>Failed attempts:</strong> {{ $guest->failed_login_attempts ?? 0 }}<br>
                                                <strong>Registered:</strong> {{ $guest->created_at?->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right bg-white p-3">
                            <x-forms.button-save title="Save" />
                            <a href="{{ route('backend_guest') }}" class="btn btn-default btn-sm ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fa fa-times-circle mr-2"></i>Reject Account</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Reject account: <strong id="rejectName"></strong>?</p>
                    <div class="form-group">
                        <label>Reason (optional, sent to investor)</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Enter rejection reason..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('css')
<style>
    .card-outline.card-primary { border-top: 4px solid #007bff; }
    .form-group label { font-weight: 600; color: #495057; }
    .text-primary { color: #007bff !important; }
    .preview_image img { border: 4px solid #fff; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); border-radius: 8px; }
    .form-control:focus { border-color: #80bdff; box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25); }
    .select2-container--default .select2-selection--single { border: 1px solid #ced4da; height: calc(2.25rem + 2px); }
</style>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    // Toggle org fields (Robust approach)
    // We also have inline onchange on the select tag to guarantee it works.
    let lastInvestorType = $('#investor_type_select').val();
    function checkInvestorType() {
        let currentType = $('#investor_type_select').val();
        if (currentType !== lastInvestorType) {
            lastInvestorType = currentType;
            if (currentType === 'organization' || currentType === 'fdi') {
                $('#org-fields').show();
            } else {
                $('#org-fields').hide();
            }
        }
    }
    $('#investor_type_select').on('change select2:select', checkInvestorType);
    setInterval(checkInvestorType, 500);

    // Approve
    $(document).on('click', '.js-approve-btn', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        Swal.fire({
            title: 'Xác nhận duyệt',
            text: `Bạn có chắc muốn duyệt tài khoản "${name}"? Hệ thống sẽ tự động gửi email thông báo cho nhà đầu tư.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Đồng ý duyệt',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('/backend/guest/approve') }}/${id}`,
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    dataType: 'json',
                    success: function (res) {
                        if (res.success) {
                            toastr.success(res.message);
                            setTimeout(() => location.reload(), 1200);
                        }
                    },
                    error: function () { toastr.error('Error. Please try again.'); }
                });
            }
        });
    });

    // Reject
    $(document).on('click', '.js-reject-btn', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#rejectName').text(name);
        $('#rejectForm').attr('action', `{{ url('/backend/guest/reject') }}/${id}`);
        $('#rejectModal textarea[name=reason]').val('');
        $('#rejectModal').modal('show');
    });

    // Lock
    $(document).on('click', '.js-lock-btn', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        Swal.fire({
            title: 'Khóa tài khoản',
            text: `Bạn có chắc muốn khóa tài khoản "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Đồng ý khóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('/backend/guest/lock') }}/${id}`,
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    dataType: 'json',
                    success: function (res) {
                        if (res.success) {
                            toastr.success(res.message);
                            setTimeout(() => location.reload(), 1200);
                        }
                    },
                    error: function () { toastr.error('Error. Please try again.'); }
                });
            }
        });
    });

    // Unlock
    $(document).on('click', '.js-unlock-btn', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        Swal.fire({
            title: 'Mở khóa tài khoản',
            text: `Bạn có chắc muốn mở khóa tài khoản "${name}"?`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Đồng ý mở khóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('/backend/guest/unlock') }}/${id}`,
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    dataType: 'json',
                    success: function (res) {
                        if (res.success) {
                            toastr.success(res.message);
                            setTimeout(() => location.reload(), 1200);
                        }
                    },
                    error: function () { toastr.error('Error. Please try again.'); }
                });
            }
        });
    });
});
</script>
@endsection