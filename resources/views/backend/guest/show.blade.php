@extends('backend.index')
@section('title', 'Hồ sơ nhà đầu tư')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-2 text-gray-800">Hồ sơ nhà đầu tư: {{ $guest->name }}</h1>
            <div>
                <a href="{{ route('backend_guest') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Quay lại</a>
                <a href="{{ route('backend_guest_edit', $guest->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Chỉnh sửa</a>
                
                @if($guest->status == 'pending')
                <button onclick="approveGuest({{ $guest->id }})" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Duyệt tài khoản</button>
                @endif
                
                @if($guest->status != 'locked')
                <button onclick="showLockModal({{ $guest->id }})" class="btn btn-danger btn-sm"><i class="fas fa-lock"></i> Khóa tài khoản</button>
                @else
                <button onclick="unlockGuest({{ $guest->id }})" class="btn btn-success btn-sm"><i class="fas fa-unlock"></i> Mở khóa</button>
                @endif
                
                <button onclick="resetPassword({{ $guest->id }})" class="btn btn-warning btn-sm text-dark"><i class="fas fa-key"></i> Đặt lại mật khẩu</button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin cơ bản -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đăng ký</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img class="img-profile rounded-circle" src="{{ $guest->avatar_url ?? asset('images/avatar-default.png') }}" style="width: 100px; height: 100px; object-fit: cover;">
                        <h5 class="mt-3 font-weight-bold">{{ $guest->name }}</h5>
                        <p class="text-muted">{{ $guest->email }}</p>
                        
                        @if($guest->status == 'active')
                            <span class="badge badge-success px-3 py-2">Hoạt động</span>
                        @elseif($guest->status == 'locked')
                            <span class="badge badge-danger px-3 py-2">Bị khóa</span>
                        @elseif($guest->status == 'pending')
                            <span class="badge badge-warning px-3 py-2">Chờ xác thực</span>
                        @else
                            <span class="badge badge-secondary px-3 py-2">{{ $guest->status }}</span>
                        @endif
                    </div>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0"><strong>Số điện thoại:</strong> <span class="float-right">{{ $guest->phone ?? '-' }}</span></li>
                        <li class="list-group-item px-0"><strong>Phân loại:</strong> 
                            <span class="float-right badge badge-info">
                                {{ $guest->investor_type == 'individual' ? 'Cá nhân' : ($guest->investor_type == 'organization' ? 'Tổ chức' : 'FDI') }}
                            </span>
                        </li>
                        <li class="list-group-item px-0"><strong>Tổ chức / Cty:</strong> <span class="float-right">{{ $guest->organization_name ?? '-' }}</span></li>
                        <li class="list-group-item px-0"><strong>Mã số thuế:</strong> <span class="float-right">{{ $guest->tax_code ?? '-' }}</span></li>
                        <li class="list-group-item px-0"><strong>Quốc gia:</strong> <span class="float-right">{{ $guest->nation->name ?? '-' }}</span></li>
                        <li class="list-group-item px-0"><strong>Ngày đăng ký:</strong> <span class="float-right">{{ $guest->created_at->format('d/m/Y H:i') }}</span></li>
                        @if($guest->status == 'locked')
                        <li class="list-group-item px-0 text-danger"><strong>Lý do khóa:</strong> <br>{{ $guest->locked_reason }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Hoạt động & Bookmark -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách Bookmark (Quan tâm)</h6>
                </div>
                <div class="card-body">
                    @if($bookmarks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Loại</th>
                                        <th>Tên dự án / Bài viết</th>
                                        <th>Tag / Ghi chú</th>
                                        <th>Thời gian lưu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookmarks as $b)
                                    <tr>
                                        <td>{{ str_contains($b->interestable_type, 'Project') ? 'Dự án' : 'Bài viết' }}</td>
                                        <td>
                                            @if($b->interestable)
                                                <strong>{{ $b->interestable->name ?? $b->interestable->title }}</strong>
                                            @else
                                                <span class="text-muted">Đã bị xóa</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($b->tag) <span class="badge badge-secondary">{{ $b->tag }}</span><br> @endif
                                            @if($b->notes) <small class="text-muted">{{ $b->notes }}</small> @endif
                                        </td>
                                        <td>{{ $b->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted my-5">Nhà đầu tư chưa lưu mục nào.</p>
                    @endif
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lịch sử hoạt động gần đây (Audit Logs)</h6>
                </div>
                <div class="card-body">
                    @if(isset($auditLogs) && $auditLogs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Thời gian</th>
                                        <th>Hành động</th>
                                        <th>Người thực hiện</th>
                                        <th>Chi tiết / Lý do</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($auditLogs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($log->action == 'lock')
                                                <span class="badge badge-danger">Khóa tài khoản</span>
                                            @elseif($log->action == 'unlock')
                                                <span class="badge badge-success">Mở khóa</span>
                                            @elseif($log->action == 'approve')
                                                <span class="badge badge-info">Phê duyệt</span>
                                            @elseif($log->action == 'reject')
                                                <span class="badge badge-secondary">Từ chối</span>
                                            @elseif($log->action == 'reset_password_requested')
                                                <span class="badge badge-warning">Yêu cầu Reset Pass</span>
                                            @else
                                                <span class="badge badge-light">{{ $log->action }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->user_id ? 'Admin ID: '.$log->user_id : 'Hệ thống' }}</td>
                                        <td>
                                            @if($log->changes && is_array($log->changes))
                                                @foreach($log->changes as $key => $val)
                                                    <small><strong>{{ $key }}:</strong> {{ is_array($val) ? json_encode($val) : $val }}</small><br>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted italic">Chưa có lịch sử hoạt động nào.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Khóa Tài Khoản -->
<div class="modal fade" id="lockModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formLockGuest" method="POST" action="">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Khóa tài khoản nhà đầu tư</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        Hệ thống sẽ gửi email thông báo cho nhà đầu tư kèm theo lý do khóa.
                    </div>
                    <div class="form-group">
                        <label for="lockReason" class="font-weight-bold">Lý do khóa tài khoản <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="lockReason" name="reason" rows="3" required placeholder="Nhập lý do vi phạm chi tiết..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xác nhận Khóa</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function showLockModal(id) {
        let url = '{{ route("backend_guest_lock", ":id") }}'.replace(':id', id);
        $('#formLockGuest').attr('action', url);
        $('#lockModal').modal('show');
    }

    function unlockGuest(id) {
        if(confirm('Bạn có chắc chắn muốn mở khóa tài khoản này?')) {
            let url = '{{ route("backend_guest_unlock", ":id") }}'.replace(':id', id);
            $.post(url, { _token: '{{ csrf_token() }}' }, function(res) {
                if(res.success) location.reload();
            });
        }
    }
    
    function approveGuest(id) {
        if(confirm('Phê duyệt tài khoản này? Hệ thống sẽ gửi email thông báo.')) {
            let url = '{{ route("backend_guest_approve", ":id") }}'.replace(':id', id);
            $.post(url, { _token: '{{ csrf_token() }}' }, function(res) {
                if(res.success) location.reload();
            });
        }
    }

    function resetPassword(id) {
        if(confirm('Gửi email cấp lại mật khẩu (có hiệu lực 24h) cho tài khoản này?')) {
            let url = '{{ route("backend_guest_reset_password", ":id") }}'.replace(':id', id);
            $.post(url, { _token: '{{ csrf_token() }}' }, function(res) {
                if(res.success) {
                    alert(res.message);
                } else {
                    alert('Lỗi: ' + res.message);
                }
            }).fail(function() {
                alert('Có lỗi xảy ra trên máy chủ.');
            });
        }
    }
</script>
@endpush
