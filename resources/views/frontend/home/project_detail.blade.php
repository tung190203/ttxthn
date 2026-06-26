@extends('frontend.index')

@section('content')
<style>
    .pj-banner .breadcrumb {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 8px;
        padding: 8px 16px;
        display: inline-flex;
        margin-bottom: 0;
    }

    /* ===== FULLPAGE SCROLL-SNAP: áp dụng trên html element =====
       Chỉ hoạt động khi body có class pj-detail-page (JS thêm vào) */
    body.pj-detail-page {
        overflow: hidden; /* tắt scroll của body */
    }

    body.pj-detail-page .page {
        height: 100vh;
        overflow-y: auto;
        scroll-snap-type: y mandatory;
        scroll-behavior: smooth;
        overflow-x: hidden;
    }

    /* Mỗi section = 1 trang (Ngoại trừ phần banner đầu tiên có layout riêng) */
    body.pj-detail-page .page__content section:not(.pj-banner) {
        min-height: 100vh;
        scroll-snap-align: start;
        scroll-snap-stop: always;
        /* Padding để trừ khoảng trống cho Header và Nav (thanh menu bên dưới) */
        padding-top: 100px;
        padding-bottom: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    body.pj-detail-page .page__content section.pj-banner {
        scroll-snap-align: start;
        scroll-snap-stop: always;
        min-height: 100vh; /* Giữ snap nhưng không ép layout flex */
    }

    /* Footer sẽ được bọc lại bằng JS để snap chuẩn 100vh mà không vỡ layout */

    /* ===== NAV STICKY BOTTOM ===== */
    body.pj-detail-page .project-nav {
        position: fixed !important;
        bottom: 0;
        top: auto !important;
        left: 0;
        right: 0;
        z-index: 9999;
        background: #fff;
        box-shadow: 0 -2px 12px rgba(0,0,0,0.10);
        padding: 8px 0;
    }

    @media (max-width: 991px) {
        body.pj-detail-page .project-nav {
            padding: 6px 0;
        }
    }

    /* Fix Lợi thế nổi bật: đảo chiều xen kẽ khi đã bọc vào slide */
    body.pj-detail-page .swiper-slide:nth-child(even) .advantage {
        flex-direction: row-reverse;
    }

    /* Tùy chỉnh thanh cuộn cho các khu vực nội dung dài (như Thông tin chung) */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.4);
    }

    /* Đảm bảo icon không bị phóng to quá mức trên iPad/Mobile */
    body.pj-detail-page #ke-hoach-trien-khai .step-card > img {
        max-width: 150px;
        height: auto;
    }

    /* =========================================================
       CHỈ ÁP DỤNG ÉP KHUNG 100VH CHO MÀN HÌNH DESKTOP VÀ IPAD (>= 768px)
       Trên Mobile (< 768px), giao diện sẽ mở rộng tự nhiên.
       ========================================================= */
    @media (min-width: 768px) {
        /* Thu bé ảnh Thiết kế & mặt bằng để vừa trong 100vh */
        body.pj-detail-page #thiet-ke-va-mat-bang {
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 100px;
            padding-bottom: 140px; /* Trừ hao khoảng trống cho nav và header */
        }
        body.pj-detail-page #thiet-ke-va-mat-bang > .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }
        body.pj-detail-page #thiet-ke-va-mat-bang .section__title,
        body.pj-detail-page #thiet-ke-va-mat-bang .section__desc {
            flex-shrink: 0;
        }
        body.pj-detail-page #thiet-ke-va-mat-bang .design-slider {
            flex: 1;
            min-height: 0;
        }
        body.pj-detail-page #thiet-ke-va-mat-bang .design-slider__container,
        body.pj-detail-page #thiet-ke-va-mat-bang .design-slider__container > .swiper-wrapper,
        body.pj-detail-page #thiet-ke-va-mat-bang .design-slider__slide,
        body.pj-detail-page #thiet-ke-va-mat-bang .design-slider__frame {
            height: 100% !important;
            width: 100%;
        }
        body.pj-detail-page #thiet-ke-va-mat-bang .design-slider__frame::before {
            display: none !important;
        }
        body.pj-detail-page #thiet-ke-va-mat-bang .design-slider__frame img {
            position: absolute !important;
            top: 0;
            left: 0;
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }
        body.pj-detail-page #thiet-ke-va-mat-bang .design-thumb-slider {
            flex-shrink: 0;
        }
        
        /* Fix chiều cao Kế hoạch triển khai để vừa 100vh bằng cách ép khoảng cách */
        body.pj-detail-page #ke-hoach-trien-khai {
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 100px;
            padding-bottom: 130px;
        }
        body.pj-detail-page #ke-hoach-trien-khai > .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
            justify-content: center;
        }
        /* Thu gọn khoảng cách các phần tử con để tự động khít 100vh mà không thu nhỏ ảnh */
        body.pj-detail-page #ke-hoach-trien-khai .section__title {
            margin-bottom: 30px !important;
        }
        body.pj-detail-page #ke-hoach-trien-khai .step-card {
            padding: 20px !important;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        body.pj-detail-page #ke-hoach-trien-khai .step-card > img {
            margin-bottom: 15px !important;
        }
        body.pj-detail-page #ke-hoach-trien-khai .step-card h5 {
            margin-bottom: 15px !important;
        }
        body.pj-detail-page #ke-hoach-trien-khai .step-card ul {
            margin-bottom: 0 !important;
        }
        body.pj-detail-page #ke-hoach-trien-khai .step-card ul li {
            margin-bottom: 8px;
        }
        body.pj-detail-page #ke-hoach-trien-khai .note {
            margin-top: 20px !important;
        }
    }

    /* Đưa Kế hoạch triển khai thành dạng vuốt ngang trên Mobile để nhét vừa 1 session 100vh */
    @media (max-width: 767.98px) {
        body.pj-detail-page #ke-hoach-trien-khai {
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 80px;
            padding-bottom: 120px;
        }
        body.pj-detail-page #ke-hoach-trien-khai > .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
            justify-content: center;
        }
        body.pj-detail-page #ke-hoach-trien-khai .row {
            flex-wrap: nowrap;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 20px;
            justify-content: flex-start !important;
            scrollbar-width: none; /* Ẩn thanh cuộn trên Firefox */
            scroll-snap-type: x mandatory; /* Bắt dính từng slide */
            scroll-behavior: smooth;
        }
        body.pj-detail-page #ke-hoach-trien-khai .row::-webkit-scrollbar {
            display: none; /* Ẩn thanh cuộn trên Chrome/Safari */
        }
        /* Ẩn mũi tên trên màn hình nhỏ */
        body.pj-detail-page #ke-hoach-trien-khai .row > .col-md-1 {
            display: none !important;
        }
        /* Thẻ bước chiếm 100% chiều rộng như slide */
        body.pj-detail-page #ke-hoach-trien-khai .row > .col-md-3 {
            flex: 0 0 100%;
            max-width: 100%;
            scroll-snap-align: center;
        }
        body.pj-detail-page #ke-hoach-trien-khai .step-card {
            padding: 15px !important;
            height: 100%;
        }
        body.pj-detail-page #ke-hoach-trien-khai .step-card > img {
            max-width: 80px;
            margin-bottom: 10px !important;
        }
        body.pj-detail-page #ke-hoach-trien-khai .step-card h5 {
            font-size: 14px;
            margin-bottom: 10px !important;
        }
        body.pj-detail-page #ke-hoach-trien-khai .step-card ul li {
            margin-bottom: 5px;
            font-size: 13px;
        }
    }
</style>
<div class="page__content">
    <!-- main content-->
    @if ($project->layout_id == 1)
    <section class="pj-banner pt-100">
        <nav>
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="link-unstyled" href="{{ asset('/') }}"><i
                                class="fal fa-home me-2"></i><span>{{ __('app.home') }}</span></a></li>
                    @if (!empty($backUrl) && $backUrl !== url()->current())
                    <li class="breadcrumb-item">
                        <a href="{{ $backUrl }}">{{$backLabel}}</a>
                    </li>
                    @endif
                    <li class="breadcrumb-item active">{{$project->name}}</li>
                </ol>
            </div>
        </nav>
        <img class="pj-banner__bg" src="{{ $project->banner_image ?? 'default-banner.jpg' }}" alt="">
        <div class="pj-banner__wrapper">
            <div class="container" style="text-align: center;">
                <div style="background: rgba(255, 255, 255, 0.349);border-radius:8px;padding:10px 20px;display: inline-block;">
                    <div class="pj-banner__subtitle">{{ __('app.projects') }}</div>
                    <div class="pj-banner__title">{{ $project->name }}</div>
                </div>
                <div class="pj-banner__separator"></div>
                <div class="pj-banner__desc" style="background: white;border-radius:8px;padding:10px 20px;opacity: 0.9; text-align: justify;">{{ $project->short_desc ?? '' }}</div>
            </div>
        </div>
    </section>
    @elseif($project->layout_id == 2)
    <section class="pj-banner pt-100">
        <nav>
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="link-unstyled" href="{{ asset('/') }}"><i
                                class="fal fa-home me-2"></i><span>{{ __('app.home') }}</span></a></li>
                    <li class="breadcrumb-item active">{{ __('app.investment_project_list') }}</li>
                </ol>
            </div>
        </nav>
        <img class="pj-banner__bg" src="{{ $project->banner_image ?? 'default-banner.jpg' }}" alt="">
        <div class="pj-banner__wrapper custom-wrapper">
            <div class="container" style="text-align: right;">
                <div style="background: rgba(255, 255, 255, 0.349);border-radius:8px;padding:10px 20px;display: inline-block;text-align: right;">
                    <div class="pj-banner__subtitle text-end">{{ __('app.projects') }}</div>
                    <div class="pj-banner__title text-end">{{ $project->name }}</div>
                </div>
                <div class="custom_desc" style="text-align:start">{{ $project->short_desc ?? '' }}</div>
            </div>
        </div>
    </section>
    @elseif($project->layout_id == 3)
    <section class="pj-banner pt-100">
        <nav>
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="link-unstyled" href="{{ asset('/') }}"><i
                                class="fal fa-home me-2"></i><span>{{ __('app.home') }}</span></a></li>
                    <li class="breadcrumb-item active">{{ __('app.investment_project_list') }}</li>
                </ol>
            </div>
        </nav>
        <img class="pj-banner__bg" src="{{ $project->banner_image ?? 'default-banner.jpg' }}" alt="">
        <div class="pj-banner__wrapper custom-wrapper">
            <div class="container">
                <div style="background: rgba(255, 255, 255, 0.349);border-radius:8px;padding:10px 20px;display: inline-block;text-align: left;">
                    <div class="pj-banner__subtitle text-start">{{ __('app.projects') }}</div>
                    <div class="pj-banner__title text-start">{{ $project->name }}</div>
                </div>
                <div class="d-flex justify-content-end">
                    <div class="custom_desc">{{ $project->short_desc ?? '' }}</div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @php
    // Kiểm tra các section có content hay không (strip tags và trim để loại bỏ HTML rỗng)
    $hasGeneralInfo = !empty(trim(strip_tags($project->description ?? '')));
    $hasLocation = !empty($project->location_image);
    $hasAdvantages = count(array_filter(explode(';', $project->advantage_images ?? ''))) > 0;
    $effectiveVrtourLink = $project->hide_vrtour ? null : $project->link_vrtour;
    $effectiveSandTableLink = $project->hide_saban ? null : $project->link_sand_table;
    $hasVirtualMap = !empty($effectiveSandTableLink) || !empty($effectiveVrtourLink);
    $hasDesign = count(array_filter(explode(';', $project->design_images ?? ''))) > 0;
    $hasLegal = count(array_filter(explode(';', $project->legal_file ?? ''))) > 0;
    $hasInvestmentGuide = isset($preferential) && count($preferential) > 0;
    $hasPlan = isset($project->plan) && (
    !empty(trim(strip_tags($project->plan->content1 ?? ''))) ||
    !empty(trim(strip_tags($project->plan->content2 ?? ''))) ||
    !empty(trim(strip_tags($project->plan->content3 ?? '')))
    );
    $hasNews = isset($posts) && count($posts) > 0;
    @endphp

    <nav class="project-nav" id="project-detail-nav">
        <div class="container">
            <ul class="project-nav__list">
                @if($hasGeneralInfo)
                <li><a class="active" href="#thong-tin-chung">{{ __('app.general_info') }}</a></li>
                @endif
                @if($hasLocation)
                <li><a href="#vi-tri">{{ __('app.location') }}</a></li>
                @endif
                @if($hasAdvantages)
                <li><a href="#loi-the-noi-bat">{{ __('app.key_advantages') }}</a></li>
                @endif
                @if($hasVirtualMap)
                <li><a href="#sa-ban-ao">{{ __('app.virtual_map') }}</a></li>
                @endif
                @if($hasDesign)
                <li><a href="#thiet-ke-va-mat-bang">{{ __('app.design_and_layout') }}</a></li>
                @endif
                @if($hasLegal)
                <li><a href="#phap-ly">{{ __('app.legal_documents') }}</a></li>
                @endif
                @if($hasInvestmentGuide)
                <li><a href="#thu-tuc-dau-tu">{{ __('app.investment_procedure') }}</a></li>
                @endif
                @if($hasPlan)
                <li><a href="#ke-hoach-trien-khai">{{ __('app.implementation_plan') }}</a></li>
                @endif
                @if($hasNews)
                <li><a href="#tin-tuc">{{ __('app.news') }}</a></li>
                @endif
            </ul>
        </div>
    </nav>

    @if($hasGeneralInfo)
    <section class="section" id="thong-tin-chung" style="height: 100vh; padding-top: 100px; padding-bottom: 160px; display: flex; flex-direction: column; position: relative;">
        <img class="section__bg" src="{{ asset('./images/achitect-bg.png') }}" alt="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0;">
        <div class="container" style="flex: 1; display: flex; flex-direction: column; min-height: 0; position: relative; z-index: 1;">
            <h2 class="section__title" style="flex-shrink: 0;">{{ __('app.general_info') }}</h2>
            <div class="mx-auto custom-scrollbar" style="max-width: 800px; flex: 1; min-height: 0; overflow-y: auto; padding-right: 15px; width: 100%;">
                {!! $project->description !!}
            </div>
        </div>
    </section>
    @endif

    @if($hasLocation)
    <section class="section" id="vi-tri" style="height: 100vh; padding-top: 100px; padding-bottom: 160px; display: flex; flex-direction: column;">
        <div class="container text-center" style="flex: 1; display: flex; flex-direction: column; min-height: 0; justify-content: center; align-items: center;">
            <h2 class="section__title" style="flex-shrink: 0; margin-bottom: 30px;">{{ __('app.location') }}</h2>
            <div style="display: flex; align-items: center; justify-content: center; width: 100%; max-height: 100%; min-height: 0;">
                <img src="{{ $project->location_image ?? asset('./images/position.jpg') }}" alt="" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>
        </div>
    </section>
    @endif

    @if($hasAdvantages)
    <section class="section section--light-blue" id="loi-the-noi-bat">
        <div class="container" style="position: relative;">
            <h2 class="section__title">{{ __('app.key_advantages') }}</h2>
            
            <div class="advantage-slider">
                <div class="news-slider__nav" style="z-index: 10;">
                    <div class="advantage-slider__prev news-slider__prev"><i class="fal fa-fw fa-lg fa-angle-left"></i></div>
                    <div class="advantage-slider__next news-slider__next"><i class="fal fa-fw fa-lg fa-angle-right"></i></div>
                </div>
                <div class="advantage-slider__container swiper-container" style="overflow: hidden;">
                    <div class="swiper-wrapper">
                        @php
                        $images = $project->advantage_images ? array_filter(explode(';', $project->advantage_images)) : [];
                        $titlesRaw = is_array($project->advantage_titles)
                        ? $project->advantage_titles
                        : json_decode($project->advantage_titles, true);
                        $titles = $titlesRaw['vi'] ?? (is_array($titlesRaw) ? $titlesRaw : []);
                        $descsRaw = is_array($project->advantage_descriptions)
                        ? $project->advantage_descriptions
                        : json_decode($project->advantage_descriptions, true);
                        $descs = $descsRaw['vi'] ?? (is_array($descsRaw) ? $descsRaw : []);
                        $count = count($images);
                        @endphp

                        @for ($i = 0; $i < $count; $i++)
                        <div class="swiper-slide">
                            <div class="advantage mt-20">
                                <a class="advantage__frame" href="#!">
                                    <img src="{{ $images[$i] ?? '' }}" alt="" />
                                </a>
                                <div class="advantage__body">
                                    <div class="advantage__index">{{ $i + 1 }}</div>
                                    <div class="advantage__index-bg">{{ $i + 1 }}</div>
                                    <div class="advantage__title">{{ $titles[$i] ?? '' }}</div>
                                    <div class="advantage__desc text-justify">
                                        {!! $descs[$i] ?? '' !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
</section>
@endif

@if($hasVirtualMap)
<section class="section position-relative" id="sa-ban-ao" style="height: 100vh; padding: 0; margin: 0; overflow: hidden;">
    <!-- Khung Iframe 360 tràn viền 100vh -->
    @if($effectiveSandTableLink)
    <div id="vr-iframe-wrapper" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; pointer-events: none;">
        <iframe src="{{ $effectiveSandTableLink }}" frameborder="0" allowfullscreen allow="fullscreen" style="width: 100%; height: 100%; display: block;"></iframe>
    </div>
    
    <!-- Lớp khiên bảo vệ Scroll (Click để bật tương tác) -->
    <div id="vr-scroll-protector" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 5; cursor: pointer; display: flex; align-items: flex-end; justify-content: center; padding-bottom: 120px;" onclick="enableVrInteraction()">
        <div style="background: rgba(0,0,0,0.7); color: white; padding: 10px 24px; border-radius: 30px; font-size: 15px; border: 1px solid rgba(255,255,255,0.3); pointer-events: none; box-shadow: 0 4px 10px rgba(0,0,0,0.5);">
            <i class="fal fa-hand-pointer me-2"></i> Bấm vào màn hình để xoay Sa bàn
        </div>
    </div>
    
    <!-- Nút "Tắt tương tác" (Ẩn mặc định, hiện ra khi đã bật tương tác) -->
    <div id="vr-close-interaction" style="position: absolute; top: 110px; right: 20px; z-index: 20; display: none; cursor: pointer;" onclick="disableVrInteraction()">
        <div style="background: rgba(220, 53, 69, 0.9); color: white; padding: 10px 20px; border-radius: 8px; font-weight: bold; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
            <i class="fal fa-lock me-2"></i> Khóa Sa bàn để Cuộn trang
        </div>
    </div>
    @endif

    <!-- Lớp phủ chứa Tiêu đề và Nút bấm nằm đè lên trên Iframe -->
    <div class="section--overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 10; pointer-events: none; padding-top: 100px;">
        <div class="container text-center">
            <h2 class="section__title text-white" style="pointer-events: auto; text-shadow: 0 2px 10px rgba(0,0,0,0.8);">{{ __('app.virtual_map') }}</h2>
            
            @if($effectiveVrtourLink)
            <div class="mt-4" style="pointer-events: auto; position: relative; height: 100px;">
                <a href="{{ $effectiveVrtourLink }}" class="btn btn-warning text-white custom-btn-vrtour"
                    target="_blank" rel="noopener noreferrer">
                    {{ __('app.view_vr_tour') }}
                </a>
            </div>
            @endif
        </div>
    </div>
</section>

<script>
    function enableVrInteraction() {
        document.getElementById('vr-iframe-wrapper').style.pointerEvents = 'auto';
        document.getElementById('vr-scroll-protector').style.display = 'none';
        document.getElementById('vr-close-interaction').style.display = 'block';
    }
    
    function disableVrInteraction() {
        document.getElementById('vr-iframe-wrapper').style.pointerEvents = 'none';
        document.getElementById('vr-scroll-protector').style.display = 'flex';
        document.getElementById('vr-close-interaction').style.display = 'none';
    }
</script>
@endif

@if($hasDesign)
<section class="section" id="thiet-ke-va-mat-bang">
    <div class="container">
        <h2 class="section__title">{{ __('app.design_and_layout') }}</h2>
        @if(!empty($project->design_short_desc))
        <div class="section__desc">{{ $project->design_short_desc }}</div>
        @endif
        <div class="design-slider">
            <div class="design-slider__container swiper-container">
                <div class="swiper-wrapper">
                    @php
                    $design_images = $project->design_images ? array_filter(explode(';', $project->design_images)) : [];
                    $design_descs_raw = is_array($project->design_description)
                    ? $project->design_description
                    : json_decode($project->design_description, true);
                    $design_descs = $design_descs_raw ?? [];
                    if (!is_array($design_descs)) {
                    $design_descs = [$design_descs];
                    }
                    $design_count = count($design_images);
                    @endphp

                    @if($design_count > 0)
                    @foreach ($design_images as $index => $image)
                    <div class="design-slider__slide swiper-slide">
                        <div class="design-slider__frame">
                            <img src="{{ $image }}" alt="Design image {{ $index + 1 }}" />
                        </div>
                        @if(!empty($design_descs[$index]))
                        <div class="design-slider__overlay">
                            <div class="design-slider__content">
                                {{ $design_descs[$index] }}
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                    @else
                    <p>Không có hình ảnh thiết kế.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="design-thumb-slider mt-3 mt-lg-10">
            <div class="design-thumb-slider__prev"><i class="fal fa-lg fa-angle-left"></i></div>
            <div class="design-thumb-slider__next"><i class="fal fa-lg fa-angle-right"></i></div>
            <div class="design-thumb-slider__container swiper-container">
                <div class="swiper-wrapper">
                    @for ($i = 0; $i < $design_count; $i++)
                        <div class="swiper-slide">
                        <div class="design-thumb-slider__frame"><img src="{{ $design_images[$i] }}" alt="" /></div>
                </div>
                @endfor
            </div>
        </div>
    </div>
    </div>
</section>
@endif

@if($hasLegal)
<section class="section section--light-blue" id="phap-ly">
    <div class="container">
        <h2 class="section__title">{{ __('app.legal_documents') }}</h2>
        @if(!empty($project->legal_short_desc))
        <div class="section__desc">{{ $project->legal_short_desc }}</div>
        @endif
        <div class="legal-grid">
            @php
            $legal_files = $project->legal_file ? explode(';', $project->legal_file) : [];
            $legal_descs_data = is_array($project->legal_description)
            ? $project->legal_description
            : json_decode($project->legal_description, true);
            $legal_descs = $legal_descs_data ?? [];
            @endphp
            @if(count($legal_files) > 0)
            @foreach ($legal_files as $index => $file)
            <a class="legal" href="{{ asset($file) }}" target="_blank" rel="noopener noreferrer">
                <img class="legal__icon" src="{{ asset('./images/icon-pdf.svg') }}" alt="" />
                <div class="legal__body">
                    <div class="legal__title">
                        @if(isset($legal_descs[$index]) && !empty($legal_descs[$index]))
                        <div class="fw-bold text-truncate-multiline">
                            {{ $legal_descs[$index] }}
                        </div>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
            @endif
        </div>
        <nav class="d-flex justify-content-center mt-40 mt-lg-60">
        </nav>
    </div>
</section>
@endif

@if($hasInvestmentGuide)
<section class="section" id="thu-tuc-dau-tu">
    <div class="container">
        <h2 class="section__title pb-2">{{ __('app.incentives_process_procedures') }}</h2>
        <div class="news-slider">
            <div class="news-slider__nav">
                <div class="news-slider__prev"><i class="fal fa-fw fa-lg fa-angle-left"></i></div>
                <div class="news-slider__next"><i class="fal fa-fw fa-lg fa-angle-right"></i></div>
            </div>
            <div class="news-slider__container swiper-container">
                <div class="swiper-wrapper">
                    @foreach ($preferential as $item)
                    <div class="swiper-slide">
                        <div class="news"><a class="news__frame"
                                href="{{ route('investment_guide_detail', ['id' => $item->id, 'slug' => $item->slug, 'ref' => $project->name]) }}"><img
                                    src="{{ $item->image }}" alt="" /></a>
                            <div class="news__body">
                                <div class="news__info">
                                    <div class="news__time"><i
                                            class="fal fa-clock me-2"></i><span>{{ \Carbon\Carbon::parse($item->published_at)->format('d/m/Y') }}</span>
                                    </div>
                                    <a class="news__like" href="javascript:void(0)"
                                        data-id="{{ $item->id }}" data-type="App\Models\InvestmentGuide">
                                        <i class="fas fa-fw fa-heart {{ $item->is_interested ? 'text-danger' : '' }}"></i>
                                    </a>
                                </div>
                                <h3 class="news__title custom-desc"><a
                                        href="{{ route('investment_guide_detail', ['id' => $item->id, 'slug' => $item->slug, 'ref' => $project->name]) }}"
                                        data-tippy-content="{{$item->name}}">{{ $item->name }}</a>
                                </h3>
                                <div class="news__desc">{{ $item->description }}</div>
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
                <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="{{ url($locale . '/' . __('app.investment_guide_link')) }}" style="text-transform: capitalize;">{{ __('app.view_more') }}</a>
                </nav>
    </div>
</section>
@endif

@if($hasPlan)
<section class="section position-relative" id="ke-hoach-trien-khai">
    <img src="{{asset('/images/texture-8.png')}}" class="texture texture-bottom-left" alt="">
    <img src="{{asset('/images/texture-9.png')}}" class="texture texture-top-right" alt="">
    <div class="container">
        <h2 class="section__title text-center mb-5">{{ __('app.implementation_plan') }}</h2>
        <div class="row g-2 align-items-stretch justify-content-center position-relative">

            <!-- Bước 1 -->
            <div class="col-12 col-md-3 d-flex">
                <div class="step-card flex-fill d-flex flex-column align-items-center p-4 rounded">
                    <img src="{{asset('images/search-icon.png')}}" alt="">
                    <h5 class="fw-bold text-uppercase mb-3 text-center">
                        {{ __('app.project_preparation') }}
                    </h5>
                    <ul class="list-unstyled flex-fill">
                        @if (app()->getLocale() === 'vi' || app()->getLocale() === 'vn')
                        {!! optional($project->plan)->content1 ?? '' !!}
                        @else
                        {!! optional($project->plan)->content1_en ?? '' !!}
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Arrow 1 -->
            <div class="col-12 col-md-1 d-flex justify-content-center align-items-baseline">
                <div class="arrow-container">
                    <img src="{{ asset('images/arrow.svg') }}" class="arrow-img" alt="">
                </div>
            </div>

            <!-- Bước 2 -->
            <div class="col-12 col-md-3 d-flex">
                <div class="step-card flex-fill d-flex flex-column align-items-center p-4 rounded">
                    <img src="{{asset('images/partner-icon.png')}}" alt="">
                    <h5 class="fw-bold text-uppercase mb-3 text-center">
                        {{ __('app.investment_promotion') }}
                    </h5>
                    <ul class="list-unstyled flex-fill">
                        @if (app()->getLocale() === 'vi' || app()->getLocale() === 'vn')
                        {!! optional($project->plan)->content2 ?? '' !!}
                        @else
                        {!! optional($project->plan)->content2_en ?? '' !!}
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Arrow 2 -->
            <div class="col-12 col-md-1 d-flex justify-content-center align-items-baseline">
                <div class="arrow-container">
                    <img src="{{ asset('images/arrow.svg') }}" class="arrow-img" alt="">
                </div>
            </div>

            <!-- Bước 3 -->
            <div class="col-12 col-md-3 d-flex">
                <div class="step-card flex-fill d-flex flex-column align-items-center p-4 rounded">
                    <img src="{{asset('images/analytic-icon.png')}}" alt="">
                    <h5 class="fw-bold text-uppercase mb-3 text-center">
                        {{ __('app.implementation_monitoring') }}
                    </h5>
                    <ul class="list-unstyled flex-fill">
                        @if (app()->getLocale() === 'vi' || app()->getLocale() === 'vn')
                        {!! optional($project->plan)->content3 ?? '' !!}
                        @else
                        {!! optional($project->plan)->content3_en ?? '' !!}
                        @endif
                    </ul>
                </div>
            </div>

        </div>
        <div class="d-flex justify-content-end align-items-start align-items-md-center mt-4 mt-lg-5 gap-2 gap-md-4 note">
            <div>
                <img src="{{asset('images/warning-arrow.svg')}}" style="width:15px;height:15px" alt="">
                <div class="d-inline-block ms-2">{{ __('app.in_progress') }}</div>
            </div>
            <div>
                <img src="{{asset('images/success-traces.svg')}}" style="width:15px;height:15px" alt="">
                <div class="d-inline-block ms-2">{{ __('app.completed') }}</div>
            </div>
        </div>
    </div>
</section>
@endif

@if($hasNews)
<section class="section" id="tin-tuc">
    <div class="container">
        <h2 class="section__title">{{ __('app.news') }}</h2>
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
                                href="{{ route('post_detail', ['id' => $item->id, 'slug' => $item->slug, 'ref' => $project->name]) }}"><img
                                    src="{{ $item->image }}" alt="" /></a>
                            <div class="news__body">
                                <div class="news__info">
                                    <div class="news__time"><i
                                            class="fal fa-clock me-2"></i><span>{{ \Carbon\Carbon::parse($item->published_at)->format('d/m/Y') }}</span>
                                    </div>
                                    <a class="news__like" href="javascript:void(0)"
                                        data-id="{{ $item->id }}" data-type="App\Models\Post">
                                        <i class="fas fa-fw fa-heart {{ $item->is_interested ? 'text-danger' : '' }}"></i>
                                    </a>
                                </div>
                                <h3 class="news__title custom-desc"><a
                                        href="{{ route('post_detail', ['id' => $item->id, 'slug' => $item->slug, 'ref' => $project->name]) }}"
                                        data-tippy-content="{{$item->name}}">{{ $item->name }}</a>
                                </h3>
                                <div class="news__desc">{{ $item->description }}</div>
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
@endif
</div>
@endsection

@push('bottom')
<script>
    $(document).ready(function() {
        $('body').addClass('pj-detail-page');

        // Bọc cụm cuối trang vào 1 thẻ section min-height 100vh (display: block) 
        // để đảm bảo snap lấp đầy màn hình mà KHÔNG phá vỡ layout gốc trên màn hình lớn.
        $('.newsletter-wrapper, .news-links__partners, footer.footer').wrapAll('<section class="section footer-snap-wrapper" style="min-height: 100vh; scroll-snap-align: start; scroll-snap-stop: always; background-color: #F4F7FC;"></section>');

        // Tự động cuộn lướt (Auto-slide) phần Kế hoạch triển khai trên Mobile
        const planRow = document.querySelector('#ke-hoach-trien-khai .row');
        if (planRow && window.innerWidth < 768) {
            setInterval(function() {
                const card = planRow.querySelector('.col-md-3');
                if (!card) return;
                const scrollStep = card.offsetWidth;
                
                // Nếu cuộn đến cuối cùng, vòng lại thẻ đầu
                if (planRow.scrollLeft + planRow.clientWidth >= planRow.scrollWidth - 10) {
                    planRow.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    planRow.scrollBy({ left: scrollStep, behavior: 'smooth' });
                }
            }, 3000); // Tự động trượt sau mỗi 3 giây
        }

        const $scrollEl = $('.page');
        const $navLinks = $('.project-nav__list a');
        const $nav      = $('#project-detail-nav');
        const navEl     = $nav[0];
        const footer    = document.querySelector('footer');

        // Click: snap đến đúng section
        $navLinks.on('click', function(e) {
            e.preventDefault();
            const targetId = $(this).attr('href');
            const $target  = $(targetId);
            if ($target.length && $scrollEl.length) {
                const pageTop = $scrollEl[0].getBoundingClientRect().top;
                const secTop  = $target[0].getBoundingClientRect().top;
                const offset  = $scrollEl[0].scrollTop + (secTop - pageTop);
                $scrollEl[0].scrollTo({ top: offset, behavior: 'smooth' });
            }
        });

        // ===== NAV BOTTOM: đẩy lên khi qua hết .page__content =====
        const pageContent = document.querySelector('.page__content');

        function updateNavBottom() {
            if (!pageContent || !navEl) return;
            const contentBottom = pageContent.getBoundingClientRect().bottom;
            const viewH = window.innerHeight;
            // Nếu contentBottom < viewH, nghĩa là phần nội dung chính đã cuộn qua, 
            // các thành phần sau nó (newsletter, partners, footer) đang xuất hiện
            const overlap = viewH - contentBottom;
            const newBottom = overlap > 0 ? Math.round(overlap) + 'px' : '0px';
            navEl.style.setProperty('bottom', newBottom, 'important');
        }

        // Dùng IntersectionObserver cho một element "cột mốc" (ví dụ .newsletter-wrapper hoặc chính footer)
        // để kích hoạt updateNavBottom mượt hơn, nhưng tính toán thì vẫn dựa trên pageContent.
        const firstFooterEl = document.querySelector('.newsletter-wrapper') || document.querySelector('footer');
        if (firstFooterEl) {
            const thresholds = Array.from({ length: 101 }, (_, i) => i / 100);
            new IntersectionObserver(function(entries) {
                updateNavBottom();
            }, { threshold: thresholds }).observe(firstFooterEl);
        }

        // ===== STICKY HEADER (Vì .page là thẻ cuộn nên phải viết riêng) =====
        const $header = $('.header');
        const $headerWrapper = $('.header__wrapper');
        function updateStickyHeader() {
            if ($(window).width() < 1200) return;
            if ($scrollEl[0].scrollTop > 80) {
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
        }

        // Scroll + scrollend
        if ($scrollEl.length) {
            let rafPending = false;
            function onScroll() {
                if (!rafPending) {
                    rafPending = true;
                    requestAnimationFrame(function() {
                        updateNavBottom();
                        updateScrollSpy();
                        updateStickyHeader();
                        rafPending = false;
                    });
                }
            }
            $scrollEl[0].addEventListener('scroll',    onScroll, { passive: true });
            $scrollEl[0].addEventListener('scrollend', onScroll, { passive: true });
        }

        // Scroll spy
        function updateScrollSpy() {
            if (!$scrollEl.length) return;
            const scrollTop = $scrollEl[0].scrollTop;
            const viewH     = $scrollEl[0].clientHeight;
            const midPoint  = scrollTop + viewH / 2;

            let currentId = null;
            $scrollEl.find('.page__content section[id]').each(function() {
                let top = 0, el = this;
                while (el && el !== $scrollEl[0]) { top += el.offsetTop; el = el.offsetParent; }
                const bottom = top + this.offsetHeight;
                if (midPoint >= top && midPoint < bottom) {
                    currentId = '#' + this.id;
                    return false;
                }
            });

            if (currentId) {
                $navLinks.removeClass('active');
                $navLinks.filter('[href="' + currentId + '"]').addClass('active');
            }
        }

        updateNavBottom();
        updateScrollSpy();

        // ===== KHỞI TẠO SWIPER CHO LỢI THẾ NỔI BẬT =====
        if ($('.advantage-slider__container').length) {
            new Swiper('.advantage-slider__container', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    prevEl: '.advantage-slider__prev',
                    nextEl: '.advantage-slider__next',
                }
            });
        }
    });
</script>
@endpush