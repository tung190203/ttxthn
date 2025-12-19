@extends('frontend.index')

@section('content')
    <div class="page__content">
        <!-- main content-->
        @if ($project->layout_id == 1)
            <section class="pj-banner">
                <nav>
                    <div class="container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="link-unstyled" href="/"><i
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
            <section class="pj-banner">
                <nav>
                    <div class="container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="link-unstyled" href="/"><i
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
            <section class="pj-banner">
                <nav>
                    <div class="container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="link-unstyled" href="/"><i
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
        <nav class="project-nav">
            <div class="container">
                <ul class="project-nav__list">
                    <li><a class="active" href="#thong-tin-chung">{{ __('app.general_info') }}</a></li>
                    <li><a href="#vi-tri">{{ __('app.location') }}</a></li>
                    <li><a href="#loi-the-noi-bat">{{ __('app.key_advantages') }}</a></li>
                    <li><a href="#sa-ban-ao">{{ __('app.virtual_map') }}</a></li>
                    <li><a href="#thiet-ke-va-mat-bang">{{ __('app.design_and_layout') }}</a></li>
                    <li><a href="#phap-ly">{{ __('app.legal_documents') }}</a></li>
                    <li><a href="#thu-tuc-dau-tu">{{ __('app.investment_procedure') }}</a></li>
                    <li><a href="#ke-hoach-trien-khai">{{ __('app.implementation_plan') }}</a></li>
                    <li><a href="#tin-tuc">{{ __('app.news') }}</a></li>
                </ul>                
            </div>
        </nav>
        <section class="section" id="thong-tin-chung"><img class="section__bg" src="{{ asset('./images/achitect-bg.png') }}"
                alt="">
            <div class="container">
                <h2 class="section__title">{{ __('app.general_info') }}</h2>
                <div class="mx-auto" style="max-width: 800px;">
                    {!! $project->description !!}
                </div>
            </div>
        </section>
        <section class="section pb-0" id="vi-tri">
            <div class="container">
                <h2 class="section__title">{{ __('app.location') }}</h2><img class="w-100"
                    src="{{ $project->location_image ?? asset('./images/position.jpg') }}" alt="">
            </div>
        </section>
        <section class="section section--light-blue" id="loi-the-noi-bat">
            <div class="container">
                <h2 class="section__title">{{ __('app.key_advantages') }}</h2>
                <div>
                    @php
                        $images = $project->advantage_images ? explode(';', $project->advantage_images) : [];
                        
                        // Kiểm tra nếu là array thì dùng luôn, nếu là string thì mới decode
                        $titles = is_array($project->advantage_titles) 
                            ? $project->advantage_titles 
                            : json_decode($project->advantage_titles, true) ?? [];

                        $descs = is_array($project->advantage_descriptions) 
                            ? $project->advantage_descriptions 
                            : json_decode($project->advantage_descriptions, true) ?? [];

                        $count = min(count($images), count($titles['vi'] ?? []), count($descs['vi'] ?? []));
                    @endphp

                    @for ($i = 0; $i < $count; $i++)
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
                    @endfor
                </div>
            </div>
        </section>
        <section class="position-relative" id="sa-ban-ao">
            <div class="section section--overlay">
                <div class="container">
                    <h2 class="section__title text-white">{{ __('app.virtual_map') }}</h2>
                    @if($project->link_vrtour)
                        <div class="mt-3">
                            {{-- <a href="{{ route('show_Vrtour', $project->slug) }}" class="btn btn-warning text-white custom-btn-vrtour" --}}
                             <a href="{{ $project->link_vrtour }}" class="btn btn-warning text-white custom-btn-vrtour"
                                target="_blank" rel="noopener noreferrer">
                                {{ __('app.view_vr_tour') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @if($project->link_sand_table)
                <div class="ratio ratio-2x1">
                    <iframe src="{{ $project->link_sand_table }}" frameborder="0" allowfullscreen allow="fullscreen"></iframe>
                </div>
            @endif
            <div class="container">
                <h2 class="section__title text-white">{{ __('app.virtual_map') }}</h2>
            </div>
        </section>
        <section class="section" id="thiet-ke-va-mat-bang">
            <div class="container">
                <h2 class="section__title">{{ __('app.design_and_layout') }}</h2>
                <div class="section__desc">{{ $project->design_short_desc }}</div>
                <div class="design-slider">
                    <div class="design-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            @php
                                // 1. Xử lý ảnh thiết kế
                                $design_images = $project->design_images ? explode(';', $project->design_images) : [];

                                // 2. Xử lý mô tả thiết kế: Kiểm tra nếu đã là array thì dùng luôn, nếu là string thì mới decode
                                $design_descs_raw = is_array($project->design_description) 
                                    ? $project->design_description 
                                    : json_decode($project->design_description, true);

                                // Đảm bảo kết quả cuối cùng là mảng và lấy theo ngôn ngữ (ví dụ: 'vi')
                                $design_descs = $design_descs_raw['vi'] ?? [];

                                // 3. Tính toán số lượng bản ghi để lặp
                                // Lưu ý: nên dùng count của mảng ngôn ngữ cụ thể để chính xác hơn
                                $design_count = min(count($design_images), count($design_descs));
                            @endphp
                            @for ($i = 0; $i < $design_count; $i++)
                                <div class="design-slider__slide swiper-slide">
                                    <div class="design-slider__frame">
                                        <img src="{{ $design_images[$i] ?? '' }}" alt="" />
                                    </div>
                                    <div class="design-slider__overlay">
                                        <div class="design-slider__content">{{ $design_descs[$i] ?? '' }}</div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="design-thumb-slider mt-3 mt-lg-20">
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
        <section class="section section--light-blue" id="phap-ly">
            <div class="container">
                <h2 class="section__title">{{ __('app.legal_documents') }}</h2>
                <div class="section__desc">{{ $project->legal_short_desc }}</div>
                <div class="legal-grid">
                    @php
                        // 1. Xử lý danh sách file (chuỗi nối nhau bằng dấu ;)
                        $legal_files = $project->legal_file ? explode(';', $project->legal_file) : [];
                        
                        // 2. Xử lý mô tả pháp lý: Kiểm tra kiểu dữ liệu trước khi decode
                        $legal_descs_data = is_array($project->legal_description) 
                            ? $project->legal_description 
                            : json_decode($project->legal_description, true);

                        // 3. Lấy mảng theo ngôn ngữ hiện tại (mặc định là 'vi')
                        $legal_descs = $legal_descs_data['vi'] ?? [];
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
                {{-- <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="/projects">Xem
                        thêm</a> --}}
                </nav>
            </div>
        </section>
        <section class="section" id="thu-tuc-dau-tu">
            {{-- <img class="texture-1" src="{{ asset('./images/texture-1.png') }}" alt="">
            <img class="texture-2" src="{{ asset('./images/texture-2.png') }}" alt=""> --}}
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
                                            href="{{ route('investment_guide_detail', ['id' => $item->id, 'slug' => $item->slug, 'ref' => $project->name, 'ref' => $project->name]) }}"><img
                                                src="{{ $item->image }}" alt="" /></a>
                                        <div class="news__body">
                                            <div class="news__info">
                                                <div class="news__time"><i
                                                        class="fal fa-clock me-2"></i><span>{{ \Carbon\Carbon::parse($item->published_at)->format('d/m/Y') }}</span>
                                                </div>
                                                <a class="news__like" href="javascript:void(0)"
                                            data-id="{{ $item->id }}" data-type="App\Models\InvestmentGuide">
                                             <i class="fas fa-fw fa-heart {{ $item->is_interested ? 'text-danger' : '' }}"></i>
                                            </div>
                                            <h3 class="news__title  custom-desc"><a
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
                <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="{{ __('app.investment_guide_link') }}">{{ __('app.view_more') }}</a>
                </nav>
            </div>
        </section>
        <section class="section position-relative" id="ke-hoach-trien-khai">
            <img src="{{asset('/images/texture-8.png')}}" class="texture texture-bottom-left" alt="">
            <img src="{{asset('/images/texture-9.png')}}" class="texture texture-top-right" alt="">
            <div class="container">
                <h2 class="section__title text-center mb-5">{{ __('app.implementation_plan') }}</h2>
                <div class="row g-2 align-items-stretch justify-content-center position-relative">

                    <!-- Bước 1 -->
                    <div class="col-12 col-lg-3 d-flex">
                        <div class="step-card flex-fill d-flex flex-column align-items-center p-4 rounded">
                                <img src="{{asset('images/search-icon.png')}}" alt="">
                            <h5 class="fw-bold text-uppercase mb-3 text-center">
                                {{-- {{ optional($project->plan)->title1 ?? '' }} --}}
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
                    <div class="col-12 col-lg-3 d-flex">
                        <div class="step-card flex-fill d-flex flex-column align-items-center p-4 rounded">
                                <img src="{{asset('images/partner-icon.png')}}" alt="">
                            <h5 class="fw-bold text-uppercase mb-3 text-center">
                                {{-- {{ optional($project->plan)->title2 ?? '' }} --}}
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
                    <div class="col-12 col-lg-3 d-flex">
                        <div class="step-card flex-fill d-flex flex-column align-items-center p-4 rounded">
                                <img src="{{asset('images/analytic-icon.png')}}" alt="">
                            <h5 class="fw-bold text-uppercase mb-3 text-center">
                                {{-- {{ optional($project->plan)->title3 ?? '' }} --}}
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

        <section class="section" id="tin-tuc">
            {{-- <img class="texture-1" src="{{ asset('./images/texture-1.png') }}" alt="">
            <img class="texture-2" src="{{ asset('./images/texture-2.png') }}" alt=""> --}}
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
                                            </div>
                                            <h3 class="news__title  custom-desc"><a
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
                <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="/tin-tuc" style="text-transform: capitalize;">{{ __('app.view_more') }}</a>
                </nav>
            </div>
        </section>
    </div>
@endsection

@push('bottom')
    <script>
        $(document).ready(function () {
            $('.project-nav__list a').click(function () {
                $('.project-nav__list a').removeClass('active');
                $(this).addClass('active');
            });
        });
        $(document).ready(function () {
            const urlParams = new URLSearchParams(window.location.search);
            const hide = urlParams.get("hide");

            if (hide === "saban") {
                const sabanEl = $("#sa-ban-ao");
                if (sabanEl.length) sabanEl.hide();
            }
        });
    </script>
@endpush