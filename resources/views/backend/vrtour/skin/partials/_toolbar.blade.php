@if(auth()->user()->is_super_admin || auth()->user()->is_approve)
    <div class="col-12 mb-2" id="approvalToolbar" style="display:none;">
        <div class="card">
            <div class="p-3 d-flex flex-column flex-lg-row align-items-center justify-content-between">
                <label class="mb-0">
                    <input type="checkbox" id="checkAllSkin"> Chọn tất cả
                </label>
                <div class="d-flex align-items-center">
                    <button class="btn btn-warning mr-2" id="btnViewDiff">
                        <i class="fa fa-exchange-alt"></i> Xem thay đổi
                    </button>
                    <button class="btn btn-success mr-2" id="btnApproveAll">
                        <i class="fa fa-check"></i> Chấp nhận
                    </button>
                    <button class="btn btn-danger" id="btnRejectAll">
                        <i class="fa fa-times"></i> Từ chối
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif