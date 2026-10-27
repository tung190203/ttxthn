<div class="col-12 skin_div" id="skin_screen" data-id="1">
    <div class="card">
        <form action="" method="GET" class="form-horizontal" id="skin_screenForm">
            <div class="card-body table-responsive p-4">
                @include('backend.vrtour.skin.partials._header', [
                    'key' => 'welcome', 'title' => 'Màn hình chào mừng',
                    'type' => \App\Models\SkinApproval::TYPE_WELCOME,
                ])
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