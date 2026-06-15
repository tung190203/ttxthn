@extends('backend.index')
@use('\Illuminate\Support\HtmlString')

@section('title')
    Sửa nội dung
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend_vrtour_content_index') }}">Nội dung</a></li>
    <li class="breadcrumb-item active">Sửa nội dung</li>
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
                        @can('vr_tour/content')
                            <x-forms.button-save/>
                        @endcan
                        @if ($pano->is_draft && (auth()->user()->is_super_admin || auth()->user()->is_approve))
                                <form action="{{ route('backend_vrtour_content_approve', $pano->id) }}" method="POST"
                                    style="display:inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        Duyệt
                                    </button>
                                </form>

                                <form action="{{ route('backend_vrtour_content_reject', $pano->id) }}" method="POST"
                                    style="display:inline-block"
                                    onsubmit="return confirm('Bạn có chắc muốn từ chối bản chỉnh sửa này?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Từ chối
                                    </button>
                                </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card card-primary">
                <form action="{{ route('backend_vrtour_content_store', $pano->id) }}" method="post" enctype="multipart/form-data" class="form-horizontal" id="formDataGrid">
                    @csrf
                    @if ($pano->is_draft)
                        @if ($pano->approval_level == 0)
                            <div class="alert alert-secondary">
                                Chờ duyệt cấp 1
                            </div>
                        @elseif($pano->approval_level == 1)
                            <div class="alert alert-primary">
                                Chờ duyệt cấp 2
                            </div>
                        @endif
                    @elseif($pano->status == 'approved')
                        <div class="alert alert-success">
                            Đã duyệt
                        </div>
                    @elseif($pano->status == 'rejected')
                        <div class="alert alert-danger">
                            Đã từ chối
                        </div>
                    @endif
                    <div class="card-body">
                        <x-forms.input name="ct_label_audio" value="{{ $pano->label_audio }}"
                                       label="Label Audio" type="text" readonly/>
                        <x-forms.input name="ct_title" value="{{ (old('ct_title') ?: $pano->title) }}"
                                       label="Tiêu đề VN" type="text" :messages="$errors->get('ct_title')"/>
                        <x-forms.input name="ct_title_en" value="{{ (old('ct_title_en') ?: $pano->title_en) }}"
                                       label="Tiêu đề EN" type="text" :messages="$errors->get('ct_title_en')"/>
                        <x-forms.textarea editor="true" name="ct_content" value="{{ old('ct_content') ?: $pano->content }}" label="Nội dung VN"
                                        type="image" :messages="$errors->get('ct_content')"/>
                        <x-forms.textarea editor="true" name="ct_content_en" value="{{ old('ct_content_en') ?: $pano->content_en }}" label="Nội dung EN"
                                        type="image" :messages="$errors->get('ct_content_en')"/>
                        <x-forms.upload name="ct_audio" value="{{ (old('ct_audio') ?: $pano->audio) }}"
                                       label="Voice VN" type="text" :messages="$errors->get('ct_audio')"/>
                        <x-forms.upload name="ct_audio_en" value="{{ (old('ct_audio_en') ?: $pano->audio_en) }}"
                                       label="Voice EN" type="text" :messages="$errors->get('ct_audio_en')"/>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>
        const range     = document.getElementById('hp_opacity');
        const output    = document.getElementById('rangeValue');
        function updateValue() {
            output.textContent = parseFloat(range.value).toFixed(2);
        }
        range.addEventListener('input', updateValue);
        updateValue();
    </script>
@endsection