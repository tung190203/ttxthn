@extends('backend.index')
@use('\Illuminate\Support\HtmlString')

@section('title')
    {{ $investment_guide->exists ? 'Sửa cẩm nang đầu tư' : 'Thêm mới cẩm nang đầu tư' }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend_investment_guide') }}">Cẩm nang đầu tư</a></li>
    <li class="breadcrumb-item active">{{ $investment_guide->exists ? 'Sửa cẩm nang đầu tư' : 'Thêm mới cẩm nang đầu tư' }}</li>
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
                        @can('investment_guide/' . ($investment_guide->exists ? 'edit' : 'add'))
                            <x-forms.button-save/>
                        @endcan
                        @can('investment_guide/import')
                            <x-forms.button-url title="Tạo từ link" class="btn-warning text-white" icon="fa fa-link"
                                                url="{{ route('backend_investment_guide_show_import_form') }}"/>
                        @endcan
                        @if($investment_guide->exists)
                            @can('investment_guide/add')
                                <x-forms.button-url title="Thêm mới" class="btn-info" icon="fa fa-plus"
                                                    url="{{ route('backend_investment_guide_create') }}"/>
                            @endcan
                            @can('investment_guide/delete')
                                <x-forms.button-url title="Xóa" class="btn-danger" icon="fa fa-trash"
                                                    url="{{ route('backend_investment_guide_delete', $investment_guide->id) }}"/>
                            @endcan
                            @if(
                                (auth('web')->user()->is_super_admin || auth('web')->user()->is_approve) &&
                                $investment_guide->status_approve === 'pending'
                            )
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-sm fw-bold btn-success" data-toggle="modal" data-target="#approveModal-{{ $investment_guide->id }}">
                                    <i class="fa fa-check" aria-hidden="true"></i> Duyệt
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="approveModal-{{ $investment_guide->id }}" tabindex="-1" aria-labelledby="approveModalLabel-{{ $investment_guide->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title" id="approveModalLabel-{{ $investment_guide->id }}">
                                                    Xác nhận duyệt dự án
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Bạn có chắc chắn muốn duyệt dự án: <strong>{{ $investment_guide->name }}</strong>?
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
                                                <form action="{{ route('backend_investment_guide_approve', $investment_guide->id) }}" method="post" class="d-inline">
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
                <form action="{{ route('backend_investment_guide_save', $investment_guide) }}" method="post"
                      enctype="multipart/form-data"
                      class="form-horizontal" id="formDataGrid">
                    @csrf
                    <div class="card-body">

                        <x-forms.input name="name" value="{{ old('name') ?: $investment_guide->name }}" label="Tên bài viết"
                                       :required="true"
                                       onkeyup="changeNameToSlug('name', 'slug', false)"
                                       :messages="$errors->get('name')"/>
                        <x-forms.input name="slug" value="{{ old('slug') ?: $investment_guide->slug }}" label="Slug"
                                       :messages="$errors->get('slug')"/>
                        <x-forms.input name="priority" value="{{ (old('priority') ?: $investment_guide->priority) ?: 9999 }}"
                                       label="Sắp xếp" type="number" :messages="$errors->get('priority')"/>
                        <x-forms.select name="cat_id" label="Danh mục cha" :options="new HtmlString($option_categories)"
                                        :messages="$errors->get('cat_id')"/>
                        <x-forms.select-multiple name="projects" label="Thuộc các dự án" :options="$option_projects" :selected="old('projects', $investment_guide->projects->pluck('id')->toArray())"
                                            :messages="$errors->get('projects')" help="Chọn các dự án trực thuộc" />

                        <x-forms.upload name="image" value="{{ old('image') ?: $investment_guide->image }}" label="Image"
                                        type="image" :messages="$errors->get('image')"/>

                        @if(auth('web')->user()->is_super_admin)
                        <x-forms.switch name="status" value="{{ $investment_guide->status ?? 1 }}" label="Hiển thị"
                            :messages="$errors->get('status')"/>
                        @endif
                        <x-forms.switch name="is_hot" value="{{ $investment_guide->is_hot ?? 1 }}" label="Nổi bật"
                                        :messages="$errors->get('is_hot')"/>
                                        <x-forms.input name="published_at" label="Ngày xuất bản" type="date"
                            :value="$investment_guide->published_at ? \Carbon\Carbon::parse($investment_guide->published_at)->format('Y-m-d') : null"
                            :messages="$errors->get('published_at')"
                            required />

                        <x-forms.textarea name="description" :required="true"
                                          value="{{ old('description') ?: $investment_guide->description }}"
                                          label="Mô tả" :messages="$errors->get('description')"/>
                        <x-forms.textarea name="content" :required="true"
                                          value="{{ old('content') ?: $investment_guide->content }}"
                                          label="Nội dung chi tiết" editor="true"
                                          :messages="$errors->get('content')"/>
                        <x-forms.upload-multi-combo name="files" :value="[
                                            'images' => explode(';', $investment_guide->files ?? ''),
                                            'descs' => json_decode($investment_guide->short_file_descs ?? '[]', true),
                                        ]" label="Tệp đính kèm"
                                            :messages="$errors->get('files')" />

                        <x-forms.input name="meta_title" value="{{ old('meta_title') ?: $investment_guide->meta_title }}"
                                       label="Meta Title"
                                       :messages="$errors->get('meta_title')"/>
                        <x-forms.input name="meta_keywords"
                                       value="{{ old('meta_keywords') ?: $investment_guide->meta_keywords }}"
                                       label="Meta Keywords"
                                       :messages="$errors->get('meta_keywords')"/>
                        <x-forms.textarea name="meta_description"
                                          value="{{ old('meta_description') ?: $investment_guide->meta_description }}"
                                          label="Meta Description" :messages="$errors->get('meta_description')"/>

                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
