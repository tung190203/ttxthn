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
                                        class="fal fa-home me-2"></i><span>Trang chủ</span></a></li>
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
                    <div class="container">
                        <div class="pj-banner__subtitle">Dự án</div>
                        <div class="pj-banner__title">{{ $project->name }}</div>
                        <div class="pj-banner__separator"></div>
                        <div class="pj-banner__desc">{{ $project->short_desc ?? '' }}</div>
                        <div class="pj-banner__icon"><i class="fal fa-arrow-down"></i></div>
                    </div>
                </div>
            </section>
        @elseif($project->layout_id == 2)
            <section class="pj-banner">
                <nav>
                    <div class="container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="link-unstyled" href="/"><i
                                        class="fal fa-home me-2"></i><span>Trang chủ</span></a></li>
                            <li class="breadcrumb-item active">Danh mục dự án đầu tư</li>
                        </ol>
                    </div>
                </nav>
                <img class="pj-banner__bg" src="{{ $project->banner_image ?? 'default-banner.jpg' }}" alt="">
                <div class="pj-banner__wrapper custom-wrapper">
                    <div class="container">
                        <div class="pj-banner__subtitle text-end">Dự án</div>
                        <div class="pj-banner__title text-end">{{ $project->name }}</div>
                        <div class="custom_desc">{{ $project->short_desc ?? '' }}</div>
                    </div>
                </div>
            </section>
        @elseif($project->layout_id == 3)
            <section class="pj-banner">
                <nav>
                    <div class="container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="link-unstyled" href="/"><i
                                        class="fal fa-home me-2"></i><span>Trang chủ</span></a></li>
                            <li class="breadcrumb-item active">Danh mục dự án đầu tư</li>
                        </ol>
                    </div>
                </nav>
                <img class="pj-banner__bg" src="{{ $project->banner_image ?? 'default-banner.jpg' }}" alt="">
                <div class="pj-banner__wrapper custom-wrapper">
                    <div class="container">
                        <div class="pj-banner__subtitle text-start">Dự án</div>
                        <div class="pj-banner__title text-start">{{ $project->name }}</div>
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
                    <li><a class="active" href="#thong-tin-chung">Thông tin chung</a></li>
                    <li><a href="#vi-tri">Vị trí</a></li>
                    <li><a href="#loi-the-noi-bat">Lợi thế nổi bật</a></li>
                    <li><a href="#sa-ban-ao">Sa bàn ảo</a></li>
                    <li><a href="#thiet-ke-va-mat-bang">Thiết kế & mặt bằng</a></li>
                    <li><a href="#phap-ly">Văn bản pháp quy</a></li>
                    <li><a href="#thu-tuc-dau-tu">Thủ tục đầu tư</a></li>
                    <li><a href="#tin-tuc">Tin tức</a></li>
                </ul>
            </div>
        </nav>
        <section class="section" id="thong-tin-chung"><img class="section__bg" src="{{ asset('./images/achitect-bg.png') }}"
                alt="">
            <div class="container">
                <h2 class="section__title">Thông tin chung</h2>
                <div class="mx-auto" style="max-width: 800px;">
                    {!! $project->description !!}
                </div>
            </div>
        </section>
        <section class="section pb-0" id="vi-tri">
            <div class="container">
                <h2 class="section__title">Vị trí</h2><img class="w-100" src="{{ $project->location_image ?? asset('./images/position.jpg') }}"
                    alt="">
            </div>
        </section>
        <section class="section section--light-blue" id="loi-the-noi-bat">
            <div class="container">
                <h2 class="section__title">Lợi thế nổi bật</h2>
                <div>
                    @php
                        $images = $project->advantage_images ? explode(';', $project->advantage_images) : [];
                        $titles = $project->advantage_titles ? json_decode($project->advantage_titles, true) : [];
                        $descs = $project->advantage_descriptions
                            ? json_decode($project->advantage_descriptions, true)
                            : [];

                        // Lấy giá trị min của 3 mảng
                        $count = min(count($images), count($titles), count($descs));
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
                    <h2 class="section__title text-white">Sa bàn ảo</h2>
                    @if($project->link_vrtour)
                        <div class="mt-3">
                            <a href="{{ route('show_Vrtour', $project->slug) }}" class="btn btn-warning text-white custom-btn-vrtour"
                                target="_blank" rel="noopener noreferrer">
                                Xem VR Tour
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @if($project->link_sand_table)
            <div class="ratio ratio-2x1">
                <iframe src="{{ $project->link_sand_table }}" frameborder="0" allowfullscreen
                    allow="fullscreen"></iframe>
            </div>
            @endif
            <div class="container">
                <h2 class="section__title text-white">Sa bàn ảo</h2>
            </div>
        </section>
        <section class="section" id="thiet-ke-va-mat-bang">
            <div class="container">
                <h2 class="section__title">Thiết kế & mặt bằng</h2>
                <div class="section__desc">{{ $project->design_short_desc }}</div>
                <div class="design-slider">
                    <div class="design-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            @php
                                $design_images = $project->design_images ? explode(';', $project->design_images) : [];
                                $design_descs = $project->design_description
                                    ? json_decode($project->design_description, true)
                                    : [];
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
                                    <div class="design-thumb-slider__frame"><img src="{{ $design_images[$i] }}"
                                            alt="" /></div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section section--light-blue" id="phap-ly">
            <div class="container">
                <h2 class="section__title">Văn bản pháp quy</h2>
                <div class="section__desc">{{ $project->legal_short_desc }}</div>
                <div class="legal-grid">
                    @php
                        $legal_files = $project->legal_file ? explode(';', $project->legal_file) : [];
                        $legal_descs = $project->legal_description ? json_decode($project->legal_description, true) : [];
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
                {{-- <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="/projects">Xem thêm</a> --}}
                </nav>
            </div>
        </section>
        <section class="section" id="thu-tuc-dau-tu">
            {{-- <img class="texture-1" src="{{ asset('./images/texture-1.png') }}" alt="">
            <img class="texture-2" src="{{ asset('./images/texture-2.png') }}" alt=""> --}}
        <div class="container">
            <h2 class="section__title">Ưu đãi, quy trình, thủ tục đầu tư</h2>
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
                                            <div class="news__like"><i class="fal fa-fw fa-heart"></i></div>
                                        </div>
                                        <h3 class="news__title  custom-desc"><a
                                                href="{{ route('investment_guide_detail', ['id' => $item->id, 'slug' => $item->slug, 'ref' => $project->name]) }}" data-tippy-content="{{$item->name}}">{{ $item->name }}</a>
                                        </h3>
                                        <div class="news__desc">{{ $item->description }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="/cam-nang-dau-tu">Xem thêm</a>
            </nav>
        </div>
    </section>
        <section class="section" id="tin-tuc">
            {{-- <img class="texture-1" src="{{ asset('./images/texture-1.png') }}"
                alt="">
                <img class="texture-2" src="{{ asset('./images/texture-2.png') }}" alt=""> --}}
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
                                            href="{{ route('post_detail', ['id' => $item->id, 'slug' => $item->slug, 'ref' => $project->name]) }}"><img
                                                src="{{ $item->image }}" alt="" /></a>
                                        <div class="news__body">
                                            <div class="news__info">
                                                <div class="news__time"><i
                                                        class="fal fa-clock me-2"></i><span>{{ \Carbon\Carbon::parse($item->published_at)->format('d/m/Y') }}</span>
                                                </div>
                                                <div class="news__like"><i class="fal fa-fw fa-heart"></i></div>
                                            </div>
                                            <h3 class="news__title  custom-desc"><a
                                                    href="{{ route('post_detail', ['id' => $item->id, 'slug' => $item->slug, 'ref' => $project->name]) }}" data-tippy-content="{{$item->name}}">{{ $item->name }}</a>
                                            </h3>
                                            <div class="news__desc">{{ $item->description }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="/tin-tuc">Xem thêm</a>
                </nav>
            </div>
        </section>
    </div>
@endsection

@push('bottom')
    <script>
        $(document).ready(function() {
            $('.project-nav__list a').click(function() {
                $('.project-nav__list a').removeClass('active');
                $(this).addClass('active');
            });
        });
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const hide = urlParams.get("hide");

            if (hide === "saban") {
                const sabanEl = $("#sa-ban-ao");
                if (sabanEl.length) sabanEl.hide();
            }
        });
    </script>
@endpush
