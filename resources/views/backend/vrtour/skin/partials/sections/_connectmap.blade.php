<div class="col-12 skin_div" id="skin_connectmap" data-id="3">
    <div class="card">
        <form action="" method="GET" class="form-horizontal">
            <div class="card-body table-responsive p-4">
                @include('backend.vrtour.skin.partials._header', [
                    'key' => 'connectMap', 'title' => 'Sơ đồ liên kết vùng',
                    'type' => \App\Models\SkinApproval::TYPE_CONNECT_MAP,
                ])
                <x-forms.upload name="image" value="" label="Ảnh VN" type="image"/>
                <x-forms.upload name="image_en" value="" label="Ảnh EN" type="image"/>
                <x-forms.textarea name="content" editor="true" value="" label="Nội dung chi tiết VN"/>
                <x-forms.textarea name="content_en" editor="true" value="" label="Nội dung chi tiết EN"/>
                <input hidden id="connectmap_id">
            </div>
        </form>
    </div>
</div>