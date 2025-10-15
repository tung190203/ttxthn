@extends('backend.index')
@use('\Illuminate\Support\HtmlString')

@section('title')
    {{ $menu->exists ? 'Sửa menu' : 'Thêm mới menu' }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend_menu') }}">Menu</a></li>
    <li class="breadcrumb-item active">{{ $menu->exists ? 'Sửa menu' : 'Thêm mới menu' }}</li>
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
                        @can('menu/' . ($menu->exists ? 'edit' : 'add'))
                            <x-forms.button-save/>
                        @endcan
                        @if($menu->exists)
                            @can('menu/add')
                                <x-forms.button-url title="Thêm mới" class="btn-info" icon="fa fa-plus"
                                                    url="{{ route('backend_menu_create') }}"/>
                            @endcan
                            @can('menu/delete')
                                <x-forms.button-url title="Xóa" class="btn-danger" icon="fa fa-trash"
                                                    url="{{ route('backend_menu_delete', $menu->id) }}"/>
                            @endcan
                            @if(
                                (auth('web')->user()->is_super_admin || auth('web')->user()->is_approve) &&
                                $menu->status_approve === 'pending'
                            )
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-sm fw-bold btn-success" data-toggle="modal" data-target="#approveModal-{{ $menu->id }}">
                                    <i class="fa fa-check" aria-hidden="true"></i> Duyệt
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="approveModal-{{ $menu->id }}" tabindex="-1" aria-labelledby="approveModalLabel-{{ $menu->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title" id="approveModalLabel-{{ $menu->id }}">
                                                    Xác nhận duyệt menu
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Bạn có chắc chắn muốn duyệt menu: <strong>{{ $menu->name }}</strong>?
                                                <p class="text-muted mt-2">
                                                    @if(auth('web')->user()->is_super_admin)
                                                        Sau khi duyệt, menu sẽ được cập nhật trạng thái và hiển thị cho người dùng.
                                                    @elseif(auth('web')->user()->is_approve)
                                                        Sau khi duyệt thành công, menu sẽ chờ duyệt lần cuối bởi admin.
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ route('backend_menu_reject', $menu->id) }}" method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger fw-bold">Yêu cầu chỉnh sửa</button>
                                                </form>
                                                <form action="{{ route('backend_menu_approve', $menu->id) }}" method="post" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success fw-bold">Duyệt menu</button>
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
                <form action="{{ route('backend_menu_save', $menu) }}" method="post"
                      enctype="multipart/form-data"
                      class="form-horizontal" id="formDataGrid">
                    @csrf
                    <div class="card-body">

                        <x-forms.input name="name" value="{{ old('name') ?: $menu->name }}" label="Title"
                                       :required="true"
                                       :messages="$errors->get('name')"/>
                        <x-forms.select name="parent_id" label="Menu cha" :options="new HtmlString($option_menu)"
                                        :messages="$errors->get('parent_id')"/>
                        {{-- <x-forms.upload name="image" value="{{ old('image') ?: $menu->image }}" label="Image"
                                        type="image" :messages="$errors->get('image')"/> --}}
                        <x-forms.input name="priority" value="{{ (old('priority') ?: $menu->priority) ?: 9999 }}"
                                       label="Sắp xếp" type="number" :messages="$errors->get('priority')"/>
                        <x-forms.switch name="status" value="{{ $menu->status ?? 1 }}" label="Hiển thị"
                                        :messages="$errors->get('status')"/>
                        {{-- <x-forms.select name="page_id" label="[1]. Trang" :options="new HtmlString($option_pages)"
                                        :messages="$errors->get('page_id')"/>
                        <x-forms.select name="cat_id" label="[2]. menu"
                                        :options="new HtmlString($option_categories)"
                                        :messages="$errors->get('cat_id')"/>
                        <x-forms.input name="custom_link" value="{{ old('custom_link') ?: $menu->custom_link }}"
                                       label="[3]. URL"
                                       :messages="$errors->get('custom_link')"/> --}}

                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
