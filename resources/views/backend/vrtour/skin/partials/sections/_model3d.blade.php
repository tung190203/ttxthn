<div class="col-12 skin_div" id="skin_model3d" data-id="8">
    <div class="card">
        <form action="" method="GET" class="form-horizontal">
            <div class="card-body table-responsive p-4">
                @include('backend.vrtour.skin.partials._header', [
                    'key' => 'model3d',
                    'title' => 'Mô hình 3d',
                    'type' => \App\Models\SkinApproval::TYPE_MODEL3D,
                ])
                <x-forms.switch name="model3d_show_3dmodel" label="Hiển thị mô hình 3D" value=""
                    :messages="$errors->get('show_3dmodel')" />
                <input hidden id="model3d_id">
            </div>
        </form>
    </div>
</div>