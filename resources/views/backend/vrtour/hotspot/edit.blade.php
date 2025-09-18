@extends('backend.index')
@use('\Illuminate\Support\HtmlString')

@section('title')
    Sửa hotspot
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend_vrtour_hotspot_index') }}">Hotspot</a></li>
    <li class="breadcrumb-item active">Sửa hotspot</li>
@endsection

@section('content')

    <script src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('backend_assets/js/globals.js') }}"></script>
    <script>CKFinder.config({connectorPath: '/ckfinder/connector'});</script>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="float-right mb-3">
                        @can('hotspot/add')
                            <x-forms.button-save/>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card card-primary">
                <form action="{{ route('backend_vrtour_hotspot_store', $hotspot->id) }}" method="post" enctype="multipart/form-data" class="form-horizontal" id="formDataGrid">
                    @csrf
                    <div class="card-body">
                        <x-forms.upload name="hp_url" value="{{ old('hp_url') ?: $hotspot->url }}" label="Ảnh VN"
                                        type="image" :messages="$errors->get('hp_url')"/>
                        <x-forms.upload name="hp_url_en" value="{{ old('hp_url_en') ?: $hotspot->url_en }}" label="Ảnh EN"
                                        type="image" :messages="$errors->get('hp_url_en')"/>
                        <x-forms.input name="hp_potision" value="{{ (old('hp_potision') ?: $hotspot->potision) }}"
                                       label="Vị trí" type="text" :messages="$errors->get('hp_potision')" readonly/>
                        <x-forms.input name="hp_tooltip" value="{{ (old('hp_tooltip') ?: $hotspot->tooltip) }}"
                                       label="Mô tả VN" type="text" :messages="$errors->get('hp_tooltip')"/>
                        <x-forms.input name="hp_tooltip_en" value="{{ (old('hp_tooltip_en') ?: $hotspot->tooltip_en) }}"
                                       label="Mô tả EN" type="text" :messages="$errors->get('hp_tooltip_en')"/>
                        <x-forms.switch name="hp_opacity" label="Hiển thị" value="{{ $hotspot->opacity }}"
                            :messages="$errors->get('hp_opacity')" />
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection