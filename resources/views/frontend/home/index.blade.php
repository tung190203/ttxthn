@extends('frontend.index')
@php
$countAllProject = App\Models\Project::count();
$countAllIndustrial = App\Models\ProjectIndustries::count();
$isDirectMap = request()->has('skip_intro') || request()->get('skip_intro') === '1' || (request()->has('lat') && request()->has('lng')) || request()->has('project_id');
@endphp
@section('content')
<script>
(function() {
    try {
        var sp = new URLSearchParams(window.location.search);
        var hasCoords = sp.has('lat') && sp.has('lng');
        var skipIntro = sp.has('skip_intro') || sp.get('skip_intro') === '1' || hasCoords;
        var hasPending = sessionStorage.getItem('pending_focus_project');
        if (skipIntro || hasPending) {
            var st = document.createElement('style');
            st.id = 'skip-intro-immediate-css';
            st.innerHTML = '#video-loader-container, #mapIntroLayer { display: none !important; opacity: 0 !important; visibility: hidden !important; pointer-events: none !important; } .home-page.home-map-locked { overflow: visible !important; } .home-map-locked #pjSearchFull, .home-map-locked #pjSearchMini { opacity: 1 !important; pointer-events: auto !important; visibility: visible !important; }';
            document.head.appendChild(st);
        }
    } catch(e) {}
})();
</script>
<div class="page__content home-page {{ $isDirectMap ? '' : 'home-map-locked' }}">
    <div class="page__sections">
        <!-- main content-->
        <section class="banner-home home-map-stage" id="home-map-stage" style="position: relative;">
            @if(!file_exists(public_path('videos/intro.mov')))
            <div class="home-map-intro" id="mapIntroLayer" style="{{ $isDirectMap ? 'display: none !important;' : '' }}">
                <div class="home-map-intro__bg"></div>
                <div class="container home-map-intro__inner">
                    <div class="home-map-intro__content">
                        <h1 class="home-map-intro__title font-heading">
                            @php $locale = app()->getLocale(); @endphp
                            @if($locale == 'vi' || $locale == 'vn')
                                <span>{{__('app.map')}}</span>
                                <span>{{__('app.investment')}}</span>
                                <strong>{{__('app.hanoi')}}</strong>
                            @else
                                <strong>{{__('app.hanoi')}}</strong>
                                <span>{{__('app.investment')}}</span>
                                <span>{{__('app.map')}}</span>
                            @endif
                        </h1>
                        <button class="home-map-intro__cta" id="startProjectIntro" onclick="window.handleStartProjectIntro(event)" type="button" style="cursor: pointer; position: relative; z-index: 2005; pointer-events: auto;">
                            {{ __('app.explore_projects') }}
                        </button>
                    </div>
                    <div class="home-map-intro__visual" aria-hidden="true">
                        <div class="home-map-intro__orb">
                            <img src="{{ asset('images/ha_noi_icon.png') }}" alt="">
                        </div>
                        <div class="home-map-intro__plate">
                            <img src="{{ asset('images/position_cn2.png') }}" alt="">
                        </div>
                    </div>
                    <div class="home-map-intro__stats">
                        @php $locale = app()->getLocale(); @endphp
                        @foreach ($setting['features'] as $item)
                        <div class="home-map-intro__stat">
                            <strong class="stat-number-animate" style="opacity: 0;">{{ $item['title'][$locale] ?? ($item['title']['vi'] ?? '') }}</strong>
                            <span>{{ $item['content'][$locale] ?? ($item['content']['vi'] ?? '') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div id="video-loader-container" class="home-video-intro" style="{{ $isDirectMap ? 'display: none !important;' : '' }}">
                <picture id="intro-poster" onclick="window.handleStartProjectIntro(event)" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 10; transition: opacity 0.5s ease, visibility 0.5s ease; display: block; cursor: pointer;">
                    <source media="(max-width: 576px)" srcset="{{ asset('images/KV-phone.jpg') }}">
                    <source media="(max-width: 992px)" srcset="{{ asset('images/KV-ipad.jpg') }}">
                    <img src="{{ asset('images/intro.png') }}" alt="" onclick="window.handleStartProjectIntro(event)" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                </picture>
                <video id="loader-video" muted playsinline preload="auto" onended="window.unlockMap && window.unlockMap()">
                    <source src="{{ asset('videos/intro.mp4') }}" type="video/mp4">
                    <source src="{{ asset('videos/intro.mov') }}" type="video/quicktime">
                    Trình duyệt của bạn không hỗ trợ video.
                </video>
                <div class="home-video-intro__overlay">
                    <div class="container home-video-intro__inner">
                        <div class="home-map-intro__content">
                            <h1 class="home-map-intro__title font-heading">
                            @php $locale = app()->getLocale(); @endphp
                            @if($locale == 'vi' || $locale == 'vn')
                                <span>{{__('app.map')}}</span>
                                <span>{{__('app.investment')}}</span>
                                <strong>{{__('app.hanoi')}}</strong>
                            @else
                                <strong>{{__('app.hanoi')}}</strong>
                                <span>{{__('app.investment')}}</span>
                                <span>{{__('app.map')}}</span>
                            @endif
                            </h1>
                            <button class="home-video-intro__cta" id="startProjectIntro" onclick="window.handleStartProjectIntro(event)" type="button" style="cursor: pointer; position: relative; z-index: 2005; pointer-events: auto;">
                                {{ __('app.explore_projects') }}
                            </button>
                        </div>
                        <div class="home-map-intro__stats">
                            @php $locale = app()->getLocale(); @endphp
                            @foreach ($setting['features'] as $item)
                            <div class="home-map-intro__stat">
                                <strong class="stat-number-animate" style="opacity: 0;">{{ $item['title'][$locale] ?? ($item['title']['vi'] ?? '') }}</strong>
                                <span>{{ $item['content'][$locale] ?? ($item['content']['vi'] ?? '') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <button id="skip-video-btn" class="btn-skip-video" type="button" onclick="window.unlockMap && window.unlockMap()" style="cursor: pointer;">
                    {{__('app.skip_video')}} <i class="fas fa-forward ms-2"></i>
                </button>
            </div>
            @endif

            <script>
            // Khởi tạo ngay lập tức hàm kích hoạt phát video intro & mở bản đồ
            window.handleStartProjectIntro = function(e) {
                if (e) {
                    if (typeof e.preventDefault === 'function') e.preventDefault();
                    if (typeof e.stopPropagation === 'function') e.stopPropagation();
                }
                
                var vContainer = document.getElementById('video-loader-container');
                var lVideo = document.getElementById('loader-video');

                if (!lVideo) {
                    if (typeof window.unlockMap === 'function') window.unlockMap();
                    return;
                }

                if (vContainer) {
                    vContainer.classList.add('is-playing');
                }

                try {
                    var playPromise = lVideo.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(function(error) {
                            console.warn('Video intro play error, unlocking map directly:', error);
                            if (typeof window.unlockMap === 'function') window.unlockMap();
                        });
                    }
                } catch(err) {
                    console.warn('Direct video play error:', err);
                    if (typeof window.unlockMap === 'function') window.unlockMap();
                }
            };

            window.unlockMap = function(immediate) {
                var lVideo = document.getElementById('loader-video');
                var vContainer = document.getElementById('video-loader-container');
                var introLayer = document.getElementById('mapIntroLayer');
                var homePage = document.querySelector('.home-page');

                if (lVideo) {
                    try { lVideo.pause(); lVideo.currentTime = 0; } catch(e){}
                }
                if (vContainer) {
                    if (immediate) {
                        vContainer.style.setProperty('display', 'none', 'important');
                        vContainer.classList.remove('is-playing', 'video-fade-out');
                    } else {
                        vContainer.classList.add('video-fade-out');
                    }
                }
                if (introLayer) {
                    if (immediate) {
                        introLayer.style.setProperty('display', 'none', 'important');
                        introLayer.classList.add('is-hidden');
                    } else {
                        introLayer.classList.add('is-hidden');
                    }
                }
                if (homePage) {
                    homePage.classList.remove('home-map-locked');
                }

                var pjSearchFull = document.getElementById('pjSearchFull');
                var pjSearchMini = document.getElementById('pjSearchMini');
                if (pjSearchFull) {
                    pjSearchFull.style.visibility = '';
                    pjSearchFull.style.opacity = '1';
                    pjSearchFull.style.pointerEvents = 'auto';
                }
                if (pjSearchMini) {
                    pjSearchMini.style.visibility = '';
                    pjSearchMini.style.opacity = '1';
                    pjSearchMini.style.pointerEvents = 'auto';
                }

                var header = document.querySelector('.header');
                if (header) {
                    header.classList.add('map-header-active');
                    if (!header.classList.contains('is-sticky')) {
                        var headerWrapper = document.querySelector('.header__wrapper');
                        if (headerWrapper) {
                            header.style.minHeight = headerWrapper.offsetHeight + 'px';
                        }
                        header.classList.add('is-sticky');
                    }
                }

                var finalize = function() {
                    if (vContainer) {
                        vContainer.style.setProperty('display', 'none', 'important');
                        vContainer.classList.remove('is-playing', 'video-fade-out');
                    }
                    if (introLayer) {
                        introLayer.style.setProperty('display', 'none', 'important');
                    }
                    if (typeof map !== 'undefined' && map) {
                        map.invalidateSize();
                    }

                    var urlParamsCheck = new URLSearchParams(window.location.search);
                    var isShared = urlParamsCheck.has('lat') && urlParamsCheck.has('lng');

                    if (isShared) {
                        if (typeof restoreLayersAndFilterState === 'function') {
                            isInitialStateRestored = false;
                            restoreLayersAndFilterState();
                        }
                    } else {
                        if (typeof window.loadDefaultMap === 'function') {
                            window.loadDefaultMap();
                        }
                        window.dispatchEvent(new Event('resize'));
                    }
                };

                if (immediate) {
                    finalize();
                } else {
                    setTimeout(finalize, 650);
                }
            };

            // Tự động mở khóa bản đồ ngay nếu có yêu cầu xem map hoặc focus dự án
            try {
                var urlParamsCheck = new URLSearchParams(window.location.search);
                var isDirect = urlParamsCheck.has('skip_intro') || urlParamsCheck.get('skip_intro') === '1' || (urlParamsCheck.has('lat') && urlParamsCheck.has('lng')) || !!sessionStorage.getItem('pending_focus_project');
                if (isDirect) {
                    window.unlockMap(true);
                }
            } catch(e){}
            </script>
            <div class="w-full">
                <div id="map">
                    <!-- Railway Legend -->
                    <div id="railway-legend" style="display:none;">
                        <!-- Icon hiển thị khi thu gọn -->
                        <button id="railway-legend-icon" class="railway-legend__icon-btn" title="{{__('app.open_annotation')}}">
                            <i class="fas fa-train"></i>
                        </button>

                        <!-- Panel hiển thị khi mở rộng -->
                        <div id="railway-legend-panel">
                            <div class="railway-legend__header">
                                <i class="fas fa-train me-2"></i>
                                <span>{{__('app.rainway_line_annotation')}}</span>
                                <button id="railway-legend-toggle" title="Thu gọn"><i class="fas fa-times"></i></button>
                            </div>
                            <div id="railway-legend__body">
                                <div class="railway-legend__grid" id="railway-legend-items">
                                    <!-- Items sẽ được render bởi JS -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pj-search" id="pjSearchFull" style="visibility: hidden;">
                <div class="container py-3"
                    style="position: absolute; top:50%;left:50%;transform: translate(-50%,-100%);">
                    <!-- FORM: TÌM KIẾM DỰ ÁN -->
                    <div class="pj-search__body custom_body tab-content active" id="projectTabContent">
                        <div class="pj-search__top">
                            <div class="pj-search__col">
                                <div class="input-group">
                                    <input class="form-control" type="text" id="searchInput"
                                        placeholder="{{ __('app.enter_project_name') }}">
                                    <div class="input-group-text"><i class="fal fa-lg fa-search"></i></div>
                                </div>
                            </div>
                            <div class="pj-search__col">
                                <button class="pj-search__btn" id="applyBtn"
                                    type="button">{{ __('app.search') }}</button>
                            </div>
                        </div>
                        <div class="pj-search__bottom">
                            <div class="pj-search__col custom-select" style="position: relative;">
                                <div class="input-group">
                                    <input class="form-control" type="text" id="districtFilter"
                                        placeholder="{{ __('app.locations') }}" autocomplete="off">
                                    <div class="input-group-text cursor-pointer" id="openDropdown">
                                        <i class="fal fa-lg fa-map-marker-alt cursor-pointer"></i>
                                    </div>
                                </div>
                                <div id="districtDropdown" class="mt-1 bg-white border border-gray-300 rounded shadow"
                                    style="position: absolute; z-index: 999;">
                                    <!-- Nội dung dropdown -->
                                </div>
                            </div>
                            <div class="pj-search__col">
                                <select class="form-select" id="typeFilter">
                                    <option value="all">{{ __('app.project_types') }}</option>
                                    @foreach ($types as $type)
                                    <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="pj-search__col">
                                <select class="form-select" id="industryFilter">
                                    <option value="all">{{ __('app.industry_field') }}</option>
                                    @foreach ($industries as $industry)
                                    <option value="{{ $industry['id'] }}">{{ $industry['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="pj-search__col">
                                <div class="range-input">
                                    <div class="range-input__content">
                                        <div class="range-input__label">{{ __('app.investment_scale') }}</div>
                                        <div class="range-input__price">0</div>
                                    </div>
                                    <input class="range-input__input" id="priceRange" type="range" value="0" min="0"
                                        max="{{ $maxPrice ?? 10000 }}" step="50">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FORM: SP KHU CÔNG NGHIỆP -->
                    <div class="pj-search__body custom_body tab-content orange-theme" id="industrialTabContent"
                        style="display: none">
                        <div class="pj-search__top">
                            <div class="pj-search__col position-relative">
                                <div class="input-group">
                                    <input class="form-control" type="text" id="searchInputSp"
                                        placeholder="{{ __('app.search_keyword') }}" autocomplete="off">
                                    <div class="input-group-text"><i class="fal fa-lg fa-search"></i></div>
                                </div>

                                <!-- POPUP GỢI Ý - Đã thêm z-index và xử lý truncate -->
                                <div id="suggestionPopupSp" class="suggestion-orange-popup shadow-lg"
                                    style="z-index: 10000;">
                                    <div class="suggestion-header">
                                        <span>{{ __('app.suggested_projects') }}</span>
                                        <!-- Spinner để báo hiệu đang tìm kiếm -->
                                        <div class="spinner-border spinner-border-sm text-warning d-none"
                                            id="suggestionLoaderSp"></div>
                                    </div>
                                    <div class="suggestion-content">
                                        <ul class="list-unstyled mb-0" id="suggestionListSp">

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="pj-search__col">
                                <button class="pj-search__btn orange-btn" id="applyBtnSp"
                                    type="button">{{ __('app.search') }}</button>
                            </div>
                        </div>
                        <div class="pj-search__bottom">
                            <div class="pj-search__col custom-select" style="position: relative;">
                                <div class="input-group">
                                    <input class="form-control" type="text" id="districtFilterSp"
                                        placeholder="{{ __('app.locations') }}" autocomplete="off">
                                    <div class="input-group-text cursor-pointer" id="openDropdownSp">
                                        <i class="fal fa-lg fa-map-marker-alt cursor-pointer"></i>
                                    </div>
                                </div>
                                <div id="districtDropdownSp" class="mt-1 bg-white border border-gray-300 rounded shadow"
                                    style="position: absolute; z-index: 999;">
                                    <!-- Nội dung dropdown -->
                                </div>
                            </div>
                            <div class="pj-search__col">
                                <select class="form-select" id="project_id">
                                    <option value="all">{{ __('app.choice_project') }}</option>
                                    @foreach ($list_projects as $project)
                                    <option value="{{ $project['id'] }}">{{ $project['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="pj-search__col">
                                <select class="form-select" id="product_type">
                                    <option value="all">{{ __('app.product_type') }}</option>
                                    @foreach ($product_types as $type)
                                    <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="pj-search__col">
                                <div class="range-input">
                                    <div class="range-input__content">
                                        <div class="range-input__label text-white">{{ __('app.rental_price') }}</div>
                                        <div class="range-input__price1 text-white">0</div>
                                    </div>
                                <input class="white-range" id="priceRangeSp" type="range" value="0"
                                    min="0" max="{{ $maxPriceSp ?? 10000 }}" step="50">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs dưới form -->
                    <div class="custom_tabs">
                        <button class="custom-btn active text-uppercase" id="projectTab"
                            onclick="showTab('project')">{{ __('app.search_project') }}</button>
                        <button class="custom-btn text-uppercase" id="industrialTab"
                            onclick="showTab('industrial')">{{ __('app.industrial_products') }}</button>
                    </div>
                </div>
            </div>
            <div class="pj-search" id="pjSearchMini" style="visibility: hidden;">
            <div class="container py-3" style="position: absolute; top:50%;left:50%;transform: translate(-50%,-100%);">
                    <div class="pj-search__body custom_body tab-content active" id="projectTabContentMini"
                        style="border-bottom-left-radius:8px !important">
                        <div class="pj-search__top">
                            <div class="pj-search__col">
                                <div class="input-group">
                                    <input class="form-control" type="text" id="searchInput"
                                        placeholder="{{ __('app.enter_project_name') }}">
                                    <div class="input-group-text"><i class="fal fa-lg fa-search"></i></div>
                                </div>
                            </div>
                            <div class="pj-search__col">
                            <button class="pj-search__btn" id="applyBtn" type="button">{{ __('app.search') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section home-about-section" id="home-intro-section">
            <div class="container">
                <div class="home-about-section__grid">
                    <div class="home-about-section__content">
                        @php
                            $locale = app()->getLocale();
                            
                            $sec2Title = $setting['section2_title'][$locale] ?? ($setting['section2_title']['vi'] ?? '');
                            if (!empty(trim(strip_tags($sec2Title)))) {
                                // If it starts with <p> and ends with </p>, replace it with <h2> and </h2> to keep the H2 styling
                                if (preg_match('/^<p>(.*?)<\/p>$/s', trim($sec2Title), $matches)) {
                                    $sec2Title = '<h2 class="font-heading">' . $matches[1] . '</h2>';
                                } else {
                                    $sec2Title = '<h2 class="font-heading">' . $sec2Title . '</h2>';
                                }
                            }
                            
                            $sec2Content = $setting['section2_content'][$locale] ?? ($setting['section2_content']['vi'] ?? '');
                        @endphp
                        
                        {!! $sec2Title !!}
                        <div class="mt-3">
                            {!! $sec2Content !!}
                        </div>
                        <a class="home-inline-link" href="#investment-section">{{ __('app.discover_hanoi') }} <i
                                class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                    <div class="home-about-section__visual" aria-hidden="true">
                        <img class="home-about-section__map" src="{{ asset('images/kv-map.png') }}" alt="">
                        @php
                            $badges = $setting['about_badges'] ?? [];
                            $badgeClasses = ['home-about-section__badge--top', 'home-about-section__badge--left', 'home-about-section__badge--right'];
                        @endphp
                        @foreach(array_slice($badges, 0, 3) as $i => $badge)
                            @php
                                $class = $badgeClasses[$i];
                                $title = $badge['title'][$locale] ?? ($badge['title']['vi'] ?? '');
                                $content = $badge['content'][$locale] ?? ($badge['content']['vi'] ?? '');
                                $link = $badge['link'] ?? '';
                            @endphp
                            @if(!empty($title) || !empty($content))
                            <div {!! $link ? 'onclick="window.open(\''.$link.'\', \'_blank\')" style="cursor: pointer;"' : '' !!} class="home-about-section__badge {{ $class }}">
                                <strong>{{ $title }}</strong>
                                <span>{{ $content }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        <section class="section" id="investment-section" style="display: flex; flex-direction: column; justify-content: center;">
            <div class="container">
                <div class="home-section-kicker text-uppercase">— {{ __('app.key_projects') }}</div>
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 gap-3">
                    <h2 class="section__title m-0 font-heading" style="text-transform: none; font-size: clamp(20px, 4vw, 32px); font-weight: 800; line-height: 1.3;">{!! __('app.outstanding_investment_opportunities') !!}</h2>
                            @php
                                $localeLink = $locale === 'vi' ? 'vn' : $locale;
                            @endphp
                    <a href="{{ url($localeLink . '/' . __('app.projects_link') ) }}" class="text-decoration-none fw-bold flex-shrink-0" style="color: #1A6DFF;">{{ __('app.view_more') }}
                        <i class="fas fa-arrow-right ms-1"></i></a>
                </div>

                <div class="project-nav-wrapper mb-30" style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; min-width: 0; gap: 12px; flex: 1;">
                        <!-- "Tất cả" cố định -->
                        <div class="project-nav-fixed">
                            <a class="{{ request('industry') ? '' : 'active' }}"
                                href="{{ route('home_page') }}#investment-section">
                                {{ __('app.all') }}
                            </a>
                        </div>

                        <!-- Các ngành scroll ngang -->
                        <div class="project-nav-scroll swiper-container" style="flex: 1; overflow: hidden;">
                            <ul class="project-nav__list_custom swiper-wrapper">
                                @foreach ($industries as $industry)
                                <li class="swiper-slide" data-tippy-content="{{ $industry['name'] }}" style="width: auto; max-width: none; flex: 0 0 auto;">
                                    <a class="{{ request('industry') == $industry['id'] ? 'active' : '' }}"
                                        href="{{ route('home_page', ['industry' => $industry['id']]) }}#investment-section">
                                        {{ $industry['name'] }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Arrows -->
                    <div class="project-nav-override" style="display: flex; gap: 10px; flex-shrink: 0; margin-left: 20px;">
                        <div class="project-prev hover-pill" style="width: 54px; height: 32px; border-radius: 50px; border: 1px solid #b3ccff; color: #6699ff; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;"><i class="fal fa-fw fa-arrow-left"></i></div>
                        <div class="project-next hover-pill" style="width: 54px; height: 32px; border-radius: 50px; border: 1px solid #b3ccff; color: #6699ff; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;"><i class="fal fa-fw fa-arrow-right"></i></div>
                    </div>
                </div>

                <div id="project-slider-wrapper" style="position: relative; min-height: 200px;">
                    @include('frontend.home.partials.project_slider')
                </div>
            </div>
        </section>
        <section class="section" id="policy-section" style="background: linear-gradient(135deg, #073A73 0%, #1C5FA6 50%, #33AAFA 100%); color: white; padding: 60px 0; display:flex; align-items:center;">
            <div class="container" style="padding-left: 5vw; padding-right: 5vw;">
                <div class="row">
                    <div class="col-lg-5 mb-4 mb-lg-0 pe-lg-5">
                        <div class="home-section-kicker text-uppercase" style="color: #60a5fa;">— {{ __('app.policies_and_incentives') }}</div>
                        <h2 class="policy-title font-heading" style="font-size: clamp(28px, 5vw, 40px); font-weight: 800; margin-bottom: 20px;">{{ __('app.investment_incentives') }}</h2>
                        
                        {{-- DESKTOP: Wrap normally --}}
                        <div class="policy-tags d-none d-lg-flex flex-wrap gap-2 mb-4" style="gap: 10px;">
                            <a href="javascript:void(0)" class="policy-tag active" data-id="" style="background: #33AAFA; color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; text-decoration: none; display: inline-block; margin-bottom: 10px; text-transform: uppercase;">{{ __('app.all') }}</a>
                            @foreach($guide_categories as $cat)
                            <a href="javascript:void(0)" class="policy-tag" data-id="{{ $cat->id }}" style="border: 1px solid rgba(255,255,255,0.3); color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; text-decoration: none; display: inline-block; margin-bottom: 10px;">{{ mb_strtoupper($cat->name) }}</a>
                            @endforeach
                        </div>

                        {{-- MOBILE/TABLET: Swiper Slide --}}
                        <div class="swiper-container policy-tags-slider w-100 mb-4 d-block d-lg-none">
                            <div class="swiper-wrapper policy-tags">
                                <div class="swiper-slide" style="width: auto;">
                                    <a href="javascript:void(0)" class="policy-tag active flex-shrink-0" data-id="" style="background: #33AAFA; color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; text-decoration: none; display: inline-block; text-transform: uppercase;">{{ __('app.all') }}</a>
                                </div>
                                @foreach($guide_categories as $cat)
                                <div class="swiper-slide" style="width: auto;">
                                    <a href="javascript:void(0)" class="policy-tag flex-shrink-0" data-id="{{ $cat->id }}" style="border: 1px solid rgba(255,255,255,0.3); color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; text-decoration: none; display: inline-block;">{{ mb_strtoupper($cat->name) }}</a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <a href="{{ $guide_category_parent ? $guide_category_parent->getUrl() : '#' }}" class="view-all-policy fw-bold" style="color: #F4C430; text-decoration: none; font-size: 14px; display: inline-block; margin-top: 10px;">{{ __('app.view_all_incentives') }} <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                    
                    <div class="col-lg-7">
                        <div class="policy-accordion" id="policyAccordion">
                            
                            @foreach($investment_guides as $index => $guide)
                            <!-- Item {{ $index + 1 }} -->
                            <div class="policy-item border-bottom py-3" style="border-color: rgba(255,255,255,0.2) !important;">
                                <div class="policy-header d-flex justify-content-between align-items-center {{ $index == 0 ? 'expanded' : '' }}" style="cursor: pointer;" onclick="togglePolicy(this)">
                                    <div class="d-flex align-items-center" style="width: calc(100% - 30px);">
                                        <span class="policy-num me-3" style="color: #F4C430; font-weight: bold; font-size: 16px; min-width: 25px;">{{ sprintf('%02d', $index + 1) }}</span>
                                        <h4 class="policy-item-title mb-0" style="font-size: 16px; font-weight: 600; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; line-height: 1.5; padding-bottom: 2px;">{{ $guide->name }}</h4>
                                    </div>
                                    <span class="policy-icon flex-shrink-0" style="color: rgba(255,255,255,0.5); font-size: 20px; width: 20px; text-align: right;">{{ $index == 0 ? '-' : '+' }}</span>
                                </div>
                                <div class="policy-body mt-2" style="display: {{ $index == 0 ? 'block' : 'none' }}; padding-left: 40px;">
                                    <a href="{{ $guide->getUrl() }}" style="color: rgba(255,255,255,0.8); text-decoration: none; display: block; font-size: 14px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $guide->description ? \Illuminate\Support\Str::limit(strip_tags($guide->description), 150) : 'Xem chi tiết thông tin về chính sách và ưu đãi đầu tư...' }}
                                    </a>
                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section" id="news-links-section" style="display: flex; flex-direction: column; justify-content: center;">
            {{-- <img class="texture-1" src="./images/texture-1.png" alt="" />
            <img class="texture-2" src="./images/texture-2.png" alt="" /> --}}
            <div class="container">
                <div class="home-section-kicker text-uppercase">— {{ __('app.news_and_events') }}</div>
                <div class="d-flex justify-content-between align-items-center mb-4 position-relative">
                    <h2 class="section__title m-0 font-heading" style="text-transform: none; font-size: clamp(24px, 4vw, 32px); font-weight: 800;">{!! __('app.latest_updates') !!}</h2>
                    <div class="d-flex align-items-center gap-4">
                        @php
                            $locale = app()->getLocale() === 'vi' ? 'vn' : app()->getLocale();
                        @endphp
                        <a href="{{ url($locale . '/' . __('app.news_link')) }}" class="text-decoration-none fw-bold"
                            style="color: #1677ff; font-size: 14px;">{{ __('app.view_more') }} <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
                <div class="news-slider">
                    <div class="news-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            @foreach ($posts as $item)
                            <div class="swiper-slide">
                                <div class="news" style="height: 100%; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: #fff; overflow: hidden; display: flex; flex-direction: column;">
                                    <a class="news__frame"
                                        href="{{ route('post_detail', ['id' => $item['id'], 'slug' => $item['slug'], 'ref' => 'app.news']) }}"
                                        style="display: block; height: 220px; padding-bottom: 0; overflow: hidden;">
                                        <img src="{{ $item['image'] }}" alt="" style="width: 100%; height: 100%; object-fit: cover;" />
                                    </a>
                                    <div class="news__body" style="padding: 20px; display: flex; flex-direction: column; flex-grow: 1; border: 1px solid #f0f0f0; border-top: none; border-radius: 0 0 12px 12px;">
                                        <div class="custom-news-info mb-2" style="display: flex; align-items: center; gap: 15px; border: none; padding: 0;">
                                            <span class="news__category fw-bold" style="color: #1677ff; font-size: 12px; text-transform: uppercase;">{{ $item['category_name'] ?? 'Tin tức' }}</span>
                                            <span class="news__time" style="color: #999; font-size: 12px; font-weight: 500;">{{ \Carbon\Carbon::parse($item['published_at'])->format('d/m/Y') }}</span>
                                        </div>
                                        <h3 class="news__title mt-2 mb-3" style="font-size: 16px; line-height: 1.5; margin-bottom: auto;">
                                            <a href="{{ route('post_detail', ['id' => $item['id'], 'slug' => $item['slug'], 'ref' => 'app.news']) }}"
                                                data-tippy-content="{{ $item['name'] }}" style="color: #1a2b4c; text-decoration: none; text-transform: uppercase; font-weight: 800; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                {{ $item['name'] }}
                                            </a>
                                        </h3>
                                        <div class="mt-4 pt-2">
                                            <a href="{{ route('post_detail', ['id' => $item['id'], 'slug' => $item['slug'], 'ref' => 'app.news']) }}" class="text-decoration-none fw-bold" style="color: #1677ff; font-size: 12px; text-transform: uppercase;">{{ __('app.read_more') }} <i class="fas fa-arrow-right ms-1"></i></a>
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

        <section class="section d-flex flex-column p-0" id="footer-section">
            @include('frontend.footer')
        </section>
    </div>

    <div class="modal fade" id="filterResultModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('app.filter_results') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('app.close') }}"></button>
                </div>
                <div class="modal-body">
                    <ul id="resultList" class="list-group"></ul>
                    <nav>
                        <ul id="pagination" class="pagination justify-content-center mt-3"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CHIA SẺ BẢN ĐỒ (MODULE G-04) -->
    <div class="modal fade" id="shareMapModal" tabindex="-1" aria-labelledby="shareMapModalLabel" aria-hidden="true" style="z-index: 100050;">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content shadow-lg border-0" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #1d4ed8, #2563eb); padding: 14px 20px;">
                    <h5 class="modal-title font-weight-bold d-flex align-items-center" id="shareMapModalLabel" style="font-size: 16px; margin: 0; color: #fff;">
                        <i class="fas fa-share-alt me-2 mr-2"></i> Chia sẻ liên kết bản đồ
                    </h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 24px; color: #fff; line-height: 1; cursor: pointer; padding: 0; opacity: 0.9;">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-3" style="font-size: 13.5px; line-height: 1.5;">
                        Đường dẫn này lưu giữ chính xác toạ độ góc nhìn, mức độ phóng to, bản đồ nền, các lớp dữ liệu đang bật và bộ lọc tìm kiếm hiện tại của bạn:
                    </p>

                    <div class="input-group mb-3" style="box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-radius: 8px; overflow: hidden;">
                        <input type="text" id="shareMapUrlInput" class="form-control" readonly style="background-color: #f8fafc; font-size: 13px; font-weight: 500; color: #0f172a; border: 1px solid #cbd5e1; padding: 10px 12px;" onclick="this.select();">
                        <button class="btn btn-primary px-3 font-weight-bold" type="button" id="btnCopyShareUrl" style="white-space: nowrap; border-radius: 0 8px 8px 0; color: #ffffff !important; background-color: #1169ff !important; border-color: #1169ff !important;">
                            <i class="fas fa-copy me-1 mr-1" style="color: #ffffff !important;"></i> <span id="btnCopyShareUrlText" style="color: #ffffff !important;">Sao chép</span>
                        </button>
                    </div>
                    <div class="p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0; font-size: 12.5px; color: #475569;">
                        <div class="font-weight-bold text-dark mb-1" style="font-size: 13px;">
                            <i class="fas fa-info-circle text-primary me-1 mr-1"></i> Thông tin trạng thái đang chia sẻ:
                        </div>
                        <div id="shareStateSummary" style="line-height: 1.6;">
                            <!-- Sẽ được điền bằng Javascript -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2 px-4 bg-light d-flex justify-content-between align-items-center" style="border-top: 1px solid #e2e8f0;">
                    <small class="text-muted"><i class="fas fa-link me-1 mr-1"></i> Liên kết có hiệu lực vĩnh viễn</small>
                    <button type="button" class="btn btn-primary btn-sm px-4" data-bs-dismiss="modal" data-dismiss="modal" style="color: #ffffff !important; background-color: #1169ff !important; border-color: #1169ff !important; font-weight: 600;">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL XUẤT DỮ LIỆU BẢN ĐỒ (MODULE G-05) -->
    <div class="modal fade" id="exportMapModal" tabindex="-1" aria-labelledby="exportMapModalLabel" aria-hidden="true" style="z-index: 100050;">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 530px;">
            <div class="modal-content shadow-lg border-0" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #1d4ed8, #2563eb); padding: 14px 20px;">
                    <h5 class="modal-title font-weight-bold d-flex align-items-center" id="exportMapModalLabel" style="font-size: 16px; margin: 0; color: #fff;">
                        <i class="fas fa-file-export me-2 mr-2"></i> Xuất dữ liệu không gian bản đồ
                    </h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 24px; color: #fff; line-height: 1; cursor: pointer; padding: 0; opacity: 0.9;">&times;</button>
                </div>
                <div class="modal-body p-4" style="background: #ffffff;">
                    <p class="text-muted mb-3" style="font-size: 13.5px; line-height: 1.5;">
                        Tải dữ liệu dự án đầu tư theo đúng bộ lọc và phạm vi hiển thị hiện tại để mở trong phần mềm GIS (Google Earth, QGIS, ArcGIS) hoặc Microsoft Excel:
                    </p>

                    <div class="p-3 mb-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0; font-size: 12.5px; color: #475569;">
                        <div class="font-weight-bold text-dark mb-1" style="font-size: 13px;">
                            <i class="fas fa-filter text-primary me-1 mr-1"></i> Phạm vi & Bộ lọc áp dụng:
                        </div>
                        <div id="exportFilterSummary" style="line-height: 1.6;">
                            <!-- Sẽ được điền bằng Javascript -->
                        </div>
                    </div>

                    <style>
                        .export-option-card {
                            background: #ffffff;
                            border: 1px solid #e2e8f0;
                            border-radius: 10px;
                            padding: 12px 14px;
                            transition: all 0.2s ease;
                            text-decoration: none !important;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        }
                        .export-option-card:hover {
                            background: #f8fafc;
                            border-color: #cbd5e1;
                            transform: translateY(-1px);
                            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
                        }
                        .export-option-card .export-icon {
                            width: 40px;
                            height: 40px;
                            border-radius: 8px;
                            background: #f1f5f9;
                            color: #1e40af;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 17px;
                            margin-right: 12px;
                            flex-shrink: 0;
                            transition: all 0.2s ease;
                        }
                        .export-option-card:hover .export-icon {
                            background: #eff6ff;
                            color: #1d4ed8;
                        }
                    </style>

                    <div class="d-flex flex-column gap-2" style="display: flex; flex-direction: column; gap: 10px;">
                        <!-- Định dạng KML -->
                        <a href="#" id="btnDownloadKml" class="export-option-card">
                            <div class="d-flex align-items-center" style="display: flex; align-items: center; min-width: 0;">
                                <div class="export-icon">
                                    <i class="fas fa-globe-americas"></i>
                                </div>
                                <div style="min-width: 0;">
                                    <div class="d-flex align-items-center" style="display: flex; align-items: center; gap: 6px;">
                                        <strong style="font-size: 13.5px; color: #0f172a; font-weight: 600;">Google Earth KML</strong>
                                        <span class="badge" style="font-size: 10.5px; background: #e2e8f0; color: #475569; font-weight: 500; padding: 2px 6px; border-radius: 4px;">.kml</span>
                                    </div>
                                    <span class="text-muted" style="font-size: 12px; display: block; line-height: 1.4; margin-top: 2px;">Ghim 3D, nhãn tên, popup chi tiết &amp; thư mục trạng thái trên Google Earth</span>
                                </div>
                            </div>
                            <span class="btn btn-sm px-3 ms-2" style="font-size: 12px; font-weight: 600; color: #1e40af; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; white-space: nowrap;">
                                <i class="fas fa-download me-1 mr-1"></i> Tải về
                            </span>
                        </a>

                        <!-- Định dạng GeoJSON -->
                        <a href="#" id="btnDownloadGeoJson" class="export-option-card">
                            <div class="d-flex align-items-center" style="display: flex; align-items: center; min-width: 0;">
                                <div class="export-icon">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div style="min-width: 0;">
                                    <div class="d-flex align-items-center" style="display: flex; align-items: center; gap: 6px;">
                                        <strong style="font-size: 13.5px; color: #0f172a; font-weight: 600;">Định dạng GeoJSON</strong>
                                        <span class="badge" style="font-size: 10.5px; background: #e2e8f0; color: #475569; font-weight: 500; padding: 2px 6px; border-radius: 4px;">.geojson</span>
                                    </div>
                                    <span class="text-muted" style="font-size: 12px; display: block; line-height: 1.4; margin-top: 2px;">Chuẩn GIS không gian (WGS84), tương thích QGIS, ArcGIS, Mapbox, geojson.io</span>
                                </div>
                            </div>
                            <span class="btn btn-sm px-3 ms-2" style="font-size: 12px; font-weight: 600; color: #1e40af; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; white-space: nowrap;">
                                <i class="fas fa-download me-1 mr-1"></i> Tải về
                            </span>
                        </a>

                        <!-- Định dạng CSV -->
                        <a href="#" id="btnDownloadCsv" class="export-option-card">
                            <div class="d-flex align-items-center" style="display: flex; align-items: center; min-width: 0;">
                                <div class="export-icon">
                                    <i class="fas fa-table"></i>
                                </div>
                                <div style="min-width: 0;">
                                    <div class="d-flex align-items-center" style="display: flex; align-items: center; gap: 6px;">
                                        <strong style="font-size: 13.5px; color: #0f172a; font-weight: 600;">Bảng tính CSV</strong>
                                        <span class="badge" style="font-size: 10.5px; background: #e2e8f0; color: #475569; font-weight: 500; padding: 2px 6px; border-radius: 4px;">.csv</span>
                                    </div>
                                    <span class="text-muted" style="font-size: 12px; display: block; line-height: 1.4; margin-top: 2px;">Tọa độ Lat/Long chuẩn, mã hóa tiếng Việt UTF-8 BOM hiển thị trên Microsoft Excel</span>
                                </div>
                            </div>
                            <span class="btn btn-sm px-3 ms-2" style="font-size: 12px; font-weight: 600; color: #1e40af; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; white-space: nowrap;">
                                <i class="fas fa-download me-1 mr-1"></i> Tải về
                            </span>
                        </a>
                    </div>
                </div>
                <div class="modal-footer py-2 px-4 bg-light d-flex justify-content-between align-items-center" style="border-top: 1px solid #e2e8f0;">
                    <small class="text-muted"><i class="fas fa-check-circle text-primary me-1 mr-1"></i> Tương thích Google Earth, QGIS &amp; Excel</small>
                    <button type="button" class="btn btn-primary btn-sm px-4" data-bs-dismiss="modal" data-dismiss="modal" style="color: #ffffff !important; background-color: #1169ff !important; border-color: #1169ff !important; font-weight: 600;">Đóng</button>
                </div>
            </div>
        </div>
    </div>
        <div id="homePopup"
            style="display:none; position:fixed; inset:0;
               background:rgba(0,0,0,0.6); z-index:9999;
               justify-content:center; align-items:center;">

            <div id="popupBox"
                style="position:relative; width:80%; max-width:900px;
                 background:#fff; border-radius:12px; overflow:hidden;
                 box-shadow:0 4px 20px rgba(0,0,0,0.3);">

            <div id="popupBody" style="position:relative; width:100%; aspect-ratio:16/9; overflow:hidden;">

                    <a id="popupLink" href="#" target="_blank"
                        style="display:block; width:100%; height:100%;
                     background-position:center; background-repeat:no-repeat;
                     background-size:cover;">
                </a>

                    <button id="closePopup"
                        style="position:absolute; top:10px; right:10px;
                     border:none; background:rgba(0,0,0,0.5);
                     color:#fff; font-size:24px; font-weight:bold;
                     cursor:pointer; border-radius:50%; width:36px; height:36px;
                     line-height:32px; text-align:center;">
                    ×
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('bottom')
<style>
/* ===== CUSTOM FONTS - CHỈ ÁP DỤNG CHO TRANG CHỦ ===== */
/* 👉 Bước 1: Copy file font của bạn vào /public/fonts/
   Sau đó đổi tên file ở dưới cho khớp với file bạn có */
@font-face {
    font-family: 'BaiJamjuree';
    src: url('/fonts/BaiJamjuree-Medium.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'BeVietnamPro';
    src: url('/fonts/BeVietnamPro-Medium.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
}

/* 👉 Bước 2: Dùng class này trong HTML bất cứ đâu trong trang chủ */
.font-heading {
    font-family: 'BaiJamjuree', sans-serif !important;
}

.font-content {
    font-family: 'BeVietnamPro', sans-serif !important;
}

/* 👉 Bước 3: Tự động áp dụng font cho toàn trang chủ */
.home-page {
    font-family: 'BeVietnamPro', sans-serif;
}

.home-page h1,
.home-page h2,
.home-page h3,
.home-page h4,
.home-page h5,
.home-page h6,
.home-page .section__title,
.home-page .home-map-intro__title,
.home-page .home-section-kicker {
    font-family: 'BeVietnamPro', sans-serif;
    font-weight: 700;
    font-size: 24px;
}

/* ===================================================== */

html {
    scroll-behavior: smooth;
}

.home-page {
    background: #f4f8ff;
}

.home-map-stage {
    min-height: calc(100vh - 0px);
    overflow: hidden;
    background: #061c45;
}

.home-map-stage #map {
    min-height: calc(100vh - 0px);
}

.home-map-locked #pjSearchFull,
.home-map-locked #pjSearchMini {
    opacity: 0;
    pointer-events: none;
    visibility: hidden;
}

.home-map-intro {
    position: absolute;
    inset: 0;
    z-index: 12000;
    display: flex;
    align-items: center;
    color: #fff;
    overflow: hidden;
    transition: opacity 0.65s ease, visibility 0.65s ease;
}

.home-map-intro.is-hidden {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

.home-map-intro__bg {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 72% 28%, rgba(68, 214, 255, 0.45), transparent 22%),
        radial-gradient(circle at 62% 62%, rgba(29, 118, 255, 0.45), transparent 34%),
        linear-gradient(124deg, #0a4f96 0%, #0b65c6 44%, #12a8e8 100%);
}

.home-map-intro__bg::before,
.home-map-intro__bg::after {
    content: "";
    position: absolute;
    inset: auto -12% 6% 18%;
    height: 210px;
    border: 2px solid rgba(137, 225, 255, 0.45);
    border-color: rgba(137, 225, 255, 0.45) transparent transparent transparent;
    border-radius: 50%;
    transform: rotate(-8deg);
}

.home-map-intro__bg::after {
    inset: 8% -16% auto 28%;
    height: 260px;
    border-color: rgba(255, 255, 255, 0.38) transparent transparent transparent;
    transform: rotate(19deg);
}

.home-map-intro__inner {
    position: relative;
    z-index: 1;
    min-height: calc(100vh - 0px);
    display: grid;
    grid-template-columns: minmax(0, 0.86fr) minmax(360px, 1.14fr);
    align-items: center;
    gap: 36px;
    padding-top: 96px;
    padding-bottom: 110px;
}

.home-map-intro__content {
    position: relative;
    z-index: 2;

}

.home-map-intro__title {
    margin: 0 0 28px;
    font-weight: 700;
    line-height: 1.2;
    text-transform: uppercase;
}

.home-map-intro__title span,
.home-map-intro__title strong {
    display: block;
    font-size: clamp(48px, 6.8vw, 96px);
    letter-spacing: 0;
}

.home-map-intro__title span:nth-child(2) {
    color: transparent;
    -webkit-text-stroke: 0.1px #93E7FF;
}

.home-map-intro__title strong {
    color: #F4C430;
}

.home-map-intro__cta {
    min-height: 46px;
    padding: 0 22px;
    border: 0;
    border-radius: 6px;
    background: #1169ff;
    color: #fff;
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    box-shadow: 0 16px 34px rgba(0, 46, 150, 0.34);
}

.home-map-intro__visual {
    position: relative;
    min-height: 480px;
}

.home-map-intro__orb {
    position: absolute;
    top: 4%;
    right: 17%;
    width: min(34vw, 330px);
    aspect-ratio: 1;
    border-radius: 50%;
    display: grid;
    place-items: center;
    background: radial-gradient(circle, #ffffff 0%, #dbeaff 52%, #8daed7 100%);
    box-shadow: 0 24px 80px rgba(2, 32, 84, 0.45), inset 0 0 28px rgba(255, 255, 255, 0.8);
}

.home-map-intro__orb img {
    width: 58%;
    height: auto;
    filter: drop-shadow(0 18px 24px rgba(0, 51, 142, 0.42));
}

.home-map-intro__plate {
    position: absolute;
    right: 0;
    bottom: 9%;
    width: min(54vw, 660px);
    aspect-ratio: 2.15;
    border-radius: 50%;
    overflow: hidden;
    box-shadow: 0 34px 86px rgba(0, 21, 68, 0.42);
    transform: perspective(800px) rotateX(58deg) rotateZ(-4deg);
    transform-origin: center;
}

.home-map-intro__plate img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: scale(1.12);
}

.home-map-intro__stats {
    position: absolute;
    left: 12px;
    right: 12px;
    bottom: 30px;
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 10px;
}

.home-map-intro__stat {
    min-height: 86px;
    padding: 16px 20px;
    border: 1px solid rgba(255, 255, 255, 0.28);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.16);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.28);
    backdrop-filter: blur(8px);
}

.home-map-intro__stat strong {
    display: block;
    font-size: clamp(24px, 2.5vw, 40px);
    line-height: 1;
    letter-spacing: 0;
}

.home-map-intro__stat span {
    display: block;
    margin-top: 8px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.78);
}

.home-about-section {
    position: relative;
    min-height: 86vh;
    display: flex !important;
    align-items: center;
    padding: 92px 0;
    overflow: hidden;
    background: linear-gradient(135deg, #fdfdff 0%, #eaf5ff 40%, #c4e4ff 100%);
}

.home-about-section__grid {
    display: grid;
    grid-template-columns: minmax(0, 0.85fr) minmax(380px, 1.15fr);
    align-items: center;
    gap: 40px;
}



.home-section-kicker {
    margin: 20px 0;
    color: #1677ff;
    font-size: 14px;
    font-weight: 900;
    text-transform: uppercase;
}

.home-about-section h2 {
    max-width: 620px;
    margin: 0 0 18px;
    color: #15233a;
    font-size: clamp(28px, 3vw, 32px);
    font-weight: 900;
    letter-spacing: 0;
}

.home-about-section p {
    max-width: 620px;
    margin: 0 0 20px;
    color: #101010;
    font-size: clamp(14px, 1.8vw, 24px);
    font-weight: 400;
}

.home-inline-link {
    color: #1269df;
    font-size: 14px;
    font-weight: 900;
    text-decoration: none;
}

.home-about-section__visual {
    position: relative;
    min-height: 550px;
    display: grid;
    place-items: center;
}

.home-about-section__visual::before {
    content: "";
    position: absolute;
    width: min(65vw, 850px);
    aspect-ratio: 1;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(22, 119, 255, 0.18), rgba(77, 211, 255, 0.08) 52%, transparent 72%);
}

.home-about-section__map {
    position: relative;
    width: min(55vw, 680px);
    filter: drop-shadow(0 26px 44px rgba(22, 119, 255, 0.36));
}

.home-about-section__badge {
    position: absolute;
    min-width: 118px;
    padding: 12px 15px;
    border-radius: 8px;
    background: #fff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    color: #0e5eea;
    transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s ease;
    animation: floatBadge 4s ease-in-out infinite;
    cursor: default;
    z-index: 1;
}

.home-about-section__badge::before {
    content: "";
    position: absolute;
    inset: -6px;
    border-radius: 14px;
    z-index: -1;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    background: rgba(0, 180, 255, 0.12);
    border: 1px solid rgba(0, 255, 255, 0.5);
    box-shadow: inset 0 0 12px rgba(0, 255, 255, 0.2), 0 8px 30px rgba(0, 100, 255, 0.25);
}

.home-about-section__badge:hover {
    transform: translateY(-16px) scale(1.1) rotate(0deg) !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    z-index: 10;
}

@keyframes floatBadge {
    0%, 100% { transform: translateY(0) rotate(var(--badge-rot, 0deg)); }
    50% { transform: translateY(-12px) rotate(var(--badge-rot, 0deg)); }
}

.home-about-section__badge strong {
    display: block;
    font-size: 28px;
    line-height: 1;
    letter-spacing: 0;
}

.home-about-section__badge span {
    display: block;
    margin-top: 5px;
    color: #1d2b45;
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
}

.home-about-section__badge--top {
    top: 30px;
    right: 12%;
    --badge-rot: 6deg;
    transform: rotate(var(--badge-rot));
    animation-delay: 0s;
}

.home-about-section__badge--left {
    left: 2%;
    bottom: 120px;
    --badge-rot: -5deg;
    transform: rotate(var(--badge-rot));
    animation-delay: 1s;
}

.home-about-section__badge--right {
    right: 2%;
    bottom: 150px;
    --badge-rot: 5deg;
    transform: rotate(var(--badge-rot));
    background: linear-gradient(135deg, #1b7dff, #0753dc);
    color: #fff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    animation-delay: 2s;
}

.home-about-section__badge--right:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.home-about-section__badge--right span {
    color: rgba(255, 255, 255, 0.86);
}

.header {
    position: fixed !important;
    top: 0;
    left: 0;
    right: 0;
    width: 100%;
    z-index: 1030;
}

@media (max-width: 991.98px) {
    .home-about-section__grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    .home-about-section__visual {
        min-height: 400px;
    }
    .home-about-section__badge strong {
        font-size: 20px;
    }
    .home-about-section__badge span {
        font-size: 9px;
    }
    .home-about-section__map {
        width: 60vw;
        max-width: 400px;
    }
    .home-about-section__badge--top {
        top: 0;
        right: 24%;
    }
    .home-about-section__badge--left {
        bottom: 60px;
        left: 20%;
    }
    .home-about-section__badge--right {
        bottom: 80px;
        right: 15%;
    }
}

@media (max-width: 767.98px) {
    .home-about-section {
        padding: 80px 0 20px !important;
        min-height: 100vh;
    }
    .home-about-section__grid {
        gap: 10px;
    }
    .home-about-section__visual {
        min-height: 240px;
    }
    .home-about-section__map {
        width: 55vw;
        max-width: 240px;
    }
    .home-about-section h2,
    .home-about-section h2 * {
        font-size: 22px !important;
        margin: 0 0 6px !important;
        line-height: 1.2 !important;
    }
    .home-about-section p,
    .home-about-section p *,
    .home-about-section .mt-3,
    .home-about-section .mt-3 * {
        font-size: 16px !important;
        margin-bottom: 4px !important;
        line-height: 1.35 !important;
    }
    .home-about-section__badge {
        padding: 4px 6px;
        min-width: 60px;
        border-radius: 6px;
    }
    .home-about-section__badge strong {
        font-size: 11px;
    }
    .home-about-section__badge span {
        font-size: 6px;
        margin-top: 1px;
    }
    .home-about-section__badge--top {
        top: 40px;
        right: 20%;
    }
    .home-about-section__badge--left {
        bottom: 80px;
        left: 12%;
    }
    .home-about-section__badge--right {
        bottom: 80px;
        right: 8%;
    }
    .home-inline-link {
        font-size: 12px;
    }
}

.page__content {
    /* padding-top: 112px; */
    overflow: hidden;
}

.page__sections {
    height: calc(100vh - 0px);
    overflow-y: auto;
    scroll-snap-type: y mandatory;
    scroll-behavior: smooth;
    overflow-x: hidden;
}

.page__sections>section,
.page__sections>.pj-search {
    min-height: calc(100vh - 0px);
    scroll-snap-align: start;
    scroll-snap-stop: always;
}

.page__sections>section {
    display: block;
}

.page__sections>.pj-search {
    position: relative;
}

.section.section--final {
    min-height: calc(100vh - 0px);
    display: grid;
    place-items: center;
    text-align: center;
    background: #eef4ff;
    padding: 32px 0;
}

.news-links__partners {
    padding: 40px 0;
}

/* pj-search wrapper phủ lên bản đồ nhưng phải trong suốt với sự kiện chuột
           để không chặn click/drag bản đồ ở vùng trống xung quanh form */
#pjSearchFull,
#pjSearchMini {
    pointer-events: none;
}

/* Chỉ cho phép tương tác trên phần nội dung thực của form */
#pjSearchFull .pj-search__body,
#pjSearchFull .custom_tabs,
#pjSearchMini .pj-search__body {
    pointer-events: auto;
}

/* Xóa ô vuông xanh (focus outline) khi click/focus vào ranh giới */
.leaflet-interactive:focus {
    outline: none !important;
}

/* Style lại tooltip cho tinh tế hơn */
.boundary-tooltip {
    background: rgba(255, 255, 255, 0.9);
    border: none !important;
    border-radius: 4px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
    color: #333;
    font-weight: 600;
    padding: 4px 8px;
    font-size: 12px;
}

.railway-tooltip {
    background: rgba(33, 33, 33, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-radius: 6px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    color: #fff;
    font-weight: 500;
    padding: 6px 12px;
    font-size: 13px;
}

.railway-tooltip:before {
    border: none !important;
}

.boundary-tooltip:before {
    border: none !important;
}

.suggestion-orange-popup {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #fff;
    z-index: 1000;
    margin-top: 8px;
    border-radius: 8px;
    display: none;
    overflow: hidden;
}

.suggestion-orange-popup.active {
    display: block;
    animation: fadeInDown 0.2s ease-out;
}

.suggestion-header {
    background: #fff3e0;
    padding: 8px 15px;
    font-size: 12px;
    font-weight: bold;
    color: #e65100;
    text-transform: uppercase;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #ffe0b2;
}

.suggestion-content {
    max-height: 280px;
    overflow-y: auto;
}

.suggestion-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    text-decoration: none;
    border-bottom: 1px solid #f5f5f5;
    transition: background 0.2s;
}

.suggestion-item .icon-box {
    width: 36px;
    height: 36px;
    background: #fff3e0;
    color: #ff9800;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    flex-shrink: 0;
}

.suggestion-item .info .name {
    font-weight: 600;
    color: #333;
    font-size: 14px;
    line-height: 1.2;
}

.suggestion-item .info .sub {
    font-size: 12px;
    color: #777;
}

.suggestion-footer {
    padding: 10px;
    text-align: center;
    background: #fafafa;
}

.suggestion-footer a {
    font-size: 13px;
    text-decoration: none;
    font-weight: 500;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Video Loader Styles */
#video-loader-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 20000;
    background: #000;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: auto;
}

.home-video-intro {
    display: flex;
}

#loader-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.home-video-intro__overlay {
    position: absolute;
    inset: 0;
    z-index: 2002;
    color: #fff;
    pointer-events: none;
    transition: opacity 0.35s ease, visibility 0.35s ease;
}

.home-video-intro.is-playing .home-video-intro__overlay {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

.home-video-intro.is-playing #intro-poster {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

.home-video-intro__inner {
    position: relative;
    min-height: 100%;
    padding-top: 96px;
    padding-bottom: 118px;
    display: flex;
    align-items: center;
}

.home-video-intro__overlay .home-map-intro__content {
    width: min(560px, 52vw);
    pointer-events: auto;
}

.home-video-intro__overlay .home-map-intro__stats {
    left: 12px;
    right: 12px;
}

.home-video-intro__cta {
    min-height: 46px;
    padding: 0 22px;
    border: 0;
    border-radius: 6px;
    background: #1169ff;
    color: #fff;
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    box-shadow: 0 16px 34px rgba(0, 46, 150, 0.34);
    pointer-events: auto;
}

.btn-skip-video {
    position: absolute;
    top: 50%;
    right: 24px;
    bottom: auto;
    transform: translateY(-50%);
    min-height: 48px;
    padding: 0 18px;
    background: rgba(4, 22, 48, 0.62);
    backdrop-filter: blur(5px);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 50px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    z-index: 2005;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.home-video-intro:not(.is-playing) .btn-skip-video {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

.btn-skip-video:hover {
    background: rgba(4, 22, 48, 0.86);
    border-color: #fff;
    transform: translateY(-50%) translateX(-4px);
}

.video-fade-out {
    opacity: 0;
    transition: opacity 0.8s ease-out;
    pointer-events: none;
}

@media (max-width: 768px) {
    .home-video-intro__inner {
        padding-top: 86px;
        padding-bottom: 205px;
        align-items: flex-start;
        justify-content: center;
        flex-direction: column;
    }

    .home-video-intro__overlay .home-map-intro__content {
        width: 100%;
    }

    .btn-skip-video {
        top: 100px;
        right: 16px;
        bottom: auto;
        transform: none;
        min-height: 42px;
        padding: 0 14px;
        font-size: 12px;
    }

    .btn-skip-video:hover {
        transform: translateX(-4px);
    }
}
/* ── RAILWAY LEGEND ─────────────────────────────────────── */
#railway-legend {
    position: absolute;
    bottom: 24px;
    left: 12px;
    z-index: 1500;
            pointer-events: none; /* Let clicks pass through empty spaces */
}

.railway-legend__icon-btn {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #1a6fc4;
    color: #fff;
    border: 2px solid #fff;
    box-shadow: 0 4px 15px rgba(26, 111, 196, 0.4);
    font-size: 18px;
    cursor: pointer;
    align-items: center;
    justify-content: center;

    /* Animation */
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    transform-origin: center;
    opacity: 0;
    transform: scale(0.5);
    visibility: hidden;
    pointer-events: none;
    z-index: 2;
            display: flex; /* So the flex rules stay even when hidden */
}
.railway-legend__icon-btn:hover {
    background: #1d7ddc;
}

#railway-legend.legend-collapsed .railway-legend__icon-btn {
    opacity: 1;
    transform: scale(1);
    visibility: visible;
    pointer-events: auto;
}

#railway-legend-panel {
    background: rgba(255, 255, 255, 0.96);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(26, 111, 196, 0.2);
    border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    min-width: 220px;
    max-width: 280px;
    overflow: hidden;
    pointer-events: auto;

    /* Animation */
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    transform-origin: bottom left;
    opacity: 1;
    transform: scale(1);
    visibility: visible;
    position: relative;
    z-index: 1;
}

#railway-legend.legend-collapsed #railway-legend-panel {
    opacity: 0;
    transform: scale(0.6) translateY(20px);
    visibility: hidden;
    pointer-events: none;
}

.railway-legend__header {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 14px;
    background: #1a6fc4;
    border-bottom: 1px solid #1459a0;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.3px;
    user-select: none;
}
.railway-legend__header span {
    flex: 1;
}
#railway-legend-toggle {
    background: none;
    border: none;
            color: rgba(255,255,255,0.8);
    cursor: pointer;
    padding: 0 2px;
    font-size: 15px;
    transition: color 0.2s;
    line-height: 1;
}
        #railway-legend-toggle:hover { color: #fff; }

#railway-legend__body {
    max-height: 250px;
    overflow-y: auto;
}

/* Custom scrollbar for legend body */
#railway-legend__body::-webkit-scrollbar {
    width: 5px;
}
#railway-legend__body::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.05);
}
#railway-legend__body::-webkit-scrollbar-thumb {
    background: rgba(26, 111, 196, 0.4);
    border-radius: 10px;
}
#railway-legend__body::-webkit-scrollbar-thumb:hover {
    background: rgba(26, 111, 196, 0.6);
}

.railway-legend__grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 12px;
    padding: 12px 14px;
}
.railway-legend__item {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    color: #333;
    font-size: 12px;
    font-weight: 500;
    line-height: 1.35;
}
.railway-legend__swatch {
    flex-shrink: 0;
    width: 24px;
    height: 6px;
    border-radius: 3px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    margin-top: 5px;
}
/* Mobile: 1 cột, góc dưới trái, nhỏ hơn */
@media (max-width: 768px) {
    #railway-legend {
        top: 20%;
        bottom: auto;
        left: 10px;
    }
    .railway-legend__icon-btn {
        bottom: auto;
        top: 0;
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    #railway-legend-panel {
        transform-origin: top left;
        min-width: 180px;
        max-width: 240px;
        border-radius: 8px;
    }
    #railway-legend.legend-collapsed #railway-legend-panel {
        transform: scale(0.6) translateY(-20px);
    }
    .railway-legend__header {
        font-size: 12px;
        padding: 8px 10px;
    }
    #railway-legend__body {
        max-height: 200px;
    }
    .railway-legend__grid {
        gap: 10px;
        padding: 10px;
    }
    .railway-legend__item {
        font-size: 11.5px;
    }
}
/* RAILWAY STATION ICON */
.railway-station-icon {
    background: #8b1d41;
    border: 2px solid #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.4);
    transition: all 0.3s ease;
}
.railway-station-icon:hover {
    transform: scale(1.2);
    background: #a3224d;
    z-index: 1000 !important;
}
.railway-station-icon i {
    font-size: 11px;
}

.station-popup .leaflet-popup-content-wrapper {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 8px;
    padding: 5px;
}
.station-popup__title {
    color: #8b1d41;
    font-weight: 700;
    font-size: 14px;
    margin-bottom: 2px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.station-popup__line {
    font-size: 12px;
    color: #666;
    margin-bottom: 5px;
    font-style: italic;
}
.station-popup__desc {
    font-size: 12px;
    color: #333;
    line-height: 1.4;
}

/* Làm nổi bật nút Layer Control của Leaflet */
.leaflet-control-layers {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
}

.leaflet-control-layers-expanded {
    background: #fff !important;
    border-radius: 10px !important;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15) !important;
    padding: 12px 18px !important;
    border: 1px solid rgba(26, 111, 196, 0.2) !important;
    margin: 10px !important;
    transform-origin: top right;
}

/* Ngăn lòi panel ngoài ý muốn */
.leaflet-control-layers:not(.leaflet-control-layers-expanded) .leaflet-control-layers-list {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    max-height: 0 !important;
    pointer-events: none !important;
}

.leaflet-control-layers-expanded .leaflet-control-layers-list {
    display: block;
    animation: fadeInLayerList 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

@keyframes fadeInLayerList {
    0% {
        opacity: 0;
        transform: translateY(-8px) scale(0.95);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.leaflet-control-layers-toggle {
    width: 52px !important;
    height: 52px !important;
    border-radius: 50% !important;
    background-color: #1a6fc4 !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 576 512'%3E%3Cpath fill='%23ffffff' d='M264.5 5.2c14.9-6.9 32.1-6.9 47 0l218.6 101c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 149.8C37.4 145.8 32 137.3 32 128s5.4-17.9 13.9-21.8L264.5 5.2zM476.9 209.6l53.2 24.6c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 277.8C37.4 273.8 32 265.3 32 256s5.4-17.9 13.9-21.8l53.2-24.6 152 70.2c23.4 10.8 50.4 10.8 73.8 0l152-70.2zM224.1 411.2c23.4 10.8 50.4 10.8 73.8 0l152-70.2 53.2 24.6c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 411.8C37.4 407.8 32 399.3 32 390s5.4-17.9 13.9-21.8l53.2-24.6 152 70.2z'/%3E%3C/svg%3E") !important;
    background-size: 26px 26px !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
    box-shadow: 0 6px 20px rgba(26, 111, 196, 0.55) !important;
    transition: transform 0.2s ease, box-shadow 0.2s ease !important;
    animation: layer-bounce-idle 3s ease-in-out 1.5s infinite !important;
    transform-origin: center bottom;
}

.leaflet-control-layers-toggle:hover {
    background-color: #1459a0 !important;
    transform: scale(1.1) !important;
    box-shadow: 0 8px 28px rgba(26, 111, 196, 0.7) !important;
}

.leaflet-control-layers-toggle:active {
    transform: scale(0.93) !important;
    transition: transform 0.1s ease !important;
}

@keyframes layer-bounce-idle {
            0%   { transform: translateY(0) scale(1); }
            10%  { transform: translateY(-8px) scale(1.05); }
            20%  { transform: translateY(0px) scale(1); }
            30%  { transform: translateY(-4px) scale(1.02); }
            40%  { transform: translateY(0px) scale(1); }
            100% { transform: translateY(0) scale(1); }
}

.leaflet-control-layers-base label,
.leaflet-control-layers-overlays label {
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    padding: 4px 0;
    display: flex;
    align-items: center;
}

.leaflet-control-layers-separator {
    border-top: 1px dashed #ccc !important;
    margin: 10px 0 !important;
}

@media (max-width: 991.98px) {

    .home-map-intro__inner,
    .home-about-section__grid {
        grid-template-columns: 1fr;
    }

    .home-map-intro__inner {
        align-content: center;
        gap: 0;
        padding-top: 86px;
        padding-bottom: 168px;
    }

    .home-map-intro__visual {
        min-height: 310px;
        margin-top: -26px;
    }

    .home-map-intro__orb {
        right: 7%;
        width: min(52vw, 250px);
    }

    .home-map-intro__plate {
        width: min(82vw, 520px);
        right: 4%;
    }

    .home-map-intro__stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .home-about-section__visual {
        min-height: 360px;
    }

    .home-about-section__map {
        width: min(68vw, 360px);
    }
}

@media (max-width: 575.98px) {
    .home-map-intro__inner {
        padding-bottom: 205px;
    }

    .home-map-intro__title span,
    .home-map-intro__title strong {
        font-size: 48px;
    }

    .home-map-intro__stats {
        bottom: 18px;
        grid-template-columns: 1fr 1fr;
    }

    .home-map-intro__stat {
        min-height: 74px;
        padding: 12px;
    }

    .home-map-intro__stat span {
        font-size: 10px;
    }

    .home-about-section {
        padding: 72px 0;
    }

    .home-about-section__badge {
        min-width: 96px;
        padding: 10px 12px;
    }

    .home-about-section__badge strong {
        font-size: 22px;
    }
}

/* ── VỊ TRÍ CONTROLS TRÊN BẢN ĐỒ ─────────────────────────── */

/* Zoom In / Zoom Out bên trái – hạ xuống tránh header cố định */
.home-map-stage .leaflet-top.leaflet-left {
    top: 62px; /* điều chỉnh giá trị này nếu muốn cao/thấp hơn */
}

/* Layer toggle button (nút tròn xanh) bên phải – hạ xuống cùng mức */
.home-map-stage .leaflet-top.leaflet-right {
    top: 62px; /* điều chỉnh giá trị này nếu muốn cao/thấp hơn */
}

/* Mobile: giữ nguyên mức đã chỉnh */
@media (max-width: 768px) {
    .home-map-stage .leaflet-top.leaflet-left {
        top: 65px;
    }
    .home-map-stage .leaflet-top.leaflet-right {
        top: 65px;
    }
}

/* ── HEADER NỀN KHI Ở MAP (sau khi video kết thúc / bỏ qua) ── */
/* Khi map được mở khóa, header luôn hiện nền giống như khi scroll */
.header.map-header-active .header__wrapper {
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 9999;
    background: rgba(26, 109, 255, 0.9) !important;
    -webkit-backdrop-filter: blur(10px);
    backdrop-filter: blur(10px);
    transition: background 0.35s ease, box-shadow 0.35s ease, backdrop-filter 0.35s ease;
}

.header.map-header-active .header__logo,
.header.map-header-active .navbar__logo {
    -webkit-box-align: end;
    -ms-flex-align: end;
    align-items: flex-end;
    padding: 2px;
}

.header.map-header-active .header__logo img,
.header.map-header-active .navbar__logo img {
    height: 38px;
    margin: 0 calc(100% - 1.25rem) -41px 0;
}

/* --- PROJECT NAV REDESIGN --- */
.project-nav-fixed a,
.project-nav__list_custom a {
    background-color: transparent !important;
    border: 1px solid #b3ccff !important;
    color: #6699ff !important;
    font-weight: 600 !important;
    border-radius: 50px !important;
    padding: 3px 18px !important;
    transition: all 0.2s;
    text-transform: uppercase;
}
.project-nav-fixed a:hover,
.project-nav__list_custom a:hover,
.hover-pill:hover {
    background-color: #f0f5ff !important;
    color: #2f71ff !important;
}
.project-nav-fixed a.active,
.project-nav__list_custom a.active {
    background-color: #2f71ff !important;
    border-color: #2f71ff !important;
    color: #fff !important;
}

/* --- PROJECT COMPLEX SLIDER --- */
.project-complex-slider {
    position: relative;
    padding-top: 20px;
}
.project-slide-first {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    height: 100%;
}
.project-slide-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}
.project-large-card {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    min-height: 482px;
    display: flex;
    align-items: flex-end;
    padding: 30px;
}
.project-large-card__bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    transition: transform 0.5s ease;
    z-index: 0;
}
.project-large-card:hover .project-large-card__bg {
    transform: scale(1.05);
}
.project-large-card__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10, 20, 40, 0.9) 0%, rgba(10, 20, 40, 0.4) 50%, rgba(10, 20, 40, 0) 100%);
    z-index: 1;
}
.project-large-card__link {
    position: absolute;
    inset: 0;
    z-index: 3;
}
.project-large-card__content {
    position: relative;
    z-index: 2;
    color: #fff;
    width: 100%;
}
.project-large-card__badge {
    background: #ffc107;
    color: #000;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-block;
    margin-bottom: 12px;
}
.project-large-card__title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 20px;
    line-height: 1.3;
}
.project-large-card__stats {
    display: flex;
    gap: 30px;
}
.project-large-card__stats .stat-item {
    display: flex;
    flex-direction: column;
}
.project-large-card__stats .stat-item strong {
    font-size: 14px;
    color: #ffc107;
}
.project-large-card__stats .stat-item span {
    font-size: 11px;
    color: rgba(255,255,255,0.7);
}

.project-small-cards-col {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.project-small-card {
    display: flex;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    height: 150px;
    transition: transform 0.2s;
    position: relative;
}
.project-small-card:hover {
    transform: translateY(-4px);
}
.project-small-card__image {
    width: 40%;
    background-size: cover;
    background-position: center;
    flex-shrink: 0;
}
.project-small-card__content {
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    width: 60%;
}
.project-small-card__category {
    color: #1677ff;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 8px;
}
.project-small-card__title {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 16px;
    line-height: 1.4;
}
.project-small-card__title a {
    color: #1a1a1a;
    text-decoration: none;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal;
}
.project-small-card__stats {
    display: flex;
    gap: 24px;
}
.project-small-card__stats .stat-item {
    display: flex;
    flex-direction: column;
}
.project-small-card__stats .stat-item strong {
    font-size: 13px;
    color: #1677ff;
}
.project-small-card__stats .stat-item span {
    font-size: 11px;
    color: #888;
}

@media (max-width: 991.98px) {
    .project-slide-first {
        grid-template-columns: 1fr;
    }
    .project-slide-grid {
        grid-template-columns: 1fr;
    }
    .project-small-card {
        height: 120px;
    }
    .project-small-cards-col--tablet,
    .project-slide-grid--tablet {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
    }
    .project-small-card__image {
        width: 35%;
    }
    .project-small-card__content {
        width: 65%;
        padding: 12px;
    }
    .project-small-card__title {
        font-size: 14px;
        margin-bottom: 8px;
    }
    .project-small-card__stats {
        gap: 12px;
    }
    .project-small-card__stats .stat-item strong {
        font-size: 11px;
    }
    .project-large-card__title {
        font-size: 20px;
    }
}

@media (max-width: 767.98px) {
    .project-small-cards-col--tablet,
    .project-slide-grid--tablet {
        grid-template-columns: 1fr;
    }
    .project-slide-first--mobile {
        display: block;
        height: 100%;
    }
    .project-large-card {
        min-height: 440px;
        height: 100%;
    }
    .project-small-card {
        height: 130px;
    }
}
.marker-cluster-small,
.marker-cluster-medium,
.marker-cluster-large {
    background-color: rgba(255, 255, 255, 0.6) !important;
}
.marker-cluster-small div,
.marker-cluster-medium div,
.marker-cluster-large div {
    background-color: rgba(255, 255, 255, 0.9) !important;
    color: #333 !important;
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<link href="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.css" rel="stylesheet" />
<script src="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.js"></script>
<script src="https://unpkg.com/@maplibre/maplibre-gl-leaflet@0.0.21/leaflet-maplibre-gl.js"></script>
<script src="/js/industry-styles.js"></script>
<script src="/js/boundaries.js"></script>
<script src="/js/railways/railways.js"></script>
<script src="/js/railways/railway_stations.js"></script>
<script src="/js/railways/railway_depots.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation for stats
    function animateStats() {
        const statElements = document.querySelectorAll('.stat-number-animate');
        statElements.forEach(el => {
            const originalText = el.getAttribute('data-target') || el.innerText.trim();
            if (!el.getAttribute('data-target')) {
                el.setAttribute('data-target', originalText);
            }

            const match = originalText.match(/^([\d.,]+)(.*)$/);
            if (match) {
                const rawNum = match[1].replace(/,/g, '');
                const target = parseFloat(rawNum);
                if (isNaN(target)) {
                    el.style.opacity = '1';
                    return;
                }
                const suffix = match[2];
                const hasDecimal = rawNum.includes('.');

                let current = 0;
                const duration = 1000; // Tối đa 1 giây
                const stepTime = 30; 
                const steps = duration / stepTime;
                const increment = target / steps;

                // Hiển thị giá trị ban đầu là 0
                el.innerText = '0' + suffix;
                el.style.opacity = '1';

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    
                    let displayNum = hasDecimal ? current.toFixed(1) : Math.floor(current);
                    if (!hasDecimal && current >= 1000) {
                        displayNum = Math.floor(current).toLocaleString('en-US');
                    }

                    el.innerText = displayNum + suffix;
                }, stepTime);
            }
        });
    }
    animateStats();

    const introLayer = document.getElementById('mapIntroLayer');
    const startBtn = document.getElementById('startProjectIntro');
    const videoContainer = document.getElementById('video-loader-container');
    const loaderVideo = document.getElementById('loader-video');
    const skipBtn = document.getElementById('skip-video-btn');
    const introPoster = document.getElementById('intro-poster');
    const videoOverlay = document.querySelector('.home-video-intro__overlay');

    const _urlParamsCheck = new URLSearchParams(window.location.search);
    const _isSharedMapDetected = _urlParamsCheck.has('lat') && _urlParamsCheck.has('lng');
    const _shouldSkipIntro = _urlParamsCheck.has('skip_intro') || _urlParamsCheck.get('skip_intro') === '1' || _isSharedMapDetected || !!sessionStorage.getItem('pending_focus_project');

    if (loaderVideo) {
        loaderVideo.pause();
        loaderVideo.currentTime = 0;
    }

    function unlockMap(immediate) {
        const isImmediate = (immediate === true);
        if (loaderVideo) {
            loaderVideo.pause();
            loaderVideo.currentTime = 0;
        }

        if (videoContainer) {
            if (isImmediate) {
                videoContainer.style.setProperty('display', 'none', 'important');
                videoContainer.classList.remove('is-playing', 'video-fade-out');
            } else {
                videoContainer.classList.add('video-fade-out');
            }
        }
        if (introLayer) {
            if (isImmediate) {
                introLayer.style.setProperty('display', 'none', 'important');
                introLayer.classList.add('is-hidden');
            } else {
                introLayer.classList.add('is-hidden');
            }
        }
        const homePage = document.querySelector('.home-page');
        if (homePage) homePage.classList.remove('home-map-locked');

        // Xoá inline visibility:hidden đã đặt sẵn để hiển thị các cụm tìm kiếm
        const pjSearchFull = document.getElementById('pjSearchFull');
        const pjSearchMini = document.getElementById('pjSearchMini');
        if (pjSearchFull) {
            pjSearchFull.style.visibility = '';
            pjSearchFull.style.opacity = '1';
            pjSearchFull.style.pointerEvents = 'auto';
        }
        if (pjSearchMini) {
            pjSearchMini.style.visibility = '';
            pjSearchMini.style.opacity = '1';
            pjSearchMini.style.pointerEvents = 'auto';
        }

        // Bật nền header khi map được mở khóa (video xong hoặc bỏ qua)
        const $header = document.querySelector('.header');
        if ($header) {
            $header.classList.add('map-header-active');
            if (!$header.classList.contains('is-sticky')) {
                const $headerWrapper = document.querySelector('.header__wrapper');
                if ($headerWrapper) {
                    $header.style.minHeight = $headerWrapper.offsetHeight + 'px';
                }
                $header.classList.add('is-sticky');
            }
        }

        const finalizeUnlock = () => {
            if (videoContainer) {
                videoContainer.style.setProperty('display', 'none', 'important');
                videoContainer.classList.remove('is-playing', 'video-fade-out');
            }
            if (introLayer) {
                introLayer.style.setProperty('display', 'none', 'important');
            }
            if (typeof map !== 'undefined' && map) {
                map.invalidateSize();
            }

            // Nếu đang mở link chia sẻ: khôi phục chính xác toạ độ, zoom, layer và filter
            if (_isSharedMapDetected) {
                if (typeof restoreLayersAndFilterState === 'function') {
                    isInitialStateRestored = false;
                    restoreLayersAndFilterState();
                }
            }
            // Luôn đảm bảo có lớp bản đồ nền (nếu chưa có) và nạp lại marker
            if (typeof window.loadDefaultMap === 'function') {
                window.loadDefaultMap();
            }
            if (typeof window.refreshMapMarkers === 'function') {
                window.refreshMapMarkers();
            }
            window.dispatchEvent(new Event('resize'));
        };

        if (isImmediate) {
            finalizeUnlock();
        } else {
            setTimeout(finalizeUnlock, 650);
        }
    }
    window.unlockMap = unlockMap;

    if (_shouldSkipIntro) {
        unlockMap(true);
    }

    // Hàm kích hoạt phát video intro khi bấm nút Tìm hiểu dự án hoặc bấm Poster
    function handleStartProjectIntro(e) {
        if (e) {
            if (e.preventDefault) e.preventDefault();
            if (e.stopPropagation) e.stopPropagation();
        }
        
        const vContainer = document.getElementById('video-loader-container');
        const lVideo = document.getElementById('loader-video');

        if (!lVideo) {
            unlockMap();
            return;
        }

        if (vContainer) {
            vContainer.classList.add('is-playing');
        }

        const playPromise = lVideo.play();
        if (playPromise !== undefined) {
            playPromise.catch(error => {
                console.warn('Video intro play error, unlocking map directly:', error);
                unlockMap();
            });
        }
    }
    window.handleStartProjectIntro = handleStartProjectIntro;

    if (startBtn) {
        startBtn.addEventListener('click', handleStartProjectIntro);
    }

    if (introPoster) {
        introPoster.addEventListener('click', function(e) {
            handleStartProjectIntro(e);
        });
    }

    $(document).on('click', '#startProjectIntro, .home-video-intro__cta, .home-map-intro__cta, #intro-poster', function(e) {
        handleStartProjectIntro(e);
    });

    if (loaderVideo) {
        loaderVideo.onended = unlockMap;
    }

    if (skipBtn) {
        skipBtn.addEventListener('click', unlockMap);
    }
});
(function() {
'use strict';

let layerControl = null;
let _currentProjectBoundaryPolygon = null;

// Toạ độ giới hạn vùng Hà Nội (tương đối chính xác)
const hanoiBounds = L.latLngBounds(
    [20.4, 104.9],
    [21.7, 106.4]
);

// Tile layers
// Vẫn sử dụng MapTiler làm mặc định nhưng được tối ưu hóa quá trình tải tile
const defaults = L.tileLayer('https://api.maptiler.com/maps/outdoor-v2/{z}/{x}/{y}.png?key={{ config('services.maptiler.key') }}', {
        maxNativeZoom: 19,
        maxZoom: 21,
        bounds: hanoiBounds,
        noWrap: true,
        updateWhenIdle: true,       // Chỉ fetch tiles sau khi người dùng ngừng kéo bản đồ
        updateWhenZooming: false,   // Không fetch liên tục trong quá trình đang zoom
        keepBuffer: 3               // Cache nhẹ thêm các tile xung quanh để khi quay lại không cần fetch lại
    });

const streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxNativeZoom: 19,
    maxZoom: 21,
    bounds: hanoiBounds,
    noWrap: true
});

const satellite = L.tileLayer(
    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxNativeZoom: 18,
        maxZoom: 21,
        bounds: hanoiBounds,
        noWrap: true
    });
const topo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
    maxNativeZoom: 17,
    maxZoom: 21,
    bounds: hanoiBounds,
    noWrap: true
});

// MapLibre GL 3D Layer (Lazy-loaded: chỉ khởi tạo khi người dùng chủ động chọn bản đồ 3D)
let map3d = null;
const map3dPlaceholder = L.layerGroup();

function getOrCreateMap3DLayer() {
    if (map3d) return map3d;

    map3d = L.maplibreGL({
        style: 'https://api.maptiler.com/maps/streets-v2/style.json?key={{ config('services.maptiler.key') }}',
        updateInterval: 0, // Eliminate 30fps throttle to fix 3D panning jitter
        maxBounds: [[104.9, 20.4], [106.4, 21.7]] // MapLibre dùng định dạng [ [lng, lat], [lng, lat] ]
    });

    // Add 3D building extrusion when MapLibre map is ready
    map3d.on('styleload', function() {
        const glMap = map3d.getMaplibreMap();
        const style = glMap ? glMap.getStyle() : null;
        const vectorSourceId = (() => {
            if (!style || !style.sources) return null;

            const layerSource = (style.layers || []).find(layer =>
                layer['source-layer'] === 'building' && layer.source
            )?.source;

            if (layerSource) return layerSource;

            return Object.entries(style.sources).find(([, source]) =>
                source && source.type === 'vector'
                    )?.[0] || null;
        })();

        if (glMap && vectorSourceId && !glMap.getLayer('3d-buildings') && !glMap.getLayer('Building 3D')) {
            const layers = style.layers || [];
            let labelLayerId;
            for (let i = 0; i < layers.length; i++) {
                if (layers[i].type === 'symbol' && layers[i].layout['text-field']) {
                    labelLayerId = layers[i].id;
                    break;
                }
            }

            glMap.addLayer({
                    'id': '3d-buildings',
                    'source': vectorSourceId,
                    'source-layer': 'building',
                    'type': 'fill-extrusion',
                    'minzoom': 15,
                    'paint': {
                        'fill-extrusion-color': '#aaa',
                        'fill-extrusion-height': [
                            'interpolate', ['linear'],
                            ['zoom'],
                            15, 0,
                            15.05, ['get', 'render_height']
                        ],
                        'fill-extrusion-base': [
                            'interpolate', ['linear'],
                            ['zoom'],
                            15, 0,
                            15.05, ['get', 'render_min_height']
                        ],
                        'fill-extrusion-opacity': 0.6
                    }
                },
                labelLayerId
            );
        }

        // ── TRAFFIC SIMULATION ──────────────────────────────────────────
        (function initTrafficSimulation(glMap) {
            const VEHICLE_COUNT = 18;
            const MIN_ZOOM = 15;
            const PALETTE = [
                '#ffffff', '#e0e0e0', '#ffd700', '#ff5555',
                '#88ddff', '#99ee66', '#ffaa00', '#cc88ff'
            ];

            // ── Nguồn GeoJSON + layer circle (luôn hiển thị, ko cần icon) ─
            glMap.addSource('veh-src', {
                type: 'geojson',
                data: {
                    type: 'FeatureCollection',
                    features: []
                }
            });

            // Vòng ngoài (thân xe)
            glMap.addLayer({
                id: 'veh-body',
                type: 'circle',
                source: 'veh-src',
                minzoom: MIN_ZOOM,
                paint: {
                    'circle-radius': 5,
                    'circle-color': ['get', 'col'],
                    'circle-stroke-color': 'rgba(0,0,0,0.6)',
                    'circle-stroke-width': 1.2,
                    'circle-opacity': 0.92
                }
            });

            // Điểm mũi xe (hướng di chuyển) – offset nhỏ theo bearing
            glMap.addLayer({
                id: 'veh-head',
                type: 'circle',
                source: 'veh-src',
                minzoom: MIN_ZOOM,
                paint: {
                    'circle-radius': 2,
                    'circle-color': '#ffe700',
                    'circle-translate': ['literal', [4, 0]], // offset nhỏ
                    'circle-translate-anchor': 'map',
                    'circle-opacity': 0.95
                }
            });

            // ── Sinh đường theo lưới thực tế từ view hiện tại ───────────
            function makeProceduralRoads() {
                const c = glMap.getCenter();
                const lat = c.lat,
                    lng = c.lng;
                const R = 0.004; // ~400m
                const out = [];

                // Đường ngang
                for (let i = -5; i <= 5; i++) {
                    const y = lat + i * R / 4;
                    const pts = [];
                    for (let j = -6; j <= 6; j++) {
                        pts.push([lng + j * R / 5, y]);
                    }
                    out.push(pts);
                }
                // Đường dọc
                for (let i = -4; i <= 4; i++) {
                    const x = lng + i * R / 4;
                    const pts = [];
                    for (let j = -6; j <= 6; j++) {
                        pts.push([x, lat + j * R / 5]);
                    }
                    out.push(pts);
                }
                // Đường chéo nhẹ
                out.push([
                    [lng - R, lat - R * 0.4],
                    [lng - R / 2, lat - R * 0.2],
                    [lng, lat],
                    [lng + R / 2, lat + R * 0.2],
                    [lng + R, lat + R * 0.4]
                ]);
                out.push([
                    [lng - R, lat + R * 0.35],
                    [lng, lat],
                    [lng + R, lat - R * 0.35]
                ]);
                return out;
            }

            // ── Cố gắng lấy đường từ vector tiles (nếu đã load) ─────────
            function tryGetTileRoads() {
                let feats = [];
                try {
                    // MapTiler/OpenMapTiles vector source, transportation source-layer
                    feats = vectorSourceId ? glMap.querySourceFeatures(vectorSourceId, {
                        sourceLayer: 'transportation'
                    }) : [];
                } catch (e) {
                            /* source chưa sẵn sàng */ }

                if (!feats.length) {
                    // fallback: tất cả line type features
                    try {
                        feats = glMap.queryRenderedFeatures();
                    } catch (e) {}
                }

                const seen = new Set();
                const roads = [];
                for (const f of feats) {
                    if (!f.geometry) continue;
                            const lines = f.geometry.type === 'LineString' ?
                                [f.geometry.coordinates] :
                        f.geometry.type === 'MultiLineString' ?
                        f.geometry.coordinates : [];
                    for (const line of lines) {
                        if (line.length < 2) continue;
                        const key = line[0][0].toFixed(4) + line[0][1].toFixed(4);
                        if (seen.has(key)) continue;
                        seen.add(key);
                        roads.push(line);
                        if (roads.length >= 60) break;
                    }
                    if (roads.length >= 60) break;
                }
                return roads;
            }

            // ── State ────────────────────────────────────────────────────
            let roads = [];
            let fleet = [];

            function calcBrg(a, b) {
                const toR = Math.PI / 180,
                    toD = 180 / Math.PI;
                const dL = (b[0] - a[0]) * toR;
                const p1 = a[1] * toR,
                    p2 = b[1] * toR;
                const y = Math.sin(dL) * Math.cos(p2);
                const x = Math.cos(p1) * Math.sin(p2) - Math.sin(p1) * Math.cos(p2) * Math.cos(dL);
                return (Math.atan2(y, x) * toD + 360) % 360;
            }

            function segM(a, b) {
                const R = 6371000,
                    r = Math.PI / 180;
                const dLat = (b[1] - a[1]) * r,
                    dLon = (b[0] - a[0]) * r;
                const s = Math.sin(dLat / 2) ** 2 + Math.cos(a[1] * r) * Math.cos(b[1] * r) * Math.sin(
                    dLon / 2) ** 2;
                return 2 * R * Math.asin(Math.sqrt(Math.min(1, s)));
            }

            function spawn() {
                if (!roads.length) return null;
                const road = roads[Math.floor(Math.random() * roads.length)];
                if (!road || road.length < 2) return null;
                return {
                    road,
                    seg: Math.floor(Math.random() * (road.length - 1)),
                    t: Math.random(),
                    spd: 6 + Math.random() * 12, // m/s  (≈20-65 km/h)
                    col: PALETTE[Math.floor(Math.random() * PALETTE.length)]
                };
            }

            function initFleet() {
                fleet = [];
                for (let i = 0; i < VEHICLE_COUNT; i++) {
                    const v = spawn();
                    if (v) fleet.push(v);
                }
            }

            // ── Animation loop ───────────────────────────────────────────
            let rafId = null;
            let lastTs = 0;

            function tick(ts) {
                const dt = Math.min(ts - lastTs, 80);
                lastTs = ts;

                const feats = fleet.map(v => {
                    const a = v.road[v.seg],
                        b = v.road[v.seg + 1];
                    if (!a || !b) {
                        v.seg = 0;
                        v.t = 0;
                        return null;
                    }

                    const m = segM(a, b);
                    const step = m > 0 ? (v.spd * dt * 0.001) / m : 0.04;
                    v.t += step;
                    while (v.t >= 1) {
                        v.t -= 1;
                        v.seg = (v.seg + 1) % (v.road.length - 1);
                    }

                    const A = v.road[v.seg],
                        B = v.road[v.seg + 1] || A;
                    const lng = A[0] + (B[0] - A[0]) * v.t;
                    const lat = A[1] + (B[1] - A[1]) * v.t;

                    return {
                        type: 'Feature',
                        geometry: {
                            type: 'Point',
                            coordinates: [lng, lat]
                        },
                        properties: {
                            col: v.col,
                            brg: calcBrg(A, B)
                        }
                    };
                }).filter(Boolean);

                const src = glMap.getSource('veh-src');
                if (src) src.setData({
                    type: 'FeatureCollection',
                    features: feats
                });
                glMap.triggerRepaint();
                rafId = requestAnimationFrame(tick);
            }

            // ── Start / Stop ─────────────────────────────────────────────
            function start() {
                if (rafId) return;

                // Lấy đường: ưu tiên tile data, fallback procedural ngay lập tức
                const tileRoads = tryGetTileRoads();
                roads = tileRoads.length >= 5 ? tileRoads : makeProceduralRoads();

                if (!fleet.length || fleet.every(v => !v.road)) initFleet();

                lastTs = performance.now();
                rafId = requestAnimationFrame(tick);

                // Upgrade sang tile roads sau 2s (nếu chưa có)
                if (tileRoads.length < 5) {
                    setTimeout(() => {
                        const tr = tryGetTileRoads();
                        if (tr.length >= 5) {
                            roads = tr;
                            fleet = []; // respawn trên đường thực
                            initFleet();
                        }
                    }, 2000);
                }
            }

            function stop() {
                if (rafId) {
                    cancelAnimationFrame(rafId);
                    rafId = null;
                }
                const src = glMap.getSource('veh-src');
                if (src) src.setData({
                    type: 'FeatureCollection',
                    features: []
                });
                fleet = [];
                roads = [];
            }

            // ── Lifecycle ────────────────────────────────────────────────
            function check() {
                if (map3d && map.hasLayer(map3d) && map.getZoom() >= MIN_ZOOM) {
                    start();
                } else {
                    stop();
                }
            }

            // Bắt đầu ngay khi style/tiles sẵn sàng
            glMap.once('idle', () => setTimeout(check, 100));

            map.on('zoomend', () => setTimeout(check, 200));
            map.on('baselayerchange', () => setTimeout(check, 700));
            map.on('moveend', () => {
                if (!rafId) return;
                // Refresh đường khi pan
                const tr = tryGetTileRoads();
                if (tr.length >= 5) {
                    roads = tr;
                    // Re-spawn xe trên đường mới
                    fleet = fleet.map(() => spawn()).filter(Boolean);
                    while (fleet.length < VEHICLE_COUNT) {
                        const v = spawn();
                        if (v) fleet.push(v);
                    }
                } else {
                    // Tạo lại procedural nếu pan xa
                    roads = makeProceduralRoads();
                    fleet = [];
                    initFleet();
                }
            });

            // Bổ sung xe định kỳ nếu fleet thiếu
            setInterval(() => {
                if (!rafId || !roads.length) return;
                while (fleet.length < VEHICLE_COUNT) {
                    const v = spawn();
                    if (v) fleet.push(v);
                }
            }, 2500);

        })(glMap);
        // ── END TRAFFIC SIMULATION ──────────────────────────────────────
    });

    return map3d;
}

const boundaryOverlayGroup = L.layerGroup();
const railwayOverlayGroup = L.layerGroup();
const stationOverlayGroup = L.layerGroup();
const depotOverlayGroup = L.layerGroup();
let districtDisplayNames = {}; // Map of VI Name -> Localized Name

const baseLayers = {
    "{{ __('app.satellite_map') }}": satellite,
    "{{ __('app.standard_map') }}": defaults,
//    "{{ __('app.traffic_map') }}": streets,
    "{{ __('app.topo_map') }}": topo,
    "{{ __('app.map_3d') }}": map3dPlaceholder
};

// ── RAILWAY FOCUS STATE ──────────────────────────────────────────
let activeRailwayName = null;
const railwayPolylines = {}; // Phụ trợ để tìm polyline nhanh từ legend

function getRailwayLineParts(text) {
    const normalized = removeDiacritics(String(text || '')).replace(/,/g, '.');
    const match = normalized.match(/(?:so|tuyen)?\s*(\d+)\s*([a-z]?)(?:\.(\d+))?/i);
    if (!match) return null;

    return {
        main: `${match[1]}${match[2] || ''}`.toUpperCase(),
        sub: match[3] || '',
    };
}

function getRailwayExactLineKey(text) {
    const parts = getRailwayLineParts(text);
    if (!parts) return null;
    return parts.sub ? `${parts.main}.${parts.sub}` : parts.main;
}

function getRailwayLineKey(text) {
    const parts = getRailwayLineParts(text);
    if (!parts) return null;
    return parts.main === '3' && ['1', '2', '3'].includes(parts.sub) ? '3' : parts.main;
}

function isRailwayProject(project) {
    const name = removeDiacritics(project?.name || '');
    return project?.industry_number === 7 ||
        name.includes('duong sat') ||
        name.includes('dsdt') ||
        name.includes('metro');
}

function findRailwayProjectByName(railwayName) {
    const lineKey = getRailwayLineKey(railwayName);
    const projects = window.projectResults || [];
    if (!lineKey || projects.length === 0) return null;

    return projects.find(project => {
        if (Array.isArray(project.railway_lines) && project.railway_lines.length > 0) {
            const exactLineKey = getRailwayExactLineKey(railwayName);
            return project.railway_lines.some(line => {
                const selectedParts = getRailwayLineParts(line);
                return getRailwayExactLineKey(line) === exactLineKey ||
                    (!selectedParts?.sub && getRailwayLineKey(line) === lineKey);
            });
        }

        if (!isRailwayProject(project)) return false;
        return getRailwayLineKey(project.name) === lineKey;
    }) || null;
}

function rememberProjectResults(projects) {
    const merged = new Map((window.projectResults || []).map(project => [project.id, project]));
    (projects || []).forEach(project => merged.set(project.id, project));
    window.projectResults = Array.from(merged.values());
}

function openRailwayFallbackPopup(railwayName, latlng) {
            L.popup({ maxWidth: 260 })
        .setLatLng(latlng)
        .setContent(`
                    <div class="info-box" style="max-width:250px;">
                        <strong>${typeof getRailwayDisplayName === 'function' ? getRailwayDisplayName(railwayName) : railwayName}</strong><br>
                        Chưa tìm thấy dự án tương ứng trong dữ liệu đang hiển thị.
                    </div>
                `)
        .openOn(map);
}

function openRailwayProjectPopup(project, latlng) {
    showProjectBoundary(project);
            L.popup({ maxWidth: 260 })
        .setLatLng(latlng)
        .setContent(createProjectPopupContent(project))
        .openOn(map);
}

function fetchRailwayProjects(callback) {
    let lang = document.documentElement.lang || 'vn';
    if (lang === 'vi') lang = 'vn';

    $.ajax({
        url: `/${lang}/api/railway-projects`,
        method: 'GET',
        success: function(data) {
            rememberProjectResults(data);
            callback();
        },
        error: function() {
            callback();
        }
    });
}

function showRailwayProjectPopup(railwayName, latlng, boundsForSearch = null) {
    const project = findRailwayProjectByName(railwayName);
    if (project) {
        openRailwayProjectPopup(project, latlng);
        return;
    }

    if (boundsForSearch) {
        fetchRailwayProjects(function() {
            const fetchedProject = findRailwayProjectByName(railwayName);
            if (fetchedProject) {
                openRailwayProjectPopup(fetchedProject, latlng);
            } else {
                openRailwayFallbackPopup(railwayName, latlng);
            }
        });
        return;
    }

    openRailwayFallbackPopup(railwayName, latlng);
}

function collectRailwayLineParts(latlngs, parts = [], currentPart = []) {
    if (!Array.isArray(latlngs)) return parts;

    latlngs.forEach(item => {
        if (Array.isArray(item)) {
            if (currentPart.length > 0) {
                parts.push(currentPart.splice(0));
            }
            collectRailwayLineParts(item, parts, currentPart);
            return;
        }

        if (item && typeof item.lat === 'number' && typeof item.lng === 'number') {
            currentPart.push(item);
        }
    });

    if (currentPart.length > 0) {
        parts.push(currentPart.splice(0));
    }

    return parts;
}

function getRailwayPopupLatLng(polyline) {
    const parts = collectRailwayLineParts(polyline.getLatLngs()).filter(part => part.length > 0);
            const firstPoint = parts[0]?.[0] || polyline.getBounds().getCenter();

    let totalDistance = 0;
    const segments = [];

    parts.forEach(part => {
        for (let i = 1; i < part.length; i++) {
            const from = part[i - 1];
            const to = part[i];
            const distance = from.distanceTo(to);

            if (distance > 0) {
                        segments.push({ from, to, distance });
                totalDistance += distance;
            }
        }
    });

    if (totalDistance === 0 || segments.length === 0) {
        return firstPoint;
    }

    let walkedDistance = 0;
    const midpointDistance = totalDistance / 2;

    for (const segment of segments) {
        if (walkedDistance + segment.distance >= midpointDistance) {
            const ratio = (midpointDistance - walkedDistance) / segment.distance;

            return L.latLng(
                segment.from.lat + (segment.to.lat - segment.from.lat) * ratio,
                segment.from.lng + (segment.to.lng - segment.from.lng) * ratio
            );
        }

        walkedDistance += segment.distance;
    }

    return segments[segments.length - 1].to;
}

function updateRailwayStyles() {
    railwayOverlayGroup.eachLayer(layer => {
        if (!(layer instanceof L.Polyline)) return;

        const name = layer.options.railwayName;

        // Cấu hình mặc định
        let weight = 4;
        let opacity = 0.8;

        if (activeRailwayName) {
            if (name === activeRailwayName) {
                weight = 8; // Tuyến đang chọn thì đậm lên
                opacity = 1;
                layer.bringToFront();
            } else {
                weight = 3;
                opacity = 0.35; // Làm mờ các tuyến khác
            }
        }

                layer.setStyle({ weight, opacity });
    });
}

// ── RAILWAY LEGEND ────────────────────────────────────────────────
const plannedLineSuffix = " {{ __('app.planned_line') }}";

function getRailwayDisplayName(name) {
    const match = name.match(/số (\d+)/i);
    if (match) {
        const num = parseInt(match[1], 10);
        if (num >= 6) {
            return name + plannedLineSuffix;
        }
    }
    return name;
}

function buildRailwayLegend() {
    const container = document.getElementById('railway-legend-items');
    if (!container) return;
    container.innerHTML = '';
    Object.entries(railways).forEach(([name, data]) => {
        const item = document.createElement('div');
        item.className = 'railway-legend__item';
        item.style.cursor = 'pointer';
        item.innerHTML = `
                    <span class="railway-legend__swatch" style="background:${data.color};"></span>
                    <span>${getRailwayDisplayName(name)}</span>
                `;

        // Click vào tên trong legend để focus
        item.onclick = (e) => {
            e.stopPropagation();
            activeRailwayName = name;
            updateRailwayStyles();
            renderRailwayStations(name, data);
            renderRailwayDepots(name);

            const targetPoly = railwayPolylines[name];
            if (targetPoly) {
                        map.fitBounds(targetPoly.getBounds(), { padding: [50, 50] });
                showRailwayProjectPopup(name, getRailwayPopupLatLng(targetPoly), targetPoly.getBounds());
            }
        };

        container.appendChild(item);
    });
}

function showRailwayLegend() {
    const legend = document.getElementById('railway-legend');
    if (legend) {
        buildRailwayLegend();
        legend.classList.remove('legend-collapsed');
        legend.style.display = 'block';
    }
}

function hideRailwayLegend() {
    const legend = document.getElementById('railway-legend');
    if (legend) legend.style.display = 'none';
}

// Toggle thu gọn/mở rộng body legend
document.addEventListener('click', function(e) {
    if (e.target.closest('#railway-legend-toggle')) {
        const legend = document.getElementById('railway-legend');
        if (legend) legend.classList.add('legend-collapsed');
    }
    if (e.target.closest('#railway-legend-icon')) {
        const legend = document.getElementById('railway-legend');
        if (legend) legend.classList.remove('legend-collapsed');
    }
});

// ── END RAILWAY LEGEND ───────────────────────────────────────────────

function renderRailwayLayers() {
    railwayOverlayGroup.clearLayers();
    Object.entries(railways).forEach(([name, data]) => {
        const poly = L.polyline(data.coords, {
            color: data.color,
            weight: 4,
            opacity: 0.8,
            lineJoin: 'round',
            interactive: true,
            railwayName: name
        });

        railwayPolylines[name] = poly; // Lưu lại để legend có thể truy cập

        poly.bindTooltip(getRailwayDisplayName(name), {
            sticky: true,
            direction: 'top',
            className: 'railway-tooltip'
        });

        poly.on('click', function(e) {
            L.DomEvent.stopPropagation(e);

            // Nếu đã đang focus chính tuyến này, có thể toggle off hoặc giữ nguyên
            // Ở đây mặc định là chọn tuyến này
            activeRailwayName = name;
            updateRailwayStyles();

            renderRailwayStations(name, data);
            renderRailwayDepots(name);

            if (map.getZoom() < 13) {
                        map.fitBounds(this.getBounds(), { padding: [50, 50] });
            }

            showRailwayProjectPopup(name, e.latlng || this.getBounds().getCenter(), this.getBounds());
        });

        railwayOverlayGroup.addLayer(poly);
    });
}

function renderRailwayDepots(selectedName = null) {
    depotOverlayGroup.clearLayers();
    if (!selectedName || typeof railwayDepots === 'undefined') return;

    const filteredDepots = railwayDepots.filter(d => {
        return selectedName.includes(d.line) || d.line.includes(selectedName);
    });

    filteredDepots.forEach(d => {
        const poly = L.polygon(d.coords, {
            color: '#8b1d41',
            weight: 2,
            dashArray: '5, 5',
            fillColor: '#8b1d41',
            fillOpacity: 0.2,
            interactive: true
        });

        poly.bindTooltip(d.name, {
            sticky: true,
            direction: 'top',
            className: 'boundary-tooltip'
        });

        poly.bindPopup(`
                    <div class="station-popup">
                        <div class="station-popup__title">
                            <i class="fas fa-warehouse"></i> ${d.name}
                        </div>
                        <div class="station-popup__line">${d.line}</div>
                        <div class="station-popup__desc">${d.desc}</div>
                    </div>
                `);

        depotOverlayGroup.addLayer(poly);
    });

    if (!map.hasLayer(depotOverlayGroup)) {
        depotOverlayGroup.addTo(map);
    }
}

function renderBoundaryLayers() {
    if (typeof boundaries === 'undefined') return;
    boundaryOverlayGroup.clearLayers();

    Object.entries(boundaries).forEach(([nameVi, coords]) => {
        const displayName = districtDisplayNames[nameVi] || nameVi;
        const poly = L.polygon(coords, {
            color: '#1a6fc4',
            weight: 1.5,
            dashArray: '4, 4',
            fillColor: '#4a9ede',
            fillOpacity: 0.08,
            interactive: true
        });
        poly.bindTooltip(displayName, {
            sticky: true,
            direction: 'top',
            className: 'boundary-tooltip'
        });
        poly.on('mouseover', function() {
                    this.setStyle({ fillOpacity: 0.25, weight: 2.5 });
        });
        poly.on('mouseout', function() {
                    this.setStyle({ fillOpacity: 0.08, weight: 1.5 });
        });
        boundaryOverlayGroup.addLayer(poly);
    });
}

// Initial render with static data
function renderRailwayStations(selectedName = null, selectedData = null) {
    stationOverlayGroup.clearLayers();

    if (!selectedName || !selectedData) return;

    // Helper to snap a point to the nearest line coordinate
    function snapToLine(lat, lng, lineData) {
        if (!lineData || !lineData.coords) return [lat, lng];

        // Flatten coordinates to get a simple array of [lat, lng]
        let coords = lineData.coords;
        if (Array.isArray(coords[0]) && Array.isArray(coords[0][0])) {
            coords = coords.flat(1);
        }

        let nearest = [lat, lng];
        let minD = Infinity;

        for (let i = 0; i < coords.length; i++) {
            const p = coords[i];
            const d = Math.pow(p[0] - lat, 2) + Math.pow(p[1] - lng, 2);
            if (d < minD) {
                minD = d;
                nearest = p;
            }
        }
        return nearest;
    }

    const targetLine = selectedData;

    // Note: railwayStations variable is now loaded from /js/railway_stations.js
    if (typeof railwayStations === 'undefined') {
        console.error("railwayStations data not loaded");
        return;
    }

    // Filter stations that match the clicked line name
    // Logic: if selectedName is "ĐSĐT số 3.1", it matches "ĐSĐT số 3"
    const filteredStations = railwayStations.filter(st => {
        return selectedName.includes(st.line) || st.line.includes(selectedName);
    });

    filteredStations.forEach(st => {
        // Snap the coordinates to the nearest point on the line
        const snapped = snapToLine(st.lat, st.lng, targetLine);

        const icon = L.divIcon({
            className: 'railway-station-icon',
            html: '<i class="fas fa-train"></i>',
            iconSize: [26, 26],
            iconAnchor: [13, 13],
            popupAnchor: [0, -13]
        });

                const marker = L.marker(snapped, { icon: icon });

        const popupContent = `
                    <div class="station-popup">
                        <div class="station-popup__title">
                            <i class="fas fa-train"></i> ${st.name}
                        </div>
                        <div class="station-popup__line">${st.line}</div>
                        <div class="station-popup__desc">${st.desc}</div>
                    </div>
                `;

        marker.bindPopup(popupContent, {
            className: 'station-popup',
            maxWidth: 220
        });

        stationOverlayGroup.addLayer(marker);
    });

    if (!map.hasLayer(stationOverlayGroup)) {
        stationOverlayGroup.addTo(map);
    }
}

renderBoundaryLayers();
renderRailwayLayers();
// renderRailwayStations(); // Hidden by default, shown on click

const overlayLayers = {};

const defaultCenter = [21.0285, 105.8542];
const defaultZoom = 12;

// Kiểm tra tham số chia sẻ từ URL ngay từ đầu
const _sharedParams = new URLSearchParams(window.location.search);
const _hasSharedLoc = _sharedParams.has('lat') && _sharedParams.has('lng');
const _initialCenter = _hasSharedLoc ? [parseFloat(_sharedParams.get('lat')), parseFloat(_sharedParams.get('lng'))] : defaultCenter;
const _initialZoom = _hasSharedLoc ? (parseInt(_sharedParams.get('zoom')) || 12) : defaultZoom;

// Sử dụng hanoiBounds đã được định nghĩa ở trên (tại phần Tile layers)
const bounds = hanoiBounds;

// Tạo bản đồ và giới hạn vùng
const map = L.map('map', {
    center: _initialCenter, // Khởi tạo vị trí ban đầu theo link chia sẻ (nếu có)
    zoom: _initialZoom,
    layers: [], // Lazy load: không nạp tile ngay lúc mở trang mà đợi sau khi xem/bỏ qua video
    maxBounds: bounds, // Giới hạn không cho pan ra khỏi vùng này
    maxBoundsViscosity: 1.0, // 1.0 = không bao giờ cho kéo ra ngoài
    zoomControl: true, // Bật lại nút + / -
    scrollWheelZoom: true, // Bật zoom bằng chuột
    doubleClickZoom: true, // Bật zoom khi click đúp
    touchZoom: true, // Bật zoom trên màn hình cảm ứng
    boxZoom: true, // Bật zoom bằng cách vẽ hộp
    bounceAtZoomLimits: false, // Ngăn chặn pinch-zoom nảy ra ngoài min/max zoom trên mobile
    attributionControl: false,
    keyboard: false // Disable default keyboard panning to allow custom 3D rotation
});

window.map = map;

let _portalTargetMarker = null;
let _portalTargetBoundary = null;

window.portalFlyToProject = function(item) {
    if (!item || !item.lat || !item.lng) return;

    // 1. Mở khóa bản đồ ngay lập tức và ẩn video intro
    if (typeof window.unlockMap === 'function') {
        window.unlockMap(true);
    } else if (typeof unlockMap === 'function') {
        unlockMap(true);
    }
    const introLayer = document.getElementById('mapIntroLayer');
    if (introLayer) {
        introLayer.style.setProperty('display', 'none', 'important');
        introLayer.classList.add('is-hidden');
    }
    const homePage = document.querySelector('.home-page');
    if (homePage) homePage.classList.remove('home-map-locked');
    const vContainer = document.getElementById('video-loader-container');
    if (vContainer) {
        vContainer.style.setProperty('display', 'none', 'important');
        vContainer.classList.remove('is-playing', 'video-fade-out');
    }
    const lVideo = document.getElementById('loader-video');
    if (lVideo) {
        try { lVideo.pause(); lVideo.currentTime = 0; } catch(e){}
    }

    const pjSearchFull = document.getElementById('pjSearchFull');
    const pjSearchMini = document.getElementById('pjSearchMini');
    if (pjSearchFull) {
        pjSearchFull.style.visibility = '';
        pjSearchFull.style.opacity = '1';
        pjSearchFull.style.pointerEvents = 'auto';
    }
    if (pjSearchMini) {
        pjSearchMini.style.visibility = '';
        pjSearchMini.style.opacity = '1';
        pjSearchMini.style.pointerEvents = 'auto';
    }

    if (typeof window.loadDefaultMap === 'function') {
        window.loadDefaultMap();
    }
    map.invalidateSize();

    // 2. Cuộn mượt màn hình lên vị trí bản đồ
    const mapEl = document.getElementById('map');
    if (mapEl) {
        mapEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // 3. Chuẩn hóa dữ liệu dự án thành đối tượng loc chuẩn của bản đồ
    const loc = {
        id: item.project_id || item.id,
        name: item.name,
        lat: parseFloat(item.lat),
        lng: parseFloat(item.lng),
        link: item.detail_url || item.url,
        link_vrtour: item.link_vrtour || item.vr_url,
        districts: item.districts || (item.district ? [item.district] : ['TP. Hà Nội']),
        district: item.district,
        price: item.price || 0,
        detail_image: item.detail_image || item.image,
        image: item.image,
        type_number: item.type_number || 1,
        industry_number: item.industry_number || 1,
        area: item.area || null,
        unit: item.unit || 'ha',
        has_occupancy_rate: item.has_occupancy_rate || false,
        occupancy_rate: item.occupancy_rate || 0,
        is_invest: (item.is_invest !== undefined) ? item.is_invest : 0,
        boundary: item.boundary || null
    };

    // 4. Đánh dấu đang focus dự án từ thông báo để tránh fitBounds kéo lệch camera
    window._portalFocusingProject = true;
    clearTimeout(window._portalFocusResetTimer);
    window._portalFocusResetTimer = setTimeout(function() {
        window._portalFocusingProject = false;
    }, 2500);

    // Xóa marker tạm của lần click trước nếu có
    if (_portalTargetMarker) {
        try { map.removeLayer(_portalTargetMarker); } catch(e){}
        _portalTargetMarker = null;
    }

    // Tạo Marker chuẩn riêng biệt trên map để không bị mất popup khi markersLayer refresh theo bounds mới
    const activeMarker = createMarker(loc);
    activeMarker.addTo(map);
    _portalTargetMarker = activeMarker;

    // 5. Vẽ đúng đường ranh giới boundary polygon của dự án
    if (typeof showProjectBoundary === 'function') {
        showProjectBoundary(loc);
    }

    // 6. Bay camera đến đúng vị trí dự án
    map.flyTo([loc.lat, loc.lng], 16, { animate: true, duration: 1.0 });

    // 7. Active mở đúng cái popup chuẩn cũ của dự án
    setTimeout(function() {
        if (activeMarker) {
            activeMarker.openPopup();
        }
    }, 500);
};

// Kiểm tra nếu có dự án chờ focus từ trang khác chuyển sang
try {
    const pendingFocus = sessionStorage.getItem('pending_focus_project');
    if (pendingFocus) {
        sessionStorage.removeItem('pending_focus_project');
        const pItem = JSON.parse(pendingFocus);
        setTimeout(function() {
            if (typeof window.portalFlyToProject === 'function') {
                window.portalFlyToProject(pItem);
            }
        }, 250);
    }
} catch(e){}

// Bảo vệ chống can thiệp hoặc điều hướng bản đồ ra ngoài phạm vi Hà Nội
const _originalSetView = map.setView.bind(map);
const _originalFlyTo = map.flyTo.bind(map);
const _originalPanTo = map.panTo.bind(map);
const _originalFitBounds = map.fitBounds.bind(map);
const _originalSetMinZoom = map.setMinZoom.bind(map);
let _currentMinAllowedZoom = 0;

map.setView = function(center, zoom, options) {
    const latLng = L.latLng(center);
    if (!bounds.contains(latLng)) {
        console.warn('Toạ độ điều hướng nằm ngoài phạm vi Hà Nội:', latLng);
        return map;
    }
    return _originalSetView(latLng, zoom, options);
};

map.flyTo = function(center, zoom, options) {
    const latLng = L.latLng(center);
    if (!bounds.contains(latLng)) {
        console.warn('Toạ độ điều hướng nằm ngoài phạm vi Hà Nội:', latLng);
        return map;
    }
    return _originalFlyTo(latLng, zoom, options);
};

map.panTo = function(center, options) {
    const latLng = L.latLng(center);
    if (!bounds.contains(latLng)) {
        console.warn('Toạ độ điều hướng nằm ngoài phạm vi Hà Nội:', latLng);
        return map;
    }
    return _originalPanTo(latLng, options);
};

map.fitBounds = function(b, options) {
    const targetBounds = L.latLngBounds(b);
    if (!bounds.intersects(targetBounds)) {
        console.warn('Vùng bounds nằm ngoài phạm vi Hà Nội:', targetBounds);
        return map;
    }
    return _originalFitBounds(b, options);
};

map.setMinZoom = function(zoom) {
    if (zoom < _currentMinAllowedZoom) {
        return map;
    }
    return _originalSetMinZoom(zoom);
};

// Không cho phép gỡ bỏ maxBounds
map.setMaxBounds = function() {
    return map;
};

function lockMapToBounds(isInit = false) {
    const urlParams = new URLSearchParams(window.location.search);
    const hasSharedParams = urlParams.has('lat') && urlParams.has('lng');

    // Tạm mở khóa zoom
    _currentMinAllowedZoom = 0;
    map.setMinZoom(0);
    map.setMaxZoom(21);
    
    // Tính toán mức zoom sao cho vùng bounds lấp đầy (COVER) toàn bộ màn hình
    let targetZoom = map.getBoundsZoom(bounds, true);
    
    // Nếu kích thước map = 0 (đang ẩn), targetZoom có thể lỗi (Infinity), ta fallback về defaultZoom
    if (!isFinite(targetZoom)) {
        targetZoom = defaultZoom;
    }
    
    // Thiết lập giới hạn: Không cho zoom out ra ngoài targetZoom, nhưng VẪN cho zoom in (max 21)
    _currentMinAllowedZoom = hasSharedParams ? Math.min(targetZoom, 8) : targetZoom;
    map.setMinZoom(_currentMinAllowedZoom);
    map.setMaxZoom(21);
    
    // ĐẶC BIỆT: Nếu là link chia sẻ đã có toạ độ chỉ định, TUYỆT ĐỐI KHÔNG reset view về trung tâm Hà Nội!
    if (hasSharedParams) {
        return;
    }

    // Đặt lại mức zoom về targetZoom nếu:
    // 1. Là lần load bản đồ đầu tiên (isInit = true)
    // 2. Hoặc mức zoom hiện tại đang nhỏ hơn targetZoom (bị lòi ra ngoài do resize)
    try {
        if (isInit || map.getZoom() < targetZoom) {
            map.setView(bounds.getCenter(), targetZoom);
        }
    } catch (e) {
        map.setView(bounds.getCenter(), targetZoom);
    }
}

// Chạy lần đầu
lockMapToBounds(true);

// Chạy lại khi thay đổi kích thước cửa sổ hoặc browser zoom (Ctrl +/-)
window.addEventListener('resize', function() {
    lockMapToBounds(false);
});

// Đồng bộ ô tích (checkbox) trong menu chọn lớp bản đồ (Layer Control)
function syncLayerControlCheckboxes() {
    if (!layerControl) return;

    if (typeof layerControl._update === 'function') {
        layerControl._update();
    }

    const container = layerControl.getContainer();
    if (!container) return;

    const overlaysList = container.querySelectorAll('.leaflet-control-layers-overlays label');
    overlaysList.forEach(label => {
        const text = (label.textContent || '').trim();
        const checkbox = label.querySelector('input[type="checkbox"]');
        if (!checkbox) return;

        if (text.includes("{{ __('app.boundary_map') }}") || text.includes('Ranh giới') || (window.boundaryLayerName && text.includes(window.boundaryLayerName))) {
            if (typeof boundaryOverlayGroup !== 'undefined') {
                checkbox.checked = map.hasLayer(boundaryOverlayGroup);
            }
        } else if (text.includes("{{ __('app.railway_map') }}") || text.includes('đường sắt') || (window.railwayLayerName && text.includes(window.railwayLayerName))) {
            if (typeof railwayOverlayGroup !== 'undefined') {
                checkbox.checked = map.hasLayer(railwayOverlayGroup);
            }
        }
    });
}

// Hàm lazy load bản đồ mặc định – mặc định nạp bản đồ Vệ tinh và tự động bật ranh giới (nếu layer đang Hiện)
window.loadDefaultMap = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('lat') && urlParams.has('lng') && urlParams.has('basemap')) {
        return; // Không nạp mặc định đè lên link chia sẻ đã chỉ định basemap
    }

    if (typeof map !== 'undefined' && typeof satellite !== 'undefined') {
        if (!map.hasLayer(defaults) && !map.hasLayer(satellite) && !map.hasLayer(topo) && (!map3d || !map.hasLayer(map3d))) {
            satellite.addTo(map);
            if (typeof boundaryOverlayGroup !== 'undefined' && !map.hasLayer(boundaryOverlayGroup)) {
                if (window.isBoundaryLayerActive) {
                    boundaryOverlayGroup.addTo(map);
                }
            }
            syncLayerControlCheckboxes();
            setTimeout(function() {
                map.invalidateSize();
            }, 100);
        }
    }
};

// Nếu màn hình Intro không tồn tại hoặc đã bị ẩn sẵn từ trước, load mặc định ngay
const checkIntro = document.getElementById('mapIntroLayer');
if (!checkIntro || checkIntro.classList.contains('is-hidden') || checkIntro.style.display === 'none') {
    window.loadDefaultMap();
}

// Chạy lại khi người dùng đổi lớp bản đồ (VD: từ Tiêu chuẩn sang Vệ tinh hoặc 3D)
// vì Leaflet có thể tự reset minZoom/maxZoom khi đổi base layer
map.on('baselayerchange', function(e) {
    if (e && e.layer === satellite) {
        // Chỉ mặc định bật ranh giới khi ở bản đồ Vệ tinh VÀ layer đang được kích hoạt (Hiện)
        if (typeof boundaryOverlayGroup !== 'undefined' && !map.hasLayer(boundaryOverlayGroup) && window.isBoundaryLayerActive) {
            boundaryOverlayGroup.addTo(map);
        }
    } else {
        // Các bản đồ khác (Tiêu chuẩn, Địa hình, 3D) mặc định tắt ranh giới khi chuyển sang
        if (typeof boundaryOverlayGroup !== 'undefined' && map.hasLayer(boundaryOverlayGroup)) {
            map.removeLayer(boundaryOverlayGroup);
        }
    }

    syncLayerControlCheckboxes();

    if (e && e.layer === map3dPlaceholder) {
        const real3D = getOrCreateMap3DLayer();
        if (!map.hasLayer(real3D)) {
            map.addLayer(real3D);
        }
    } else if (map3d && map.hasLayer(map3d)) {
        map.removeLayer(map3d);
    }
    lockMapToBounds(false);

    if (e && e.name && typeof saveBaseLayerToSession === 'function') {
        saveBaseLayerToSession(e.name);
    }
});

// Click ra ngoài bản đồ để bỏ chọn (reset focus)
map.on('click', function() {
    activeRailwayName = null;
    updateRailwayStyles();
    stationOverlayGroup.clearLayers(); // Ẩn các nhà ga khi bỏ chọn tuyến
            depotOverlayGroup.clearLayers();   // Ẩn các depot khi bỏ chọn tuyến
    if (_currentProjectBoundaryPolygon) {
        map.removeLayer(_currentProjectBoundaryPolygon);
        _currentProjectBoundaryPolygon = null;
    }
    if (layerControl && layerControl.forceCollapse) {
        layerControl.forceCollapse();
    }
});

// Ngăn chặn sự kiện cuộn/nhấp chuột truyền từ legend xuống bản đồ
if (typeof L !== 'undefined') {
    const legendEl = document.getElementById('railway-legend');
    if (legendEl) {
        L.DomEvent.disableClickPropagation(legendEl);
        L.DomEvent.disableScrollPropagation(legendEl);
    }
}

// Auto-tilt logic for Map3D - Optimized
let tilingTicking = false;

function updateTilt() {
    if (map3d && map.hasLayer(map3d)) {
        const z = map.getZoom();
        const glMap = map3d.getMaplibreMap();
        if (glMap) {
            // Start tilting at zoom 15, max tilt (60 degrees) at zoom 19+
            let pitch = 0;
            if (z >= 15) {
                pitch = Math.min(60, (z - 15) * 15);
            }
            // Only update if pitch actually changed to save performance
            if (glMap.getPitch() !== pitch) {
                glMap.setPitch(pitch);
            }
        }
    }
    tilingTicking = false;
}

// Only update tilt on zoom to prevent lag during movement (panning)
map.on('zoomend', function() {
    if (!tilingTicking) {
        requestAnimationFrame(updateTilt);
        tilingTicking = true;
    }
});

// 3D Rotation and Tilt Controls via Keyboard & Smooth Ease
function rotate3D(bearingDelta, pitchDelta) {
    if (!map3d || !map.hasLayer(map3d)) return;
    const glMap = map3d.getMaplibreMap();
    if (!glMap) return;

    const currentBearing = glMap.getBearing();
    const currentPitch = glMap.getPitch();

    glMap.easeTo({
        bearing: currentBearing + bearingDelta,
        pitch: Math.min(85, Math.max(0, currentPitch + pitchDelta)),
        duration: 200,
        easing: (t) => t
    });
}

const redIcon = new L.Icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});


// Thêm nút reset bản đồ và định vị
let isUserOutsideHanoi = false;

const resetControl = L.control({
    position: window.innerWidth <= 1024 ? 'topright' : 'bottomright'
});
const currentLocation = L.control({
    position: window.innerWidth <= 1024 ? 'topright' : 'bottomright'
});
const fullScreenControl = L.control({
    position: window.innerWidth <= 1024 ? 'topright' : 'bottomright'
});
const shareControl = L.control({
    position: window.innerWidth <= 1024 ? 'topright' : 'bottomright'
});
const exportControl = L.control({
    position: window.innerWidth <= 1024 ? 'topright' : 'bottomright'
});

window.addEventListener('resize', () => {
    const newPosition = window.innerWidth <= 1024 ? 'topright' : 'bottomright';
    resetControl.setPosition(newPosition);
    if (!isUserOutsideHanoi) {
        currentLocation.setPosition(newPosition);
    }
    shareControl.setPosition(newPosition);
    exportControl.setPosition(newPosition);
    fullScreenControl.setPosition(newPosition);

    resetControl.remove();
    shareControl.remove();
    exportControl.remove();
    currentLocation.remove();
    fullScreenControl.remove();

    if (newPosition === 'topright') {
        // Muốn C -> B -> A trên mobile (hiển thị dưới cùng lên)
        fullScreenControl.addTo(map);
        if (!isUserOutsideHanoi) {
            currentLocation.addTo(map);
        }
        // // exportControl.addTo(map); // Tạm ẩn nút xuất dữ liệu trên bản đồ // Tạm ẩn nút xuất dữ liệu
        shareControl.addTo(map);
        resetControl.addTo(map);
    } else {
        // Muốn A -> B -> C trên web
        resetControl.addTo(map);
        shareControl.addTo(map);
        // // exportControl.addTo(map); // Tạm ẩn nút xuất dữ liệu trên bản đồ // Tạm ẩn nút xuất dữ liệu
        if (!isUserOutsideHanoi) {
            currentLocation.addTo(map);
        }
        fullScreenControl.addTo(map);
    }
});

resetControl.onAdd = function(map) {
    const btn = L.DomUtil.create('button', 'leaflet-bar leaflet-control leaflet-control-custom');
    btn.innerHTML = '<i class="fas fa-redo-alt"></i>';
            btn.title = '{{ __('app.reset_map') }}';

    btn.style.backgroundColor = 'white';
    btn.style.width = '48px';
    btn.style.height = '48px';
    btn.style.cursor = 'pointer';
    btn.style.fontSize = '18px';
    btn.style.lineHeight = '30px';
    btn.style.textAlign = 'center';
    btn.style.margin = '10px'; // Thêm khoảng cách khỏi mép dưới/phải

    L.DomEvent.disableClickPropagation(btn);

    btn.onclick = function() {
        resetMap();
    };

    return btn;
};

resetControl.addTo(map);

// Hàm hiển thị thông báo Toast nhẹ nhàng trên bản đồ
function showMapToast(message, type = 'info') {
    let container = document.getElementById('mapToastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'mapToastContainer';
        container.style.cssText = 'position:fixed;top:90px;left:50%;transform:translateX(-50%);z-index:999999;pointer-events:none;display:flex;flex-direction:column;gap:8px;align-items:center;width:max-content;max-width:90vw;';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const bgColors = {
        warning: 'linear-gradient(135deg, rgba(234, 88, 12, 0.95), rgba(194, 65, 12, 0.95))',
        error: 'linear-gradient(135deg, rgba(220, 38, 38, 0.95), rgba(185, 28, 28, 0.95))',
        success: 'linear-gradient(135deg, rgba(22, 163, 74, 0.95), rgba(21, 128, 61, 0.95))',
        info: 'linear-gradient(135deg, rgba(30, 41, 59, 0.95), rgba(15, 23, 42, 0.95))'
    };
    const icons = {
        warning: '<i class="fas fa-exclamation-triangle" style="margin-right:8px;font-size:15px;"></i>',
        error: '<i class="fas fa-times-circle" style="margin-right:8px;font-size:15px;"></i>',
        success: '<i class="fas fa-check-circle" style="margin-right:8px;font-size:15px;"></i>',
        info: '<i class="fas fa-info-circle" style="margin-right:8px;font-size:15px;"></i>'
    };

    toast.style.cssText = 'background:' + (bgColors[type] || bgColors.info) + ';' +
        'color:#ffffff;padding:11px 22px;border-radius:50px;font-size:14px;font-family:inherit;font-weight:500;' +
        'box-shadow:0 10px 25px -5px rgba(0,0,0,0.3), 0 8px 10px -6px rgba(0,0,0,0.2);' +
        'backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);display:flex;align-items:center;justify-content:center;' +
        'opacity:0;transform:translateY(-15px) scale(0.96);transition:all 0.35s cubic-bezier(0.16, 1, 0.3, 1);pointer-events:auto;';

    toast.innerHTML = (icons[type] || '') + '<span>' + message + '</span>';
    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0) scale(1)';
    });

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-15px) scale(0.96)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 350);
    }, 3200);
}

// Hàm lấy toạ độ vị trí qua IP (cứu cánh cho Mac/PC/máy tính bàn không có chip GPS vệ tinh)
function fetchLocationByIP(onSuccess, onError) {
    fetch('https://ipwho.is/')
        .then(res => res.json())
        .then(data => {
            if (data && data.success && data.latitude && data.longitude) {
                onSuccess(data.latitude, data.longitude);
            } else {
                if (typeof onError === 'function') onError();
            }
        })
        .catch(() => {
            fetch('https://freeipapi.com/api/json')
                .then(res => res.json())
                .then(data => {
                    if (data && data.latitude && data.longitude) {
                        onSuccess(data.latitude, data.longitude);
                    } else {
                        if (typeof onError === 'function') onError();
                    }
                })
                .catch(() => {
                    if (typeof onError === 'function') onError();
                });
        });
}

function applyUserLocation(lat, lng, isSilent = false) {
    const latLng = L.latLng(lat, lng);

    // Nếu người dùng không ở khu vực Hà Nội: ẩn nút định vị đi luôn và thông báo
    if (!bounds.contains(latLng)) {
        isUserOutsideHanoi = true;
        currentLocation.remove();
        if (!isSilent) {
            showMapToast('{{ __('app.location_outside_hanoi') }}', 'warning');
        }
        return false;
    }

    if (isSilent) return true;

    map.setView(latLng, 16);

    if (map._currentLocationMarker) {
        map.removeLayer(map._currentLocationMarker);
    }
    map._currentLocationMarker = L.marker(latLng, {
            icon: redIcon
        }).addTo(map)
        .bindPopup("{{ __('app.current_location') }}")
        .openPopup();

    showMapToast('{{ __('app.current_location') }}', 'success');
    return true;
}

currentLocation.onAdd = function(map) {
    const btn = L.DomUtil.create('button', 'leaflet-bar leaflet-control leaflet-control-custom');
    btn.innerHTML = '<i class="fas fa-crosshairs"></i>';
            btn.title = '{{ __('app.current_location') }}';

    btn.style.backgroundColor = 'white';
    btn.style.width = '48px';
    btn.style.height = '48px';
    btn.style.margin = '0px';
    btn.style.cursor = 'pointer';
    btn.style.fontSize = '18px';
    btn.style.lineHeight = '30px';
    btn.style.textAlign = 'center';
    btn.style.margin = '10px';
    btn.style.marginBottom = '0';

    L.DomEvent.disableClickPropagation(btn);

    btn.onclick = function() {
        btn.disabled = true;
        btn.style.opacity = '0.6';

        const handleSuccess = function(lat, lng) {
            btn.disabled = false;
            btn.style.opacity = '1';
            applyUserLocation(lat, lng, false);
        };

        const handleFail = function() {
            btn.disabled = false;
            btn.style.opacity = '1';
            showMapToast('{{ __('app.unable_to_get_current_location') }}', 'warning');
        };

        // 1. Thử lấy GPS từ trình duyệt (hoạt động tốt trên điện thoại có chip GPS)
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    handleSuccess(position.coords.latitude, position.coords.longitude);
                },
                function(err) {
                    console.warn('GPS trình duyệt không khả dụng (phổ biến trên máy tính/Mac/Brave), chuyển sang IP Fallback:', err);
                    // 2. Tự động fallback qua IP định vị cho Mac và máy tính bàn
                    fetchLocationByIP(handleSuccess, handleFail);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 4000,
                    maximumAge: 60000
                }
            );
        } else {
            // Trình duyệt không hỗ trợ Geolocation: lấy qua IP
            fetchLocationByIP(handleSuccess, handleFail);
        }
    };

    return btn;
};

// Kiểm tra ngầm vị trí qua IP lúc tải trang: nếu người dùng ở ngoài Hà Nội thì ẩn nút luôn
fetchLocationByIP(function(lat, lng) {
    applyUserLocation(lat, lng, true);
}, function() {});

if (!isUserOutsideHanoi) {
    currentLocation.addTo(map);
}

fullScreenControl.onAdd = function(map) {
    const btn = L.DomUtil.create('button', 'leaflet-bar leaflet-control leaflet-control-custom');
    btn.innerHTML = '<i class="fas fa-expand"></i>';
            btn.title = '{{ __('app.full_screen') }}';

    btn.style.backgroundColor = 'white';
    btn.style.width = '48px';
    btn.style.height = '48px';
    btn.style.margin = '0px';
    btn.style.cursor = 'pointer';
    btn.style.fontSize = '18px';
    btn.style.lineHeight = '30px';
    btn.style.textAlign = 'center';
    btn.style.margin = '10px';
    btn.style.marginBottom = '0';

    L.DomEvent.disableClickPropagation(btn);

    btn.onclick = function() {
        const mapElement = document.getElementById('map');

        if (!document.fullscreenElement) {
            if (mapElement.requestFullscreen) {
                mapElement.requestFullscreen();
            } else if (mapElement.mozRequestFullScreen) {
                /* Firefox */
                mapElement.mozRequestFullScreen();
            } else if (mapElement.webkitRequestFullscreen) {
                /* Chrome, Safari & Opera */
                mapElement.webkitRequestFullscreen();
            } else if (mapElement.msRequestFullscreen) {
                /* IE/Edge */
                mapElement.msRequestFullscreen();
            }
            btn.innerHTML = '<i class="fas fa-compress"></i>';
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
            btn.innerHTML = '<i class="fas fa-expand"></i>';
        }
    };

    // Lắng nghe sự kiện thoát full screen (ví dụ: nhấn ESC) để cập nhật lại icon
    document.addEventListener('fullscreenchange', exitHandler);
    document.addEventListener('webkitfullscreenchange', exitHandler);
    document.addEventListener('mozfullscreenchange', exitHandler);
    document.addEventListener('MSFullscreenChange', exitHandler);

    function exitHandler() {
        if (!document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document
            .msFullscreenElement) {
            btn.innerHTML = '<i class="fas fa-expand"></i>';
        }
    }

    return btn;
};

fullScreenControl.addTo(map);

shareControl.onAdd = function(map) {
    const btn = L.DomUtil.create('button', 'leaflet-bar leaflet-control leaflet-control-custom');
    btn.innerHTML = '<i class="fas fa-share-alt"></i>';
    btn.title = 'Chia sẻ bản đồ kèm bộ lọc';
    btn.id = 'mapShareBtn';
    btn.setAttribute('type', 'button');
    btn.setAttribute('aria-label', 'Chia sẻ bản đồ kèm bộ lọc');

    btn.style.backgroundColor = 'white';
    btn.style.width = '48px';
    btn.style.height = '48px';
    btn.style.cursor = 'pointer';
    btn.style.fontSize = '18px';
    btn.style.lineHeight = '30px';
    btn.style.textAlign = 'center';
    btn.style.margin = '10px';
    btn.style.marginBottom = '0';
    btn.style.borderRadius = '4px';

    L.DomEvent.disableClickPropagation(btn);
    L.DomEvent.disableScrollPropagation(btn);

    L.DomEvent.on(btn, 'click', function(e) {
        L.DomEvent.stop(e);
        shareCurrentMapState();
    });

    btn.onclick = function(e) {
        if (e && e.stopPropagation) e.stopPropagation();
        shareCurrentMapState();
    };

    return btn;
};

shareControl.addTo(map);

// MODULE G-05: NÚT XUẤT DỮ LIỆU KHÔNG GIAN BẢN ĐỒ
exportControl.onAdd = function(map) {
    const btn = L.DomUtil.create('button', 'leaflet-bar leaflet-control leaflet-control-custom');
    btn.innerHTML = '<i class="fas fa-file-export"></i>';
    btn.title = 'Xuất dữ liệu không gian (GeoJSON / CSV)';
    btn.id = 'mapExportBtn';
    btn.setAttribute('type', 'button');
    btn.setAttribute('aria-label', 'Xuất dữ liệu không gian');

    btn.style.backgroundColor = 'white';
    btn.style.width = '48px';
    btn.style.height = '48px';
    btn.style.cursor = 'pointer';
    btn.style.fontSize = '18px';
    btn.style.lineHeight = '30px';
    btn.style.textAlign = 'center';
    btn.style.margin = '10px';
    btn.style.marginBottom = '0';
    btn.style.borderRadius = '4px';

    L.DomEvent.disableClickPropagation(btn);
    L.DomEvent.disableScrollPropagation(btn);

    L.DomEvent.on(btn, 'click', function(e) {
        L.DomEvent.stop(e);
        openExportModal();
    });

    btn.onclick = function(e) {
        if (e && e.stopPropagation) e.stopPropagation();
        openExportModal();
    };

    return btn;
};

// exportControl.addTo(map); // Tạm ẩn nút xuất dữ liệu trên bản đồ

function resetMap() {
    resetProjectTab();
    resetIndustrialTab();
    if (map._currentLocationMarker) {
        map.removeLayer(map._currentLocationMarker);
        map._currentLocationMarker = null;
    }
    map.setView(defaultCenter, defaultZoom);
    isMapTriggered = true;
    applyFiltersWithBounds();
}

// Sửa Prototype của Leaflet để ngăn sự kiện di chuột tự động mở bằng JS
if (!window._patchedLeafletLayers) {
    const proto = L.Control.Layers.prototype;
    const originalEx = proto._expand;
    const originalCol = proto._collapse;

    proto._expand = function() {
        if (this._clickToOpen) originalEx.call(this);
    };
    proto._collapse = function() {
        if (this._clickToClose) originalCol.call(this);
    };
    window._patchedLeafletLayers = true;
}

// Khởi tạo bình thường (không dùng collapsed: false để tránh phá DOM Leaflet)
layerControl = L.control.layers(baseLayers, overlayLayers).addTo(map);
syncLayerControlCheckboxes();

// Reset trạng thái flag an toàn
layerControl._clickToOpen = false;
layerControl._clickToClose = false;

// Public method an toàn để map onClick bên ngoài có thể gọi force form collapse
layerControl.forceCollapse = function() {
    layerControl._clickToClose = true;
    layerControl._collapse();
    layerControl._clickToClose = false;
};

// Lắng nghe sự kiện click thay vì hover
(function setupLayerControlClick() {
    const lcContainer = layerControl.getContainer();
    const toggleBtn = lcContainer.querySelector('.leaflet-control-layers-toggle');

            if(toggleBtn) {
        L.DomEvent.on(toggleBtn, 'click', function(e) {
            L.DomEvent.stopPropagation(e);
            L.DomEvent.preventDefault(e);

            if (L.DomUtil.hasClass(lcContainer, 'leaflet-control-layers-expanded')) {
                layerControl.forceCollapse();
            } else {
                layerControl._clickToOpen = true;
                layerControl._expand();
                layerControl._clickToOpen = false;
            }
        });
    }

            L.DomEvent.on(lcContainer, 'click', function(e){
        L.DomEvent.stopPropagation(e);
    });
})();

// railwayOverlayGroup.addTo(map); 
// stationOverlayGroup.addTo(map); // Optional: if you want stations to persist toggle separately
map.on('overlayadd', function(e) {
    if (e.layer === railwayOverlayGroup || (window.railwayLayerName && e.name === window.railwayLayerName) || e.name === "{{ __('app.railway_map') }}") {
        showRailwayLegend();
    }

    // Nạp dữ liệu tự động cho các Dynamic Layers từ CMS khi người dùng tích chọn
    if (e.layer && e.layer._layerConfig && !e.layer._isLoaded) {
        const config = e.layer._layerConfig;
        const group = e.layer;
        const opts = config.layer_options || {};

        if (config.type === 'WMS') {
            if (!map.getPane('wmsPane')) {
                const wp = map.createPane('wmsPane');
                wp.style.zIndex = 350;
            }

            const wmsLayerName = opts.wms_layers || (config.url_endpoint.includes('terrestris') ? 'OSM-WMS' : '');
            const wmsLayer = L.tileLayer.wms(config.url_endpoint, {
                layers: wmsLayerName,
                format: 'image/png',
                transparent: true,
                opacity: parseFloat(opts.opacity || 0.8),
                pane: 'wmsPane',
                maxZoom: 21
            });
            group.addLayer(wmsLayer);
            group._isLoaded = true;
        } else if (config.type === 'GeoJSON' || config.type === 'WFS') {
            fetch(config.url_endpoint)
                .then(r => r.json())
                .then(geoJsonData => {
                    const geoLayer = L.geoJSON(geoJsonData, {
                        style: function(feat) {
                            return {
                                color: opts.color || '#1a6fc4',
                                fillColor: opts.fillColor || '#4a9ede',
                                fillOpacity: parseFloat(opts.opacity || 0.2),
                                weight: 2
                            };
                        },
                        onEachFeature: function(feat, layer) {
                            if (feat.properties && (feat.properties.name || feat.properties.title)) {
                                layer.bindTooltip(feat.properties.name || feat.properties.title, {
                                    sticky: true,
                                    direction: 'top',
                                    className: 'boundary-tooltip'
                                });
                            }
                        }
                    });
                    group.addLayer(geoLayer);
                    group._isLoaded = true;
                })
                .catch(err => {
                    console.error('Không thể nạp dữ liệu layer ' + config.name, err);
                });
        }
    }

    if (typeof saveOverlaysToSession === 'function') {
        saveOverlaysToSession();
    }
});
map.on('overlayremove', function(e) {
    if (e.layer === railwayOverlayGroup || (window.railwayLayerName && e.name === window.railwayLayerName) || e.name === "{{ __('app.railway_map') }}") {
        hideRailwayLegend();
    }

    if (typeof saveOverlaysToSession === 'function') {
        saveOverlaysToSession();
    }
});

// Quản lý các Layer động từ Module g
window.dynamicMapOverlays = [];
window.isBoundaryLayerActive = false;
window.isRailwayLayerActive = false;
window.boundaryLayerName = '';
window.railwayLayerName = '';

// ========================================================
// MODULE G-04: GHI NHỚ TRẠNG THÁI LAYER VÀ NÚT CHIA SẺ BẢN ĐỒ
// ========================================================
let isInitialStateRestored = false;

// Helper kiểm tra an toàn một Layer có đang bật trên bản đồ hay không
function isLayerActive(layer) {
    try {
        return !!layer && typeof map !== 'undefined' && !!map && map.hasLayer(layer);
    } catch (e) {
        return false;
    }
}

// 1. Ghi nhớ các overlay layer đang bật vào sessionStorage
function saveOverlaysToSession() {
    if (!isInitialStateRestored) return;
    const activeNames = [];
    if (isLayerActive(boundaryOverlayGroup)) {
        activeNames.push(window.boundaryLayerName || 'Ranh giới hành chính');
    }
    if (isLayerActive(railwayOverlayGroup)) {
        activeNames.push(window.railwayLayerName || 'Tuyến đường sắt đô thị');
    }
    if (window.dynamicMapOverlays && window.dynamicMapOverlays.length > 0) {
        window.dynamicMapOverlays.forEach(item => {
            if (item && isLayerActive(item.layer)) {
                activeNames.push(item.name);
            }
        });
    }
    try {
        sessionStorage.setItem('map_active_overlays', JSON.stringify(activeNames));
    } catch (e) {}
}

// 2. Ghi nhớ base map đang chọn vào sessionStorage
function saveBaseLayerToSession(name) {
    if (!isInitialStateRestored) return;
    try {
        let baseKey = 'satellite';
        if (name) {
            const n = name.toLowerCase();
            if (n.includes('vệ tinh') || n === 'satellite') baseKey = 'satellite';
            else if (n.includes('tiêu chuẩn') || n === 'defaults' || n === 'standard') baseKey = 'defaults';
            else if (n.includes('địa hình') || n === 'topo') baseKey = 'topo';
            else if (n.includes('3d') || n === 'map3d') baseKey = 'map3d';
        }
        sessionStorage.setItem('map_active_basemap', baseKey);
    } catch (e) {}
}

// 3. Khôi phục Base Layer theo tên
function applyBaseLayerByName(name) {
    if (!name) return;
    const norm = name.toLowerCase();
    let target = null;
    if (norm.includes('vệ tinh') || norm === 'satellite') {
        target = satellite;
    } else if (norm.includes('tiêu chuẩn') || norm === 'defaults' || norm === 'standard') {
        target = defaults;
    } else if (norm.includes('địa hình') || norm === 'topo') {
        target = topo;
    } else if (norm.includes('3d') || norm === 'map3d') {
        target = (typeof map3dPlaceholder !== 'undefined') ? map3dPlaceholder : null;
    }

    if (target) {
        [satellite, defaults, topo].forEach(l => {
            if (isLayerActive(l) && l !== target) map.removeLayer(l);
        });
        if (map3d && isLayerActive(map3d) && target !== map3dPlaceholder) {
            map.removeLayer(map3d);
        }
        if (!isLayerActive(target)) {
            map.addLayer(target);
        }
        syncLayerControlCheckboxes();
    }
}

// 4. Khôi phục danh sách Overlay Layers theo mảng tên
function applyActiveOverlays(targetNamesLower) {
    if (!Array.isArray(targetNamesLower)) return;

    // Ranh giới
    if (typeof boundaryOverlayGroup !== 'undefined') {
        const isMatch = targetNamesLower.some(n => n.includes('ranh giới') || n === 'boundary' || (window.boundaryLayerName && n.includes(window.boundaryLayerName.toLowerCase())));
        if (isMatch && window.isBoundaryLayerActive) {
            if (!map.hasLayer(boundaryOverlayGroup)) map.addLayer(boundaryOverlayGroup);
        } else {
            if (map.hasLayer(boundaryOverlayGroup)) map.removeLayer(boundaryOverlayGroup);
        }
    }

    // Tuyến đường sắt
    if (typeof railwayOverlayGroup !== 'undefined') {
        const isMatch = targetNamesLower.some(n => n.includes('đường sắt') || n === 'railway' || (window.railwayLayerName && n.includes(window.railwayLayerName.toLowerCase())));
        if (isMatch && window.isRailwayLayerActive) {
            if (!map.hasLayer(railwayOverlayGroup)) {
                map.addLayer(railwayOverlayGroup);
                showRailwayLegend();
            }
        } else {
            if (map.hasLayer(railwayOverlayGroup)) {
                map.removeLayer(railwayOverlayGroup);
                hideRailwayLegend();
            }
        }
    }

    // Dynamic CMS layers
    if (window.dynamicMapOverlays && window.dynamicMapOverlays.length > 0) {
        window.dynamicMapOverlays.forEach(item => {
            const isMatch = targetNamesLower.some(n => n === item.name.toLowerCase() || item.name.toLowerCase().includes(n) || n.includes(item.name.toLowerCase()));
            if (isMatch) {
                if (!map.hasLayer(item.layer)) map.addLayer(item.layer);
            } else {
                if (map.hasLayer(item.layer)) map.removeLayer(item.layer);
            }
        });
    }

    syncLayerControlCheckboxes();
}

// 5. Khôi phục trạng thái từ URL parameters hoặc sessionStorage
function restoreLayersAndFilterState() {
    if (isInitialStateRestored) return;

    const urlParams = new URLSearchParams(window.location.search);
    const hasSharedParams = urlParams.has('lat') && urlParams.has('lng');

    // --- A. Vị trí bản đồ & Base Map ---
    if (hasSharedParams) {
        const urlLat = parseFloat(urlParams.get('lat'));
        const urlLng = parseFloat(urlParams.get('lng'));
        const urlZoom = parseInt(urlParams.get('zoom')) || 12;

        if (!isNaN(urlLat) && !isNaN(urlLng)) {
            map.invalidateSize();
            map.setView([urlLat, urlLng], urlZoom);
            setTimeout(() => {
                map.invalidateSize();
                map.setView([urlLat, urlLng], urlZoom);
            }, 250);
            setTimeout(() => {
                map.invalidateSize();
                map.setView([urlLat, urlLng], urlZoom);
            }, 750);
        }

        const sharedBasemap = urlParams.get('basemap');
        if (sharedBasemap) {
            applyBaseLayerByName(sharedBasemap);
        }
    } else {
        try {
            const savedBasemap = sessionStorage.getItem('map_active_basemap');
            if (savedBasemap) {
                applyBaseLayerByName(savedBasemap);
            }
        } catch (e) {}
    }

    // --- B. Danh sách Overlays ---
    let targetOverlayNames = null;

    if (hasSharedParams && urlParams.has('layers')) {
        const layersParam = urlParams.get('layers');
        targetOverlayNames = layersParam ? layersParam.split(',').map(s => s.trim().toLowerCase()) : [];
    } else {
        try {
            const savedOverlays = sessionStorage.getItem('map_active_overlays');
            if (savedOverlays) {
                targetOverlayNames = JSON.parse(savedOverlays).map(s => (s || '').toLowerCase());
            }
        } catch (e) {}
    }

    if (targetOverlayNames !== null) {
        applyActiveOverlays(targetOverlayNames);
    }

    // --- C. Khôi phục Bộ lọc tìm kiếm từ URL ---
    if (hasSharedParams) {
        const sharedSearch = urlParams.get('search');
        const sharedDistrict = urlParams.get('district');
        const sharedIndustry = urlParams.get('industry');
        const sharedPrice = urlParams.get('price');
        const sharedTab = urlParams.get('tab');
        const sharedType = urlParams.get('type');

        if (sharedTab === 'industrial') {
            const industrialTabBtn = document.querySelector('a[href="#industrialTabContent"]') || document.getElementById('tab-industrial-btn');
            if (industrialTabBtn) industrialTabBtn.click();

            if (sharedSearch) $('#searchInputSp').val(sharedSearch);
            if (sharedDistrict) $('#districtFilterSp').val(sharedDistrict);
            if (sharedPrice) {
                $('#priceRangeSp').val(sharedPrice).trigger('input');
            }
        } else {
            if (sharedSearch) $('#searchInput').val(sharedSearch);
            if (sharedDistrict) $('#districtFilter').val(sharedDistrict);
            if (sharedIndustry) $('#industryFilter').val(sharedIndustry);
            if (sharedType) $('#typeFilter').val(sharedType);
            if (sharedPrice) {
                $('#priceRange').val(sharedPrice).trigger('input');
            }
        }

        setTimeout(() => {
            isMapTriggered = true;
            if (typeof applyFiltersWithBounds === 'function') {
                applyFiltersWithBounds();
            }
        }, 500);
    }

    isInitialStateRestored = true;
}

// 6. Hàm chia sẻ trạng thái bản đồ hiện tại (Module G-04)
function shareCurrentMapState() {
    try {
        const center = map.getCenter();
        const zoom = map.getZoom();

        // 1. Base map
        let basemap = 'satellite';
        let basemapLabel = 'Bản đồ vệ tinh';
        if (isLayerActive(defaults)) {
            basemap = 'defaults';
            basemapLabel = 'Bản đồ tiêu chuẩn';
        } else if (isLayerActive(topo)) {
            basemap = 'topo';
            basemapLabel = 'Bản đồ địa hình';
        } else if (isLayerActive(map3d)) {
            basemap = 'map3d';
            basemapLabel = 'Bản đồ 3D';
        }

        // 2. Active Overlays (loại bỏ trùng lặp)
        const overlaySet = new Set();
        if (isLayerActive(boundaryOverlayGroup)) {
            overlaySet.add(window.boundaryLayerName || 'Ranh giới hành chính Thủ đô Hà Nội');
        }
        if (isLayerActive(railwayOverlayGroup)) {
            overlaySet.add(window.railwayLayerName || 'Bản đồ tuyến đường sắt đô thị');
        }
        if (window.dynamicMapOverlays && window.dynamicMapOverlays.length > 0) {
            window.dynamicMapOverlays.forEach(item => {
                if (item && item.name && isLayerActive(item.layer)) {
                    overlaySet.add(item.name);
                }
            });
        }
        const activeOverlays = Array.from(overlaySet);

        // 3. Active Filters (an toàn tuyệt đối tránh TypeError)
        const isIndustrial = $('#projectTabContent').length > 0 && $('#projectTabContent').css('display') === 'none';
        const activeTab = isIndustrial ? 'industrial' : 'project';
        const search = String((isIndustrial ? $('#searchInputSp').val() : $('#searchInput').val()) || '').trim();
        const district = String((isIndustrial ? $('#districtFilterSp').val() : $('#districtFilter').val()) || '').trim();
        const industry = String($('#industryFilter').val() || '').trim();
        const price = String((isIndustrial ? $('#priceRangeSp').val() : $('#priceRange').val()) || '').trim();
        const type = String($('#typeFilter').val() || '').trim();

        // 4. Query parameters
        const params = new URLSearchParams();
        params.set('lat', center.lat.toFixed(5));
        params.set('lng', center.lng.toFixed(5));
        params.set('zoom', zoom);
        params.set('basemap', basemap);
        if (activeOverlays.length > 0) {
            params.set('layers', activeOverlays.join(','));
        }
        params.set('tab', activeTab);
        if (search) params.set('search', search);
        if (district && district !== 'all') params.set('district', district);
        if (industry && industry !== 'all') params.set('industry', industry);
        if (price && parseInt(price) > 0) params.set('price', price);
        if (type && type !== 'all') params.set('type', type);

        const shareUrl = window.location.origin + window.location.pathname + '?' + params.toString();

        // 5. Tóm tắt thông tin chia sẻ
        let summaryHtml = `<div>• <b>Vị trí:</b> ${center.lat.toFixed(4)}, ${center.lng.toFixed(4)} (Mức phóng to ${zoom})</div>`;
        summaryHtml += `<div>• <b>Bản đồ nền:</b> ${basemapLabel}</div>`;
        summaryHtml += `<div>• <b>Lớp dữ liệu đang bật:</b> ${activeOverlays.length > 0 ? activeOverlays.map(o => '<span class="badge bg-primary text-white mr-1" style="font-size:11px;padding:3px 6px;">' + o + '</span>').join(' ') : '<em>(Chưa bật lớp nào)</em>'}</div>`;
        
        const filterDetails = [];
        if (search) filterDetails.push(`Từ khóa: "<b>${search}</b>"`);
        if (district && district !== 'all') filterDetails.push(`Quận/huyện: <b>${district}</b>`);
        if (industry && industry !== 'all') filterDetails.push(`Ngành: <b>${industry}</b>`);
        if (type && type !== 'all') filterDetails.push(`Loại: <b>${type}</b>`);
        summaryHtml += `<div>• <b>Bộ lọc áp dụng:</b> ${filterDetails.length > 0 ? filterDetails.join(' | ') : '<em>(Bộ lọc mặc định)</em>'}</div>`;

        openShareModal(shareUrl, summaryHtml);
    } catch (err) {
        console.error('Lỗi khi chuẩn bị liên kết chia sẻ bản đồ:', err);
        const fallbackUrl = window.location.href;
        openShareModal(fallbackUrl, '<div>• <b>Liên kết hiện tại</b></div>');
    }
}

function openShareModal(shareUrl, summaryHtml) {
    $('#shareMapUrlInput').val(shareUrl);
    $('#shareStateSummary').html(summaryHtml);
    $('#shareCopySuccessMsg').removeClass('d-flex').addClass('d-none');
    $('#btnCopyShareUrl').removeClass('btn-success').addClass('btn-primary')
        .css({ 'background-color': '#1169ff', 'border-color': '#1169ff', 'color': '#ffffff' });
    $('#btnCopyShareUrlText').text('Sao chép');

    // Mở modal thông qua Bootstrap hoặc jQuery
    try {
        const modalEl = document.getElementById('shareMapModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalInstance.show();
        } else if (typeof $ !== 'undefined' && $.fn.modal) {
            $('#shareMapModal').modal('show');
        } else {
            $(modalEl).show();
        }
    } catch (e) {
        if (typeof $ !== 'undefined' && $.fn.modal) {
            $('#shareMapModal').modal('show');
        }
    }

    // Chỉ focus và bôi đen sẵn liên kết để người dùng tiện xem/sao chép khi muốn
    setTimeout(() => {
        const input = document.getElementById('shareMapUrlInput');
        if (input) {
            input.focus();
            input.select();
        }
    }, 250);
}

function copyShareUrlToClipboard() {
    const input = document.getElementById('shareMapUrlInput');
    if (!input) return;
    const url = input.value;
    input.focus();
    input.select();

    const onSuccess = function() {
        $('#shareCopySuccessMsg').removeClass('d-none').addClass('d-flex');
        $('#btnCopyShareUrl').removeClass('btn-primary').addClass('btn-success')
            .css({ 'background-color': '#16a34a', 'border-color': '#16a34a', 'color': '#ffffff' });
        $('#btnCopyShareUrlText').text('Đã sao chép');
        showMapToast('Đã sao chép liên kết chia sẻ vào bộ nhớ tạm!', 'success');

        setTimeout(() => {
            $('#btnCopyShareUrl').removeClass('btn-success').addClass('btn-primary')
                .css({ 'background-color': '#1169ff', 'border-color': '#1169ff', 'color': '#ffffff' });
            $('#btnCopyShareUrlText').text('Sao chép');
        }, 3000);
    };

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(onSuccess).catch(() => {
            try {
                document.execCommand('copy');
                onSuccess();
            } catch (err) {
                // Giữ text được select để người dùng bấm Ctrl+C
            }
        });
    } else {
        try {
            document.execCommand('copy');
            onSuccess();
        } catch (err) {
            // Giữ text được select
        }
    }
}

// Lắng nghe sự kiện click nút Sao chép trong modal và nút Chia sẻ trên bản đồ
$(document).on('click', '#btnCopyShareUrl', function(e) {
    e.preventDefault();
    copyShareUrlToClipboard();
});

$(document).on('click', '#mapShareBtn', function(e) {
    e.preventDefault();
    e.stopPropagation();
    shareCurrentMapState();
});

// ========================================================
// MODULE G-05: XUẤT DỮ LIỆU KHÔNG GIAN BẢN ĐỒ (GEOJSON / CSV)
// ========================================================
function openExportModal() {
    try {
        const isIndustrial = $('#projectTabContent').length > 0 && $('#projectTabContent').css('display') === 'none';
        const activeTab = isIndustrial ? 'industrial' : 'project';
        const search = String((isIndustrial ? $('#searchInputSp').val() : $('#searchInput').val()) || '').trim();
        const district = String((isIndustrial ? $('#districtFilterSp').val() : $('#districtFilter').val()) || '').trim();
        const industry = String($('#industryFilter').val() || '').trim();
        const price = String((isIndustrial ? $('#priceRangeSp').val() : $('#priceRange').val()) || '').trim();
        const type = String($('#typeFilter').val() || '').trim();

        const params = new URLSearchParams();
        params.set('tab', activeTab);
        if (search) params.set('search', search);
        if (district && district !== 'all') params.set('district', district);
        if (industry && industry !== 'all') params.set('industry', industry);
        if (price && parseInt(price) > 0) params.set('price', price);
        if (type && type !== 'all') params.set('type', type);

        // Lấy toạ độ bounds hiện tại của bản đồ nếu đang zoom vào khu vực cụ thể
        if (typeof map !== 'undefined' && map) {
            const b = map.getBounds();
            params.set('minLat', b.getSouth().toFixed(5));
            params.set('maxLat', b.getNorth().toFixed(5));
            params.set('minLng', b.getWest().toFixed(5));
            params.set('maxLng', b.getEast().toFixed(5));
        }

        const filterDetails = [];
        filterDetails.push(`Dữ liệu: <b>${activeTab === 'industrial' ? 'Sản phẩm KCN' : 'Dự án đầu tư'}</b>`);
        if (search) filterDetails.push(`Từ khóa: "<b>${search}</b>"`);
        if (district && district !== 'all') filterDetails.push(`Quận/huyện: <b>${district}</b>`);
        if (industry && industry !== 'all') filterDetails.push(`Ngành: <b>${industry}</b>`);
        if (type && type !== 'all') filterDetails.push(`Loại: <b>${type}</b>`);
        if (price && parseInt(price) > 0) filterDetails.push(`Mức giá: <b>${price}</b>`);

        const summaryHtml = `<div>• ${filterDetails.length > 0 ? filterDetails.join(' | ') : '<em>(Toàn bộ dự án trong vùng nhìn)</em>'}</div>`;
        $('#exportFilterSummary').html(summaryHtml);

        const kmlParams = new URLSearchParams(params);
        kmlParams.set('format', 'kml');
        $('#btnDownloadKml').attr('href', '/api/map/export?' + kmlParams.toString());

        const geoParams = new URLSearchParams(params);
        geoParams.set('format', 'geojson');
        $('#btnDownloadGeoJson').attr('href', '/api/map/export?' + geoParams.toString());

        const csvParams = new URLSearchParams(params);
        csvParams.set('format', 'csv');
        $('#btnDownloadCsv').attr('href', '/api/map/export?' + csvParams.toString());

        const modalEl = document.getElementById('exportMapModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else if (typeof $ !== 'undefined' && $.fn.modal) {
            $('#exportMapModal').modal('show');
        } else {
            $(modalEl).show();
        }
    } catch (err) {
        console.error('Lỗi khi mở modal xuất dữ liệu:', err);
    }
}

$(document).on('click', '#mapExportBtn', function(e) {
    e.preventDefault();
    e.stopPropagation();
    openExportModal();
});

$(document).on('click', '#btnDownloadGeoJson, #btnDownloadCsv, #btnDownloadKml', function() {
    showMapToast('Đang tạo và tải file dữ liệu bản đồ...', 'info');
    setTimeout(() => {
        try {
            const modalEl = document.getElementById('exportMapModal');
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            } else if (typeof $ !== 'undefined' && $.fn.modal) {
                $('#exportMapModal').modal('hide');
            }
        } catch(e){}
    }, 1000);
});

// Hàm nạp động danh mục Layer bản đồ từ CMS (Module g - Bản đồ số đa lớp)
function loadDynamicMapLayers() {
    fetch('/api/layers?t=' + Date.now())
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success' && Array.isArray(res.data)) {
                // Gỡ bỏ các overlay cũ đã add trước đó vào layerControl nếu có
                if (window.dynamicMapOverlays && window.dynamicMapOverlays.length > 0) {
                    window.dynamicMapOverlays.forEach(item => {
                        layerControl.removeLayer(item.layer);
                    });
                }
                window.dynamicMapOverlays = [];
                window.isBoundaryLayerActive = false;
                window.isRailwayLayerActive = false;

                // res.data được sắp xếp từ backend theo đúng display_order
                res.data.forEach(item => {
                    const normName = (item.name || '').toLowerCase();
                    const url = item.url_endpoint || '';
                    const isBoundary = normName.includes('ranh giới') || url.includes('/api/layers/data/boundary');
                    const isRailway = normName.includes('đường sắt') || url.includes('/api/layers/data/railway');

                    let targetLayer = null;

                    if (isBoundary) {
                        targetLayer = boundaryOverlayGroup;
                        window.isBoundaryLayerActive = true;
                        window.boundaryLayerName = item.name;

                        // Mặc định bật nếu chưa có lưu session hoặc không phải link chia sẻ
                        const _hasUrlLayers = (new URLSearchParams(window.location.search)).has('layers');
                        if (!_hasUrlLayers && map.hasLayer(satellite) && !map.hasLayer(boundaryOverlayGroup)) {
                            boundaryOverlayGroup.addTo(map);
                        }
                    } else if (isRailway) {
                        targetLayer = railwayOverlayGroup;
                        window.isRailwayLayerActive = true;
                        window.railwayLayerName = item.name;
                    } else {
                        targetLayer = L.layerGroup();
                        targetLayer._layerConfig = item;
                        targetLayer._isLoaded = false;
                    }

                    // Thêm vào layer control theo đúng thứ tự của CMS
                    layerControl.addOverlay(targetLayer, item.name);
                    window.dynamicMapOverlays.push({ layer: targetLayer, name: item.name });
                });

                // Nếu layer Ranh giới bị Ẩn (is_active=false), gỡ khỏi map
                if (!window.isBoundaryLayerActive && map.hasLayer(boundaryOverlayGroup)) {
                    map.removeLayer(boundaryOverlayGroup);
                }

                // Nếu layer Đường sắt bị Ẩn (is_active=false), gỡ khỏi map & ẩn legend
                if (!window.isRailwayLayerActive && map.hasLayer(railwayOverlayGroup)) {
                    map.removeLayer(railwayOverlayGroup);
                    hideRailwayLegend();
                }

                syncLayerControlCheckboxes();

                // Khôi phục trạng thái từ URL parameters hoặc sessionStorage
                restoreLayersAndFilterState();
            }
        })
        .catch(e => {
            console.warn('Không thể nạp danh mục dynamic map layers:', e);
            restoreLayersAndFilterState();
        });
}

// Tự động đồng bộ lại lớp bản đồ khi người dùng chuyển tab quay lại trang
window.addEventListener('focus', function() {
    loadDynamicMapLayers();
});

// Khởi tạo nạp layer động
loadDynamicMapLayers();

let markersLayer = L.markerClusterGroup();
let allDistricts = [];
let allDistrictsLoaded = false;
let boundaryPolygon = null;
let currentDistrict = null;
let isMapTriggered = false;

function removeDiacritics(str) {
    return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
}

function getTypeName(typeNumber) {
    const types = {
        1: "{{ __('app.public_private_partnership') }}",
        2: "{{ __('app.off_budget_capital') }}",
        3: "{{ __('app.public_investment') }}",
    };
    return types[typeNumber] || "{{ __('app.unknown') }}";
}

// Lấy ngôn ngữ hiện tại
const currentLang = (window.APP_LANG || document.documentElement.lang || navigator.language || 'vi').toLowerCase();
const locale = (currentLang.startsWith('vi') || currentLang.startsWith('vn')) ?
    'vi-VN' :
    'en-US';

// Format số chung (area, length, ...)
const numberFormatter = new Intl.NumberFormat(locale, {
    minimumFractionDigits: 0,
    maximumFractionDigits: 2
});

function fmtNumber(value) {
    if (value === null || value === undefined || value === '') return '';
    const n = Number(value);
    if (!isFinite(n)) return '';
    return numberFormatter.format(n);
}

// Format riêng cho giá trị tiền (tỷ đồng)
function fmtPrice(value) {
            if (value === null || value === undefined || value === '') return '{{ __('app.no_price') }}';
    const n = Number(value);
            if (!isFinite(n)) return '{{ __('app.no_price') }}';
    return numberFormatter.format(n);
}

function createDropIcon(bgColor = "#2f71ff", imgUrl = "") {
    return L.divIcon({
        className: "custom-drop-marker",
        html: `
                    <svg width="32" height="46" viewBox="0 0 32 46" xmlns="http://www.w3.org/2000/svg">
                        <!-- Hình giọt nước -->
                        <path d="M16 0C7.2 0 0 7.5 0 16.7C0 28.3 16 46 16 46C16 46 32 28.3 32 16.7C32 7.5 24.8 0 16 0Z" fill="${bgColor}" />
                        <!-- Vòng trắng bên trong -->
                        <circle cx="16" cy="17" r="10" fill="white" />
                        <!-- Ảnh chèn vào -->
                        <image href="${imgUrl}" x="9" y="10" height="14" width="14" preserveAspectRatio="xMidYMid meet"/>
                    </svg>
                `,
        iconAnchor: [16, 46],
        popupAnchor: [0, -46]
    });
}

function createProjectPopupContent(loc) {
    const detailUrl = loc.link;
    const tourUrl = loc.link_vrtour;
    let tourButtonHtml = '';
    if (tourUrl) {
        if (tourUrl !== 'null' && tourUrl.trim() !== '') {
            tourButtonHtml =
                `<a href="${tourUrl}" target="_blank" class="btn btn-sm btn-secondary text-white">{{ __('app.virtual_tour') }}</a>`;
        }
    }

    const districtText = Array.isArray(loc.districts) ?
        loc.districts.join(", ") :
        loc.district || "{{ __('app.unknown') }}";

    const priceText = fmtPrice(loc.price);

    const imageUrl = `${window.location.origin}${loc.detail_image}`;

    // Xử lý diện tích/chiều dài
    let areaHtml = '';
    if (loc.area !== null && loc.area !== undefined && loc.area !== '') {
        const areaText = fmtNumber(loc.area);
        if (loc.unit === 'ha') {
            areaHtml = `{{ __('app.area') }}: ${areaText} ha<br>`;
        } else if (loc.unit === 'km') {
            areaHtml = `{{ __('app.length') }}: ${areaText} km<br>`;
        }
    }
    
    let occupancyHtml = '';
    if (loc.has_occupancy_rate) {
        occupancyHtml = `{{ __('app.occupancy_rate') }}: ${loc.occupancy_rate || 0}%<br>`;
    }
    
    let investStatusHtml = '';
    if (loc.is_invest == 1) investStatusHtml = '{{ __('app.status') }}: {{ __('app.projects_with_investors') }}<br>';
    else if (loc.is_invest == 2) investStatusHtml = '{{ __('app.status') }}: {{ __('app.projects_proposed') }}<br>';
    else investStatusHtml = '{{ __('app.status') }}: {{ __('app.projects_calling_for_investment') }}<br>';

    return `
                <div class='info-box' style="max-width:250px;">
                    <img src="${imageUrl}" alt="${loc.name}" style="width:100%; height:120px; object-fit:cover; border-radius:6px; margin-bottom:8px;">
                    <strong>${loc.name}</strong><br>
                    {{ __('app.investment_form') }}: ${getTypeName(loc.type_number)}<br>
                    {{ __('app.zone') }}: ${districtText}<br>
                    {{ __('app.investment_scale') }}: ${priceText} {{ __('app.billion_vnd') }}<br>
                    ${areaHtml}
                    ${occupancyHtml}
                    ${investStatusHtml}
                    <div style="margin-top:10px; display:flex; gap:8px; justify-content:flex-end;">
                        ${tourButtonHtml}
                        <a href="${detailUrl}" target="_blank" class="btn btn-sm btn-primary text-white">{{ __('app.information') }}</a>
                    </div>
                </div>
            `;
}

function showProjectBoundary(loc) {
    if (!loc.boundary) return;

    if (_currentProjectBoundaryPolygon) {
        map.removeLayer(_currentProjectBoundaryPolygon);
        _currentProjectBoundaryPolygon = null;
    }

    try {
        const coords = JSON.parse(loc.boundary);
        if (coords && coords.length > 0) {
            _currentProjectBoundaryPolygon = L.polygon(coords, {
                color: '#e65100',
                weight: 2,
                dashArray: '5, 5',
                fillColor: '#ffa726',
                fillOpacity: 0.2,
                interactive: true
            }).addTo(map);

            if (_currentProjectBoundaryPolygon) {
                _currentProjectBoundaryPolygon.bindTooltip(loc.name, {
                    sticky: true,
                    direction: 'top',
                    className: 'boundary-tooltip'
                });
            }
        }
    } catch (e) {
        console.error("Lỗi vẽ toạ độ boundary dự án:", e);
    }
}

function createMarker(loc) {
    const style = industryStyles[loc.industry_number];

    let marker;
    if (style) {
        let markerColor = style.color;
        if (loc.is_invest == 1) markerColor = "#d9534f"; // Đã có CĐT (Đỏ)
        else if (loc.is_invest == 2) markerColor = "#ffc107"; // Đề xuất (Vàng)
        else markerColor = "#28a745"; // Đang kêu gọi (Xanh lá cây)

        marker = L.marker([loc.lat, loc.lng], {
            icon: createDropIcon(markerColor, style.icon)
        });
    } else {
        // fallback mặc định
        marker = L.marker([loc.lat, loc.lng]);
    }

    marker.bindPopup(createProjectPopupContent(loc));

    if (loc.boundary) {
        marker.on('click', function() {
            showProjectBoundary(loc);
        });
    }

    return marker;
}

function loadMarkers(data, triggeredBySearch = false) {
    markersLayer.clearLayers();

    const filtered = data.filter(loc => {
        if (!loc.lat || !loc.lng) return false;
        const lat = parseFloat(loc.lat);
        const lng = parseFloat(loc.lng);
        if (isNaN(lat) || isNaN(lng)) return false;
        return hanoiBounds.contains([lat, lng]);
    });
    const markers = filtered.map(loc => createMarker(loc));

    if (markers.length === 0) return;

    markersLayer.addLayers(markers);
    if (!map.hasLayer(markersLayer)) {
        map.addLayer(markersLayer);
    }
    if (!triggeredBySearch || window._portalFocusingProject) return;

    if (markers.length === 1) {
        const latLng = markers[0].getLatLng();
        if (hanoiBounds.contains(latLng)) {
            map.flyTo(latLng, 16); // hoặc 15 tuỳ layout
        }
    } else {
        const group = new L.featureGroup(markers);
        const groupBounds = group.getBounds();

        if (hanoiBounds.contains(groupBounds)) {
            if (!map.getBounds().contains(groupBounds)) {
                map.fitBounds(groupBounds, {
                    padding: [50, 50],
                    maxZoom: 16
                });
            } else {
                // Nếu đã nằm trong màn hình, chỉ pan nhẹ đến giữa
                map.panTo(groupBounds.getCenter());
            }
        }
    }
}

function drawDistrictBoundary(districtName) {
    if (boundaryPolygon) {
        map.removeLayer(boundaryPolygon);
        boundaryPolygon = null;
    }

    if (districtName === "all") return;

    // `districtName` currently could be the translated name or the VI name.
    // When selecting from dropdown, we'll try to pass the VI name as data-value.
    // If it's passed directly as translated name, let's map it back to VI name.
    let viName = districtName;

    // Reverse lookup if the name isn't found in boundaries
    if (!boundaries[viName]) {
        const foundEntry = Object.entries(districtDisplayNames).find(([vi, loc]) => loc === viName);
        if (foundEntry) {
            viName = foundEntry[0];
        }
    }

    if (!boundaries[viName]) return;

    boundaryPolygon = L.polygon(boundaries[viName], {
        color: "blue",
        weight: 2,
        dashArray: "5, 5",
        fill: false
    }).addTo(map);

    map.flyToBounds(boundaryPolygon.getBounds(), {
        duration: 0.5,
        easeLinearity: 0.5
    });
}

function applyFiltersWithBounds() {
    const triggeredByMap = isMapTriggered;
    isMapTriggered = false;

    const bounds = map.getBounds();
    const activeTab = $('#projectTabContent').css('display') === 'none' ? 'industrial' : 'project';
    const selectedDistrict = activeTab === 'industrial' ? $('#districtFilterSp').val() : $('#districtFilter').val();
    const searchTerm = activeTab === 'industrial' ? $('#searchInputSp').val() : $('#searchInput').val();
    const priceRange = activeTab === 'industrial' ? $('#priceRangeSp').val() : $('#priceRange').val();
    const projectId = $('#project_id').val();
    const productType = $('#product_type').val();
    const selectedType = $('#typeFilter').val();
    const industryFilter = $('#industryFilter').val();

    if (activeTab === 'industrial') {
        const hasFilters = [
            selectedDistrict,
            searchTerm,
            projectId !== "all" ? true : null,
            parseInt(priceRange) > 0 ? true : null,
            productType !== "all" ? true : null
        ].some(val => val && val !== "");

        if (!hasFilters && !triggeredByMap) {
            return;
        }
    }

    const params = {
        minLat: bounds.getSouth(),
        maxLat: bounds.getNorth(),
        minLng: bounds.getWest(),
        maxLng: bounds.getEast(),
        tab: activeTab,
        ...(activeTab === 'industrial' ? {
            district: selectedDistrict,
            search: searchTerm,
            project_id: projectId,
            price: priceRange,
            product_type: productType
        } : {
            type: selectedType,
            district: selectedDistrict,
            search: searchTerm,
            price: priceRange,
            industry: industryFilter
        })
    };
    let lang = document.documentElement.lang || 'vn';
    if (lang === 'vi') lang = 'vn';

    $.ajax({
        url: `/${lang}/map/bounds`,
        method: 'GET',
        data: params,
        success: function(data) {
            rememberProjectResults(data);
            loadMarkers(data, !triggeredByMap);
            const allIndustrial = [];
            data.forEach(project => {
                (project.industrial || []).forEach(item => {
                    allIndustrial.push({
                        ...item,
                        project_name: project.name,
                        link: item.link || `/chi-tiet/${item.id}`
                    });
                });
            });

            window.industrialResults = allIndustrial;
            renderList(1);

            if (activeTab === "industrial" && !triggeredByMap) {
                $('#filterResultModal').modal('show');
            }

            if (selectedDistrict && selectedDistrict !== "all") {
                if (selectedDistrict !== currentDistrict) {
                    drawDistrictBoundary(selectedDistrict);
                    currentDistrict = selectedDistrict;
                }
            } else if (boundaryPolygon) {
                map.removeLayer(boundaryPolygon);
                boundaryPolygon = null;
                currentDistrict = null;
            }
        },
        error: function(err) {
            console.error("{{ __('app.error_loading_data') }}:", err);
        }
    });
}

function renderList(page = 1) {
    const itemsPerPage = 4;
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const allItems = window.industrialResults || [];
    const items = allItems.slice(start, end);
    const $resultList = $("#resultList");

    if (allItems.length === 0) {
        resultList.innerHTML = `
                            <li class="list-group-item text-muted justify-content-center">
                                {{ __('app.no_matching_results') }}
                            </li>
                        `;
        $('#pagination').empty();
        return;
    }

    const labels = {
                projects: '{{ __('app.projects') }}',
                code: '{{ __('app.code') }}',
                area: '{{ __('app.area') }}',
                projectTypes: '{{ __('app.project_types') }}',
                unknown: '{{ __('app.unknown') }}',
                location: '{{ __('app.location') }}',
                nameProject: '{{ __('app.name_project') }}',
                lot: '{{ __('app.lot') }}',
    };

    resultList.innerHTML = items.map(item => `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${item.intended_use ?? labels.unknown}</strong><br>
                        <small>${labels.projects}: ${item.project_name}</small> - ${labels.code}: ${item.code.replace(/^cmss_/, '')}<br>
                        <small>${labels.nameProject}: ${labels.lot} ${item.code.replace(/^cmss_/, '') ?? labels.unknown} - ${labels.area}: ${item.acreage ?? labels.unknown} ${item.unit} - ${labels.projectTypes}: ${item.product_type_name ?? labels.unknown}</small>
                    </div>
                    <a href="${item.link}" target="_blank" class="btn custom-btn btn-sm">${labels.location}</a>
                </li>
            `).join('');

    renderPagination(page);
}


function renderPagination(currentPage) {
    const itemsPerPage = 5;
    const totalItems = (window.industrialResults || []).length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const $pagination = $("#pagination");
    pagination.innerHTML = "";

    if (totalPages <= 1) return;

    function createPageBtn(label, pageNum, active = false) {
        const $li = $("<li></li>").addClass(`page-item ${active ? "active" : ""}`).html(
            `<button class="page-link" ${active ? "disabled" : ""}>${label}</button>`);
        if (!active && pageNum !== null) {
            $li.on("click", () => renderList(pageNum));
        }
        $("#pagination").append($li);
    }

    // Previous
    const prevPage = currentPage === 1 ? totalPages : currentPage - 1;
    createPageBtn("«", prevPage);

    if (totalPages >= 5) {
        // Looping pagination
        const loopPages = [];
        for (let i = -2; i <= 2; i++) {
            let page = ((currentPage - 1 + i + totalPages) % totalPages) + 1;
            if (!loopPages.includes(page)) loopPages.push(page); // tránh trùng
        }
        loopPages.forEach(p => {
            createPageBtn(p, p, p === currentPage);
        });
    } else {
        // Hiển thị tất cả nếu < 5
        for (let i = 1; i <= totalPages; i++) {
            createPageBtn(i, i, i === currentPage);
        }
    }

    // Next
    const nextPage = currentPage === totalPages ? 1 : currentPage + 1;
    createPageBtn("»", nextPage);
}

function loadAllDistricts() {
    let lang = document.documentElement.lang || 'vn';
    if (lang === 'vi') lang = 'vn';
    $.ajax({
        url: `/${lang}/api/districts`,
        method: 'GET',
        success: function(res) {
            // Extract objects for the search dropdown containing both display and VI names
                    allDistricts = res.map(d => ({ name: d.name, name_vi: d.name_vi })).sort((a,b) => a.name.localeCompare(b.name));

            // Merge boundaries from DB and store display names
            res.forEach(d => {
                // Store the translation mapping
                if (d.name_vi) {
                    districtDisplayNames[d.name_vi] = d.name;
                }

                if (d.boundary && d.name_vi) {
                    boundaries[d.name_vi] = d.boundary;
                }
            });

            // Refresh the map overlay with localized names and updated boundaries
            renderBoundaryLayers();

            allDistrictsLoaded = true;
        },
        error: function(err) {
            console.error("{{ __('app.error_loading_districts') }}:", err);
        }
    });
}
// PRICE RANGE
$('#priceRange').on("input", function() {
    $('#priceValue').text(parseInt($(this).val()).toLocaleString('vi-VN'));
});

let priceTimeout = null;
$('#priceRange').on("change", function() {
    clearTimeout(priceTimeout);
    priceTimeout = setTimeout(applyFiltersWithBounds, 500);
});

$('#priceRangeSp').on("input", function() {
    $('#priceValueSp').text(parseInt($(this).val()).toLocaleString('vi-VN'));
});
let priceSpTimeout = null;
$('#priceRangeSp').on("change", function() {
    clearTimeout(priceSpTimeout);
    priceSpTimeout = setTimeout(applyFiltersWithBounds, 500);
});

$('#typeFilter, #districtFilter, #industryFilter').on("change", applyFiltersWithBounds);
$('#project_id, #districtFilterSp, #product_type').on("change", applyFiltersWithBounds);
$('#applyBtn,#applyBtnSp').on("click", applyFiltersWithBounds);

// --- DROPDOWN QUẬN ---
function renderDistrictDropdown(filtered = []) {
    const activeTab = $('#projectTabContent').css('display') === 'none' ? 'industrial' : 'project';
    const dropdown = activeTab === 'industrial' ? $('#districtDropdownSp') : $('#districtDropdown');
    dropdown.empty();

    if (!allDistrictsLoaded) {
                dropdown.append('<div class="px-3 py-2 text-gray-400 italic">{{ __('app.loading') }}</div>');
        return;
    }

    if (filtered.length === 0) {
                dropdown.append('<div class="px-3 py-2 text-gray-500">{{ __('app.no_matching_results') }}</div>');
        return;
    }

    filtered.forEach(d => {
                dropdown.append(`<div class="px-3 py-2 hover-options" data-value="${d.name_vi}" data-display="${d.name}">${d.name}</div>`);
    });
    dropdown.show();
}

$('#districtFilter').on('input', function() {
    const keyword = removeDiacritics($(this).val());
    const filtered = allDistricts.filter(d => removeDiacritics(d.name).includes(keyword));
    $('.custom_tabs').addClass('position-custom');
    renderDistrictDropdown(filtered);
});

$(document).on('click', '#districtDropdown div.hover-options', function() {
    const val = $(this).data('value'); // name_vi
    const display = $(this).data('display'); // translated name
    $('#districtFilter').val(display); // Show translated name in input
    $('#districtFilter').attr('data-real-value', val); // However mapping will still pass original value safely
    $('#districtDropdown').hide();
    $('.custom_tabs').removeClass('position-custom');

    // We pass the display name to applyFiltersWithBounds, or let it read from input
    // But MapController filter logic currently assumes localized name since it uses getDistricts API.
    // Wait, does MapController filtering use 'district' => translated name or VI name?
    applyFiltersWithBounds();
});

$(document).on('click', function(e) {
    if (!$(e.target).closest('.pj-search__col').length) {
        $('#districtDropdown').hide();
        $('.custom_tabs').removeClass('position-custom');
    }
});

$('#districtFilterSp').on('input', function() {
    const keyword = removeDiacritics($(this).val());
    const filtered = allDistricts.filter(d => removeDiacritics(d.name).includes(keyword));
    $('.custom_tabs').addClass('position-custom');
    renderDistrictDropdown(filtered);
});

$(document).on('click', '#districtDropdownSp div.hover-options', function() {
    const val = $(this).data('value');
    const display = $(this).data('display');
    $('#districtFilterSp').val(display);
    $('#districtFilterSp').attr('data-real-value', val);
    $('#districtDropdownSp').hide();
    $('.custom_tabs').removeClass('position-custom');
    applyFiltersWithBounds();
});

$(document).on('click', function(e) {
    if (!$(e.target).closest('.pj-search__col').length) {
        $('#districtDropdownSp').hide();
        $('.custom_tabs').removeClass('position-custom');
    }
});


// Hàm hỗ trợ gọi lại nạp marker an toàn sau khi mở khóa bản đồ
window.refreshMapMarkers = function() {
    if (typeof map === 'undefined' || !map) return;
    if (typeof markersLayer !== 'undefined' && markersLayer && !map.hasLayer(markersLayer)) {
        map.addLayer(markersLayer);
    }
    if (typeof applyFiltersWithBounds === 'function') {
        isMapTriggered = true;
        applyFiltersWithBounds();
    }
};

// MAP MOVE
let mapMoveTimeout = null;
map.on('moveend zoomend', function() {
    isMapTriggered = true;
    clearTimeout(mapMoveTimeout);
    // Tăng thời gian chờ (debounce) lên 800ms để giảm tải số lần render/fetch marker khi vuốt thả bản đồ
    mapMoveTimeout = setTimeout(applyFiltersWithBounds, 800);
});

map.whenReady(function() {
    loadAllDistricts(); // tải districts ngay khi map load
    applyFiltersWithBounds(); // tải marker ngay từ đầu

    $('#openDropdown').on('click', function() {
        const dropdown = $('#districtDropdown');
        const customTabs = $('.custom_tabs');

        if (dropdown.is(':visible')) {
            dropdown.hide();
            customTabs.removeClass('position-custom');
        } else {
            renderDistrictDropdown(allDistricts);
            customTabs.addClass('position-custom');
        }
    });
    $('#openDropdownSp').on('click', function() {
        const dropdown = $('#districtDropdownSp');
        const customTabs = $('.custom_tabs');

        if (dropdown.is(':visible')) {
            dropdown.hide();
            customTabs.removeClass('position-custom');
        } else {
            renderDistrictDropdown(allDistricts);
            customTabs.addClass('position-custom');
        }
    });
});
// Reset các tab
function resetProjectTab() {
    $('#searchInput').val('');
    $('#districtFilter').val('');
    $('#typeFilter').val('all');
    $('#industryFilter').val('all');
    $('#priceRange').val(0);
    $('#districtDropdown').hide();
}

function resetIndustrialTab() {
    $('#searchInputSp').val('');
    $('#districtFilterSp').val('');
    $('#project_id').val('all');
    $('#product_type').val('all');
    $('#priceRangeSp').val(0);
    $('#districtDropdownSp').hide();
}

function showTab(tab) {
    // Nút
    $('#projectTab').removeClass('active');
    $('#industrialTab').removeClass('active');

    // Nội dung
    $('#projectTabContent').hide();
    $('#industrialTabContent').hide();

    if (tab === 'project') {
        resetMap();
        $('#projectTab').addClass('active');
        $('#projectTabContent').show();
    } else {
        resetMap();
        $('#industrialTab').addClass('active');
        $('#industrialTabContent').show();
    }
}
window.showTab = showTab;
})();
</script>
<script>
$(document).ready(function() {
    const $miniBox = $("#pjSearchMini"); // cụm bé
    const $fullBox = $("#pjSearchFull"); // cụm to
    const $inputMini = $miniBox.find("input[type=text]"); // input trong cụm bé

    let ignoreNextDocClick = false;

    // Trạng thái ban đầu
    $fullBox.hide().addClass("fade-slide");
    $miniBox.show();
    $miniBox.addClass("opacity-minibox");
    if ($(window).width() <= 768) {
        $('#projectTabContentMini').css('maxWidth', '100%');
    } else {
        $('#projectTabContentMini').css('maxWidth', '70%');
    }

    // Khi focus vào input bé → hiện cụm to
    $inputMini.on("focus", function() {
        $miniBox.hide();
        $fullBox.show();

        requestAnimationFrame(() => {
            $fullBox.addClass("show");
        });

        ignoreNextDocClick = true;
    });

    // Click ngoài → đóng cụm to, hiện lại cụm bé
    $(document).on("click", function(e) {
        if (ignoreNextDocClick) {
            ignoreNextDocClick = false;
            return;
        }

        if (
            !$fullBox.is(e.target) &&
            $fullBox.has(e.target).length === 0 &&
            !$miniBox.is(e.target) &&
            $miniBox.has(e.target).length === 0
        ) {
            closeFullForm();
        }
    });

    function closeFullForm() {
        $fullBox.removeClass("show").hide();
        $miniBox.show();
    }
});
$(document).ready(function() {
    const popup = $('#homePopup');
    const closeBtn = $('#closePopup');
    const popupBody = $('#popupBody');
    const popupLink = $('#popupLink');

    // Danh sách popup (ảnh + link)
    const popups = [
                @foreach ($popups as $popup)
                    {
            image: "{{ asset($popup->image) }}",
            link: "{{ $popup->link }}"
        },
        @endforeach
    ];

    // Hiện popup nếu chưa tắt
    if (!localStorage.getItem('home_popup_closed') && popups.length > 0) {
        let current = 0;

        function showPopup(index) {
            const item = popups[index];
            popupLink.css('background-image', 'url(' + item.image + ')');
            popupLink.attr('href', item.link);
            popup.css('display', 'flex').hide().fadeIn(300);
        }

        showPopup(current);

        // Nếu có nhiều popup thì tự chuyển
        if (popups.length > 1) {
            setInterval(() => {
                current = (current + 1) % popups.length;
                popupBody.fadeOut(200, function() {
                    const item = popups[current];
                    popupLink.css('background-image', 'url(' + item.image + ')');
                    popupLink.attr('href', item.link);
                    popupBody.fadeIn(300);
                });
            }, 4000);
        }

        // Đóng popup
        closeBtn.on('click', function(e) {
            e.preventDefault();
            popup.fadeOut(200);
            sessionStorage.setItem('home_popup_closed', 'true');
        });
    }

    // Handle AJAX click for project categories
            $(document).on('click', '.project-nav-fixed a, .project-nav-scroll a', function (e) {
        var href = $(this).attr('href');
        if (href && href.indexOf('#investment-section') !== -1) {
            e.preventDefault();

            var $wrapper = $('#project-slider-wrapper');

            // Add loading state
            $wrapper.css('opacity', '0.5');

            // Update active classes
            $('.project-nav-fixed a, .project-nav-scroll a').removeClass('active');
            $(this).addClass('active');

            $.ajax({
                url: href,
                type: 'GET',
                        data: { ajax_project_slider: 1 },
                        success: function (response) {
                    $wrapper.html(response);
                    $wrapper.css('opacity', '1');

                    var $sliderContainers = $('#project-slider-wrapper .project-complex-slider');
                    if ($sliderContainers.length > 0 && typeof Swiper !== 'undefined') {
                        $sliderContainers.each(function() {
                            $(this).addClass('has-nav');
                            new Swiper($(this).find('.project-complex-slider__container')[0], {
                                loop: false,
                                observer: true,
                                observeParents: true,
                                navigation: {
                                    prevEl: $('.project-nav-override .project-prev')[0],
                                    nextEl: $('.project-nav-override .project-next')[0]
                                },
                                spaceBetween: 0,
                                speed: 500,
                                slidesPerView: 1
                            });
                        });
                    }

                    // Re-initialize Tippy tooltips
                    if (typeof tippy === 'function') {
                        tippy('#project-slider-wrapper [data-tippy-content]');
                    }
                },
                        error: function () {
                    $wrapper.css('opacity', '1');
                    console.error('Failed to load projects');
                }
            });
        }
    });
});
</script>
<script>
$(document).ready(function() {
    // Initializer for project-complex-slider
    var $projectSliderContainers = $('#project-slider-wrapper .project-complex-slider');
    if ($projectSliderContainers.length > 0 && typeof Swiper !== 'undefined') {
        $projectSliderContainers.each(function() {
            $(this).addClass('has-nav');
            new Swiper($(this).find('.project-complex-slider__container')[0], {
                loop: false,
                observer: true,
                observeParents: true,
                navigation: {
                    prevEl: $('.project-nav-override .project-prev')[0],
                    nextEl: $('.project-nav-override .project-next')[0]
                },
                spaceBetween: 0,
                speed: 500,
                slidesPerView: 1
            });
        });
    }

    // Initialize policy tags slider
    if ($('.policy-tags-slider').length > 0 && typeof Swiper !== 'undefined') {
        new Swiper('.policy-tags-slider', {
            slidesPerView: 'auto',
            freeMode: true,
            spaceBetween: 8,
            observer: true,
            observeParents: true,
            mousewheel: {
                forceToAxis: true,
            }
        });
    }
    const $input = $('#searchInputSp');
    const $popup = $('#suggestionPopupSp');
    const $list = $('#suggestionListSp');
    const $loader = $('#suggestionLoaderSp');
    let delayTimer;

    // Hàm thực hiện gọi AJAX
    function fetchSuggestions(keyword = '') {
        $loader.removeClass('d-none');

        $.ajax({
            url: "{{ route('ajax_project_suggestions') }}",
            method: 'GET',
            data: {
                keyword: keyword
            },
            success: function(data) {
                $loader.addClass('d-none');
                let html = '';

                if (data && data.length > 0) {
                    data.forEach(function(item) {
                        html += `
                            <li>
                                <a href="javascript:void(0)" class="suggestion-item" data-name="${item.name}">
                                    <div class="info">
                                        <div class="name text-truncate fw-bold">${item.name}</div>
                                        <div class="sub text-truncate small text-muted">${item.district_name || 'Đang cập nhật vị trí'}</div>
                                    </div>
                                </a>
                            </li>`;
                    });
                    $list.html(html);
                    $popup.addClass('active');
                } else {
                    $list.html(
                        '<div class="p-3 text-muted small text-center">Không tìm thấy dự án phù hợp</div>'
                    );
                    $popup.addClass('active');
                }
            },
            error: function() {
                $loader.addClass('d-none');
            }
        });
    }

    // Sự kiện khi gõ phím (Debounce)
    $input.on('keyup', function() {
        let keyword = $(this).val();
        clearTimeout(delayTimer);

        delayTimer = setTimeout(function() {
            fetchSuggestions(keyword);
        }, 300);
    });

    $input.on('focus click', function() {
        let keyword = $(this).val();
        if (!$popup.hasClass('active')) {
            fetchSuggestions(keyword);
        }
    });

    $(document).on('click', '.suggestion-item', function(e) {
        e.preventDefault();
        const projectName = $(this).data('name');
        $input.val(projectName);
        $popup.removeClass('active');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.pj-search__col').length) {
            $popup.removeClass('active');
        }
    });
});

$(function () {
    const $pageSections = $('.page__sections');
    const $header = $('.header');
    const $headerWrapper = $('.header__wrapper');

    if ($pageSections.length) {
        $pageSections.on('scroll', function () {
            if ($pageSections.scrollTop() > 80) {
                if (!$header.hasClass('is-sticky')) {
                    $header.css('min-height', $headerWrapper.outerHeight() + 'px');
                    $header.addClass('is-sticky');
                }
            } else {
                if ($header.hasClass('is-sticky')) {
                    $header.removeClass('is-sticky');
                    $header.css('min-height', '');
                }
            }
        });
    }
});

function togglePolicy(header) {
    const $body = $(header).next('.policy-body');
    const $icon = $(header).find('.policy-icon');
    
    // Lưu lại trạng thái của item hiện tại trước khi đóng các item khác
    const isVisible = $body.is(':visible');

    // Close other items
    const allItems = document.querySelectorAll('.policy-item');
    allItems.forEach(item => {
        if (item.querySelector('.policy-header') !== header) {
            $(item.querySelector('.policy-body')).slideUp(200);
            item.querySelector('.policy-icon').innerText = '+';
            $(item.querySelector('.policy-header')).removeClass('expanded');
        }
    });
    
    // Toggle current item
    if (!isVisible) {
        $body.slideDown(200);
        $icon.text('-');
        $(header).addClass('expanded');
    } else {
        $body.slideUp(200);
        $icon.text('+');
        $(header).removeClass('expanded');
    }
}
</script>
    <script>
        $(document).ready(function() {
            $('.policy-tag').click(function(e) {
                e.preventDefault();
                $('.policy-tag').removeClass('active').css({
                    'background': 'transparent',
                    'border': '1px solid rgba(255,255,255,0.3)'
                });
                $(this).addClass('active').css({
                    'background': '#33AAFA',
                    'border': 'none'
                });

                var cat_id = $(this).data('id');
                
                $.ajax({
                    url: window.location.href.split('?')[0],
                    type: 'GET',
                    data: {
                        ajax_investment_guide: 1,
                        cat_id: cat_id
                    },
                    success: function(response) {
                        $('#policyAccordion').html(response);
                    }
                });
            });
        });
    </script>
@endpush
