@extends('backend.index')
@use('\Illuminate\Support\HtmlString')

@section('title')
    Cấu hình chung
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Cấu hình chung</li>
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

                        {{-- các field khác --}}
                        <x-forms.input name="settings[site_name]" value="{{ $settings['site_name'] ?? '' }}" label="Site name" />
                        <x-forms.upload name="settings[favicon]" value="{{ $settings['favicon'] ?? '' }}" label="Favicon" type="image" />
                        <x-forms.upload name="settings[logo]" value="{{ $settings['logo'] ?? '' }}" label="Logo" type="image" />
                        <x-forms.upload name="settings[logo_footer]" value="{{ $settings['logo_footer'] ?? '' }}" label="Logo Footer" type="image" />
                        <x-forms.textarea name="settings[footer_info]" :required="true" editor="true" value="{{ $settings['footer_info'] ?? '' }}" label="Footer info" />
                        <x-forms.input name="settings[address]" value="{{ $settings['address'] ?? '' }}" label="Address" />
                        <x-forms.input name="settings[website]" value="{{ $settings['website'] ?? '' }}" label="Website" />
                        <x-forms.input name="settings[phone]" value="{{ $settings['phone'] ?? '' }}" label="Phone" />
                        <x-forms.input name="settings[email]" value="{{ $settings['email'] ?? '' }}" label="Email" />
                        <x-forms.textarea name="settings[copyright_notice]" :required="true" editor="true" value="{{ $settings['copyright_notice'] ?? '' }}" label="Copyright notice" />
                        <x-forms.textarea name="settings[copyright]" :required="true" value="{{ $settings['copyright'] ?? '' }}" label="Copyright" />

                        <x-forms.switch name="settings[noindex]" value="{{ !empty($settings['noindex']) ? 1 : 0 }}" label="Chặn index" :messages="$errors->get('noindex')" />

                        {{-- Danh sách link + ảnh --}}
                        @php
                            $links = !empty($settings['banners']) ? json_decode($settings['banners'], true) : [];
                            if (empty($links)) {
                                $links = [['link' => '', 'image' => '']];
                            }
                        @endphp

                        <div class="form-group row mt-4">
                            <label class="col-sm-3 col-form-label">Danh sách liên kết + ảnh</label>
                            <div class="col-sm-9">
                                {{-- Hidden để khi xoá hết vẫn gửi --}}
                                <input type="hidden" name="settings[banners]" value="">

                                <div id="links-wrapper">
                                    @foreach($links as $i => $item)
                                        <div class="link-item border p-3 mb-3 rounded" data-index="{{ $i }}">

                                            <x-forms.input name="settings[banners][{{ $i }}][link]" value="{{ $item['link'] ?? '' }}" label="Link" />
                                            <x-forms.upload name="settings[banners][{{ $i }}][image]" value="{{ $item['image'] ?? '' }}" label="Ảnh" />

                                            <div class="form-group row">
                                                <div class="col-sm-9 offset-sm-3">
                                                    <button type="button" class="btn btn-danger btn-sm remove-link">Xóa</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="button" class="btn btn-primary mt-2" id="add-link">+ Thêm liên kết</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        // tạo id an toàn từ index
        function inputIdFromIndex(index) {
            return `settings_banners_${index}_image`;
        }

        // HTML cho item mới
        function createLinkItemHtml(index) {
            const inputNameLink = `settings[banners][${index}][link]`;
            const inputNameImage = `settings[banners][${index}][image]`;
            const imgIdBase = inputIdFromIndex(index);

            return `
                <div class="link-item border p-3 mb-3 rounded" data-index="${index}">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Link</label>
                        <div class="col-sm-9">
                            <input type="text" name="${inputNameLink}" class="form-control" placeholder="Link">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Ảnh</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                               <span class="input-group-btn color-white">
                                   <a id="${imgIdBase}" class="btn btn-primary border-radius-0">
                                       <i class="far fa-image"></i> Chọn file
                                   </a>
                                </span>
                                <input id="${imgIdBase}_input" class="form-control" type="text" name="${inputNameImage}">
                            </div>
                            <div id="${imgIdBase}_preview" class="preview_image" style="max-width: 200px"></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="button" class="btn btn-danger btn-sm remove-link">Xóa</button>
                        </div>
                    </div>
                </div>
            `;
        }

        function bindUploaderForIndex(index) {
            const imgIdBase = inputIdFromIndex(index);
            const btn = document.getElementById(imgIdBase);
            if (!btn) return;
            btn.addEventListener('click', function () {
                selectFileWithCKFinder(`${imgIdBase}_input`, `${imgIdBase}_preview`);
            });
        }

        // Thêm mới
        document.getElementById('add-link').addEventListener('click', function () {
            const wrapper = document.getElementById('links-wrapper');
            let max = -1;
            wrapper.querySelectorAll('.link-item').forEach(it => {
                const di = parseInt(it.getAttribute('data-index'));
                if (!isNaN(di) && di > max) max = di;
            });
            const nextIndex = max + 1;

            const tmp = document.createElement('div');
            tmp.innerHTML = createLinkItemHtml(nextIndex);
            const newEl = tmp.firstElementChild;
            wrapper.appendChild(newEl);

            newEl.querySelector('.remove-link').addEventListener('click', () => newEl.remove());
            bindUploaderForIndex(nextIndex);
        });

        // Xóa item cũ
        document.querySelectorAll('.remove-link').forEach(btn => {
            btn.addEventListener('click', function () {
                const item = btn.closest('.link-item');
                if (item) item.remove();
            });
        });
    </script>
@endsection
