@extends('backend.index')

@section('title')
Quản lý Tài khoản Nhà đầu tư
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active">Tài khoản Nhà đầu tư</li>
@endsection

@section('content')

<hr class="mt-0">
<section class="content">
    <div class="container-fluid">
        <!-- Báo cáo thống kê nhanh -->
        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $report['total'] }}</h3>
                        <p>Tổng Nhà đầu tư</p>
                    </div>
                    <div class="icon"><i class="fas fa-users"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $report['active'] }}</h3>
                        <p>Đang hoạt động</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-check"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $report['pending'] }}</h3>
                        <p>Chờ xác thực</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-clock"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $report['locked'] }}</h3>
                        <p>Tài khoản bị khóa</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-lock"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-10">
                <form action="" method="GET" class="form-filter-top-index">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" value="{{ $filter['name'] }}"
                                    placeholder="Họ và tên">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" name="email" class="form-control" value="{{ $filter['email'] }}"
                                    placeholder="Email">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" name="phone" class="form-control" value="{{ $filter['phone'] }}"
                                    placeholder="Số điện thoại">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select name="investor_type" class="form-control">
                                    <option value="">-- Loại nhà đầu tư --</option>
                                    <option value="individual" {{ $filter['investor_type'] === 'individual' ? 'selected' : '' }}>Cá nhân</option>
                                    <option value="organization" {{ $filter['investor_type'] === 'organization' ? 'selected' : '' }}>Tổ chức</option>
                                    <option value="fdi" {{ $filter['investor_type'] === 'fdi' ? 'selected' : '' }}>Doanh nghiệp FDI</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select name="status" class="form-control">
                                    <option value="">-- Trạng thái --</option>
                                    <option value="pending" {{ $filter['status'] === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                    <option value="active" {{ $filter['status'] === 'active' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="locked" {{ $filter['status'] === 'locked' ? 'selected' : '' }}>Đã khoá</option>
                                    <option value="rejected" {{ $filter['status'] === 'rejected' ? 'selected' : '' }}>Đã từ chối</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 text-left">
                            <div class="form-group text-nowrap">
                                <button type="submit" class="btn btn-primary btn-sm mr-1">
                                    <i class="fa fa-search"></i> Tìm kiếm
                                </button>
                                <a href="{{ route('backend_guest') }}" class="btn btn-default btn-sm">Làm mới</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xl-2">
                <div class="float-right mb-3">
                    <a href="{{ route('backend_guest_export', request()->query()) }}" class="btn btn-success btn-sm mb-1">
                        <i class="fas fa-file-excel mr-1"></i> Xuất Excel
                    </a>
                    @can('guest')
                    <x-forms.button-url title="Thêm mới" class="btn-info" icon="fa fa-plus"
                        url="{{ route('backend_guest_create') }}" />
                    @endcan
                    @can('guest')
                    <x-forms.button-bulk-delete url="{{ route('backend_guest_bulk_delete')}}" />
                    @endcan
                </div>
            </div>
        </div>

        {{-- Summary badges --}}
        <div class="row mb-3">
            <div class="col-12">
                @php
                    $pendingCount  = \App\Models\Guest::where('status', 'pending')->whereNotNull('email_verified_at')->whereIn('investor_type', ['organization', 'fdi'])->count();
                    $activeCount   = \App\Models\Guest::where('status', 'active')->count();
                    $lockedCount   = \App\Models\Guest::where('status', 'locked')->count();
                    $rejectedCount = \App\Models\Guest::where('status', 'rejected')->count();
                @endphp
                <a href="{{ route('backend_guest', ['status' => 'pending', 'investor_type' => '']) }}" class="btn btn-sm btn-warning mr-1">
                    <i class="fa fa-clock"></i> Chờ duyệt <span class="badge badge-light">{{ $pendingCount }}</span>
                </a>
                <a href="{{ route('backend_guest', ['status' => 'active']) }}" class="btn btn-sm btn-success mr-1">
                    <i class="fa fa-check-circle"></i> Active <span class="badge badge-light">{{ $activeCount }}</span>
                </a>
                <a href="{{ route('backend_guest', ['status' => 'locked']) }}" class="btn btn-sm btn-danger mr-1">
                    <i class="fa fa-lock"></i> Locked <span class="badge badge-light">{{ $lockedCount }}</span>
                </a>
                <a href="{{ route('backend_guest', ['status' => 'rejected']) }}" class="btn btn-sm btn-secondary mr-1">
                    <i class="fa fa-times-circle"></i> Rejected <span class="badge badge-light">{{ $rejectedCount }}</span>
                </a>
            </div>
        </div>

        <form method="post" action="{{ route('backend_guest_save_data_index') }}" id="formDataGrid">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-bordered table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th width="30px"><input type="checkbox" id="checkAll"></th>
                                        <th nowrap>Ảnh đại diện</th>
                                        <th nowrap>Họ và tên</th>
                                        <th nowrap>Email</th>
                                        <th nowrap>Số điện thoại</th>
                                        <th nowrap>Loại nhà đầu tư</th>
                                        <th nowrap>Tổ chức/Doanh nghiệp</th>
                                        <th nowrap>Trạng thái</th>
                                        <th nowrap>Ngày đăng ký</th>
                                        <th nowrap>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($guests as $g)
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="{{ $g->id }}" class="check-item"></td>
                                        <td>
                                            @if($g->avatar_url)
                                                <img src="{{ $g->avatar_url }}" width="40" height="40" style="border-radius:50%; object-fit:cover;">
                                            @else
                                                <div style="width:40px; height:40px; border-radius:50%; background:#e9ecef; display:flex; align-items:center; justify-content:center; font-size:16px; color:#6c757d;">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('backend_guest_edit', $g->id) }}" class="font-weight-bold">{{ $g->name }}</a>
                                        </td>
                                        <td>{{ $g->email }}</td>
                                        <td>{{ $g->phone }}</td>
                                        <td>
                                            @if($g->investor_type === 'individual')
                                                <span class="badge badge-info">Cá nhân</span>
                                            @elseif($g->investor_type === 'organization')
                                                <span class="badge badge-primary">Tổ chức</span>
                                            @elseif($g->investor_type === 'fdi')
                                                <span class="badge badge-dark">FDI</span>
                                            @endif
                                        </td>
                                        <td>{{ $g->organization_name ?? '—' }}</td>
                                        <td>
                                            @if($g->status === 'active')
                                                <span class="badge badge-success">Hoạt động</span>
                                            @elseif($g->status === 'pending')
                                                <span class="badge badge-warning">Chờ duyệt</span>
                                            @elseif($g->status === 'locked')
                                                <span class="badge badge-danger">Đã khoá</span>
                                            @elseif($g->status === 'rejected')
                                                <span class="badge badge-secondary">Đã từ chối</span>
                                            @endif
                                        </td>
                                        <td nowrap>{{ $g->created_at?->format('d-m-Y') }}</td>
                                        <td nowrap>
                                            {{-- Show --}}
                                            <a href="{{ route('backend_guest_show', $g->id) }}" class="btn btn-xs btn-info" title="Xem hồ sơ">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            
                                            {{-- Edit --}}
                                            <a href="{{ route('backend_guest_edit', $g->id) }}" class="btn btn-xs btn-primary" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            {{-- Approve (Pending org/fdi) --}}
                                            @if($g->status === 'pending' && in_array($g->investor_type, ['organization', 'fdi']))
                                            <button type="button" class="btn btn-xs btn-success js-approve-btn"
                                                data-id="{{ $g->id }}" data-name="{{ $g->name }}"
                                                title="Approve">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-xs btn-warning js-reject-btn"
                                                data-id="{{ $g->id }}" data-name="{{ $g->name }}"
                                                title="Reject">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            @endif

                                            {{-- Lock/Unlock --}}
                                            @if($g->status === 'locked')
                                            <button type="button" class="btn btn-xs btn-info js-unlock-btn"
                                                data-id="{{ $g->id }}" data-name="{{ $g->name }}"
                                                title="Unlock">
                                                <i class="fa fa-unlock"></i>
                                            </button>
                                            @elseif($g->status === 'active')
                                            <button type="button" class="btn btn-xs btn-danger js-lock-btn"
                                                data-id="{{ $g->id }}" data-name="{{ $g->name }}"
                                                title="Lock">
                                                <i class="fa fa-lock"></i>
                                            </button>
                                            @endif

                                            {{-- Delete --}}
                                            @can('guest/delete')
                                            <a href="{{ route('backend_guest_delete', $g->id) }}"
                                                class="btn btn-xs btn-danger"
                                                onclick="return confirm('Delete this account?')"
                                                title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            @endcan
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4 text-muted">Không tìm thấy tài khoản nhà đầu tư nào.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $guests->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fa fa-times-circle mr-2"></i>Từ chối tài khoản</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Reject account: <strong id="rejectName"></strong>?</p>
                    <div class="form-group">
                        <label>Lý do (tuỳ chọn, sẽ được gửi cho nhà đầu tư)</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Nhập lý do từ chối..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-warning">Xác nhận từ chối</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    // Check all
    $('#checkAll').on('change', function () {
        $('.check-item').prop('checked', this.checked);
    });

    // Approve
    $(document).on('click', '.js-approve-btn', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        Swal.fire({
            title: 'Xác nhận duyệt',
            text: `Bạn có chắc muốn duyệt tài khoản "${name}"? Hệ thống sẽ tự động gửi email thông báo cho nhà đầu tư.`,
            icon: 'question',
            showHuỷButton: true,
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
                            toastr.success(res.message || 'Account approved.');
                            setTimeout(() => location.reload(), 1200);
                        }
                    },
                    error: function () {
                        toastr.error('An error occurred. Please try again.');
                    }
                });
            }
        });
    });

    // Reject – open modal
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
            text: `Bạn có chắc muốn khóa tài khoản "${name}"? Vui lòng nhập lý do (bắt buộc):`,
            input: 'textarea',
            inputPlaceholder: 'Nhập lý do khóa...',
            inputAttributes: {
                'aria-label': 'Nhập lý do khóa'
            },
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Đồng ý khóa',
            cancelButtonText: 'Hủy',
            preConfirm: (reason) => {
                if (!reason) {
                    Swal.showValidationMessage('Vui lòng nhập lý do khóa tài khoản');
                }
                return reason;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('/backend/guest/lock') }}/${id}`,
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}', reason: result.value },
                    dataType: 'json',
                    success: function (res) {
                        if (res.success) {
                            toastr.success(res.message || 'Account locked.');
                            setTimeout(() => location.reload(), 1200);
                        }
                    },
                    error: function (xhr) {
                        let msg = 'An error occurred. Please try again.';
                        if(xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        toastr.error(msg);
                    }
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
            showHuỷButton: true,
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
                            toastr.success(res.message || 'Account unlocked.');
                            setTimeout(() => location.reload(), 1200);
                        }
                    },
                    error: function () {
                        toastr.error('An error occurred. Please try again.');
                    }
                });
            }
        });
    });
});
</script>
@endsection