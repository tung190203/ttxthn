@extends('backend.index')
@use('\Illuminate\Support\HtmlString')

@section('title')
    {{ $coupon->exists ? 'Sửa coupon' : 'Thêm mới coupon' }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend_coupon') }}">Coupon</a></li>
    <li class="breadcrumb-item active">{{ $coupon->exists ? 'Sửa coupon' : 'Thêm mới coupon' }}</li>
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
                        @can('coupon/' . ($coupon->exists ? 'edit' : 'add'))
                            <x-forms.button-save/>
                        @endcan
                        @if($coupon->exists)
                            @can('coupon/add')
                                <x-forms.button-url title="Thêm mới" class="btn-info" icon="fa fa-plus"
                                                    url="{{ route('backend_coupon_create') }}"/>
                            @endcan
                            @can('coupon/delete')
                                <x-forms.button-url title="Xóa" class="btn-danger" icon="fa fa-trash"
                                                    url="{{ route('backend_coupon_delete', $coupon->id) }}"/>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
            <div class="card card-primary">
                <form action="{{ route('backend_coupon_save', $coupon) }}" method="post"
                      enctype="multipart/form-data"
                      class="form-horizontal" id="formDataGrid">
                    @csrf
                    <div class="card-body">

                        <x-forms.input name="name" value="{{ old('name') ?: $coupon->name }}" label="Tên coupon"
                                       :required="true"
                                       :messages="$errors->get('name')"/>
                        <x-forms.input name="value" value="{{ old('value') ?: $coupon->value }}" label="Coupon value"
                                       :messages="$errors->get('value')"/>
                        <x-forms.input name="priority" value="{{ (old('priority') ?: $coupon->priority) ?: 9999 }}"
                                       label="Sắp xếp" type="number" :messages="$errors->get('priority')"/>
                        <x-forms.select name="store_id" label="Store" :options="new HtmlString($option_stores)"
                                        :messages="$errors->get('store_id')"/>
                        <x-forms.select name="type" label="Type [Off, Ship, Deal]" :options="new HtmlString($option_types)"
                                        :messages="$errors->get('type')"/>

                        <x-forms.input name="code" value="{{ old('code') ?: $coupon->code }}" label="Coupon code"
                                       :messages="$errors->get('code')"/>

                        <x-forms.switch name="status" value="{{ $coupon->status ?? 1 }}" label="Hiển thị"
                                        :messages="$errors->get('status')"/>
                        <x-forms.switch name="is_verified" value="{{ $coupon->is_verified ?? 1 }}" label="Verified"
                                        :messages="$errors->get('is_verified')"/>
                        <x-forms.switch name="is_exclusive" value="{{ $coupon->is_exclusive ?? 1 }}" label="Exclusive"
                                        :messages="$errors->get('is_exclusive')"/>
                        <x-forms.switch name="is_featured" value="{{ $coupon->is_featured ?? 1 }}" label="Featured"
                                        :messages="$errors->get('is_featured')"/>

                        <x-forms.textarea name="description" :required="true"
                                          value="{{ old('description') ?: $coupon->description }}"
                                          label="Mô tả" :messages="$errors->get('description')"/>

                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
