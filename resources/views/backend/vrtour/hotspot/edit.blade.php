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
                        @can('vr_tour/hotspot')
                            <x-forms.button-save />
                        @endcan
                        @if ($hotspot->is_draft && (auth()->user()->is_super_admin || auth()->user()->is_approve))
                                <form action="{{ route('backend_vrtour_hotspot_approve', $hotspot->id) }}" method="POST"
                                    style="display:inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        Duyệt
                                    </button>
                                </form>

                                <form action="{{ route('backend_vrtour_hotspot_reject', $hotspot->id) }}" method="POST"
                                    style="display:inline-block"
                                    onsubmit="return confirm('Bạn có chắc muốn từ chối bản chỉnh sửa này?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Từ chối
                                    </button>
                                </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card card-primary">
                <form action="{{ route('backend_vrtour_hotspot_store', $hotspot->id) }}" method="post"
                    enctype="multipart/form-data" class="form-horizontal" id="formDataGrid">
                    @csrf
                    @if ($hotspot->is_draft)
                        @if ($hotspot->approval_level == 0)
                            <div class="alert alert-secondary">
                                Chờ duyệt cấp 1
                            </div>
                        @elseif($hotspot->approval_level == 1)
                            <div class="alert alert-primary">
                                Chờ duyệt cấp 2
                            </div>
                        @endif
                    @elseif($hotspot->status == 'approved')
                        <div class="alert alert-success">
                            Đã duyệt
                        </div>
                    @elseif($hotspot->status == 'rejected')
                        <div class="alert alert-danger">
                            Đã từ chối
                        </div>
                    @endif
                    <div class="card-body">
                        <x-forms.upload name="hp_url" value="{{ old('hp_url') ?: $hotspot->url }}" label="Ảnh VN"
                            type="image" :messages="$errors->get('hp_url')" />
                        <x-forms.upload name="hp_url_en" value="{{ old('hp_url_en') ?: $hotspot->url_en }}" label="Ảnh EN"
                            type="image" :messages="$errors->get('hp_url_en')" />
                        @php
                            $displayPotision =
                                old('hp_potision') ?:
                                (str_starts_with($hotspot->potision, 'cmss_')
                                    ? substr($hotspot->potision, 5)
                                    : $hotspot->potision);
                            $realPotision = old('hp_potision') ?: $hotspot->potision;
                        @endphp
                        <x-forms.input name="hp_potision_display" :value="$displayPotision" label="Vị trí" type="text"
                            readonly />
                        <input type="hidden" name="hp_potision" value="{{ $realPotision }}">
                        <x-forms.textarea name="hp_tooltip" :value="old('hp_tooltip') ?: $hotspot->tooltip" label="Mô tả VN" :messages="$errors->get('hp_tooltip')" />
                        <x-forms.textarea name="hp_tooltip_en" value="{{ old('hp_tooltip_en') ?: $hotspot->tooltip_en }}"
                            label="Mô tả EN" :messages="$errors->get('hp_tooltip_en')" />
                        @if (\Illuminate\Support\Str::startsWith($hotspot->potision, 'cmss'))
                            <x-forms.input name="acreage" value="{{ old('acreage') ?: $hotspot->acreage }}"
                                label="Diện tích" type="text" :messages="$errors->get('acreage')" />
                            <x-forms.select name="product_type" label="Loại sản phẩm" :options="$option_product_types" :messages="$errors->get('product_type')" />
                            <x-forms.input name="intended_use" value="{{ old('intended_use') ?: $hotspot->intended_use }}"
                                label="Chức năng" type="text" :messages="$errors->get('intended_use')" />
                            <x-forms.select name="unit" label="Đơn vị tính" :options="$hotspot_unit" :messages="$errors->get('unit')" />
                        @endif
                        <x-forms.switch name="hp_opacity" label="Hiển thị" value="{{ $hotspot->opacity }}"
                            :messages="$errors->get('hp_opacity')" />
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
