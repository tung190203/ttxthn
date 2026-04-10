@extends('backend.index')

@section('title')
    Cấu hình Chatbot
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Cấu hình Chatbot</li>
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
                    <div class="float-right mb-3 mt-3">
                        <x-forms.button-save />
                    </div>
                </div>
            </div>

            <div class="card card-primary">
                <form action="{{ route('backend_setting_save') }}" method="post" enctype="multipart/form-data"
                    class="form-horizontal" id="formDataGrid">
                    @csrf
                    <div class="card-body">

                        <ul class="nav nav-tabs" id="settingTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="vi-tab" data-toggle="tab" href="#tab-vi" role="tab"
                                    aria-controls="tab-vi" aria-selected="true">Tiếng Việt (VI)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="en-tab" data-toggle="tab" href="#tab-en" role="tab"
                                    aria-controls="tab-en" aria-selected="false">Tiếng Anh (EN)</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="settingTabsContent">
                            
                            {{-- TAB TIẾNG VIỆT --}}
                            <div class="tab-pane fade show active" id="tab-vi" role="tabpanel" aria-labelledby="vi-tab">
                                <h4 class="mt-3">Cài đặt ngôn ngữ (VI)</h4>
                                <x-forms.input name="settings[chatbot_name][vi]" 
                                    value="{{ $settings['chatbot_name']['vi'] ?? '' }}"
                                    label="Tên Trợ lý (VI)" />
                                <x-forms.input name="settings[chatbot_tooltip][vi]" 
                                    value="{{ $settings['chatbot_tooltip']['vi'] ?? '' }}"
                                    label="Nội dung Tooltip (VI)" />
                                <x-forms.textarea name="settings[chatbot_welcome_message][vi]" :required="true"
                                    value="{{ $settings['chatbot_welcome_message']['vi'] ?? '' }}" label="Tin nhắn chào mừng (VI)" />
                            </div>

                            {{-- TAB TIẾNG ANH --}}
                            <div class="tab-pane fade" id="tab-en" role="tabpanel" aria-labelledby="en-tab">
                                <h4 class="mt-3">Cài đặt ngôn ngữ (EN)</h4>
                                <x-forms.input name="settings[chatbot_name][en]" 
                                    value="{{ $settings['chatbot_name']['en'] ?? '' }}"
                                    label="Tên Trợ lý (EN)" />
                                <x-forms.input name="settings[chatbot_tooltip][en]" 
                                    value="{{ $settings['chatbot_tooltip']['en'] ?? '' }}"
                                    label="Nội dung Tooltip (EN)" />
                                <x-forms.textarea name="settings[chatbot_welcome_message][en]" :required="true"
                                    value="{{ $settings['chatbot_welcome_message']['en'] ?? '' }}" label="Tin nhắn chào mừng (EN)" />
                            </div>
                        </div>

                        <h4 class="mt-4">Cấu hình giao diện</h4>
                        <x-forms.upload name="settings[chatbot_avatar]" value="{{ $settings['chatbot_avatar'] ?? '' }}" label="Ảnh đại diện Chatbot"
                            type="image" />
                        
                        <div class="form-group row align-items-center">
                            <label class="col-sm-3 col-form-label">Màu sắc chủ đạo</label>
                            <div class="col-sm-9 d-flex align-items-center">
                                <div class="color-picker-wrapper">
                                    <input type="color" name="settings[chatbot_primary_color]" 
                                        value="{{ $settings['chatbot_primary_color'] ?? '#1a6fc4' }}" 
                                        id="chatbot_primary_color" class="custom-color-input">
                                </div>
                                <input type="text" id="color-hex-preview" class="form-control ml-2" style="width: 120px;" 
                                    value="{{ $settings['chatbot_primary_color'] ?? '#1a6fc4' }}" readonly>
                                <small class="text-muted ml-3">Màu chính của các nút và khung Chatbot</small>
                            </div>
                        </div>

                        <style>
                            .color-picker-wrapper {
                                width: 45px;
                                height: 45px;
                                padding: 0;
                                border: 2px solid #e9ecef;
                                border-radius: 8px;
                                overflow: hidden;
                                transition: all 0.2s;
                                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                            }
                            .color-picker-wrapper:hover {
                                border-color: #007bff;
                                transform: scale(1.05);
                            }
                            .custom-color-input {
                                border: none;
                                padding: 0;
                                width: 150%;
                                height: 150%;
                                margin: -25%;
                                cursor: pointer;
                            }
                            #color-hex-preview {
                                background: #f8f9fa;
                                font-weight: 600;
                                font-family: monospace;
                                color: #495057;
                                text-align: center;
                                border-radius: 8px;
                            }
                        </style>

                        <script>
                            document.getElementById('chatbot_primary_color').addEventListener('input', function(e) {
                                document.getElementById('color-hex-preview').value = e.target.value.toUpperCase();
                            });
                        </script>

                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
