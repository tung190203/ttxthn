@extends('backend.index')

@section('title')
    Quản lý Skin
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Skin</li>
@endsection

@section('content')
    <script src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('backend_assets/js/globals.js') }}"></script>
    <script>CKFinder.config({connectorPath: '/ckfinder/connector'});</script>

    <hr class="mt-0">
    <section class="content">
        <div class="container-fluid">
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
                                        <option value="0">Toàn bộ</option>
                                        <option value="1">Màn hình chào mừng</option>
                                        <option value="3">Sơ đồ liên kết vùng</option>
                                        <option value="4">Văn bản pháp quy</option>
                                        <option value="5">Kế hoạch triển khai</option>
                                        <option value="6">Thông tin chủ đầu tư</option>
                                        <option value="7">Vị trí dự án</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 text-left">
                                <div class="form-group">
                                    <button type="button" class="btn btn-sm fw-bold btn-primary btn-info" id="update_all" role="button" style="height: 38px">
                                        <i class="fa fa-save" aria-hidden="true"></i> Cập nhật
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                @if(auth()->user()->is_super_admin || auth()->user()->is_approve)
                    <div class="col-12 mb-2" id="approvalToolbar" style="display:none;">
                        <div class="card">
                            <div class="p-3 d-flex flex-column flex-lg-row align-items-center justify-content-between">

                                <div>
                                    <label class="mb-0">
                                        <input type="checkbox" id="checkAllSkin">
                                        Chọn tất cả
                                    </label>
                                </div>

                                <div class="d-flex align-items-center">

                                    <button class="btn btn-warning mr-2" id="btnViewDiff">
                                        <i class="fa fa-exchange-alt"></i>
                                        Xem thay đổi
                                    </button>

                                    <button class="btn btn-success mr-2" id="btnApproveAll">
                                        <i class="fa fa-check"></i>
                                        Chấp nhận
                                    </button>

                                    <button class="btn btn-danger" id="btnRejectAll">
                                        <i class="fa fa-times"></i>
                                        Từ chối
                                    </button>

                                </div>

                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-12 skin_div" id="skin_screen" data-id="1">
                    <div class="card">
                        <form action="" method="GET" class="form-horizontal" id="skin_screenForm">
                            <div class="card-body table-responsive p-4">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                             @if(auth()->user()->is_super_admin || auth()->user()->is_approve)
                                            <input type="checkbox" class="skin-checkbox mr-2" id="welcomeCheckbox"
                                                value="{{ \App\Models\SkinApproval::TYPE_WELCOME }}" disabled>
                                            @endif
                                            <h3 class="mb-0">
                                                Màn hình chào mừng
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2" id="welcomeApprovalBox" style="display:none;">
                                        <div class="alert alert-warning mb-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-2">
                                                        <i class="fas fa-clock"></i>
                                                        Có nội dung đang chờ duyệt
                                                    </h5>

                                                    <div>
                                                        <strong>Người sửa:</strong>
                                                        <span id="welcomeApprovalUser"></span>
                                                    </div>

                                                    <div>
                                                        <strong>Thời gian:</strong>
                                                        <span id="welcomeApprovalCreatedAt"></span>
                                                    </div>

                                                    <div>
                                                        <strong>Cấp duyệt:</strong>
                                                        <span id="welcomeApprovalLevel"></span>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <x-forms.input name="title" value="" label="Tên dự án" />
                                <x-forms.textarea name="description" editor="true" value="" label="Mô tả dự án"/>
                                <x-forms.upload name="voice" value="" label="Voice" type="text" :messages="$errors->get('ct_audio')"/>
                                <x-forms.switch name="investor" id="investor" label="Hiển thị chủ đầu tư" value="" :messages="$errors->get('status')" />
                                <x-forms.upload name="investor_image" value="" label="Logo chủ đầu tư" type="image"/>
                                <x-forms.input name="investor_des1" value="" label="Mô tả 1"/>
                                <x-forms.input name="investor_des2" value="" label="Mô tả 2"/>
                                <x-forms.input name="investor_des3" value="" label="Mô tả 3"/>
                                <input hidden id="wlscreen_id">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-12 skin_div" id="skin_connectmap" data-id="3">
                    <div class="card">
                        <form action="" method="GET" class="form-horizontal">
                            <div class="card-body table-responsive p-4">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                            @if(auth()->user()->is_super_admin || auth()->user()->is_approve)
                                            <input type="checkbox" class="skin-checkbox mr-2" id="connectMapCheckbox"
                                                value="{{ \App\Models\SkinApproval::TYPE_CONNECT_MAP }}" disabled>
                                            @endif
                                            <h3 class="mb-0">
                                                Sơ đồ liên kết vùng
                                            </h3>

                                        </div>
                                    </div>
                                    <div class="col-12 mt-2" id="connectmapApprovalBox" style="display:none;">
                                        <div class="alert alert-warning mb-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-2">
                                                        <i class="fas fa-clock"></i>
                                                        Có nội dung đang chờ duyệt
                                                    </h5>

                                                    <div>
                                                        <strong>Người sửa:</strong>
                                                        <span id="connectMapApprovalUser"></span>
                                                    </div>

                                                    <div>
                                                        <strong>Thời gian:</strong>
                                                        <span id="connectMapApprovalCreatedAt"></span>
                                                    </div>

                                                    <div>
                                                        <strong>Cấp duyệt:</strong>
                                                        <span id="connectMapApprovalLevel"></span>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <x-forms.upload name="image" value="" label="Ảnh VN" type="image"/>
                                <x-forms.upload name="image_en" value="" label="Ảnh EN" type="image"/>
                                <x-forms.textarea name="content" editor="true" value="" label="Nội dung chi tiết VN"/>
                                <x-forms.textarea name="content_en" editor="true" value="" label="Nội dung chi tiết EN"/>
                                <input hidden id="connectmap_id">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-12 skin_div" id="skin_document" data-id="4">
                    <div class="card">
                        <form action="" method="GET" class="form-horizontal">
                            <div class="card-body table-responsive p-4">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            @if(auth()->user()->is_super_admin || auth()->user()->is_approve)
                                            <input type="checkbox" class="skin-checkbox mr-2" id="documentCheckbox"
                                                value="{{ \App\Models\SkinApproval::TYPE_DOCUMENT }}" disabled>
                                            @endif
                                            <h3 class="mb-0">
                                                Văn bản pháp quy
                                            </h3>

                                        </div>
                                    </div>
                                    <div class="col-6 text-right">
                                        <button type="button" class="btn btn-sm fw-bold btn-primary btn-info"
                                            id="add_detail" role="button">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Thêm văn bản
                                        </button>
                                    </div>
                                    <div class="col-12 mt-2" id="documentApprovalBox" style="display:none;">
                                        <div class="alert alert-warning mb-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-2">
                                                        <i class="fas fa-clock"></i>
                                                        Có nội dung đang chờ duyệt
                                                    </h5>

                                                    <div>
                                                        <strong>Người sửa:</strong>
                                                        <span id="documentApprovalUser"></span>
                                                    </div>

                                                    <div>
                                                        <strong>Thời gian:</strong>
                                                        <span id="documentApprovalCreatedAt"></span>
                                                    </div>

                                                    <div>
                                                        <strong>Cấp duyệt:</strong>
                                                        <span id="documentApprovalLevel"></span>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-12" id="multiple_document">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-12 skin_div" id="skin_plan" data-id="5">
                    <div class="card">
                        <form action="" method="GET" class="form-horizontal">
                            <div class="card-body table-responsive p-4">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            @if(auth()->user()->is_super_admin || auth()->user()->is_approve)
                                            <input type="checkbox" class="skin-checkbox mr-2" id="planCheckbox"
                                                value="{{ \App\Models\SkinApproval::TYPE_PLAN }}" disabled>
                                            @endif
                                            <h3 class="mb-0">
                                                Kế hoạch triển khai
                                            </h3>

                                        </div>
                                    </div>
                                    <div class="col-12 mt-2" id="planApprovalBox" style="display:none;">
                                        <div class="alert alert-warning mb-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-2">
                                                        <i class="fas fa-clock"></i>
                                                        Có nội dung đang chờ duyệt
                                                    </h5>

                                                    <div>
                                                        <strong>Người sửa:</strong>
                                                        <span id="planApprovalUser"></span>
                                                    </div>

                                                    <div>
                                                        <strong>Thời gian:</strong>
                                                        <span id="planApprovalCreatedAt"></span>
                                                    </div>

                                                    <div>
                                                        <strong>Cấp duyệt:</strong>
                                                        <span id="planApprovalLevel"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <x-forms.upload name="image1" value="" label="Ảnh 1" type="image"/>
                                <x-forms.input name="title1" value="" label="Giai đoạn 1" />
                                <x-forms.input name="title1_en" value="" label="Giai đoạn 1 EN" />
                                <x-forms.textarea name="pcontent1" editor="true" value="" label="Nội dung chi tiết 1 VN"/>
                                <x-forms.textarea name="pcontent1_en" editor="true" value="" label="Nội dung chi tiết 1 EN"/>

                                <x-forms.upload name="image2" value="" label="Ảnh 2" type="image"/>
                                <x-forms.input name="title2" value="" label="Giai đoạn 2" />
                                <x-forms.input name="title2_en" value="" label="Giai đoạn 2 EN" />
                                <x-forms.textarea name="pcontent2" editor="true" value="" label="Nội dung chi tiết 2 VN"/>
                                <x-forms.textarea name="pcontent2_en" editor="true" value="" label="Nội dung chi tiết 2 EN"/>

                                <x-forms.upload name="image3" value="" label="Ảnh 3" type="image"/>
                                <x-forms.input name="title3" value="" label="Giai đoạn 3" />
                                <x-forms.input name="title3_en" value="" label="Giai đoạn 3 EN" />
                                <x-forms.textarea name="pcontent3" editor="true" value="" label="Nội dung chi tiết 3 VN"/>
                                <x-forms.textarea name="pcontent3_en" editor="true" value="" label="Nội dung chi tiết 3 EN"/>

                                <x-forms.switch name="status" id="status" label="Hiển thị" value="" :messages="$errors->get('status')" />
                                <input hidden id="plan_id">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-12 skin_div" id="skin_investor" data-id="6">
                    <div class="card">
                        <form action="" method="GET" class="form-horizontal">
                            <div class="card-body table-responsive p-4">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            @if(auth()->user()->is_super_admin || auth()->user()->is_approve)
                                            <input type="checkbox" class="skin-checkbox mr-2" id="investorCheckbox"
                                                value="{{ \App\Models\SkinApproval::TYPE_INVESTOR }}" disabled>
                                            @endif
                                            <h3 class="mb-0">
                                                Thông tin chủ đầu tư
                                            </h3>

                                        </div>
                                    </div>
                                    <div class="col-12 mt-2" id="investorApprovalBox" style="display:none;">
                                        <div class="alert alert-warning mb-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-2">
                                                        <i class="fas fa-clock"></i>
                                                        Có nội dung đang chờ duyệt
                                                    </h5>

                                                    <div>
                                                        <strong>Người sửa:</strong>
                                                        <span id="investorApprovalUser"></span>
                                                    </div>

                                                    <div>
                                                        <strong>Thời gian:</strong>
                                                        <span id="investorApprovalCreatedAt"></span>
                                                    </div>

                                                    <div>
                                                        <strong>Cấp duyệt:</strong>
                                                        <span id="investorApprovalLevel"></span>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <x-forms.input name="name" value="" label="Tên" />
                                <x-forms.input name="name_en" value="" label="Tên EN" />
                                <x-forms.upload name="ivt_image" value="" label="Ảnh" type="image"/>
                                <x-forms.textarea name="content1" editor="true" value="" label="Nội dung 1 VN"/>
                                <x-forms.textarea name="content1_en" editor="true" value="" label="Nội dung 1 EN"/>
                                <x-forms.textarea name="content2" editor="true" value="" label="Nội dung 2 VN"/>
                                <x-forms.textarea name="content2_en" editor="true" value="" label="Nội dung 2 EN"/>
                                <x-forms.textarea name="content3" editor="true" value="" label="Nội dung 3 VN"/>
                                <x-forms.textarea name="content3_en" editor="true" value="" label="Nội dung 3 EN"/>
                                <x-forms.input name="website" value="" label="Website" />
                                <x-forms.input name="sologan" value="" label="Tên khác" />
                                <x-forms.input name="sologan_en" value="" label="Tên khác EN" />
                                <x-forms.switch name="investor_status" id="investor_status" label="Hiển thị" value="" :messages="$errors->get('status')" />
                                <input hidden id="investor_id">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-12 skin_div" id="skin_locationtour" data-id="7">
                    <div class="card">
                        <form action="" method="GET" class="form-horizontal">
                            <div class="card-body table-responsive p-4">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            @if(auth()->user()->is_super_admin || auth()->user()->is_approve)
                                            <input type="checkbox" class="skin-checkbox mr-2" id="locationCheckbox"
                                                value="{{ \App\Models\SkinApproval::TYPE_LOCATION }}" disabled>
                                            @endif
                                            <h3 class="mb-0">
                                                Vị trí dự án
                                            </h3>

                                        </div>
                                    </div>
                                    <div class="col-12 mt-2" id="locationApprovalBox" style="display:none;">
                                        <div class="alert alert-warning mb-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-2">
                                                        <i class="fas fa-clock"></i>
                                                        Có nội dung đang chờ duyệt
                                                    </h5>

                                                    <div>
                                                        <strong>Người sửa:</strong>
                                                        <span id="locationApprovalUser"></span>
                                                    </div>

                                                    <div>
                                                        <strong>Thời gian:</strong>
                                                        <span id="locationApprovalCreatedAt"></span>
                                                    </div>

                                                    <div>
                                                        <strong>Cấp duyệt:</strong>
                                                        <span id="locationApprovalLevel"></span>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <x-forms.textarea name="location_in_tour" id="location_in_tour" value="" label="Vị trí map"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @include('backend.vrtour.skin.partials._diff_viewer')
    </section>
    <div id="document_detail" style="display:none">
        @include('backend.vrtour.skin.row')
    </div>
@endsection

@section('script')
    <script>
        $('.skin_div').hide();
        $('#update_all').hide();
        $('#skin_investor #investor_status').bootstrapSwitch();
        $('#skin_screen #investor').bootstrapSwitch();
        $('#skin_plan #status').bootstrapSwitch();
        $("#approvalToolbar").hide();
        var count_detail = 0;
        let currentSkinType = 0; // 0 = Tất cả

        function replaceHtml(count_detail){
            var html    = $('#document_detail').html();
            html        = html.replaceAll('document_name-', 'document_name-'+count_detail).replaceAll('document_name_en-', 'document_name_en-'+count_detail).replaceAll('download-', 'download-'+count_detail).replaceAll('download_img-', 'download_img-'+count_detail).replaceAll('document_id-', 'document_id-'+count_detail);
            $('#multiple_document').append('<hr>' + html);
            return html;
        }

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        function resetApprovalCheckbox() {
            $("#checkAllSkin").prop("checked", true);
            $(".skin-checkbox").prop("checked", false).prop("disabled", true);
        }
        function disableApprovalToolbar() {
            $("#approvalToolbar").show();
            $("#checkAllSkin").prop("checked", false).prop("disabled", true);
            $("#btnApproveAll").prop("disabled", true);
            $("#btnRejectAll").prop("disabled", true);
            $("#btnViewDiff").prop("disabled", true);
        }

        function enableApprovalToolbar() {
            $("#approvalToolbar").show();
            $("#checkAllSkin").prop("checked", true).prop("disabled", false);
            $("#btnApproveAll").prop("disabled", false);
            $("#btnRejectAll").prop("disabled", false);
            $("#btnViewDiff").prop("disabled", false);
        }

        function renderApprovalBox(options) {
            const {
                data,
                checkbox,
                box,
                user,
                createdAt,
                level
            } = options;
            if (data && data.pending_skin_approval) {
                let approval = data.pending_skin_approval;
                $(checkbox).prop("disabled", false).prop("checked", true);
                $(box).show();
                $(user).text(approval.user ? approval.user.name : "");
                $(createdAt).text(
                    approval.created_at ? new Date(approval.created_at).toLocaleDateString("vi-VN") : ""
                );
                $(level).text(
                    approval.approval_level == 0 ? "Đang chờ duyệt cấp 1" : "Đang chờ duyệt cấp 2"
                );
                return true;
            } 
            $(checkbox).prop("disabled", true).prop("checked", false);
            $(box).hide();

            return false;
        }

        function fetch_data() {
            var vrtour  = $('#slt_vrtour').val();
            var type    = $('#slt_vrtour_type').val();
            currentSkinType = type;
            if (vrtour != 'all') {
                $.ajax({
                    url: assetUrl+'vrtour/skin/get-data/'+vrtour+'/'+type,
                    type: "GET",
                    success: function (response) {
                        let hasPending = false;
                        resetApprovalCheckbox();
                        $('#update_all').show();
                        //connectmap
                        var connect_map = response.data.connect_map;
                        hasPending = renderApprovalBox({
                            data: connect_map,
                            checkbox: "#connectMapCheckbox",
                            box: "#connectmapApprovalBox",
                            user: "#connectMapApprovalUser",
                            createdAt: "#connectMapApprovalCreatedAt",
                            level: "#connectMapApprovalLevel"
                        }) || hasPending;
                        $('#skin_connectmap #connectmap_id').val(connect_map != null ? connect_map['id'] : '');
                        $('#skin_connectmap #image_input').val(connect_map != null ? connect_map['image'] : '');
                        $('#skin_connectmap #image_en_input').val(connect_map != null ? connect_map['image_en'] : '');
                        CKEDITOR.instances['content'].setData(connect_map != null ? connect_map['content'] : '');
                        CKEDITOR.instances['content_en'].setData(connect_map != null ? connect_map['content_en'] : '');

                        //location in tour
                        var location = response.data.location;
                        hasPending = renderApprovalBox({
                            data: location,
                            checkbox: "#locationCheckbox",
                            box: "#locationApprovalBox",
                            user: "#locationApprovalUser",
                            createdAt: "#locationApprovalCreatedAt",
                            level: "#locationApprovalLevel"
                        }) || hasPending;
                        $('#skin_locationtour #location_in_tour').val(location['location_in_tour'] || '');

                        //investor
                        var investor = response.data.investor;
                        hasPending = renderApprovalBox({
                            data: investor,
                            checkbox: "#investorCheckbox",
                            box: "#investorApprovalBox",
                            user: "#investorApprovalUser",
                            createdAt: "#investorApprovalCreatedAt",
                            level: "#investorApprovalLevel"
                        }) || hasPending;
                        $('#skin_investor #investor_id').val(investor != null ? investor['id'] : '');
                        $('#skin_investor #name').val(investor != null ? investor['name'] : '');
                        $('#skin_investor #name_en').val(investor != null ? investor['name_en'] : '');
                        $('#skin_investor #ivt_image_input').val(investor != null ? investor['image'] : '');
                        CKEDITOR.instances['content1'].setData(investor != null ? investor['content1'] : '');
                        CKEDITOR.instances['content1_en'].setData(investor != null ? investor['content1_en'] : '');
                        CKEDITOR.instances['content2'].setData(investor != null ? investor['content2'] : '');
                        CKEDITOR.instances['content2_en'].setData(investor != null ? investor['content2_en'] : '');
                        CKEDITOR.instances['content3'].setData(investor != null ? investor['content3'] : '');
                        CKEDITOR.instances['content3_en'].setData(investor != null ? investor['content3_en'] : '');
                        $('#skin_investor #website').val(investor != null ? investor['website'] : '');
                        $('#skin_investor #sologan').val(investor != null ? investor['sologan'] : '');
                        $('#skin_investor #sologan_en').val(investor != null ? investor['sologan_en'] : '');
                        $('#skin_investor #investor_status').bootstrapSwitch('state', investor != null ? (investor['status'] == 1 ? true : false) : false);

                        //welcome
                        var screen = response.data.screen;
                        hasPending = renderApprovalBox({
                            data: screen,
                            checkbox: "#welcomeCheckbox",
                            box: "#welcomeApprovalBox",
                            user: "#welcomeApprovalUser",
                            createdAt: "#welcomeApprovalCreatedAt",
                            level: "#welcomeApprovalLevel"
                        }) || hasPending;

                        $('#skin_screen #wlscreen_id').val(screen != null ? screen['id'] : '');
                        $('#skin_screen #title').val(screen != null ? screen['title'] : '');
                        CKEDITOR.instances['description'].setData(screen != null ? screen['description'] : '');
                        $('#skin_screen #voice_input').val(screen != null ? screen['voice'] : '');
                        $('#skin_screen #investor').bootstrapSwitch('state', screen != null ? (screen['show_investor'] == 1 ? true : false) : false);
                        $('#skin_screen #investor_image_input').val(screen != null ? screen['investor_img'] : '');
                        $('#skin_screen #investor_des1').val(screen != null ? screen['investor_desc1'] : '');
                        $('#skin_screen #investor_des2').val(screen != null ? screen['investor_desc2'] : '');
                        $('#skin_screen #investor_des3').val(screen != null ? screen['investor_desc3'] : '');

                        //plan
                        var plan = response.data.plan;
                        hasPending = renderApprovalBox({
                            data: plan,
                            checkbox: "#planCheckbox",
                            box: "#planApprovalBox",
                            user: "#planApprovalUser",
                            createdAt: "#planApprovalCreatedAt",
                            level: "#planApprovalLevel"
                        }) || hasPending;
                        $('#skin_plan #plan_id').val(plan != null ? plan['id'] : '');
                        $('#skin_plan #status').bootstrapSwitch('state', plan != null ? (plan['show'] == 1 ?
                            true : false) : false);

                        $('#skin_plan #image1_input').val(plan != null ? plan['image1'] : '');
                        $('#skin_plan #title1').val(plan != null ? plan['title1'] : '');
                        $('#skin_plan #title1_en').val(plan != null ? plan['title1_en'] : '');
                        CKEDITOR.instances['pcontent1'].setData(plan != null ? plan['content1'] : '');
                        CKEDITOR.instances['pcontent1_en'].setData(plan != null ? plan['content1_en'] : '');

                        $('#skin_plan #image2_input').val(plan != null ? plan['image2'] : '');
                        $('#skin_plan #title2').val(plan != null ? plan['title2'] : '');
                        $('#skin_plan #title2_en').val(plan != null ? plan['title2_en'] : '');
                        CKEDITOR.instances['pcontent2'].setData(plan != null ? plan['content2'] : '');
                        CKEDITOR.instances['pcontent2_en'].setData(plan != null ? plan['content2_en'] : '');

                        $('#skin_plan #image3_input').val(plan != null ? plan['image3'] : '');
                        $('#skin_plan #title3').val(plan != null ? plan['title3'] : '');
                        $('#skin_plan #title3_en').val(plan != null ? plan['title3_en'] : '');
                        CKEDITOR.instances['pcontent3'].setData(plan != null ? plan['content3'] : '');
                        CKEDITOR.instances['pcontent3_en'].setData(plan != null ? plan['content3_en'] : '');

                        //document
                        var document    = response.data.document;
                        hasPending = renderApprovalBox({
                            data: document,
                            checkbox: "#documentCheckbox",
                            box: "#documentApprovalBox",
                            user: "#documentApprovalUser",
                            createdAt: "#documentApprovalCreatedAt",
                            level: "#documentApprovalLevel"
                        }) || hasPending;
                        count_detail    = 0;
                        var html        = $('#document_detail').html();
                        $('#multiple_document').html('');
                        if (document != null) {
                            $.each(document.items, function(_key, _value){
                                count_detail += 1;
                                replaceHtml(count_detail);
                                $('#multiple_document .row_detail #document_id-'+(_key+1)).val(_value['id']);
                                $('#multiple_document .row_detail #document_name-'+(_key+1)).val(_value['name']);
                                $('#multiple_document .row_detail #document_name_en-'+(_key+1)).val(_value['name_en']);
                                $('#multiple_document .row_detail #download-'+(_key+1)+'_input').val(_value['download']);
                            });
                        } else {
                            $('#multiple_document').html('');
                        }

                        if (hasPending) {
                            enableApprovalToolbar();
                        } else {
                            disableApprovalToolbar();
                        }
                        toastr["success"](response.message, 'Success');
                        if (type != '0') {
                            $('.skin_div').each(function(key, value){
                                if ($(value).data('id') == type) {
                                    $('.skin_div').hide();
                                    $(value).show();
                                }
                            });
                        } else {
                            $('.skin_div').show();
                        }
                    },
                    error: function(response) {
                        $('#update_all').hide();
                        toastr["error"](response.message,'Error')
                    }
                });
            } else {
                $('#update_all').hide();
                $('.skin_div').hide();
                $("#approvalToolbar").hide();
                toastr["error"]('Vui lòng chọn dự án', 'Error')
            }
        }

        $(document).on('change', '#slt_vrtour_type', function(){
            fetch_data();
        });

        $(document).on('change', '#slt_vrtour', function(){
            fetch_data();
        });

        $(document).on('click', '#update_all', function(){
            var vrtour      = $('#slt_vrtour').val();
            var type        = $('#slt_vrtour_type').val();
            const result    = [];
            $.each(document.querySelectorAll('#multiple_document .row_detail'), function(key, block) {
                const document_id   = $('#document_id-'+(key+1)).val();
                const name          = $('#document_name-'+(key+1)).val();
                const nameEn        = $('#document_name_en-'+(key+1)).val();
                const download      = $('#download-'+(key+1)+'_input').val();

                result.push({
                    id                  : document_id,
                    document_name       : name,
                    document_name_en    : nameEn,
                    download            : download,
                });
            });

            var data    = {
                connect_data: {
                    id          :  $('#skin_connectmap #connectmap_id').val(),  
                    image       :  $('#skin_connectmap #image_input').val(),
                    image_en    :  $('#skin_connectmap #image_en_input').val(),
                    content     :  CKEDITOR.instances['content'].getData(),
                    content_en  :  CKEDITOR.instances['content_en'].getData()
                },
                location : {
                    map         :  $('#skin_locationtour #location_in_tour').val()
                },
                investor : {
                    id          :  $('#skin_investor #investor_id').val(),  
                    name        :  $('#skin_investor #name').val(),
                    name_en     :  $('#skin_investor #name_en').val(),
                    image       :  $('#skin_investor #ivt_image_input').val(),
                    content1    :  CKEDITOR.instances['content1'].getData(),
                    content1_en :  CKEDITOR.instances['content1_en'].getData(),
                    content2    :  CKEDITOR.instances['content2'].getData(),
                    content2_en :  CKEDITOR.instances['content2_en'].getData(),
                    content3    :  CKEDITOR.instances['content3'].getData(),
                    content3_en :  CKEDITOR.instances['content3_en'].getData(),
                    website     :  $('#skin_investor #website').val(),
                    sologan     :  $('#skin_investor #sologan').val(),
                    sologan_en  :  $('#skin_investor #sologan_en').val(),
                    status      :  $('#skin_investor #investor_status').bootstrapSwitch('state')
                },
                screen : {
                    id                  :  $('#skin_screen #wlscreen_id').val(),  
                    title               :  $('#skin_screen #title').val(),
                    voice               :  $('#skin_screen #voice_input').val(),
                    description         :  CKEDITOR.instances['description'].getData(),
                    investor_img        :  $('#skin_screen #investor_image_input').val(),
                    investor_desc1      :  $('#skin_screen #investor_des1').val(),
                    investor_desc2      :  $('#skin_screen #investor_des2').val(),
                    investor_desc3      :  $('#skin_screen #investor_des3').val(),
                    status              :  $('#skin_screen #investor').bootstrapSwitch('state')
                },
                plan : {
                    id                  :  $('#skin_plan #plan_id').val(),  
                    show                :  $('#skin_plan #status').bootstrapSwitch('state'),
                    image1              :  $('#skin_plan #image1_input').val(),
                    title1              :  $('#skin_plan #title1').val(),
                    title1_en           :  $('#skin_plan #title1_en').val(),
                    content1            :  CKEDITOR.instances['pcontent1'].getData(),
                    content1_en         :  CKEDITOR.instances['pcontent1_en'].getData(),
                    image2              :  $('#skin_plan #image2_input').val(),
                    title2              :  $('#skin_plan #title2').val(),
                    title2_en           :  $('#skin_plan #title2_en').val(),
                    content2            :  CKEDITOR.instances['pcontent2'].getData(),
                    content2_en         :  CKEDITOR.instances['pcontent2_en'].getData(),
                    image3              :  $('#skin_plan #image3_input').val(),
                    title3              :  $('#skin_plan #title3').val(),
                    title3_en           :  $('#skin_plan #title3_en').val(),
                    content3            :  CKEDITOR.instances['pcontent3'].getData(),
                    content3_en         :  CKEDITOR.instances['pcontent3_en'].getData(),
                },
                document : result
            };

            $.ajax({
                url: assetUrl + 'vrtour/skin/update-data/' + vrtour+'?type='+type,
                type: "POST",
                contentType: "application/json",
                dataType: "json",
                data: JSON.stringify(data),
                success: function (response) {
                    toastr["success"](response.message, 'Success');
                    fetch_data();
                },
                error: function (xhr, status, error) {
                    toastr["error"]('Có lỗi xảy ra! Vui lòng thử lại', 'Error')
                }
            });
        });


        $(document).on('click', '#add_detail', function(){
            count_detail += 1;
            replaceHtml(count_detail);
        });

        $(document).on('click', '.delete_detail', function(){
            $(this).parent().parent().prev().remove();
            $(this).parent().parent().remove();
            count_detail -= 1;
        });

        $(document).on('click','.remove-btn', function(){
            removeImage(this);
        });
        $(document).on("change", "#checkAllSkin", function () {
            let checked = $(this).is(":checked");
            $(".skin-checkbox:not(:disabled)").prop("checked", checked);
        });
        $(document).on("click", "#btnRejectAll", function () {
            let vrtour = $("#slt_vrtour").val();
            let types = [];
            $(".skin-checkbox:checked").each(function () {
                types.push(parseInt($(this).val()));
            });
            if (types.length == 0) {
                toastr["warning"]("Vui lòng chọn ít nhất một mục.");
                return;
            }
            if (!confirm("Bạn có chắc muốn từ chối các nội dung đã chọn?")) {
                return;
            }
            $.ajax({
                url: assetUrl + "vrtour/skin/reject",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    vrtour_id: vrtour,
                    types: types
                }),
                success: function (res) {
                    toastr["success"](res.message);
                    fetch_data();
                },
                error: function (xhr) {
                    toastr["error"](
                        xhr.responseJSON?.message ??
                        "Có lỗi xảy ra."
                    );
                }
            });
        });
        $(document).on("click", "#btnApproveAll", function () {
            let vrtour = $("#slt_vrtour").val();
            let types = [];
            $(".skin-checkbox:checked").each(function () {
                types.push(parseInt($(this).val()));
            });
            if (types.length === 0) {
                toastr["warning"]("Vui lòng chọn ít nhất một mục.");
                return;
            }
            if (!confirm("Bạn có chắc muốn chấp nhận các nội dung đã chọn?")) {
                return;
            }

            $.ajax({
                url: assetUrl + "vrtour/skin/approve",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    vrtour_id: vrtour,
                    types: types
                }),
                success: function (res) {
                    toastr["success"](res.message);
                    fetch_data();
                },
                error: function (xhr) {
                    toastr["error"](
                        xhr.responseJSON?.message ??
                        "Có lỗi xảy ra."
                    );
                }
            });

        });
        $(document).on("click", "#btnViewDiff", function () {
                let vrtourId = $("#slt_vrtour").val();
                $.get(assetUrl + "vrtour/skin/diff", {
                    vrtour_id: vrtourId,
                    type: currentSkinType
                }, function (res) {
                    if (!res.status) {
                        toastr.error("Không có dữ liệu.");
                        return;
                    }
                    renderDiffViewer(res.data);
                    $("#diffModal").modal("show");
                });
            });
    </script>
@endsection