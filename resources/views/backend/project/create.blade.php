@extends('backend.index')
@use('\Illuminate\Support\HtmlString')

@section('title')
    {{ $project->exists ? 'Sửa dự án' : 'Thêm mới dự án' }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend_project') }}">Dự án</a></li>
    <li class="breadcrumb-item active"> {{ $project->exists ? 'Sửa dự án' : 'Thêm mới dự án' }}</li>
@endsection

@section('content')
    <script src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('backend_assets/js/globals.js') }}"></script>
    <script>
        CKFinder.config({
            connectorPath: '/ckfinder/connector'
        });
    </script>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="float-right mb-3">
                        @can('project/' . ($project->exists ? 'edit' : 'add'))
                            <x-forms.button-save />
                        @endcan
                        @if ($project->exists)
                            @can('project/add')
                                <x-forms.button-url title="Thêm mới" class="btn-info" icon="fa fa-plus"
                                    url="{{ route('backend_project_create') }}" />
                            @endcan
                            @can('project/delete')
                                <x-forms.button-url title="Xóa" class="btn-danger" icon="fa fa-trash"
                                    url="{{ route('backend_project_delete', $project->id) }}" />
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
            <div class="card card-primary">
                <form action="{{ route('backend_project_save', $project) }}" method="post" enctype="multipart/form-data"
                    class="form-horizontal" id="formDataGrid">
                    @csrf
                    <div class="card-body">
                        <x-forms.input name="name" value="{!! old('name') ?: $project->name !!}" label="Tên dự án"
                            :required="true" onkeyup="changeNameToSlug('name', 'slug', false)" :messages="$errors->get('name')" />
                        <x-forms.input name="slug" value="{{ old('slug') ?: $project->slug }}" label="Slug"
                            :messages="$errors->get('slug')" />
                        <x-forms.upload name="banner_image" value="{{ old('banner_image') ?: $project->banner_image }}"
                            label="Ảnh Chính" type="image" :messages="$errors->get('banner_image')" />
                        <x-forms.upload name="detail_image" value="{{ old('detail_image') ?: $project->detail_image }}"
                                label="Ảnh Phụ (nhỏ)" type="image" :messages="$errors->get('detail_image')" />
                        <x-forms.textarea name="short_desc" :required="true"
                            value="{{ old('short_desc') ?: $project->short_desc }}" label="Mô tả ngắn" :messages="$errors->get('short_desc')" />
                        <x-forms.textarea name="description" :required="true"
                            value="{{ old('description') ?: $project->description }}" label="Nội dung chi tiết"
                            editor="true" :messages="$errors->get('description')" />
                        <x-forms.input name="lat" value="{{ old('lat') ?: $project->lat }}" label="Kinh độ"
                            :messages="$errors->get('lat')" />
                        <x-forms.input name="lng" value="{{ old('lng') ?: $project->lng }}" label="Vĩ độ"
                            :messages="$errors->get('lng')" />
                        <x-forms.input name="area" value="{{ old('area') ?: $project->area }}" label="Diện tích"
                            :messages="$errors->get('area')" />
                        <x-forms.select name="type_number" label="Loại dự án" :required="true" :options="new HtmlString($option_types)"
                            :messages="$errors->get('type_number')" />
                        <x-forms.select name="industry_number" label="Ngành/Lĩnh vực" :required="true" :options="new HtmlString($option_industries)"
                            :messages="$errors->get('industry_number')" />
                        <x-forms.input name="price" value="{{ old('price') ?: $project->price }}" label="Vốn đầu tư"
                            :messages="$errors->get('price')" />
                        <x-forms.input name="link" value="{{ old('link') ?: $project->link }}" label="Link dự án"
                            :messages="$errors->get('link')" />
                        <x-forms.upload name="location_image"
                            value="{{ old('location_image') ?: $project->location_image }}"
                            label="Ảnh sơ đồ liên kết dự án" type="image" :messages="$errors->get('location_image')" />
                        <x-forms.select-multiple name="districts" label="Khu vực" :options="$option_districts" :selected="old('districts', $project->districts->pluck('id')->toArray())"
                            :messages="$errors->get('districts')" help="Chọn các khu vực liên quan" />
                        <x-forms.switch name="is_invest" label="Trạng thái đầu tư" value="{{$project->is_invest ?? 0}}"
                             :messages="$errors->get('is_invest')"/>
                        <x-forms.switch name="is_pinned" label="Có ghim dự án không" value="{{$project->is_pinned ?? 0}}"
                             :messages="$errors->get('is_pinned')"/>
                        <x-forms.input name="pin_order" value="{{ old('pin_order') ?: $project->pin_order }}"
                            label="Thứ tự ghim dự án" :messages="$errors->get('pin_order')" />
                        <x-forms.upload-multi-combo name="advantage" :value="[
                            'images' => explode(';', $project->advantage_images ?? ''),
                            'titles' => json_decode($project->advantage_titles ?? '[]', true),
                            'descs' => json_decode($project->advantage_descriptions ?? '[]', true),
                        ]" label="Ảnh và mô tả lợi thế nổi bật"
                            :editor="true"
                            :messages="$errors->get('advantage_images')" />
                        <x-forms.input name="link_vrtour" value="{{ old('link_vrtour') ?: $project->link_vrtour }}"
                            label="Link vrtour dự án" :messages="$errors->get('link_vrtour')" />
                            <x-forms.input name="link_sand_table" value="{{ old('link_sand_table') ?: $project->link_sand_table }}"
                                label="Link sa bàn ảo dự án" :messages="$errors->get('link_sand_table')" />
                        <x-forms.textarea name="design_short_desc" :required="true"
                            value="{{ old('design_short_desc') ?: $project->design_short_desc }}"
                            label="Mô tả ngắn thiết kế mặt bằng" :messages="$errors->get('design_short_desc')" />
                        <x-forms.upload-multi-combo name="design" :value="[
                            'images' => explode(';', $project->design_images ?? ''),
                            'descs' => json_decode($project->design_description ?? '[]', true),
                        ]" label="Ảnh và mô tả thiết kế mặt bằng"
                            :messages="$errors->get('design_images')" />
                        <x-forms.input name="legal_short_desc"
                            value="{{ old('legal_short_desc') ?: $project->legal_short_desc }}"
                            label="Mô tả văn bản pháp quy" :messages="$errors->get('legal_short_desc')" />
                            <x-forms.textarea
                            name="legal_description"
                             :value="old('legal_description') ?? json_decode($project->legal_description, true)"
                            label="Nội dung ngắn văn bản pháp quy"
                            :messages="$errors->get('legal_description')"
                            :repeatable="true"
                        />
                        <x-forms.select name="layout_id" label="Lựa chọn layout dự án" :required="true"
                        :options="new HtmlString($option_layouts)"
                        :selected="old('layout_id', $project->layout_id)"
                        :messages="$errors->get('layout_id')" />
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
