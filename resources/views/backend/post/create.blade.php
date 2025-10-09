@extends('backend.index')
@use('\Illuminate\Support\HtmlString')

@section('title')
    {{ $post->exists ? 'Sửa tin tức' : 'Thêm mới tin tức' }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend_post') }}">Tin tức</a></li>
    <li class="breadcrumb-item active">{{ $post->exists ? 'Sửa tin tức' : 'Thêm mới tin tức' }}</li>
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
                    <div class="float-right mb-3">
                        @can('post/' . ($post->exists ? 'edit' : 'add'))
                            <x-forms.button-save />
                        @endcan
                        @can('investment_guide/import')
                            <x-forms.button-url title="Tạo từ link" class="btn-warning text-white" icon="fa fa-link"
                                                url="{{ route('backend_post_show_import_form') }}"/>
                        @endcan
                        @if($post->exists)
                            @can('post/add')
                                <x-forms.button-url title="Thêm mới" class="btn-info" icon="fa fa-plus"
                                    url="{{ route('backend_post_create') }}" />
                            @endcan
                            @can('post/delete')
                                <x-forms.button-url title="Xóa" class="btn-danger" icon="fa fa-trash"
                                    url="{{ route('backend_post_delete', $post->id) }}" />
                            @endcan
                            @if(
                                (auth('web')->user()->is_super_admin || auth('web')->user()->is_approve) &&
                                $post->status_approve === 'pending'
                            )
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-sm fw-bold btn-success" data-toggle="modal" data-target="#approveModal-{{ $post->id }}">
                                    <i class="fa fa-check" aria-hidden="true"></i> Duyệt
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="approveModal-{{ $post->id }}" tabindex="-1" aria-labelledby="approveModalLabel-{{ $post->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title" id="approveModalLabel-{{ $post->id }}">
                                                    Xác nhận duyệt dự án
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Bạn có chắc chắn muốn duyệt dự án: <strong>{{ $post->name }}</strong>?
                                                <p class="text-muted mt-2">
                                                    @if(auth('web')->user()->is_super_admin)
                                                        Sau khi duyệt, dự án sẽ được cập nhật trạng thái và hiển thị cho người dùng.
                                                    @elseif(auth('web')->user()->is_approve)
                                                        Sau khi duyệt thành công, dự án sẽ chờ duyệt lần cuối bởi admin.
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                                <form action="{{ route('backend_post_approve', $post->id) }}" method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success fw-bold">Duyệt dự án</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="card card-primary">
                <form action="{{ route('backend_post_save', $post) }}" method="post" enctype="multipart/form-data"
                    class="form-horizontal" id="formDataGrid">
                    @csrf
                    <div class="card-body">

                        <x-forms.input name="name" value="{{ old('name') ?: $post->name }}" label="Tên bài viết"
                            :required="true" onkeyup="changeNameToSlug('name', 'slug', false)"
                            :messages="$errors->get('name')" />
                        <x-forms.input name="slug" value="{{ old('slug') ?: $post->slug }}" label="Slug"
                            :messages="$errors->get('slug')" />
                        <x-forms.input name="priority" value="{{ (old('priority') ?: $post->priority) ?: 9999 }}"
                            label="Sắp xếp" type="number" :messages="$errors->get('priority')" />
                        <x-forms.select name="cat_id" label="Danh mục cha" :options="new HtmlString($option_categories)"
                            :messages="$errors->get('cat_id')" />
                        {{-- <x-forms.select name="project_id" label="Thuộc dự án" :options="new HtmlString($option_projects)"
                            :messages="$errors->get('project_id')" /> --}}
                            <x-forms.select-multiple name="projects" label="Thuộc các dự án" :options="$option_projects" :selected="old('projects', $post->projects->pluck('id')->toArray())"
                                :messages="$errors->get('projects')" help="Chọn các dự án trực thuộc" />

                        <x-forms.upload name="image" value="{{ old('image') ?: $post->image }}" label="Image" type="image"
                            :messages="$errors->get('image')" />

                        @if(auth('web')->user()->is_super_admin)
                        <x-forms.switch name="status" value="{{ $post->status ?? 1 }}" label="Hiển thị"
                            :messages="$errors->get('status')" />
                        @endif
                        <x-forms.switch name="is_hot" value="{{ $post->is_hot ?? 1 }}" label="Nổi bật"
                            :messages="$errors->get('is_hot')" />
                            <x-forms.input name="published_at" label="Ngày xuất bản" type="date"
                            :value="$post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('Y-m-d') : null"
                            :messages="$errors->get('published_at')"
                            required />

                        <x-forms.textarea name="description" :required="true"
                            value="{{ old('description') ?: $post->description }}" label="Mô tả"
                            :messages="$errors->get('description')" />
                        <x-forms.textarea name="content" :required="true" value="{{ old('content') ?: $post->content }}"
                            label="Nội dung chi tiết" editor="true" :messages="$errors->get('content')" />

                        <x-forms.input name="meta_title" value="{{ old('meta_title') ?: $post->meta_title }}"
                            label="Meta Title" :messages="$errors->get('meta_title')" />
                        <x-forms.input name="meta_keywords" value="{{ old('meta_keywords') ?: $post->meta_keywords }}"
                            label="Meta Keywords" :messages="$errors->get('meta_keywords')" />
                        <x-forms.textarea name="meta_description"
                            value="{{ old('meta_description') ?: $post->meta_description }}" label="Meta Description"
                            :messages="$errors->get('meta_description')" />

                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection