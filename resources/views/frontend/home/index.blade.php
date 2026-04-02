@extends('frontend.index')
@php
    $countAllProject = App\Models\Project::count();
    $countAllIndustrial = App\Models\ProjectIndustries::count();
@endphp
@section('content')
    <div class="page__content">
        <!-- main content-->
        <section class="banner-home">
            <div class="w-full">
                <div id="map"></div>
            </div>
        </section>
        <div class="pj-search" id="pjSearchFull">
            <div class="container py-3" style="position: absolute; top:50%;left:50%;transform: translate(-50%,-100%);">
                <!-- FORM: TÌM KIẾM DỰ ÁN -->
                <div class="pj-search__body custom_body tab-content active" id="projectTabContent">
                    <div class="pj-search__top">
                        <div class="pj-search__col">
                            <div class="input-group">
                                <input class="form-control" type="text" id="searchInput" placeholder="{{ __('app.enter_project_name') }}">
                                <div class="input-group-text"><i class="fal fa-lg fa-search"></i></div>
                            </div>
                        </div>
                        <div class="pj-search__col">
                            <button class="pj-search__btn" id="applyBtn" type="button">{{__('app.search')}}</button>
                        </div>
                    </div>
                    <div class="pj-search__bottom">
                        <div class="pj-search__col custom-select" style="position: relative;">
                            <div class="input-group">
                                <input class="form-control" type="text" id="districtFilter" placeholder="{{ __('app.locations') }}"
                                    autocomplete="off">
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
                                <option value="all">{{__('app.project_types')}}</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pj-search__col">
                            <select class="form-select" id="industryFilter">
                                <option value="all">{{__('app.industry_field')}}</option>
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry['id'] }}">{{ $industry['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pj-search__col">
                            <div class="range-input">
                                <div class="range-input__content">
                                    <div class="range-input__label">{{__('app.investment_scale')}}</div>
                                    <div class="range-input__price">0</div>
                                </div>
                                <input class="range-input__input" id="priceRange" type="range" value="0"
                                    min="0" max="{{ $maxPrice ?? 10000 }}" step="50">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM: SP KHU CÔNG NGHIỆP -->
                <div class="pj-search__body custom_body tab-content orange-theme" id="industrialTabContent"
                    style="display: none">
                    <div class="pj-search__top">
                        <div class="pj-search__col">
                            <div class="input-group">
                                <input class="form-control" type="text" id="searchInputSp"
                                    placeholder="{{ __('app.search_keyword') }}">
                                <div class="input-group-text"><i class="fal fa-lg fa-search"></i></div>
                            </div>
                        </div>
                        <div class="pj-search__col">
                            <button class="pj-search__btn orange-btn" id="applyBtnSp" type="button">{{__('app.search')}}</button>
                        </div>
                    </div>
                    <div class="pj-search__bottom">
                        <div class="pj-search__col custom-select" style="position: relative;">
                            <div class="input-group">
                                <input class="form-control" type="text" id="districtFilterSp" placeholder="{{ __('app.locations') }}"
                                    autocomplete="off">
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
                    <button class="custom-btn active text-uppercase" id="projectTab" onclick="showTab('project')">{{ __('app.search_project') }}</button>
                    <button class="custom-btn text-uppercase" id="industrialTab" onclick="showTab('industrial')">{{ __('app.industrial_products') }}</button>
                </div>
            </div>
        </div>
        <div class="pj-search" id="pjSearchMini">
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
                            <button class="pj-search__btn" id="applyBtn" type="button">{{__('app.search')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="section" id="investment-section">
            <div class="container">
                <h2 class="section__title mb-3 text-uppercase">{{ __('app.investment_portfolio') }}</h2>

                <div class="project-nav-wrapper mb-60">
                    <!-- "Tất cả" cố định -->
                    <div class="project-nav-fixed">
                        <a class="{{ request('industry') ? '' : 'active' }}"
                            href="{{ route('home_page') }}#investment-section">
                            {{ __('app.all') }}
                        </a>
                    </div>

                    <!-- Các ngành scroll ngang -->
                    <div class="project-nav-scroll swiper-container">
                        <ul class="project-nav__list_custom swiper-wrapper">
                            @foreach ($industries as $industry)
                                <li class="swiper-slide" data-tippy-content="{{ $industry['name'] }}">
                                    <a class="{{ request('industry') == $industry['id'] ? 'active' : '' }}"
                                        href="{{ route('home_page', ['industry' => $industry['id']]) }}#investment-section">
                                        {{ $industry['name'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div id="project-slider-wrapper" style="position: relative; min-height: 200px;">
                    @include('frontend.home.partials.project_slider')
                </div>
            </div>
        </section>
        <section class="section section--bg-pattern" style="padding: 23px 0">
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
                                            <div class="counter__icon">
                                                <img src="{{ $item['icon'] ?? '' }}" alt="" />
                                            </div>
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
        <section class="section">
            {{-- <img class="texture-1" src="./images/texture-1.png" alt="" />
            <img class="texture-2" src="./images/texture-2.png" alt="" /> --}}
            <div class="container">
                <h2 class="section__title text-uppercase">{{ __('app.news') }}</h2>
                <div class="news-slider">
                    <div class="news-slider__nav">
                        <div class="news-slider__prev"><i class="fal fa-fw fa-lg fa-angle-left"></i></div>
                        <div class="news-slider__next"><i class="fal fa-fw fa-lg fa-angle-right"></i></div>
                    </div>
                    <div class="news-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            @foreach ($posts as $item)
                                <div class="swiper-slide">
                                    <div class="news">
                                        <a class="news__frame"
                                            href="{{ route('post_detail', ['id' => $item['id'], 'slug' => $item['slug'], 'ref' => 'app.news']) }}">
                                            <img src="{{ $item['image'] }}" alt="" />
                                        </a>
                                        <div class="news__body">
                                            <div class="news__info">
                                                <div class="news__time">
                                                    <i class="fal fa-clock me-2"></i>
                                                    <span>{{ \Carbon\Carbon::parse($item['published_at'])->format('d/m/Y') }}</span>
                                                </div>
                                                <a class="news__like" href="javascript:void(0)"
                                                    data-id="{{ $item['id'] }}" data-type="App\Models\Post">
                                                    <i
                                                        class="fas fa-fw fa-heart {{ $item['is_interested'] ? 'text-danger' : '' }}"></i>
                                                </a>
                                            </div>
                                            <h3 class="news__title custom-desc">
                                                <a href="{{ route('post_detail', ['id' => $item['id'], 'slug' => $item['slug'], 'ref' => 'app.news']) }}"
                                                    data-tippy-content="{{ $item['name'] }}">
                                                    {{ $item['name'] }}
                                                </a>
                                            </h3>
                                            <div class="news__desc">{{ $item['description'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @php
                    $locale = app()->getLocale() === 'vi' ? 'vn' : app()->getLocale();
                @endphp
                <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="{{ url($locale . '/' . __('app.news_link')) }}" style="text-transform: capitalize;">{{ __('app.view_more') }}</a>
                </nav>
            </div>
        </section>
        <section class="section section--medium-blue">
            <div class="container">
                <h2 class="section__title text-white text-uppercase">{{ __('app.link') }}</h2>
                @if (!empty($setting['banners']))
                    <div class="partners-slider">
                        <div class="partners-slider__container swiper-container">
                            <div class="swiper-wrapper">
                                @foreach ($setting['banners'] as $banner)
                                    <div class="swiper-slide">
                                        <div class="partners__item">
                                            <a href="{{ $banner['link'] ?? '#' }}" target="_blank">
                                                <img src="{{ $banner['image'] ?? '' }}" alt="" />
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
        
        <div class="modal fade" id="filterResultModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('app.filter_results') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('app.close') }}"></button>
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
        <div id="homePopup" 
        style="display:none; position:fixed; inset:0;
               background:rgba(0,0,0,0.6); z-index:9999;
               justify-content:center; align-items:center;">
      
        <div id="popupBox"
          style="position:relative; width:80%; max-width:900px;
                 background:#fff; border-radius:12px; overflow:hidden;
                 box-shadow:0 4px 20px rgba(0,0,0,0.3);">
      
          <div id="popupBody" 
            style="position:relative; width:100%; aspect-ratio:16/9; overflow:hidden;">
            
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
        /* Xóa ô vuông xanh (focus outline) khi click/focus vào ranh giới */
        .leaflet-interactive:focus {
            outline: none !important;
        }
        /* Style lại tooltip cho tinh tế hơn */
        .boundary-tooltip {
            background: rgba(255, 255, 255, 0.9);
            border: none !important;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            color: #333;
            font-weight: 600;
            padding: 4px 8px;
            font-size: 12px;
        }
        .boundary-tooltip:before {
            border: none !important;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
    <link href="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.css" rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.js"></script>
    <script src="https://unpkg.com/@maplibre/maplibre-gl-leaflet@0.0.21/leaflet-maplibre-gl.js"></script>
    <script src="/js/boundaries.js"></script>

    <script>
        // Tile layers
        const defaults = L.tileLayer('https://api.maptiler.com/maps/outdoor-v2/{z}/{x}/{y}.png?key=ziR13X4sfKXctiAkrRRQ', {
            maxNativeZoom: 19,
            maxZoom: 21
        });
        const streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxNativeZoom: 19,
            maxZoom: 21
        });
        const satellite = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxNativeZoom: 18,
                maxZoom: 21
            });
        const topo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
            maxNativeZoom: 17,
            maxZoom: 21
        });

        // MapLibre GL 3D Layer
        const map3d = L.maplibreGL({
            style: 'https://api.maptiler.com/maps/streets-v2/style.json?key=ziR13X4sfKXctiAkrRRQ',
            updateInterval: 0 // Eliminate 30fps throttle to fix 3D panning jitter
        });

        // Add 3D building extrusion when MapLibre map is ready
        map3d.on('styleload', function() {
            const glMap = map3d.getMaplibreMap();
            if (glMap && !glMap.getLayer('3d-buildings')) {
                const layers = glMap.getStyle().layers;
                let labelLayerId;
                for (let i = 0; i < layers.length; i++) {
                    if (layers[i].type === 'symbol' && layers[i].layout['text-field']) {
                        labelLayerId = layers[i].id;
                        break;
                    }
                }

                glMap.addLayer({
                        'id': '3d-buildings',
                        'source': 'openmaptiles',
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
                const MIN_ZOOM      = 15;
                const PALETTE = [
                    '#ffffff', '#e0e0e0', '#ffd700', '#ff5555',
                    '#88ddff', '#99ee66', '#ffaa00', '#cc88ff'
                ];

                // ── Nguồn GeoJSON + layer circle (luôn hiển thị, ko cần icon) ─
                glMap.addSource('veh-src', {
                    type: 'geojson',
                    data: { type: 'FeatureCollection', features: [] }
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
                        'circle-translate': ['literal', [4, 0]],  // offset nhỏ
                        'circle-translate-anchor': 'map',
                        'circle-opacity': 0.95
                    }
                });

                // ── Sinh đường theo lưới thực tế từ view hiện tại ───────────
                function makeProceduralRoads() {
                    const c   = glMap.getCenter();
                    const lat = c.lat, lng = c.lng;
                    const R   = 0.004; // ~400m
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
                        // openmaptiles source, transportation source-layer
                        feats = glMap.querySourceFeatures('openmaptiles', {
                            sourceLayer: 'transportation'
                        });
                    } catch (e) { /* source chưa sẵn sàng */ }

                    if (!feats.length) {
                        // fallback: tất cả line type features
                        try { feats = glMap.queryRenderedFeatures(); } catch (e) {}
                    }

                    const seen = new Set();
                    const roads = [];
                    for (const f of feats) {
                        if (!f.geometry) continue;
                        const lines = f.geometry.type === 'LineString'
                            ? [f.geometry.coordinates]
                            : f.geometry.type === 'MultiLineString'
                                ? f.geometry.coordinates : [];
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
                    const toR = Math.PI / 180, toD = 180 / Math.PI;
                    const dL  = (b[0] - a[0]) * toR;
                    const p1  = a[1] * toR, p2 = b[1] * toR;
                    const y   = Math.sin(dL) * Math.cos(p2);
                    const x   = Math.cos(p1) * Math.sin(p2) - Math.sin(p1) * Math.cos(p2) * Math.cos(dL);
                    return (Math.atan2(y, x) * toD + 360) % 360;
                }

                function segM(a, b) {
                    const R = 6371000, r = Math.PI / 180;
                    const dLat = (b[1] - a[1]) * r, dLon = (b[0] - a[0]) * r;
                    const s = Math.sin(dLat / 2) ** 2 + Math.cos(a[1] * r) * Math.cos(b[1] * r) * Math.sin(dLon / 2) ** 2;
                    return 2 * R * Math.asin(Math.sqrt(Math.min(1, s)));
                }

                function spawn() {
                    if (!roads.length) return null;
                    const road = roads[Math.floor(Math.random() * roads.length)];
                    if (!road || road.length < 2) return null;
                    return {
                        road,
                        seg: Math.floor(Math.random() * (road.length - 1)),
                        t:   Math.random(),
                        spd: 6 + Math.random() * 12,   // m/s  (≈20-65 km/h)
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
                let rafId  = null;
                let lastTs = 0;

                function tick(ts) {
                    const dt = Math.min(ts - lastTs, 80);
                    lastTs = ts;

                    const feats = fleet.map(v => {
                        const a = v.road[v.seg], b = v.road[v.seg + 1];
                        if (!a || !b) { v.seg = 0; v.t = 0; return null; }

                        const m    = segM(a, b);
                        const step = m > 0 ? (v.spd * dt * 0.001) / m : 0.04;
                        v.t += step;
                        while (v.t >= 1) {
                            v.t -= 1;
                            v.seg = (v.seg + 1) % (v.road.length - 1);
                        }

                        const A = v.road[v.seg], B = v.road[v.seg + 1] || A;
                        const lng = A[0] + (B[0] - A[0]) * v.t;
                        const lat = A[1] + (B[1] - A[1]) * v.t;

                        return {
                            type: 'Feature',
                            geometry: { type: 'Point', coordinates: [lng, lat] },
                            properties: { col: v.col, brg: calcBrg(A, B) }
                        };
                    }).filter(Boolean);

                    const src = glMap.getSource('veh-src');
                    if (src) src.setData({ type: 'FeatureCollection', features: feats });
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
                    rafId  = requestAnimationFrame(tick);

                    // Upgrade sang tile roads sau 2s (nếu chưa có)
                    if (tileRoads.length < 5) {
                        setTimeout(() => {
                            const tr = tryGetTileRoads();
                            if (tr.length >= 5) {
                                roads = tr;
                                fleet = [];       // respawn trên đường thực
                                initFleet();
                            }
                        }, 2000);
                    }
                }

                function stop() {
                    if (rafId) { cancelAnimationFrame(rafId); rafId = null; }
                    const src = glMap.getSource('veh-src');
                    if (src) src.setData({ type: 'FeatureCollection', features: [] });
                    fleet = [];
                    roads = [];
                }

                // ── Lifecycle ────────────────────────────────────────────────
                function check() {
                    if (map.hasLayer(map3d) && map.getZoom() >= MIN_ZOOM) {
                        start();
                    } else {
                        stop();
                    }
                }

                // Bắt đầu ngay khi style/tiles sẵn sàng
                glMap.once('idle', () => setTimeout(check, 100));

                map.on('zoomend',        () => setTimeout(check, 200));
                map.on('baselayerchange',() => setTimeout(check, 700));
                map.on('moveend', () => {
                    if (!rafId) return;
                    // Refresh đường khi pan
                    const tr = tryGetTileRoads();
                    if (tr.length >= 5) {
                        roads = tr;
                        // Re-spawn xe trên đường mới
                        fleet = fleet.map(() => spawn()).filter(Boolean);
                        while (fleet.length < VEHICLE_COUNT) {
                            const v = spawn(); if (v) fleet.push(v);
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
                        const v = spawn(); if (v) fleet.push(v);
                    }
                }, 2500);

            })(glMap);
            // ── END TRAFFIC SIMULATION ──────────────────────────────────────
        });

        const baseLayers = {
            "{{ __('app.default_map') }}": defaults,
            "{{ __('app.traffic_map') }}": streets,
            "{{ __('app.satellite_map') }}": satellite,
            "{{ __('app.topo_map') }}": topo,
            "{{ __('app.map_3d') }}": map3d
        };

        const boundaryOverlayGroup = L.layerGroup();
        let districtDisplayNames = {}; // Map of VI Name -> Localized Name

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
        renderBoundaryLayers();

        const overlayLayers = {
            "{{ __('app.boundary_map') }}": boundaryOverlayGroup
        };

        const defaultCenter = [21.0285, 105.8542];
        const defaultZoom = 12;

        // Toạ độ giới hạn vùng Hà Nội (tương đối chính xác)
        const bounds = L.latLngBounds(
            [20.4, 104.9],
            [21.7, 106.4]
        );

        // Tạo bản đồ và giới hạn vùng
        const map = L.map('map', {
            center: defaultCenter,
            zoom: defaultZoom,
            layers: [defaults],
            maxBounds: bounds, // Giới hạn không cho pan ra khỏi vùng này
            maxBoundsViscosity: 1.0, // 1.0 = không bao giờ cho kéo ra ngoài
            minZoom: 10, // Không cho zoom out thấp hơn mức này
            maxZoom: 21, // Cho phép zoom in sâu hơn (đến mức 21)
            attributionControl: false,
            keyboard: false // Disable default keyboard panning to allow custom 3D rotation
        });

        // Auto-tilt logic for Map3D - Optimized
        let tilingTicking = false;
        function updateTilt() {
            if (map.hasLayer(map3d)) {
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
            if (!map.hasLayer(map3d)) return;
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


        // Thêm nút reset bản đồ
        const resetControl = L.control({
            position: window.innerWidth <= 1024 ? 'topright' : 'bottomright'
        });
        const currentLocation = L.control({
            position: window.innerWidth <= 1024 ? 'topright' : 'bottomright'
        });
        const fullScreenControl = L.control({
            position: window.innerWidth <= 1024 ? 'topright' : 'bottomright'
        });

        window.addEventListener('resize', () => {
            const newPosition = window.innerWidth <= 1024 ? 'topright' : 'bottomright';
            resetControl.setPosition(newPosition);
            currentLocation.setPosition(newPosition);
            fullScreenControl.setPosition(newPosition);

            resetControl.remove();
            currentLocation.remove();
            fullScreenControl.remove();

            if (newPosition === 'topright') {
                // Muốn C -> B -> A trên mobile (hiển thị dưới cùng lên)
                fullScreenControl.addTo(map);
                currentLocation.addTo(map);
                resetControl.addTo(map);
            } else {
                // Muốn A -> B -> C trên web
                resetControl.addTo(map);
                currentLocation.addTo(map);
                fullScreenControl.addTo(map);
            }
        });

        resetControl.onAdd = function(map) {
            const btn = L.DomUtil.create('button', 'leaflet-bar leaflet-control leaflet-control-custom');
            btn.innerHTML = '<i class="fas fa-redo-alt"></i>';
            btn.title = '{{ __("app.reset_map") }}';

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

        currentLocation.onAdd = function(map) {
            const btn = L.DomUtil.create('button', 'leaflet-bar leaflet-control leaflet-control-custom');
            btn.innerHTML = '<i class="fas fa-crosshairs"></i>';
            btn.title = '{{ __("app.current_location") }}';

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
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const latLng = [position.coords.latitude, position.coords.longitude];
                        map.setView(latLng, 16);

                        if (map._currentLocationMarker) {
                            map.removeLayer(map._currentLocationMarker);
                        }
                        map._currentLocationMarker = L.marker(latLng, {
                                icon: redIcon
                            }).addTo(map)
                            .bindPopup("{{ __('app.current_location') }}")
                            .openPopup();

                    }, function() {
                        alert('{{ __('app.unable_to_get_current_location') }}');
                    });
                } else {
                    alert('{{ __('app.browser_not_support_geolocation') }}');
                }
            };

            return btn;
        };

        currentLocation.addTo(map);

        fullScreenControl.onAdd = function(map) {
            const btn = L.DomUtil.create('button', 'leaflet-bar leaflet-control leaflet-control-custom');
            btn.innerHTML = '<i class="fas fa-expand"></i>';
            btn.title = '{{ __("app.full_screen") }}';

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
                    } else if (mapElement.mozRequestFullScreen) { /* Firefox */
                        mapElement.mozRequestFullScreen();
                    } else if (mapElement.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
                        mapElement.webkitRequestFullscreen();
                    } else if (mapElement.msRequestFullscreen) { /* IE/Edge */
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
                if (!document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
                    btn.innerHTML = '<i class="fas fa-expand"></i>';
                }
            }

            return btn;
        };

        fullScreenControl.addTo(map);

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

        L.control.layers(baseLayers, overlayLayers).addTo(map);

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

        function createDropIcon(bgColor = "#2a84d0", imgUrl = "") {
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

        function createMarker(loc) {
            const industryStyles = {
                1: { color: "#2a84d0", icon: "/images/custom-icon-map/bridge.png" },
                2: { color: "#2a84d0", icon: "/images/custom-icon-map/anchor.png" },
                3: { color: "#2a84d0", icon: "/images/custom-icon-map/enviroment.png" },
                4: { color: "#2a84d0", icon: "/images/custom-icon-map/cityscape.png" },
                5: { color: "#2a84d0", icon: "/images/custom-icon-map/finance.png" },
                6: { color: "#2a84d0", icon: "/images/custom-icon-map/industrial.png" },
                7: { color: "#2a84d0", icon: "/images/custom-icon-map/train.png" },
                8: { color: "#2a84d0", icon: "/images/custom-icon-map/tourism.png" },
                9: { color: "#2a84d0", icon: "/images/custom-icon-map/planting.png" },
                10: { color: "#2a84d0", icon: "/images/custom-icon-map/technology.png" },
                11: { color: "#2a84d0", icon: "/images/custom-icon-map/education.png" },
                12: { color: "#2a84d0", icon: "/images/custom-icon-map/bus.png" },
                13: { color: "#2a84d0", icon: "/images/custom-icon-map/nature.png" },
                14: { color: "#2a84d0", icon: "/images/custom-icon-map/healthcare.png" },
                15: { color: "#2a84d0", icon: "/images/custom-icon-map/united.png" },
                16: { color: "#2a84d0", icon: "/images/custom-icon-map/artificial-intelligence.png" },
            };

            const style = industryStyles[loc.industry_number];

            let marker;
            if (style) {
                // Nếu is_invest = 0 → đổi sang màu đỏ
                const markerColor = (loc.is_invest === 1) ? "#d9534f" : style.color;

                marker = L.marker([loc.lat, loc.lng], {
                    icon: createDropIcon(markerColor, style.icon)
                });
            } else {
                // fallback mặc định
                marker = L.marker([loc.lat, loc.lng]);
            }

            const detailUrl = loc.link;
            const tourUrl = loc.link_vrtour;
            let tourButtonHtml = '';
            if (tourUrl) {
                if (tourUrl !== 'null' && tourUrl.trim() !== '') {
                    tourButtonHtml = `<a href="${tourUrl}" target="_blank" class="btn btn-sm btn-secondary text-white">{{ __('app.virtual_tour') }}</a>`;
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
                    areaHtml = `{{ __('app.area') }}: ${areaText} ha`;
                } else if (loc.unit === 'km') {
                    areaHtml = `{{ __('app.length') }}: ${areaText} km`;
                }
            }

            const popupContent = `
        <div class='info-box' style="max-width:250px;">
            <img src="${imageUrl}" alt="${loc.name}" style="width:100%; height:120px; object-fit:cover; border-radius:6px; margin-bottom:8px;">
            <strong>${loc.name}</strong><br>
            {{ __('app.investment_form') }}: ${getTypeName(loc.type_number)}<br>
            {{ __('app.zone') }}: ${districtText}<br>
            {{ __('app.investment_scale') }}: ${priceText} {{ __('app.billion_vnd') }}<br>
            ${areaHtml}
            <div style="margin-top:10px; display:flex; gap:8px; justify-content:flex-end;">
                ${tourButtonHtml}
                <a href="${detailUrl}" target="_blank" class="btn btn-sm btn-primary text-white">{{ __('app.information') }}</a>
            </div>
        </div>
    `;

            marker.bindPopup(popupContent);
            return marker;
        }

        function loadMarkers(data, triggeredBySearch = false) {
            markersLayer.clearLayers();

            const filtered = data.filter(loc => loc.lat && loc.lng && Array.isArray(loc.districts) && loc.districts.length >
                0);
            const markers = filtered.map(loc => createMarker(loc));

            if (markers.length === 0) return;

            markersLayer.addLayers(markers);
            map.addLayer(markersLayer);
            if (!triggeredBySearch) return;

            if (markers.length === 1) {
                const latLng = markers[0].getLatLng();
                map.flyTo(latLng, 16); // hoặc 15 tuỳ layout
            } else {
                const group = new L.featureGroup(markers);
                const bounds = group.getBounds();

                if (!map.getBounds().contains(bounds)) {
                    map.fitBounds(bounds, {
                        padding: [50, 50],
                        maxZoom: 16
                    });
                } else {
                    // Nếu đã nằm trong màn hình, chỉ pan nhẹ đến giữa
                    map.panTo(bounds.getCenter());
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
                location: '{{ __('app.location') }}'
            };

            resultList.innerHTML = items.map(item => `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${item.description ?? labels.unknown}</strong><br>
                        <small>${labels.projects}: ${item.project_name}</small> - ${labels.code}: ${item.code.replace(/^cmss_/, '')}<br>
                        <small>${labels.area}: ${item.acreage ?? labels.unknown} - ${labels.projectTypes}: ${item.product_type_name ?? labels.unknown}</small>
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
                const $li = $("<li></li>").addClass(`page-item ${active ? "active" : ""}`).html(`<button class="page-link" ${active ? "disabled" : ""}>${label}</button>`);
                if (!active && pageNum !== null) {
                 $li.on("click", () => renderList(pageNum));
                }
                $("#pagination") .append($li);
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


        // MAP MOVE
        let mapMoveTimeout = null;
        map.on('moveend zoomend', function() {
            isMapTriggered = true;
            clearTimeout(mapMoveTimeout);
            mapMoveTimeout = setTimeout(applyFiltersWithBounds, 500);
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
        $(document).ready(function () {
            const popup = $('#homePopup');
            const closeBtn = $('#closePopup');
            const popupBody = $('#popupBody');
            const popupLink = $('#popupLink');

            // Danh sách popup (ảnh + link)
            const popups = [
                @foreach($popups as $popup)
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
                        popupBody.fadeOut(200, function () {
                            const item = popups[current];
                            popupLink.css('background-image', 'url(' + item.image + ')');
                            popupLink.attr('href', item.link);
                            popupBody.fadeIn(300);
                        });
                    }, 4000);
                }

                // Đóng popup
                closeBtn.on('click', function (e) {
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

                            // Re-initialize Swiper specifically for the project section
                            var $sliderContainer = $('#project-slider-wrapper .news-slider');
                            if ($sliderContainer.length > 0 && typeof Swiper !== 'undefined') {
                                $sliderContainer.addClass('has-nav');
                                new Swiper($sliderContainer.find('.news-slider__container')[0], {
                                    loop: false,
                                    navigation: {
                                        prevEl: $sliderContainer.find('.news-slider__prev')[0],
                                        nextEl: $sliderContainer.find('.news-slider__next')[0]
                                    },
                                    spaceBetween: 0,
                                    speed: 500,
                                    slidesPerView: 2,
                                    breakpoints: {
                                        992: { slidesPerView: 3 }
                                    }
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
@endpush
