@extends('frontend.index')

@section('content')
    <div class="page__content">
        <!-- main content-->
        <section class="banner-home">
            <div class="w-full">
                <div id="map"></div>
            </div>
        </section>
        <div class="pj-search">
            <div class="container">
                <!-- FORM: TÌM KIẾM DỰ ÁN -->
                <div class="pj-search__body custom_body tab-content active" id="projectTabContent">
                    <div class="pj-search__top">
                        <div class="pj-search__col">
                            <div class="input-group">
                                <input class="form-control" type="text" id="searchInput" placeholder="Nhập tên dự án">
                                <div class="input-group-text"><i class="fal fa-lg fa-search"></i></div>
                            </div>
                        </div>
                        <div class="pj-search__col">
                            <button class="pj-search__btn" id="applyBtn" type="button">Tìm kiếm</button>
                        </div>
                    </div>
                    <div class="pj-search__bottom">
                        <div class="pj-search__col custom-select" style="position: relative;">
                            <div class="input-group">
                                <input class="form-control" type="text" id="districtFilter" placeholder="Địa điểm"
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
                                <option value="all">Loại dự án</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pj-search__col">
                            <select class="form-select" id="industryFilter">
                                <option value="all">Ngành/Lĩnh vực</option>
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry['id'] }}">{{ $industry['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pj-search__col">
                            <div class="range-input">
                                <div class="range-input__content">
                                    <div class="range-input__label">Quy mô vốn đầu tư</div>
                                    <div class="range-input__price">0đ</div>
                                </div>
                                <input class="range-input__input" id="priceRange" type="range" value="0"
                                    min="0" max="100000000" step="1000000">
                            </div>
                        </div>
                    </div>
                    <button onclick="resetMap()" class="pj-search__btn" style="max-width: 150px; margin-top: 10px;">
                        <i class="fas fa-redo-alt"></i> Reset Map
                    </button>

                </div>

                <!-- FORM: SP KHU CÔNG NGHIỆP -->
                <div class="pj-search__body custom_body tab-content orange-theme" id="industrialTabContent"
                    style="display: none">
                    <div class="pj-search__top">
                        <div class="pj-search__col">
                            <div class="input-group">
                                <input class="form-control" type="text" id="searchInputSp"
                                    placeholder="Từ khóa tìm kiếm">
                                <div class="input-group-text"><i class="fal fa-lg fa-search"></i></div>
                            </div>
                        </div>
                        <div class="pj-search__col">
                            <button class="pj-search__btn orange-btn" id="applyBtnSp" type="button">Tìm kiếm</button>
                        </div>
                    </div>
                    <div class="pj-search__bottom">
                        <div class="pj-search__col custom-select" style="position: relative;">
                            <div class="input-group">
                                <input class="form-control" type="text" id="districtFilterSp" placeholder="Địa điểm"
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
                                <option value="all">Chọn dự án</option>
                                @foreach ($list_projects as $project)
                                    <option value="{{ $project['id'] }}">{{ $project['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pj-search__col">
                            <select class="form-select" id="product_type">
                                <option value="all">Loại hình sản phẩm</option>
                                @foreach ($product_types as $type)
                                    <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pj-search__col">
                            <div class="range-input">
                                <div class="range-input__content">
                                    <div class="range-input__label text-white">Giá thuê</div>
                                    <div class="range-input__price1 text-white">0đ</div>
                                </div>
                                <input class="white-range" id="priceRangeSp" type="range" value="0"
                                    min="0" max="100000000" step="1000000">
                            </div>
                        </div>
                    </div>
                    <button onclick="resetMap()" class="pj-search__btn orange-btn"
                        style="max-width: 150px; margin-top: 10px;">
                        <i class="fas fa-redo-alt"></i> Reset Map
                    </button>
                </div>

                <!-- Tabs dưới form -->
                <div class="custom_tabs">
                    <button class="custom-btn active" id="projectTab" onclick="showTab('project')">TÌM KIẾM DỰ
                        ÁN</button>
                    <button class="custom-btn" id="industrialTab" onclick="showTab('industrial')">SP KHU CÔNG
                        NGHIỆP</button>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <h2 class="section__title mb-3">Danh mục đầu tư</h2>
                <ul class="project-nav__list mb-60">
                    <li><a class="active" href="#!">Tất cả</a></li>
                    <li><a href="#!">Cơ sở hạ tầng giao thông</a></li>
                    <li><a href="#!">Bất động sản dân dụng</a></li>
                    <li><a href="#!">Công trình công cộng</a></li>
                    <li><a href="#!">Công trình thương mại</a></li>
                </ul>
                {{-- <div class="news-slider">
                    <div class="news-slider__nav">
                        <div class="news-slider__prev"><i class="fal fa-fw fa-lg fa-angle-left"></i></div>
                        <div class="news-slider__next"><i class="fal fa-fw fa-lg fa-angle-right"></i></div>
                    </div>
                    <div class="news-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            @foreach (range(1, 6) as $item)
                                <div class="swiper-slide">
                                    <div>
                                        <div class="project"><a class="project__frame"
                                                href="{{ route('project_detail') }}"><img src="./images/project-1.jpg"
                                                    alt="" /></a>
                                            <div class="project__body">
                                                <h3 class="project__title"><a href="{{ route('project_detail') }}">Dự án
                                                        Khu công nghệ cao Láng - Hoà
                                                        Lạc</a></h3>
                                                <div class="project__overlay"><span>Dự án mới</span><a
                                                        class="project__like" href="{{ route('project_detail') }}"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                                <ul class="project__info">
                                                    <li><img class="me-2" src="./images/icon-map-marker.svg"
                                                            alt="" /><span>Donec venenatis fringilla augue at
                                                            ...</span>
                                                    </li>
                                                    <li><img class="me-2" src="./images/icon-dimension.svg"
                                                            alt="" /><span>120 ha</span>
                                                    </li>
                                                    <li><img class="me-2" src="./images/icon-save-money.svg"
                                                            alt="" /><span>Theo đề xuất</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-20">
                                        <div class="project"><a class="project__frame"
                                                href="{{ route('project_detail') }}"><img src="./images/project-1.jpg"
                                                    alt="" /></a>
                                            <div class="project__body">
                                                <h3 class="project__title"><a href="{{ route('project_detail') }}">Dự án
                                                        Khu công nghệ cao Láng - Hoà
                                                        Lạc</a></h3>
                                                <div class="project__overlay"><span>Dự án mới</span><a
                                                        class="project__like" href="{{ route('project_detail') }}"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                                <ul class="project__info">
                                                    <li><img class="me-2" src="./images/icon-map-marker.svg"
                                                            alt="" /><span>Donec venenatis fringilla augue at
                                                            ...</span>
                                                    </li>
                                                    <li><img class="me-2" src="./images/icon-dimension.svg"
                                                            alt="" /><span>120 ha</span>
                                                    </li>
                                                    <li><img class="me-2" src="./images/icon-save-money.svg"
                                                            alt="" /><span>Theo đề xuất</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="#!">Xem thêm</a>
                </nav> --}}
                {{-- Hiển thị tạm --}}
                <div class="col">
                    <div class="row g-20">
                        <div class="col-6 col-md-4 col-lg-6 col-xl-4">
                            <div class="project">
                                <a class="project__frame" href="{{ route('project_detail') }}">
                                    <img src="./images/project-1.jpg" alt="" /></a>
                                <div class="project__body">
                                    <h3 class="project__title"><a href="{{ route('project_detail') }}">Dự án đầu tư xây
                                            dựng cầu Trần Hưng Đạo</a></h3>
                                    <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                            href="#!"><i class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                    <ul class="project__info">
                                        <li><img class="me-2" src="./images/icon-map-marker.svg"
                                                alt="" /><span>Dự án nằm trên địa bàn các quận Hoàn Kiếm (phường
                                                Phan Chu Trinh, Chương Dương Độ), quận Hai Bà Trưng (phường Bạch Đằng) và
                                                quận Long Biên (phường Long Biên, Bồ Đề), thành phố Hà Nội</span>
                                        </li>
                                        <li><img class="me-2" src="./images/icon-dimension.svg"
                                                alt="" /><span>75,5 ha</span></li>
                                        <li><img class="me-2" src="./images/icon-save-money.svg"
                                                alt="" /><span>Theo đề xuất</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-6 col-xl-4">
                            <div class="project">
                                <a class="project__frame" href="{{ route('project_detail_cn2') }}">
                                    <img src="./images/design-1_cn2.jpg" alt="" /></a>
                                <div class="project__body">
                                    <h3 class="project__title"><a href="{{ route('project_detail') }}">Dự án Cụm công
                                            nghiệp CN2</a></h3>
                                    <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                            href="#!"><i class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                    <ul class="project__info">
                                        <li><img class="me-2" src="./images/icon-map-marker.svg"
                                                alt="" /><span>Xã Mai Đình, huyện Sóc Sơn, TP. Hà Nội</span>
                                        </li>
                                        <li><img class="me-2" src="./images/icon-dimension.svg"
                                                alt="" /><span>50,5 ha</span></li>
                                        <li><img class="me-2" src="./images/icon-save-money.svg"
                                                alt="" /><span>Theo đề xuất</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- end hiển thị tạm --}}
            </div>
        </section>
        <section class="section section--bg-pattern">
            <div class="container">
                <div class="counter">
                    <div class="counter__item">
                        <div class="counter__icon"><img src="./images/counter-1.svg" alt="" /></div>
                        <div class="counter__number">36</div>
                        <div class="counter__title">Tổng số dự án</div>
                    </div>
                    <div class="counter__item">
                        <div class="counter__icon"><img src="./images/counter-2.svg" alt="" /></div>
                        <div class="counter__number">10K+</div>
                        <div class="counter__title">Tổng vốn đầu tư</div>
                    </div>
                    <div class="counter__item">
                        <div class="counter__icon"><img src="./images/counter-3.svg" alt="" /></div>
                        <div class="counter__number">15</div>
                        <div class="counter__title">Lĩnh vực</div>
                    </div>
                    <div class="counter__item">
                        <div class="counter__icon"><img src="./images/counter-4.svg" alt="" /></div>
                        <div class="counter__number">20</div>
                        <div class="counter__title">Tổng số nhà đầu tư</div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section"><img class="texture-1" src="./images/texture-1.png" alt="" /><img
                class="texture-2" src="./images/texture-2.png" alt="" />
            <div class="container">
                <h2 class="section__title">Tin tức</h2>
                <div class="news-slider">
                    <div class="news-slider__nav">
                        <div class="news-slider__prev"><i class="fal fa-fw fa-lg fa-angle-left"></i></div>
                        <div class="news-slider__next"><i class="fal fa-fw fa-lg fa-angle-right"></i></div>
                    </div>
                    <div class="news-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            @foreach ($posts as $item)
                                <div class="swiper-slide">
                                    <div class="news"><a class="news__frame"
                                            href="{{ route('new_detail', ['id' => $item->id]) }}"><img
                                                src="./images/news/new-{{ $loop->iteration }}.jpg" alt="" /></a>
                                        <div class="news__body">
                                            <div class="news__info">
                                                <div class="news__time"><i
                                                        class="fal fa-clock me-2"></i><span>{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                                <div class="news__like"><i class="fal fa-fw fa-heart"></i></div>
                                            </div>
                                            <h3 class="news__title  custom-desc"><a
                                                    href="{{ route('new_detail', ['id' => $item->id]) }}">{{ $item->name }}</a>
                                            </h3>
                                            <div class="news__desc">{{ $item->description }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="#!">Xem thêm</a>
                </nav>
            </div>
        </section>
        <section class="section section--medium-blue">
            <div class="container">
                <h2 class="section__title text-white">Đối tác</h2>
                <div class="partners">
                    <div class="partners__item"><img src="./images/partner-1.png" alt=""></div>
                    <div class="partners__item"><img src="./images/partner-1.png" alt=""></div>
                    <div class="partners__item"><img src="./images/partner-1.png" alt=""></div>
                    <div class="partners__item"><img src="./images/partner-1.png" alt=""></div>
                    <div class="partners__item"><img src="./images/partner-1.png" alt=""></div>
                </div>
            </div>
        </section>
        <div class="modal fade" id="filterResultModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Kết quả lọc</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
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
    </div>
@endsection

@push('bottom')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
    <script src="/js/boundaries.js"></script>

    <script>
        // Tile layers
        const defaults = L.tileLayer('https://api.maptiler.com/maps/basic-v2/{z}/{x}/{y}.png?key=HjvBAK2MHmvwf84ZIhtt');
        const streets = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
        const satellite = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}');
        const topo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png');

        const baseLayers = {
            "Bản đồ mặc định": defaults,
            "Bản đồ đường xá": streets,
            "Bản đồ vệ tinh": satellite,
            "Bản đồ địa hình": topo
        };

        const defaultCenter = [21.0285, 105.8542];
        const defaultZoom = 12;

        let map = L.map('map', {
            center: defaultCenter,
            zoom: defaultZoom,
            layers: [defaults]
        });

        function resetMap() {
            resetProjectTab();
            resetIndustrialTab();
            map.setView(defaultCenter, defaultZoom);
            isMapTriggered = true;
            applyFiltersWithBounds();
        }

        L.control.layers(baseLayers).addTo(map);

        let markersLayer = L.markerClusterGroup();
        let allDistricts = [];
        let allDistrictsLoaded = false;
        let boundaryPolygon = null;
        let currentDistrict = null;
        let isMapTriggered = false;

        function getTypeName(typeNumber) {
            const types = {
                1: "Dự án hạ tầng",
                2: "Dự án bất động sản",
                3: "Khu công nghiệp",
                4: "Khu phức hợp",
                5: "Loại khác",
            };
            return types[typeNumber] || "Không rõ";
        }

        function createMarker(loc) {
            const marker = L.marker([loc.lat, loc.lng]);
            const detailUrl = loc.link || `./chi-tiet.html?id=${loc.id}`;
            const districtText = Array.isArray(loc.districts) ? loc.districts.join(", ") : loc.district || "Không rõ";
            const priceText = loc.price !== null && loc.price !== undefined ? `${loc.price.toLocaleString('vi-VN')} VND` :
                'Chưa có giá';

            marker.bindPopup(`
                <a href="${detailUrl}" target="_blank" style="text-decoration: none; color: inherit;">
                  <div class='info-box'>
                    <strong>${loc.name}</strong><br>
                    Loại: ${getTypeName(loc.type_number)}<br>
                    Khu vực: ${districtText}<br>
                    Quy mô vốn đầu tư: ${priceText}<br>
                    <em>→ Click để xem chi tiết</em>
                  </div>
                </a>
            `);
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

            if (districtName === "all" || !boundaries[districtName]) return;

            boundaryPolygon = L.polygon(boundaries[districtName], {
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

            $.ajax({
                url: '/map/bounds',
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
                    console.error("Lỗi khi tải dữ liệu:", err);
                }
            });
        }

        function renderList(page = 1) {
            const itemsPerPage = 5;
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const allItems = window.industrialResults || [];
            const items = allItems.slice(start, end);
            const $resultList = $("#resultList");

            if (allItems.length === 0) {
                resultList.innerHTML = `
            <li class="list-group-item text-muted justify-content-center">
                Chưa có kết quả tìm kiếm phù hợp.
            </li>
        `;
                $('#pagination').empty();
                return;
            }

            resultList.innerHTML = items.map(item => `
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <strong>${item.name}</strong><br>
                <small>Dự án: ${item.project_name}</small> - Mã dự án: ${item.code}<br>
                <small>Diện tích: ${item.acreage} - Loại hình: ${item.product_type_name}</small>
            </div>
            <a href="${item.link}" target="_blank" class="btn custom-btn btn-sm">Vị trí</a>
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
                const $li = $("<li></li>");
                li.className = `page-item ${active ? "active" : ""}`;
                li.innerHTML = `<button class="page-link">${label}</button>`;
                li.querySelector('button').disabled = active;
                if (!active && pageNum !== null) {
                    li.addEventListener("click", () => renderList(pageNum));
                }
                pagination.appendChild(li);
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
            $.ajax({
                url: '/api/districts',
                method: 'GET',
                success: function(res) {
                    allDistricts = res.sort();
                    allDistrictsLoaded = true;
                },
                error: function(err) {
                    console.error("Lỗi khi tải danh sách quận:", err);
                }
            });
        }
        // PRICE RANGE
        $('#priceRange').on("input", function() {
            $('#priceValue').text(parseInt($(this).val()).toLocaleString('vi-VN') + " VND");
        });

        let priceTimeout = null;
        $('#priceRange').on("change", function() {
            clearTimeout(priceTimeout);
            priceTimeout = setTimeout(applyFiltersWithBounds, 500);
        });

        $('#priceRangeSp').on("input", function() {
            $('#priceValueSp').text(parseInt($(this).val()).toLocaleString('vi-VN') + " VND");
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
                dropdown.append('<div class="px-3 py-2 text-gray-400 italic">Đang tải...</div>');
                return;
            }

            if (filtered.length === 0) {
                dropdown.append('<div class="px-3 py-2 text-gray-500">Không có kết quả</div>');
                return;
            }

            filtered.forEach(d => {
                dropdown.append(`<div class="px-3 py-2 hover-options" data-value="${d}">${d}</div>`);
            });
            dropdown.show();
        }

        $('#districtFilter').on('input', function() {
            const keyword = $(this).val().toLowerCase();
            const filtered = allDistricts.filter(d => d.toLowerCase().includes(keyword));
            $('.custom_tabs').addClass('position-custom');
            renderDistrictDropdown(filtered);
        });

        $(document).on('click', '#districtDropdown div', function() {
            const val = $(this).data('value');
            $('#districtFilter').val(val);
            $('#districtDropdown').hide();
            $('.custom_tabs').removeClass('position-custom');
            applyFiltersWithBounds();
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.pj-search__col').length) {
                $('#districtDropdown').hide();
                $('.custom_tabs').removeClass('position-custom');
            }
        });

        $('#districtFilterSp').on('input', function() {
            const keyword = $(this).val().toLowerCase();
            const filtered = allDistricts.filter(d => d.toLowerCase().includes(keyword));
            $('.custom_tabs').addClass('position-custom');
            renderDistrictDropdown(filtered);
        });

        $(document).on('click', '#districtDropdownSp div', function() {
            const val = $(this).data('value');
            $('#districtFilterSp').val(val);
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
@endpush
