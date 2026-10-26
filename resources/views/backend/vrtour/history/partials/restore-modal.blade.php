<div class="modal fade" id="restore-modal" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Khôi phục phiên bản <span id="restore-title"></span></h5>
            </div>

            <div class="modal-body" id="restore-step1">
                <p class="text-muted mb-2">Bước 1/2: Nhập lý do khôi phục.</p>
                <textarea id="restore-reason" class="form-control" rows="4" maxlength="1000"
                    placeholder="Ví dụ: Nội dung bản mới sai, cần quay lại bản đã duyệt..."></textarea>
                <div class="text-danger small mt-1 d-none" id="restore-error1"></div>
            </div>

            <div class="modal-body d-none" id="restore-step2">
                <p class="text-muted mb-2">Bước 2/2: Xác nhận.</p>
                <div class="alert alert-warning">
                    Hệ thống sẽ tạo phiên bản mới dựa trên <strong id="restore-src"></strong>.
                    Lịch sử cũ được giữ nguyên.
                </div>
                <div class="alert alert-danger d-none" id="restore-prune-warning"></div>
                <div class="alert alert-info d-none" id="restore-pending-warning"></div>
                <ul class="mb-2">
                    <li>Bản ghi được thêm lại: <strong id="sum-add">0</strong></li>
                    <li>Bản ghi bị xóa (không có ở bản này): <strong id="sum-del">0</strong></li>
                    <li>Bản ghi bị đưa về nội dung cũ: <strong id="sum-mod">0</strong></li>
                </ul>
                <div class="small text-muted">Lý do: <em id="restore-reason-view"></em></div>
                <div class="text-danger small mt-2 d-none" id="restore-error2"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="restore-next">Tiếp tục</button>
                <button type="button" class="btn btn-danger d-none" id="restore-confirm">Xác nhận khôi phục</button>
            </div>
        </div>
    </div>
</div>
