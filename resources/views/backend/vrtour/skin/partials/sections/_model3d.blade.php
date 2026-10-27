<div class="col-12 skin_div" id="skin_model3d" data-id="8">
    <div class="card">
        <form action="" method="GET" class="form-horizontal">
            <div class="card-body table-responsive p-4">
                @include('backend.vrtour.skin.partials._header', [
                    'key' => 'model3d',
                    'title' => 'Mô hình 3d',
                    'type' => \App\Models\SkinApproval::TYPE_MODEL3D,
                ])
                <x-forms.input name="title_3d" value="" label="Tiêu đề" />
                <x-forms.input name="title_3d_en" value="" label="Tiêu đề EN" />
                <x-forms.input name="file_3d" value="" label="Link mô hình 3d (Sketchfab)" />
                <x-forms.switch name="model3d_show" id="model3d_show" label="Hiển thị" value=""
                    :messages="$errors->get('status')" />
                <input hidden id="model3d_id">
            </div>
        </form>
    </div>
</div>
