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
    <script>CKFinder.config({ connectorPath: '/ckfinder/connector' });</script>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

                        {{-- Bên trái: chú thích lô đất --}}
                        <div class="d-flex flex-wrap align-items-center">
                            <span class="font-weight-bold mr-3">
                                <i class="fas fa-info-circle text-info"></i> Chú thích lô đất:
                            </span>
                            @foreach ($investmentStatuses as $status)
                                <span class="d-inline-flex align-items-center mr-4">
                                    <span class="d-inline-block mr-2"
                                        style="width:16px;height:16px;border-radius:3px;background-color: {{ $status->color_code }}"></span>
                                    {{ $status->name }}
                                </span>
                            @endforeach
                        </div>

                        {{-- Bên phải: các nút thao tác --}}
                        <div class="d-flex flex-wrap align-items-center ml-auto">
                            @can('vr_tour/hotspot')
                                <x-forms.button-save />
                            @endcan
                            @if ($hotspot->is_draft && (auth()->user()->is_super_admin || auth()->user()->is_approve))
                                @include('backend.partials._diff_viewer')
                                <form action="{{ route('backend_vrtour_hotspot_approve', $hotspot->id) }}" method="POST"
                                    class="d-inline-block ml-1">
                                    @csrf
                                    <input type="hidden" name="type" value="{{ request('type') }}">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        Duyệt
                                    </button>
                                </form>

                                <form action="{{ route('backend_vrtour_hotspot_reject', $hotspot->id) }}" method="POST"
                                    class="d-inline-block ml-1"
                                    onsubmit="return confirm('Bạn có chắc muốn từ chối bản chỉnh sửa này?')">
                                    @csrf
                                    <input type="hidden" name="type" value="{{ request('type') }}">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Từ chối
                                    </button>
                                </form>
                            @endif
                        </div>
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
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Ảnh VN</label>

                            <div class="col-sm-9">
                                <input type="text" name="hp_url" class="form-control" value="{{ $hotspot->url }}" readonly>

                                @if($hotspot->url)
                                    @php
                                        $img = $hotspot->url;
                                        if (!str_starts_with($img, '/') && !str_starts_with($img, 'http')) {
                                            $img = asset('storage/' . $img);
                                        }
                                    @endphp

                                    <img src="{{ $img }}" class="mt-3" style="max-width:200px">
                                @endif
                            </div>
                        </div>
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
                        <x-forms.textarea name="hp_tooltip" :value="old('hp_tooltip') ?: $hotspot->tooltip" label="Mô tả VN"
                            :messages="$errors->get('hp_tooltip')" />
                        <x-forms.textarea name="hp_tooltip_en" value="{{ old('hp_tooltip_en') ?: $hotspot->tooltip_en }}"
                            label="Mô tả EN" :messages="$errors->get('hp_tooltip_en')" />
                        @if (\Illuminate\Support\Str::startsWith($hotspot->potision, 'cmss'))
                            <x-forms.input name="acreage" value="{{ old('acreage') ?: $hotspot->acreage }}" label="Diện tích"
                                type="text" :messages="$errors->get('acreage')" />
                            <x-forms.select name="unit" label="Đơn vị tính" :options="$hotspot_unit"
                                :messages="$errors->get('unit')" />
                            <x-forms.select name="product_type" label="Loại sản phẩm" :options="$option_product_types"
                                :messages="$errors->get('product_type')" />
                            <x-forms.input name="intended_use" value="{{ old('intended_use') ?: $hotspot->intended_use }}"
                                label="Chức năng" type="text" :messages="$errors->get('intended_use')" />
                            <x-forms.select name="investment_product_statuses_id" label="Trạng thái lô đất"
                                :options="$option_investment_product_statuses"
                                :messages="$errors->get('investment_product_statuses_id')" />
                            <x-forms.input name="price" value="{{ old('price') ?? $hotspot->price }}" label="Giá gốc (VND)"
                                type="number" :messages="$errors->get('price')" />
                            <x-forms.input name="lease_price" value="{{ old('lease_price') ?? $hotspot->lease_price }}"
                                label="Giá thuê (VND)" type="number" :messages="$errors->get('lease_price')" />
                            <x-forms.input name="sale_price" value="{{ old('sale_price') ?? $hotspot->sale_price }}"
                                label="Giá bán (VND)" type="number" :messages="$errors->get('sale_price')" />
                            <x-forms.textarea name="transaction_note" :value="old('transaction_note') ?? $hotspot->transaction_note" label="Ghi chú giao dịch"
                                :messages="$errors->get('transaction_note')" />
                            <x-forms.textarea name="available_amenities" :value="old('available_amenities') ?? $hotspot->available_amenities" label="Tiện ích sẵn có"
                                :messages="$errors->get('available_amenities')" />
                            <x-forms.textarea name="contact_info" :value="old('contact_info') ?? $hotspot->contact_info"
                                label="Thông tin liên hệ" :messages="$errors->get('contact_info')" />
                        @endif
                        <x-forms.switch name="hp_opacity" label="Hiển thị" value="{{ $hotspot->opacity }}"
                            :messages="$errors->get('hp_opacity')" />
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection