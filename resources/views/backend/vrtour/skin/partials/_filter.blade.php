<div class="row">
    <div class="col-xl-6 text-center">
        <form action="" method="GET" class="form-filter-top-index">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <x-forms.select2
                            name="slt_vrtour"
                            id="slt_vrtour"
                            :options="$vrtour"
                            placeholder="-- Chọn dự án --"
                            :selected="old('slt_vrtour')"
                        />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <select class="form-control" id="slt_vrtour_type" style="height: 38px">
                            <option value="">-- Chọn loại skin --</option>
                            <option value="0">Toàn bộ</option>
                            <option value="1">Màn hình chào mừng</option>
                            <option value="3">Sơ đồ liên kết vùng</option>
                            <option value="5">Kế hoạch triển khai</option>
                            <option value="6">Thông tin chủ đầu tư</option>
                            <option value="7">Vị trí dự án</option>
                            <option value="8">Mô hình 3d</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 text-left">
                    <div class="form-group">
                        <button type="button" class="btn btn-sm fw-bold btn-primary btn-info" id="update_all" style="height: 38px">
                            <i class="fa fa-save" aria-hidden="true"></i> Cập nhật
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>