<div class="col-12 skin_div" id="skin_locationtour" data-id="7">
    <div class="card">
        <form action="" method="GET" class="form-horizontal">
            <div class="card-body table-responsive p-4">
                @include('backend.vrtour.skin.partials._header', [
                    'key' => 'location', 'title' => 'Vị trí dự án',
                    'type' => \App\Models\SkinApproval::TYPE_LOCATION,
                ])
                <x-forms.textarea name="location_in_tour" id="location_in_tour" value="" label="Vị trí map"/>
            </div>
        </form>
    </div>
</div>