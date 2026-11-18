@extends('frontend.index')

@section('content')
    <div class="page__content">

        @guest('guest') {{-- nếu chưa login bằng guard guest --}}
            <div class="alert alert-warning text-center my-4">
                {!! __('app.login_required') !!}
            </div>
        @endguest

        @auth('guest') {{-- nếu đã login --}}
            <!-- main content-->
            <article class="banner d-block px-0">
                <img class="banner__bg" src="{{ asset('images/banner-project.jpg') }}" alt="">
            </article>
            <section class="acc-info mb-60" style="padding-bottom: 60px;">
                <div class="container">
                    <div class="acc-info__header">
                        <div class="acc-info__left">
                            <div class="acc-info__avatar">
                                <img id="avatarPreview"
                                    src="{{ optional($user)->avatar ? asset('storage/' . $user->avatar) : asset('images/avatar-placeholder.png') }}"
                                    alt="avatar" />
                            </div>
                            <label class="upload-img acc-info__upload"><i class="fal fa-camera"></i>
                                <input id="avatarInput" type="file" accept="image/png, image/jpeg" />
                            </label>
                        </div>
                        <div class="acc-info__right">
                            <div class="acc-info__name">{{$user->name ?? ''}}</div>
                            <div class="acc-info__phone">
                                <i class="fal fa-phone me-2"></i>
                                <a href="tel:{{$user->phone ?? ''}}">{{$user->phone ?? ''}}</a>
                            </div>
                        </div>
                    </div>

                    {{-- Form chỉnh sửa thông tin --}}
                    <form class="acc-info__form" action="{{route('guest_update_account')}}" method="post">
                        @csrf
                        <!-- input hidden để submit avatar -->
                        <input type="hidden" name="avatar" id="avatarHidden">

                        {{-- Hiển thị lỗi validate --}}
                        @if($errors->any())
                            <div class="alert alert-danger mb-3 position-relative">
                                <ul class="mb-0" style="list-style: none; padding-left: 0;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success mb-3 position-relative">
                                {{ session('success') }}
                                <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row gx-4 gy-30">
                            <div class="col-md-6 col-xl-4">
                                <label class="form-label">{{ __('app.full_name') }}</label>
                                <input class="form-control" type="text" name="name"
                                    value="{{ old('name', $user->name ?? '') }}">
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label class="form-label">{{ __('app.email_address') }}</label>
                                <input class="form-control" type="email" name="email"
                                    value="{{ old('email', $user->email ?? '') }}">
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label class="form-label">{{ __('app.phone_number') }}</label>
                                <input class="form-control" type="text" name="phone"
                                    value="{{ old('phone', $user->phone ?? '') }}">
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label class="form-label">CCCD / Hộ chiếu</label>
                                <input class="form-control" type="text" name="identification_number"
                                    value="{{ old('identification_number', $user->identification_number ?? '') }}">
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label class="form-label">{{ __('app.nationality') }}</label>
                                <select class="form-control" name="nation_id">
                                    <option value="">{{ __('app.choice_nationality') }}</option>
                                    @if(isset($nations))
                                        @foreach ($nations as $n)
                                            <option value="{{ $n->id }}" {{ old('nation_id', $user->nation_id) == $n->id ? 'selected' : '' }}>{{ $n->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label class="form-label">{{ __('app.address') }}</label>
                                <input class="form-control" type="text" name="address"
                                    value="{{ old('address', $user->address ?? '') }}">
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label class="form-label">Tên tổ chức / Doanh nghiệp</label>
                                <input class="form-control" type="text" name="organization_name"
                                    value="{{ old('organization_name', $user->organization_name ?? '') }}">
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label class="form-label">Mã số thuế</label>
                                <input class="form-control" type="text" name="tax_code"
                                    value="{{ old('tax_code', $user->tax_code ?? '') }}">
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label class="form-label">{{ __('app.password') }}</label>
                                <input class="form-control" type="password" name="password" placeholder="Bỏ trống nếu không đổi">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label mb-1">Lĩnh vực quan tâm</label>
                                <div class="position-relative">
                                    <div id="selected-tags-interested_sectors"
                                        class="form-control d-flex flex-wrap align-items-center px-2 py-2 mb-1 bg-white"
                                        onclick="toggleDropdown_interested_sectors()"
                                        style="min-height: 45px; cursor: pointer; gap: 5px;">
                                        <span class="text-muted small">Tìm kiếm và chọn lĩnh vực quan tâm...</span>
                                    </div>
                                    <div id="dropdown_interested_sectors" class="border rounded shadow bg-white position-absolute w-100"
                                        style="z-index: 9999; display: none; max-height: 220px; overflow-y: auto;">
                                        <div class="mb-2 px-2 pt-2">
                                            <input type="text" class="form-control form-control-sm" placeholder="Tìm kiếm..."
                                                oninput="filterOptions_interested_sectors(this.value)">
                                        </div>
                                        <div id="dropdown-options-interested_sectors" class="px-2 pb-2 text-start">
                                            @php
                                                $selectedSectors = old('interested_sectors', $user->interested_sectors ?? []);
                                                if (!is_array($selectedSectors)) $selectedSectors = [];
                                            @endphp
                                            @if(isset($industries) && count($industries) > 0)
                                                @foreach ($industries as $industry)
                                                    <div class="form-check text-start d-flex align-items-center mb-1">
                                                        <input type="checkbox" class="form-check-input custom-large me-2 flex-shrink-0" name="interested_sectors[]"
                                                            value="{{ $industry['id'] }}" id="industry_{{ $industry['id'] }}"
                                                            onchange="updateTags_interested_sectors()" style="margin-top: 0;"
                                                            {{ in_array($industry['id'], $selectedSectors) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="industry_{{ $industry['id'] }}">{{ $industry['name'] }}</label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-30">
                            <button class="button" type="submit">{{ __('app.edit') }}</button>
                        </div>
                    </form>
                </div>
            </section>

            <section class="section section--bg-pattern">
                <div class="container">
                    <div class="features-slider">
                        <div class="features-slider__container swiper-container">
                          <div class="swiper-wrapper">
                            @php
                                $locale = app()->getLocale();
                            @endphp

                            @foreach ($setting['features'] as $item)
                                <div class="swiper-slide">
                                    <div class="counter">
                                        <div class="counter__item">
                                            <div class="counter__number">
                                                {{ $item['title'][$locale] ?? $item['title']['vi'] ?? '0' }}
                                            </div>
                                            <div class="counter__title">
                                                {{ $item['content'][$locale] ?? $item['content']['vi'] ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                          </div>
                        </div>
                      </div>
                </div>
            </section>

        @endauth
    </div>
@endsection

@push('bottom')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const avatarInput = document.getElementById('avatarInput');
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarHidden = document.getElementById('avatarHidden');

        if (avatarInput) {
            avatarInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const result = e.target.result;
                        avatarPreview.src = result;
                        avatarHidden.value = result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
        
        // Khởi tạo hiển thị tags lĩnh vực quan tâm ngay khi load trang
        if (typeof updateTags_interested_sectors === 'function') {
            updateTags_interested_sectors();
        }
    });

    window.toggleDropdown_interested_sectors = function() {
        const el = document.getElementById('dropdown_interested_sectors');
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }

    window.updateTags_interested_sectors = function() {
        const checkboxes = document.querySelectorAll('#dropdown-options-interested_sectors .form-check input[type="checkbox"]');
        const container = document.getElementById('selected-tags-interested_sectors');
        if (!container) return;
        container.innerHTML = '';

        let selectedCount = 0;
        const selectedLabels = [];

        checkboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                selectedCount++;
                const label = document.querySelector('label[for="' + checkbox.id + '"]').innerText;
                selectedLabels.push({ value: checkbox.value, label: label });
            }
        });

        if (selectedCount === 0) {
            container.innerHTML = '<span class="text-muted small">Tìm kiếm và chọn lĩnh vực quan tâm...</span>';
        } else {
            selectedLabels.forEach(item => {
                const span = document.createElement('span');
                span.className = 'badge bg-primary text-white d-flex align-items-center px-2 py-2 rounded-pill';
                span.style.maxWidth = '100%';
                span.innerHTML = `
                    <span class="text-truncate" style="max-width: 100%;">${item.label}</span>
                    <button type="button" class="ms-2 flex-shrink-0 bg-transparent border-0 text-white" style="font-size: 1rem; line-height: 1;"
                        onclick="removeTag_interested_sectors('${item.value}', event)">
                        &times;
                    </button>
                `;
                container.appendChild(span);
            });
        }
    }

    window.removeTag_interested_sectors = function(value, event) {
        event.stopPropagation();
        const checkbox = Array.from(document.querySelectorAll('#dropdown-options-interested_sectors .form-check input[type="checkbox"]'))
            .find(cb => cb.value === value);
        if (checkbox) {
            checkbox.checked = false;
            updateTags_interested_sectors();
        }
    }

    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('dropdown_interested_sectors');
        const tags = document.getElementById('selected-tags-interested_sectors');
        if (dropdown && tags && !dropdown.contains(e.target) && !tags.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    window.filterOptions_interested_sectors = function(keyword) {
        const lowerKeyword = keyword.toLowerCase();
        const options = document.querySelectorAll('#dropdown-options-interested_sectors .form-check');
        options.forEach(option => {
            const label = option.querySelector('label').innerText.toLowerCase();
            option.style.display = label.includes(lowerKeyword) ? 'block' : 'none';
        });
    }
</script>
@endpush
