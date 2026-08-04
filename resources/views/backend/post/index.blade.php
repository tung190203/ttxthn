@extends('backend.index')

@section('title')
    Quản lý tin tức
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Tin tức</li>
@endsection

@section('content')

    <hr class="mt-0">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-3"></div>
                <div class="col-xl-6 text-center">
                    <form action="" method="GET" class="form-filter-top-index">
                        <input type="hidden" name="tab" value="{{ $activeTab ?? 'approved' }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" value="{{ $filter['name'] }}"
                                           placeholder="Tìm kiếm">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control" name="cat_id" onchange="this.form.submit()">
                                        <option value="0">Loại danh mục</option>
                                        {!! $options['categories'] !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control" name="status" onchange="this.form.submit()">
                                        <option value="-1">Tất cả trạng thái</option>
                                        {!! $options['status'] !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 text-left">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-sm">Tìm kiếm</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xl-3">
                    <div class="float-right mb-3">
                        @can('post/edit')
                            <x-forms.button-save/>
                        @endcan
                        @can('post/add')
                            <x-forms.button-url title="Thêm mới" class="btn-info" icon="fa fa-plus"
                                                url="{{ route('backend_post_create') }}"/>
                        @endcan
                        @can('post/delete')
                            <x-forms.button-bulk-delete url="{{ route('backend_post_bulk_delete')}}"/>
                        @endcan
                    </div>
                </div>
            </div>
            
            <ul class="nav nav-tabs mb-3" id="custom-tabs-two-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ ($activeTab ?? 'approved') == 'approved' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['tab' => 'approved', 'page' => 1]) }}">
                        Đã duyệt
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ ($activeTab ?? '') == 'pending' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['tab' => 'pending', 'page' => 1]) }}">
                        Chờ duyệt
                        @if(($pendingCount ?? 0) > 0)
                            <span class="badge badge-danger ml-1">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>

            <form method="post" action="{{ route('backend_post_save_data_index') }}" id="formDataGrid">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                {!! $dataGrid !!}
                            </div>
                        </div>
                        {{ $posts->withQueryString()->links() }}
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
