<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center">
            @if(auth()->user()->is_super_admin || auth()->user()->is_approve)
                <input type="checkbox" class="skin-checkbox mr-2" id="{{ $key }}Checkbox"
                    value="{{ $type }}" disabled>
            @endif
            <h3 class="mb-0">{{ $title }}</h3>
        </div>
    </div>
    <div class="col-12 mt-2" id="{{ $key }}ApprovalBox" style="display:none;">
        <div class="alert alert-warning mb-3">
            <h5 class="mb-2"><i class="fas fa-clock"></i> Có nội dung đang chờ duyệt</h5>
            <div><strong>Người sửa:</strong> <span id="{{ $key }}ApprovalUser"></span></div>
            <div><strong>Thời gian:</strong> <span id="{{ $key }}ApprovalCreatedAt"></span></div>
            <div><strong>Cấp duyệt:</strong> <span id="{{ $key }}ApprovalLevel"></span></div>
        </div>
    </div>
</div>
<hr>