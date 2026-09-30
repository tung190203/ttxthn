@extends('frontend.index')

@section('content')
<div class="page__content">
    <article class="banner d-block px-0">
        <img class="banner__bg" src="{{ asset('images/banner-project.jpg') }}" alt="">
    </article>

    <section class="section">
        <div class="container">
            <h2 class="section__title mb-4 text-uppercase">Bảng điều khiển cá nhân</h2>

            <ul class="nav nav-tabs mb-4" id="dashboardTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" style="font-size: 16px;">TỔNG QUAN</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" style="font-size: 16px;">LỊCH SỬ TRA CỨU</button>
                </li>
            </ul>

            <div class="tab-content" id="dashboardTabContent">
                <!-- TỔNG QUAN -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="alert alert-info mb-0 py-2">
                            <i class="fal fa-info-circle me-2"></i> Kéo thả các tiêu đề khối bên dưới để sắp xếp bố cục theo ý muốn.
                        </div>
                        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#exportPreviewModal">
                            <i class="fal fa-file-export me-2"></i>Xuất dữ liệu (Excel/PDF)
                        </button>
                    </div>

                    <!-- THỐNG KÊ TỔNG QUAN -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="card text-white h-100 rounded shadow-sm border-0" style="background: linear-gradient(45deg, #0d6efd, #0b5ed7);">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-uppercase mb-1" style="font-size: 13px; opacity: 0.9;">Dự án quan tâm</h6>
                                            <h2 class="mb-0 fw-bold">{{ $list_project_interest->count() }}</h2>
                                        </div>
                                        <div class="fs-1" style="opacity: 0.3;"><i class="fal fa-building"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="card text-white h-100 rounded shadow-sm border-0" style="background: linear-gradient(45deg, #198754, #157347);">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-uppercase mb-1" style="font-size: 13px; opacity: 0.9;">Tin tức quan tâm</h6>
                                            <h2 class="mb-0 fw-bold">{{ count($list_post_interest) }}</h2>
                                        </div>
                                        <div class="fs-1" style="opacity: 0.3;"><i class="fal fa-newspaper"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div class="card text-white h-100 rounded shadow-sm border-0" style="background: linear-gradient(45deg, #fd7e14, #e86e10);">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-uppercase mb-1" style="font-size: 13px; opacity: 0.9;">Dự án cập nhật mới</h6>
                                            <h2 class="mb-0 fw-bold">{{ $updatedBookmarks->count() }}</h2>
                                        </div>
                                        <div class="fs-1" style="opacity: 0.3;"><i class="fal fa-bell"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white h-100 rounded shadow-sm border-0" style="background: linear-gradient(45deg, #0dcaf0, #0bacce);">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-uppercase mb-1" style="font-size: 13px; opacity: 0.9;">Lịch sử tra cứu</h6>
                                            <h2 class="mb-0 fw-bold">{{ $viewHistories->count() }}</h2>
                                        </div>
                                        <div class="fs-1" style="opacity: 0.3;"><i class="fal fa-history"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="dashboard-widgets" class="row">
                        <!-- KHỐI 1: BOOKMARK -->
                        <div class="col-lg-6 mb-4 dashboard-widget" data-id="bookmarks">
                            <div class="card h-100 rounded-0">
                                <div class="card-header bg-light border-bottom cursor-move d-flex justify-content-between align-items-center" style="cursor: grab;">
                                    <h5 class="m-0 fw-bold text-uppercase" style="font-size: 16px;"><i class="fal fa-bookmark me-2"></i>Mục yêu thích</h5>
                                    <i class="fal fa-arrows-alt text-secondary"></i>
                                </div>
                                <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                                    @if($bookmarks->isEmpty())
                                        <div class="p-4 text-center text-secondary">
                                            Chưa có dữ liệu.
                                        </div>
                                    @else
                                        <div class="list-group list-group-flush">
                                            @foreach($bookmarks as $b)
                                                @if($b->interestable)
                                                    @php
                                                        $url = '#'; $typeLabel = ''; $typeClass = '';
                                                        if($b->interestable_type == 'App\Models\Project') {
                                                            $url = route('project_detail', ['slug' => $b->interestable->slug ?? 'untitled']);
                                                            $typeLabel = 'Dự án'; $typeClass = 'bg-primary';
                                                        } elseif($b->interestable_type == 'App\Models\Post') {
                                                            $url = route('post_detail', ['slug' => $b->interestable->slug ?? 'untitled', 'id' => $b->interestable->id]);
                                                            $typeLabel = 'Tin tức'; $typeClass = 'bg-success';
                                                        } elseif($b->interestable_type == 'App\Models\InvestmentGuide') {
                                                            $url = route('investment_guide_detail', ['slug' => $b->interestable->slug ?? 'untitled', 'id' => $b->interestable->id]);
                                                            $typeLabel = 'Hướng dẫn'; $typeClass = 'bg-info text-dark';
                                                        }
                                                    @endphp
                                                    <a href="{{ $url }}" class="list-group-item list-group-item-action p-3">
                                                        <h6 class="mb-2 fw-bold text-dark">{{ $b->interestable->name ?? $b->interestable->title }}</h6>
                                                        <div class="d-flex align-items-center mb-1">
                                                            <span class="badge {{ $typeClass }} me-2">{{ $typeLabel }}</span>
                                                            <small class="text-secondary">Ngày lưu: {{ $b->created_at->format('d/m/Y') }}</small>
                                                            @if($b->tag)
                                                                <span class="badge bg-secondary ms-2">{{ $b->tag }}</span>
                                                            @endif
                                                        </div>
                                                        @if($b->notes)
                                                            <div class="mt-2 text-secondary small fst-italic">
                                                                - Ghi chú: {{ $b->notes }}
                                                            </div>
                                                        @endif
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- KHỐI 2: DỰ ÁN CẬP NHẬT -->
                        <div class="col-lg-6 mb-4 dashboard-widget" data-id="updates">
                            <div class="card h-100 rounded-0">
                                <div class="card-header bg-light border-bottom cursor-move d-flex justify-content-between align-items-center" style="cursor: grab;">
                                    <h5 class="m-0 fw-bold text-uppercase" style="font-size: 16px;"><i class="fal fa-bell me-2"></i>Cập nhật mới</h5>
                                    <i class="fal fa-arrows-alt text-secondary"></i>
                                </div>
                                <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                                    @if($updatedBookmarks->isEmpty())
                                        <div class="p-4 text-center text-secondary">
                                            Không có cập nhật mới trong 7 ngày qua.
                                        </div>
                                    @else
                                        <div class="list-group list-group-flush">
                                            @foreach($updatedBookmarks as $b)
                                                @php
                                                    $url = '#';
                                                    if($b->interestable_type == 'App\Models\Project') { $url = route('project_detail', ['slug' => $b->interestable->slug ?? 'untitled']); }
                                                    elseif($b->interestable_type == 'App\Models\Post') { $url = route('post_detail', ['slug' => $b->interestable->slug ?? 'untitled', 'id' => $b->interestable->id]); }
                                                    elseif($b->interestable_type == 'App\Models\InvestmentGuide') { $url = route('investment_guide_detail', ['slug' => $b->interestable->slug ?? 'untitled', 'id' => $b->interestable->id]); }
                                                @endphp
                                                <a href="{{ $url }}" class="list-group-item list-group-item-action p-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0 fw-bold text-dark w-75">{{ $b->interestable->name ?? $b->interestable->title }}</h6>
                                                        <span class="badge bg-danger">MỚI</span>
                                                    </div>
                                                    <small class="text-secondary mt-2 d-block">Thời gian cập nhật: {{ $b->interestable->updated_at->format('d/m/Y H:i') }}</small>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- KHỐI 3: GỢI Ý DỰ ÁN -->
                        <div class="col-12 mb-4 dashboard-widget" data-id="suggestions">
                            <div class="card rounded-0">
                                <div class="card-header bg-light border-bottom cursor-move d-flex justify-content-between align-items-center" style="cursor: grab;">
                                    <h5 class="m-0 fw-bold text-uppercase" style="font-size: 16px;"><i class="fal fa-lightbulb-on me-2"></i>Gợi ý dự án đầu tư</h5>
                                    <i class="fal fa-arrows-alt text-secondary"></i>
                                </div>
                                <div class="card-body">
                                    @if($suggestions->isEmpty())
                                        <div class="text-center text-secondary">
                                            Vui lòng cập nhật thông tin lĩnh vực quan tâm để nhận gợi ý phù hợp.
                                        </div>
                                    @else
                                        <div class="row">
                                            @foreach($suggestions as $s)
                                                <div class="col-md-4 mb-4">
                                                    <div class="project border h-100">
                                                        <a class="project__frame" href="{{ route('project_detail', ['slug' => $s->slug ?? 'untitled']) }}">
                                                            <img src="{{ $s->detail_image ?? asset('images/project-1.jpg') }}" alt="">
                                                        </a>
                                                        <div class="project__body p-3">
                                                            <h3 class="project__title" style="font-size: 16px;">
                                                                <a href="{{ route('project_detail', ['slug' => $s->slug ?? 'untitled']) }}">{{ $s->name }}</a>
                                                            </h3>
                                                            <p class="text-secondary small mb-0" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                                                {{ Str::limit(strip_tags($s->short_desc), 120) }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LỊCH SỬ TRA CỨU -->
                <div class="tab-pane fade" id="history" role="tabpanel">
                    <div class="card rounded-0">
                        <div class="card-body p-0">
                            @if($viewHistories->isEmpty())
                                <div class="p-4 text-center text-secondary">
                                    Không có dữ liệu lịch sử tra cứu.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center" width="20%">Thời gian</th>
                                                <th>Tên dự án</th>
                                                <th class="text-center" width="15%">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($viewHistories as $vh)
                                                @if($vh->project)
                                                <tr>
                                                    <td class="text-center align-middle">{{ $vh->viewed_at->format('d/m/Y H:i') }}</td>
                                                    <td class="align-middle fw-bold">
                                                        <a href="{{ route('project_detail', ['slug' => $vh->project->slug ?? 'untitled']) }}" class="text-dark">{{ $vh->project->name }}</a>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <a href="{{ route('project_detail', ['slug' => $vh->project->slug ?? 'untitled']) }}" class="btn btn-sm btn-outline-primary rounded-0">Xem chi tiết</a>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="project-interest">
        <div class="container position-relative">
            <h2 class="section__title mb-3 text-uppercase">
                {{ __('app.interested_projects') }}
                
                <form action="{{ route('guest.compare.create') }}" method="POST" class="d-inline-block ms-3" id="form-compare">
                    @csrf
                    <div id="compare-inputs"></div>
                    <button type="submit" id="btn-compare" class="btn btn-sm btn-primary shadow-sm" disabled style="display: none;">
                        <i class="fal fa-balance-scale me-1"></i> So sánh (<span id="compare-count">0</span>)
                    </button>
                    <small id="compare-error" class="text-danger ms-2 d-none">Chỉ so sánh từ 2-4 dự án</small>
                </form>
            </h2>
            <div class="project-nav-wrapper mb-60">
                <div class="project-nav-fixed">
                  <a class="{{ request('industry') ? '' : 'active' }}"
                     href="{{ route('guest.dashboard', ['keyword' => request('keyword')]) }}#project-interest">
                    {{ __('app.all') }}
                  </a>
                </div>
                <div class="project-nav-scroll swiper-container">
                    <ul class="project-nav__list_custom swiper-wrapper">
                      @foreach($industries as $industry)
                        <li class="swiper-slide" data-tippy-content="{{ $industry['name'] }}">
                          <a class="{{ request('industry') == $industry['id'] ? 'active' : '' }}"
                             href="{{ route('guest.dashboard', ['industry' => $industry['id']]) }}#project-interest">
                            {{ $industry['name'] }}
                          </a>
                        </li>
                      @endforeach
                    </ul>
                </div>
            </div>

            <form class="pj-search mb-60" action="{{ route('guest.dashboard') }}#project-interest" method="GET" style="padding: 0; background: 0;">
                <div class="input-group">
                    <input name="keyword" value="{{ request('keyword') }}" class="form-control" type="text" placeholder="{{ __('app.search') }}" />
                    <div class="input-group-text">
                        <button type="submit" class="btn p-0"><i class="fal fa-lg fa-search"></i></button>
                    </div>
                </div>
                @if(request('industry'))
                    <input type="hidden" name="industry" value="{{ request('industry') }}">
                @endif
            </form>                    
            @if(count($list_project_interest) === 0)
            <p class="text-center">{{ __('app.no_interested_projects') }}</p>
            @else                 
            <div class="news-slider">
                <div class="news-slider__nav">
                    <div class="news-slider__prev"><i class="fal fa-fw fa-lg fa-angle-left"></i></div>
                    <div class="news-slider__next"><i class="fal fa-fw fa-lg fa-angle-right"></i></div>
                </div>
                <div class="news-slider__container swiper-container">
                    <div class="swiper-wrapper">
                        @foreach($list_project_interest as $item)
                            <div class="swiper-slide project-slide" data-name="{{ strtolower($item['name']) }}">
                                <div>
                                    <div class="project"><a class="project__frame" href="{{ route('project_detail',['slug' => $item['slug'], 'ref' => 'Dự án kêu gọi đầu tư']) }}"><img src="{{$item['detail_image'] ?? './images/project-1.jpg' }}"
                                                alt="" /></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="{{ route('project_detail',['slug' => $item['slug'], 'ref' => 'Dự án kêu gọi đầu tư']) }}" data-tippy-content="{{$item['name']}}">{{$item['name']}}</a></h3>
                                            @if($item['is_invest'] == 0)
                                            <div class="project__overlay"><span>{{ __('app.projects_calling_for_investment') }}</span>
                                                <a class="project__like" href="javascript:void(0)" data-id="{{ $item['id'] }}" data-type="App\Models\Project"><i class="fas fa-fw fa-lg fa-heart {{ $item['is_interested'] ? 'text-danger' : '' }}"></i></a>
                                            </div>
                                            @elseif($item['is_invest'] == 1)
                                            <div class="project__overlay"><span>{{ __('app.projects_with_investors') }}</span>
                                                <a class="project__like" href="javascript:void(0)" data-id="{{ $item['id'] }}" data-type="App\Models\Project"><i class="fas fa-fw fa-lg fa-heart {{ $item['is_interested'] ? 'text-danger' : '' }}"></i></a>
                                            </div>
                                            @elseif($item['is_invest'] == 2)
                                            <div class="project__overlay"><span>{{ __('app.projects_proposed') }}</span>
                                                <a class="project__like" href="javascript:void(0)" data-id="{{ $item['id'] }}" data-type="App\Models\Project"><i class="fas fa-fw fa-lg fa-heart {{ $item['is_interested'] ? 'text-danger' : '' }}"></i></a>
                                            </div>
                                            @endif
                                            <ul class="project__info">
                                                <li><img class="me-2" src="{{ asset('/images/icon-map-marker.svg') }}"
                                                        alt="" /><span data-tippy-content="{{ __('app.project_under') }} {{ $item->districts->pluck('name')->join(', ') }}, {{ __('app.hanoi_city') }}">{{ __('app.project_under') }} {{ $item->districts->pluck('name')->join(', ') }}, {{ __('app.hanoi_city') }}</span></li>
                                                <li>
                                                    <img class="me-2" src="{{ asset('/images/icon-dimension.svg') }}" alt="" />
                                                    @php
                                                        $hasArea = $item->area !== null && $item->area !== '';
                                                        $formattedArea = $hasArea ? formatDecimalByLocale($item->area) . ' ' . ($item->unit_type_text ?? '') : __('app.updating');
                                                    @endphp
                                                    <span>{{ $formattedArea }}</span>
                                                </li>
                                                <li><img class="me-2" src="{{ asset('/images/icon-save-money.svg') }}" alt="" /><span>{{ $item->price !== null && $item->price !== '' ? number_format($item->price, 0, ',', '.') . ' ' . __('app.billion_vnd') : __('app.updating') }}</span></li>
                                            </ul>
                                            @if($item->interest_tag || $item->interest_notes)
                                                <div class="mt-2 p-2 bg-light rounded text-start" style="font-size: 13px;">
                                                    @if($item->interest_tag)
                                                        <span class="badge bg-secondary mb-1">{{ $item->interest_tag }}</span><br>
                                                    @endif
                                                    @if($item->interest_notes)
                                                        <span class="text-muted fst-italic"><i class="fal fa-quote-left me-1"></i>{{ Str::limit($item->interest_notes, 50) }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                                <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" 
                                                    onclick="openBookmarkModal('{{ $item->interest_id }}', '{{ e($item->interest_notes) }}', '{{ e($item->interest_tag) }}')">
                                                    <i class="fal fa-edit me-1"></i> Ghi chú & Phân loại
                                                </button>
                                                
                                                <div class="form-check m-0">
                                                    <input class="form-check-input compare-checkbox cursor-pointer" type="checkbox" value="{{ $item['id'] }}" id="compare_{{ $item['id'] }}">
                                                    <label class="form-check-label small cursor-pointer fw-bold text-primary" for="compare_{{ $item['id'] }}">
                                                        So sánh
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
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
            <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="{{ url($locale . '/' . __('app.projects_link')) }}" style="text-transform: capitalize;">{{ __('app.view_more') }}</a></nav>
            @endif
        </div>
    </section>

    <section class="section" id="post-interest">
        <div class="container">
            <h2 class="section__title">{{ __('app.interested_news') }}</h2>
        
            @if(count($list_post_interest) === 0)
                <p class="text-center">{{ __('app.no_interested_news') }}</p>
            @else
                <div class="news-slider">
                    <div class="news-slider__nav">
                        <div class="news-slider__prev"><i class="fal fa-fw fa-lg fa-angle-left"></i></div>
                        <div class="news-slider__next"><i class="fal fa-fw fa-lg fa-angle-right"></i></div>
                    </div>
                    <div class="news-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            @foreach($list_post_interest as $item)
                                <div class="swiper-slide">
                                    <div class="news">
                                        <a class="news__frame" href="{{ route('post_detail',['id' => $item['id'], 'slug' => $item['slug'], 'ref' => 'app.news']) }}">
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
                                                    <i class="fas fa-fw fa-heart {{ $item['is_interested'] ? 'text-danger' : '' }}"></i>
                                                </a>
                                            </div>
                                            <h3 class="news__title custom-desc">
                                                <a href="{{ route('post_detail',['id' => $item['id'], 'slug' => $item['slug'], 'ref' => 'app.news']) }}"
                                                   data-tippy-content="{{ $item['name'] }}">
                                                    {{ $item['name'] }}
                                                </a>
                                            </h3>
                                            <div class="news__desc">{{ $item['description'] }}</div>
                                            @if($item['interest_tag'] || $item['interest_notes'])
                                                <div class="mt-2 p-2 bg-light rounded text-start" style="font-size: 13px;">
                                                    @if($item['interest_tag'])
                                                        <span class="badge bg-secondary mb-1">{{ $item['interest_tag'] }}</span><br>
                                                    @endif
                                                    @if($item['interest_notes'])
                                                        <span class="text-muted fst-italic"><i class="fal fa-quote-left me-1"></i>{{ Str::limit($item['interest_notes'], 50) }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="mt-3 text-center">
                                                <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" 
                                                    onclick="openBookmarkModal('{{ $item['interest_id'] }}', '{{ e($item['interest_notes']) }}', '{{ e($item['interest_tag']) }}')">
                                                    <i class="fal fa-edit me-1"></i> Ghi chú & Phân loại
                                                </button>
                                            </div>
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
                <nav class="d-flex justify-content-center mt-40 mt-lg-60">
                    <a class="button" href="{{ url($locale . '/' . __('app.news_link')) }}" style="text-transform: capitalize;">{{ __('app.view_more') }}</a>
                </nav>
            @endif
        </div>
    </section>

</div>
    <!-- Bookmark Edit Modal -->
    <div class="modal fade" id="bookmarkModal" tabindex="-1" aria-labelledby="bookmarkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="bookmarkModalLabel">Chỉnh sửa Ghi chú & Phân loại</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="bookmarkForm">
                        <input type="hidden" id="bookmark_id" name="bookmark_id">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nhãn phân loại (Tag)</label>
                            <select class="form-select" id="bookmark_tag" name="tag">
                                <option value="">-- Không có nhãn --</option>
                                <option value="Quan trọng">Quan trọng</option>
                                <option value="Đang theo dõi">Đang theo dõi</option>
                                <option value="Đã liên hệ">Đã liên hệ</option>
                                <option value="Chờ cập nhật">Chờ cập nhật</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú cá nhân</label>
                            <textarea class="form-control" id="bookmark_notes" name="notes" rows="4" placeholder="Nhập ghi chú cho dự án/tin tức này..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="saveBookmark()">Lưu thay đổi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Preview Modal -->
    <div class="modal fade" id="exportPreviewModal" tabindex="-1" aria-labelledby="exportPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="exportPreviewModalLabel"><i class="fal fa-file-search me-2"></i>Xem trước dữ liệu trích xuất</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="alert alert-warning m-3 mb-0 border-0 rounded-0 border-start border-4 border-warning">
                        <i class="fal fa-info-circle me-2"></i> Bảng dưới đây là bản xem trước của dữ liệu sẽ được xuất ra file Excel/PDF. Dữ liệu bao gồm các Dự án và Tin tức mà bạn đã đánh dấu quan tâm, kèm theo các Ghi chú và Nhãn phân loại cá nhân của bạn.
                    </div>
                    <div class="table-responsive p-3">
                        <table class="table table-bordered table-striped table-hover mb-0" style="font-size: 14px;">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%" class="text-center">STT</th>
                                    <th width="10%">Loại</th>
                                    <th width="30%">Tên danh mục</th>
                                    <th width="15%">Nhãn phân loại</th>
                                    <th width="25%">Ghi chú cá nhân</th>
                                    <th width="15%">Ngày lưu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $stt = 1; @endphp
                                @forelse($bookmarks as $b)
                                    @if($b->interestable)
                                    <tr>
                                        <td class="text-center">{{ $stt++ }}</td>
                                        <td>
                                            @if($b->interestable_type == 'App\Models\Project')
                                                <span class="badge bg-primary">Dự án</span>
                                            @elseif($b->interestable_type == 'App\Models\Post')
                                                <span class="badge bg-success">Tin tức</span>
                                            @else
                                                <span class="badge bg-secondary">Khác</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold">{{ $b->interestable->name ?? $b->interestable->title ?? '' }}</td>
                                        <td>
                                            @if($b->tag)
                                                <span class="badge bg-secondary">{{ $b->tag }}</span>
                                            @endif
                                        </td>
                                        <td class="fst-italic text-muted">{{ Str::limit($b->notes, 50) }}</td>
                                        <td>{{ $b->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Chưa có dữ liệu nào để xuất.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    @if($bookmarks->count() > 0)
                        <a href="{{ route('guest.dashboard.export') }}" class="btn btn-success" onclick="$('#exportPreviewModal').modal('hide');">
                            <i class="fal fa-file-excel me-2"></i>Xác nhận Xuất Excel
                        </a>
                        <!-- Nút PDF có thể làm sau hoặc disable tạm -->
                        <button type="button" class="btn btn-danger" onclick="alert('Tính năng xuất PDF đang được phát triển');" disabled>
                            <i class="fal fa-file-pdf me-2"></i>Xuất PDF
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('dashboard-widgets');
        if (el) {
            var sortable = Sortable.create(el, {
                handle: '.cursor-move',
                animation: 150,
                onEnd: function () {
                    var newLayout = [];
                    document.querySelectorAll('.dashboard-widget').forEach(function(widget) {
                        newLayout.push(widget.getAttribute('data-id'));
                    });
                    
                    $.ajax({
                        url: '{{ route('guest.dashboard.layout') }}',
                        method: 'POST',
                        data: { _token: '{{ csrf_token() }}', layout: newLayout },
                        success: function(res) {
                            if(res.success) {
                                toastr.success('Đã lưu bố cục thành công.');
                            }
                        }
                    });
                }
            });

            var savedLayout = @json($layout);
            if (savedLayout && savedLayout.length > 0) {
                var container = el;
                savedLayout.forEach(function(id) {
                    var widget = container.querySelector('[data-id="' + id + '"]');
                    if (widget) container.appendChild(widget);
                });
            }
        }
    });
</script>

<script>
    function openBookmarkModal(id, notes, tag) {
        document.getElementById('bookmark_id').value = id;
        document.getElementById('bookmark_notes').value = notes || '';
        document.getElementById('bookmark_tag').value = tag || '';
        
        var bookmarkModal = new bootstrap.Modal(document.getElementById('bookmarkModal'));
        bookmarkModal.show();
    }

    function saveBookmark() {
        const id = document.getElementById('bookmark_id').value;
        const notes = document.getElementById('bookmark_notes').value;
        const tag = document.getElementById('bookmark_tag').value;

        $.ajax({
            url: '/interest/' + id,
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                notes: notes,
                tag: tag
            },
            success: function(res) {
                if(res.success) {
                    alert(res.message);
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra.');
                }
            },
            error: function(err) {
                if (err.status === 401) {
                    alert('Vui lòng đăng nhập.');
                } else {
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
                }
            }
        });
    }
    // Compare selection logic
    const compareCheckboxes = document.querySelectorAll('.compare-checkbox');
    const compareInputs = document.getElementById('compare-inputs');
    const btnCompare = document.getElementById('btn-compare');
    const compareCount = document.getElementById('compare-count');
    const compareError = document.getElementById('compare-error');

    compareCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateCompareState();
        });
    });

    function updateCompareState() {
        // Get all checked boxes
        const checked = document.querySelectorAll('.compare-checkbox:checked');
        const count = checked.length;
        
        // Update count display
        compareCount.textContent = count;
        
        // Show/hide button
        if (count > 0) {
            btnCompare.style.display = 'inline-block';
        } else {
            btnCompare.style.display = 'none';
        }
        
        // Validate constraints (2-4 projects)
        if (count >= 2 && count <= 4) {
            btnCompare.disabled = false;
            compareError.classList.add('d-none');
        } else {
            btnCompare.disabled = true;
            if (count > 4) {
                compareError.classList.remove('d-none');
            } else {
                compareError.classList.add('d-none');
            }
        }
        
        // Update hidden form inputs
        compareInputs.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'project_ids[]';
            input.value = cb.value;
            compareInputs.appendChild(input);
        });
    }
</script>
@endpush
