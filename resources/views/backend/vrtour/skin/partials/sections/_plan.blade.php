<div class="col-12 skin_div" id="skin_plan" data-id="5">
    <div class="card">
        <form action="" method="GET" class="form-horizontal">
            <div class="card-body table-responsive p-4">
                @include('backend.vrtour.skin.partials._header', [
                    'key' => 'plan', 'title' => 'Kế hoạch triển khai',
                    'type' => \App\Models\SkinApproval::TYPE_PLAN,
                ])
                @foreach ([1, 2, 3] as $i)
                    <x-forms.upload name="image{{ $i }}" value="" label="Ảnh {{ $i }}" type="image"/>
                    <x-forms.input name="title{{ $i }}" value="" label="Giai đoạn {{ $i }}" />
                    <x-forms.input name="title{{ $i }}_en" value="" label="Giai đoạn {{ $i }} EN" />
                    <x-forms.textarea name="pcontent{{ $i }}" editor="true" value="" label="Nội dung chi tiết {{ $i }} VN"/>
                    <x-forms.textarea name="pcontent{{ $i }}_en" editor="true" value="" label="Nội dung chi tiết {{ $i }} EN"/>
                @endforeach
                <x-forms.switch name="status" id="status" label="Hiển thị" value="" :messages="$errors->get('status')" />
                <input hidden id="plan_id">
            </div>
        </form>
    </div>
</div>