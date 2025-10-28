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
                                        @if (!empty($backUrl) && $backUrl !== url()->current())
                                        <li class="breadcrumb-item">
                                            <a href="{{ $backUrl }}">{{$backLabel}}</a>
                                        </li>
                                    @endif
                        <li class="breadcrumb-item active">{{$post->name}}</li>
                    </ol>
                </div>
            </nav>
        </div>
        <article class=" post custom-news">
            <div class="container text-justify">
                <h1 class="section__title fw-700 text-justify">{{ $post->name }}</h1>
            
                @if (!empty($post->description))
                    <h2 class="section__subtitle custom-text-bold mb-4" style="font-weight: normal; font-size: 16px;">
                        {{ $post->description }}
                    </h2>
                @endif
            
                <div class="post__content">
                    {!! $post->content !!}
                </div>
            
                <div class="post__footer">
                    <span class="post__time me-4">
                        <i class="fal fa-clock me-2"></i><span>{{\Carbon\Carbon::parse($post->published_at)->format('d/m/Y')}}</span>
                    </span>
                </div>
            </div>
        </article>
        <section class="">
            {{-- <img class="texture-1" src="./images/texture-1.png" alt="">
            <img class="texture-2" src="./images/texture-2.png" alt=""> --}}
            <div class="container">
                <h2 class="section__title">Tin tức liên quan</h2>
                <div class="news-slider">
                    <div class="news-slider__nav">
                        <div class="news-slider__prev"><i class="fal fa-fw fa-lg fa-angle-left"></i></div>
                        <div class="news-slider__next"><i class="fal fa-fw fa-lg fa-angle-right"></i></div>
                    </div>
                    <div class="news-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            @foreach($list_post_popular as $item)
                                <div class="swiper-slide">
                                    <div class="news"><a class="news__frame" href="{{ route('post_detail',['id' => $item->id, 'slug' => $item->slug]) }}"><img
                                                    src="{{ $item->image }}"
                                                    alt=""/></a>
                                        <div class="news__body">
                                            <div class="news__info">
                                                <div class="news__time"><i
                                                            class="fal fa-clock me-2"></i><span>{{\Carbon\Carbon::parse($item->published_at)->format('d/m/Y')}}</span></div>
                                                            <a class="news__like" href="javascript:void(0)"
                                                            data-id="{{ $item->id }}" data-type="App\Models\Post">
                                                             <i class="fas fa-fw fa-heart {{ $item->is_interested ? 'text-danger' : '' }}"></i>
                                                         </a>
                                            </div>
                                            <h3 class="news__title custom-desc"><a href="{{ route('post_detail',['id' => $item->id, 'slug' => $item->slug]) }}">{{$item->name}}</a></h3>
                                            <div class="news__desc">
                                                {{ $item->description }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="/tin-tuc" style="text-transform: capitalize;">Xem thêm</a></nav>
            </div>
        </section>
    </div>
@endsection

@push('bottom')

@endpush
