<script>
    $(document).ready(function () {
        const params = new URLSearchParams(window.location.search);
        $('#slt_vrtour').val(params.get('vrtour')).trigger('change');
        $('#slt_vrtour_type').val(params.get('type')).trigger('change');
    });
    $('.skin_div').hide();
    $('#update_all').hide();
    $('#skin_investor #investor_status').bootstrapSwitch();
    $('#skin_model3d #model3d_show').bootstrapSwitch();
    $('#skin_screen #investor').bootstrapSwitch();
    $('#skin_plan #status').bootstrapSwitch();
    $("#approvalToolbar").hide();
    let currentSkinType = 0; // 0 = Tất cả

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    function refreshApprovalBadge() {
        $.get('/backend/vrtour/approval/get-pending-count', function (res) {
            $('.approval-count').text(res.count);
            if (res.count <= 0) {
                $('.approval-count').hide();
            } else {
                $('.approval-count').show();
            }
        });
    }
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
        if (vrtour && vrtour != 'all' && type !== '' && type !== null) {
            $.ajax({
                url: assetUrl + 'vrtour/skin/get-data/' + vrtour + '/' + type,
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
                        box: "#connectMapApprovalBox",
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
                    $('#skin_locationtour #location_in_tour').val(location != null ? (location['location_in_tour'] || '') : '');

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
                    $('#skin_plan #status').bootstrapSwitch('state', plan != null ? (plan['show'] == 1 ? true : false) : false);

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

                    //model 3d
                    var model3d = response.data.model3d;
                    hasPending = renderApprovalBox({
                        data: model3d,
                        checkbox: "#model3dCheckbox",
                        box: "#model3dApprovalBox",
                        user: "#model3dApprovalUser",
                        createdAt: "#model3dApprovalCreatedAt",
                        level: "#model3dApprovalLevel"
                    }) || hasPending;
                    $('#skin_model3d #model3d_id').val(model3d != null ? model3d['id'] : '');
                    $('#skin_model3d #title_3d').val(model3d != null ? model3d['title_3d'] : 'Mô hình 3d');
                    $('#skin_model3d #title_3d_en').val(model3d != null ? model3d['title_3d_en'] : '3d model');
                    $('#skin_model3d #file_3d').val(model3d != null ? model3d['file_3d'] : '');
                    $('#skin_model3d #model3d_show').bootstrapSwitch('state', model3d != null ? (model3d['show'] == 1) : false);

                    if (hasPending) {
                        enableApprovalToolbar();
                    } else {
                        disableApprovalToolbar();
                    }
                    toastr["success"](response.message, 'Success');
                    console.log("fetc",response);
                    if (type != '0') {
                        $('.skin_div').each(function (key, value) {
                            if ($(value).data('id') == type) {
                                $('.skin_div').hide();
                                $(value).show();
                            }
                        });
                    } else {
                        $('.skin_div').show();
                    }
                },
                error: function (xhr) {
                    $('#update_all').hide();
                    toastr["error"](xhr.responseJSON?.message ?? 'Có lỗi xảy ra, vui lòng thử lại', 'Error');
                }
            });
        } else {
            $('#update_all').hide();
            $('.skin_div').hide();
            $("#approvalToolbar").hide();
            if ((!vrtour || vrtour == 'all') && type) {
                toastr["error"]('Vui lòng chọn dự án', 'Error');
            }
        }
    }

    $(document).on('change', '#slt_vrtour_type', function () {
        fetch_data();
        refreshApprovalBadge();
    });

    $(document).on('change', '#slt_vrtour', function () {
        fetch_data();
        refreshApprovalBadge();
    });

    $(document).on('click', '#update_all', function () {
        var vrtour = $('#slt_vrtour').val();
        var type   = $('#slt_vrtour_type').val();

        var data = {
            connect_data: {
                id          : $('#skin_connectmap #connectmap_id').val(),
                image       : $('#skin_connectmap #image_input').val(),
                image_en    : $('#skin_connectmap #image_en_input').val(),
                content     : CKEDITOR.instances['content'].getData(),
                content_en  : CKEDITOR.instances['content_en'].getData()
            },
            location: {
                map         : $('#skin_locationtour #location_in_tour').val()
            },
            investor: {
                id          : $('#skin_investor #investor_id').val(),
                name        : $('#skin_investor #name').val(),
                name_en     : $('#skin_investor #name_en').val(),
                image       : $('#skin_investor #ivt_image_input').val(),
                content1    : CKEDITOR.instances['content1'].getData(),
                content1_en : CKEDITOR.instances['content1_en'].getData(),
                content2    : CKEDITOR.instances['content2'].getData(),
                content2_en : CKEDITOR.instances['content2_en'].getData(),
                content3    : CKEDITOR.instances['content3'].getData(),
                content3_en : CKEDITOR.instances['content3_en'].getData(),
                website     : $('#skin_investor #website').val(),
                sologan     : $('#skin_investor #sologan').val(),
                sologan_en  : $('#skin_investor #sologan_en').val(),
                status      : $('#skin_investor #investor_status').bootstrapSwitch('state')
            },
            screen: {
                id              : $('#skin_screen #wlscreen_id').val(),
                title           : $('#skin_screen #title').val(),
                voice           : $('#skin_screen #voice_input').val(),
                description     : CKEDITOR.instances['description'].getData(),
                investor_img    : $('#skin_screen #investor_image_input').val(),
                investor_desc1  : $('#skin_screen #investor_des1').val(),
                investor_desc2  : $('#skin_screen #investor_des2').val(),
                investor_desc3  : $('#skin_screen #investor_des3').val(),
                status          : $('#skin_screen #investor').bootstrapSwitch('state')
            },
            plan: {
                id          : $('#skin_plan #plan_id').val(),
                show        : $('#skin_plan #status').bootstrapSwitch('state'),
                image1      : $('#skin_plan #image1_input').val(),
                title1      : $('#skin_plan #title1').val(),
                title1_en   : $('#skin_plan #title1_en').val(),
                content1    : CKEDITOR.instances['pcontent1'].getData(),
                content1_en : CKEDITOR.instances['pcontent1_en'].getData(),
                image2      : $('#skin_plan #image2_input').val(),
                title2      : $('#skin_plan #title2').val(),
                title2_en   : $('#skin_plan #title2_en').val(),
                content2    : CKEDITOR.instances['pcontent2'].getData(),
                content2_en : CKEDITOR.instances['pcontent2_en'].getData(),
                image3      : $('#skin_plan #image3_input').val(),
                title3      : $('#skin_plan #title3').val(),
                title3_en   : $('#skin_plan #title3_en').val(),
                content3    : CKEDITOR.instances['pcontent3'].getData(),
                content3_en : CKEDITOR.instances['pcontent3_en'].getData()
            },
            model3d: {
                id          : $('#skin_model3d #model3d_id').val(),
                title_3d    : $('#skin_model3d #title_3d').val(),
                title_3d_en : $('#skin_model3d #title_3d_en').val(),
                file_3d     : $('#skin_model3d #file_3d').val(),
                show        : $('#skin_model3d #model3d_show').bootstrapSwitch('state')
            }
        };

        $.ajax({
            url: assetUrl + 'vrtour/skin/update-data/' + vrtour + '?type=' + type,
            type: "POST",
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify(data),
            success: function (response) {
                toastr["success"](response.message, 'Success');
                if (response.notice) toastr.warning(response.notice);
                refreshApprovalBadge();
                fetch_data();
            },
            error: function (xhr, status, error) {
                toastr["error"]('Có lỗi xảy ra! Vui lòng thử lại', 'Error');
            }
        });
    });

    $(document).on('click', '.remove-btn', function () {
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
                refreshApprovalBadge();
                fetch_data();
            },
            error: function (xhr) {
                toastr["error"](xhr.responseJSON?.message ?? "Có lỗi xảy ra.");
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
                if (res.notice) toastr.warning(res.notice);
                refreshApprovalBadge();
                fetch_data();
            },
            error: function (xhr) {
                toastr["error"](xhr.responseJSON?.message ?? "Có lỗi xảy ra.");
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