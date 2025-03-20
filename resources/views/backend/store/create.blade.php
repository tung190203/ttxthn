@extends('backend.index')
@use('\Illuminate\Support\HtmlString')

@section('title')
    {{ $store->exists ? 'Sửa store' : 'Thêm mới store' }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend_store') }}">Store</a></li>
    <li class="breadcrumb-item active">{{ $store->exists ? 'Sửa store' : 'Thêm mới store' }}</li>
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
                        @can('store/' . ($store->exists ? 'edit' : 'add'))
                            <x-forms.button-save/>
                        @endcan
                        @if($store->exists)
                            @can('store/add')
                                <x-forms.button-url title="Thêm mới" class="btn-info" icon="fa fa-plus"
                                                    url="{{ route('backend_store_create') }}"/>
                            @endcan
                            @can('store/delete')
                                <x-forms.button-url title="Xóa" class="btn-danger" icon="fa fa-trash"
                                                    url="{{ route('backend_store_delete', $store->id) }}"/>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
            <div class="card card-primary">
                <form action="{{ route('backend_store_save', $store) }}" method="post"
                      enctype="multipart/form-data"
                      class="form-horizontal" id="formDataGrid">
                    @csrf
                    <div class="card-body">

                        <x-forms.input name="name" value="{{ old('name') ?: $store->name }}" label="Tên store"
                                       :required="true"
                                       onkeyup="changeNameToSlug('name', 'slug', false)"
                                       :messages="$errors->get('name')"/>
                        <x-forms.input name="slug" value="{{ old('slug') ?: $store->slug }}" label="Slug"
                                       :messages="$errors->get('slug')"/>
                        <x-forms.input name="priority" value="{{ (old('priority') ?: $store->priority) ?: 9999 }}"
                                       label="Sắp xếp" type="number" :messages="$errors->get('priority')"/>
                        <x-forms.select name="cat_id" label="Danh mục cha" :options="new HtmlString($option_categories)"
                                        :messages="$errors->get('cat_id')"/>

                        <x-forms.upload name="image" value="{{ old('image') ?: $store->image }}" label="Image"
                                        type="image" :messages="$errors->get('image')"/>

                        <x-forms.switch name="status" value="{{ $store->status ?? 1 }}" label="Hiển thị"
                                        :messages="$errors->get('status')"/>
                        <x-forms.switch name="is_hot" value="{{ $store->is_hot ?? 1 }}" label="Nổi bật"
                                        :messages="$errors->get('is_hot')"/>

                        <x-forms.textarea name="content" :required="true"
                                          value="{{ old('content') ?: $store->content }}"
                                          label="Nội dung chi tiết" editor="true"
                                          :messages="$errors->get('content')"/>

                        <x-forms.input name="meta_title" value="{{ old('meta_title') ?: $store->meta_title }}"
                                       label="Meta Title"
                                       :messages="$errors->get('meta_title')"/>
                        <x-forms.input name="meta_keywords"
                                       value="{{ old('meta_keywords') ?: $store->meta_keywords }}"
                                       label="Meta Keywords"
                                       :messages="$errors->get('meta_keywords')"/>
                        <x-forms.textarea name="meta_description"
                                          value="{{ old('meta_description') ?: $store->meta_description }}"
                                          label="Meta Description" :messages="$errors->get('meta_description')"/>

                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
