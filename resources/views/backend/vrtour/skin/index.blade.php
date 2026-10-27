@extends('backend.index')

@section('title')
    Quản lý Skin
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Skin</li>
@endsection

@section('content')
    <script src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('backend_assets/js/globals.js') }}"></script>
    <script>CKFinder.config({connectorPath: '/ckfinder/connector'});</script>

    <hr class="mt-0">
    <section class="content">
        <div class="container-fluid">
            @include('backend.vrtour.skin.partials._filter')

            <div class="row">
                @include('backend.vrtour.skin.partials._toolbar')
                @include('backend.vrtour.skin.partials.sections._welcome')
                @include('backend.vrtour.skin.partials.sections._connectmap')
                @include('backend.vrtour.skin.partials.sections._plan')
                @include('backend.vrtour.skin.partials.sections._investor')
                @include('backend.vrtour.skin.partials.sections._location')
                @include('backend.vrtour.skin.partials.sections._model3d')
            </div>
        </div>
        @include('backend.vrtour.skin.partials._diff_viewer')
    </section>
@endsection

@section('script')
    @include('backend.vrtour.skin.partials._script')
@endsection