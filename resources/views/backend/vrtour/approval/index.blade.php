@extends('backend.index')
@section('title')
    Quản lý duyệt vrtour
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active">Quản lý duyệt vrtour</li>
@endsection
@section('content')
    <hr class="mt-0">
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-xl-6">
                    <form method="GET">
                        <input type="hidden" name="tab" value="{{ $activeTab }}">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ $filter['name'] }}" placeholder="Tìm kiếm dự án">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary">Tìm kiếm </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'approved' ? 'active' : '' }}"
                        href="{{ request()->fullUrlWithQuery([
                            'tab' => 'approved',
                            'page' => 1,
                        ]) }}">
                        Đã duyệt
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'pending' ? 'active' : '' }}"
                        href="{{ request()->fullUrlWithQuery([
                            'tab' => 'pending',
                            'page' => 1,
                        ]) }}">
                        Chờ duyệt
                        @if ($pendingCount > 0)
                            <span class="badge badge-danger ml-1">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>
                </li>
            </ul>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-grid-admin text-center">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Dự án</th>
                                        <th colspan="2">Skin popup</th>
                                        <th colspan="2">Nội dung</th>
                                        <th colspan="2">Lô đất</th>
                                        <th rowspan="2">Action</th>
                                    </tr>
                                    <tr>
                                        <th>Chờ duyệt</th>
                                        <th>Đã duyệt</th>
                                        <th>Chờ duyệt</th>
                                        <th>Đã duyệt</th>
                                        <th>Chờ duyệt</th>
                                        <th>Đã duyệt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $project)
                                        <tr>
                                            <td class="text-left pl-3">
                                                {{ $project->name }}
                                            </td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $project->skinApproval_pending_count }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge badge-success">
                                                    {{ $project->skinApproval_approved_count }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $project->content_pending_count }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">
                                                    {{ $project->content_approved_count }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $project->hotspot_pending_count }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge badge-success">
                                                    {{ $project->hotspot_approved_count }}
                                                </span>
                                            </td>
                                            
                                            <td>
                                                <a target="_blank"
                                                    href="{{ route('backend_vrtour_skin_index', [
                                                        'vrtour' => $project->id,
                                                        'type' => \App\Models\SkinApproval::TYPE_ALL,
                                                    ]) }}"
                                                    class="btn btn-secondary btn-sm">
                                                    Skin popup
                                                </a>
                                                <a target="_blank"
                                                    href="{{ route('backend_vrtour_content_index', [
                                                        'vrtour' => $project->id,
                                                    ]) }}"
                                                    class="btn btn-primary btn-sm">
                                                    Nội dung
                                                </a>
                                                <a target="_blank"
                                                    href="{{ route('backend_vrtour_hotspot_index', [
                                                        'vrtour' => $project->id,
                                                        'type' => 2,
                                                    ]) }}"
                                                    class="btn btn-info btn-sm">
                                                    Lô đất
                                                </a>  
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
