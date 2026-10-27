<div class="col-12 skin_div" id="skin_investor" data-id="6">
    <div class="card">
        <form action="" method="GET" class="form-horizontal">
            <div class="card-body table-responsive p-4">
                @include('backend.vrtour.skin.partials._header', [
                    'key' => 'investor', 'title' => 'Thông tin chủ đầu tư',
                    'type' => \App\Models\SkinApproval::TYPE_INVESTOR,
                ])
                <x-forms.input name="name" value="" label="Tên" />
                <x-forms.input name="name_en" value="" label="Tên EN" />
                <x-forms.upload name="ivt_image" value="" label="Ảnh" type="image"/>
                @foreach ([1, 2, 3] as $i)
                    <x-forms.textarea name="content{{ $i }}" editor="true" value="" label="Nội dung {{ $i }} VN"/>
                    <x-forms.textarea name="content{{ $i }}_en" editor="true" value="" label="Nội dung {{ $i }} EN"/>
                @endforeach
                <x-forms.input name="website" value="" label="Website" />
                <x-forms.input name="sologan" value="" label="Tên khác" />
                <x-forms.input name="sologan_en" value="" label="Tên khác EN" />
                <x-forms.switch name="investor_status" id="investor_status" label="Hiển thị" value="" :messages="$errors->get('status')" />
                <input hidden id="investor_id">
            </div>
        </form>
    </div>
</div>