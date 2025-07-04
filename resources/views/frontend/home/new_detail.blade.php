@extends('frontend.index')

@section('content')
    <div class="page__content">
        <!-- main content-->
        <div class="pt-20">
            <nav>
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="link-unstyled" href="#!"><i
                                        class="fal fa-home me-2"></i><span>Trang chủ</span></a></li>
                        <li class="breadcrumb-item active">Tin tức</li>
                    </ol>
                </div>
            </nav>
        </div>
        <article class="section post">
            <div class="container">
                <h1 class="section__title fw-700">{{ $post->name }}</h1>
            
                @if (!empty($post->description))
                    <h2 class="section__subtitle text-muted mb-4" style="font-weight: normal; font-size: 16px;">
                        {{ $post->description }}
                    </h2>
                @endif
            
                <div class="post__content">
                    {!! $post->content !!}
                </div>
            
                <div class="post__footer">
                    <span class="post__time me-4">
                        <i class="fal fa-clock me-2"></i><span>{{$post->created_at->format('d/m/Y H:i')}}</span>
                    </span>
                </div>
            </div>
        </article>
        <section class="section"><img class="texture-1" src="./images/texture-1.png" alt=""><img class="texture-2"
                                                                                                 src="./images/texture-2.png"
                                                                                                 alt="">
            <div class="container">
                <h2 class="section__title">Tin tức liên quan</h2>
                <div class="news-slider">
                    <div class="news-slider__nav">
                        <div class="news-slider__prev"><i class="fal fa-fw fa-lg fa-angle-left"></i></div>
                        <div class="news-slider__next"><i class="fal fa-fw fa-lg fa-angle-right"></i></div>
                    </div>
                    <div class="news-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            {{-- @foreach(range(1,6) as $item)
                                <div class="swiper-slide">
                                    <div class="news"><a class="news__frame" href="{{ route('new_detail') }}"><img
                                                    src="./images/news-1.jpg"
                                                    alt=""/></a>
                                        <div class="news__body">
                                            <div class="news__info">
                                                <div class="news__time"><i
                                                            class="fal fa-clock me-2"></i><span>15/04/2024</span></div>
                                                <div class="news__like"><i class="fal fa-fw fa-heart"></i></div>
                                            </div>
                                            <h3 class="news__title"><a href="{{ route('new_detail') }}">Tin tức thủ đô
                                                    mới của Malaysia chuyển
                                                    về
                                                    Kuala Lumper</a></h3>
                                            <div class="news__desc">Mỗi một nhiệm vụ cụ thể phải được phân tích, mô tả
                                                chi
                                                tiết, rõ ràng; Kế hoạch triển khai phù hợp với phương pháp luận và tiến
                                                độ
                                                công việc cũ ...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach --}}
                        </div>
                    </div>
                </div>
                <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="#!">Xem thêm</a></nav>
            </div>
        </section>
    </div>
@endsection

@push('bottom')

@endpush
