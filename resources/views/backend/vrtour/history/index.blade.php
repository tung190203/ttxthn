@extends('backend.index')

@section('title')
    Lịch sử phiên bản VRTour
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Lịch sử phiên bản</li>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">

            @include('backend.vrtour.history.partials.project-select')

            @if (!$selectedVrtour)
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <h5>Chưa chọn dự án</h5>
                        <p class="text-muted mb-0">Vui lòng chọn dự án và nhấn "Xem lịch sử".</p>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Dự án: <strong>{{ $selectedVrtour->name }}</strong></h5>
                    </div>

                    @include('backend.vrtour.history.partials.tabs-nav')

                    <div class="card-body">
                        @include('backend.vrtour.history.partials.date-filter')

                        {{-- $tab đã được controller whitelist (skin|panorama|hotspot) nên an toàn --}}
                        @include('backend.vrtour.history.tabs.' . $tab)
                    </div>
                </div>
            @endif
        </div>
    </section>

    @if ($canRestore && $selectedVrtour && $tab === 'panorama')
        @include('backend.vrtour.history.partials.restore-modal')
    @endif
@endsection

@section('script')
    @include('backend.vrtour.history.scripts.project-select')

    @if ($selectedVrtour)
        @include('backend.vrtour.history.scripts.date-filter')

        @if ($tab === 'panorama')
            @include('backend.vrtour.history.scripts.compare')
            @if ($canRestore)
                @include('backend.vrtour.history.scripts.restore')
            @endif
        @endif
    @endif
@endsection