@extends('frontend.index')

@section('content')
    <div class="page__content">
        <!-- main content-->
        <article class="banner">
            <div class="banner__breadcrumb">
                <nav>
                    <div class="container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active"></li>
                        </ol>
                    </div>
                </nav>
            </div>
            <img class="banner__bg" src="./images/tin-tuc-banner.jpg" alt=""/>
            <div class="banner__title">Tin tức</div>
        </article>
        <section class=" pt-40 pb-40">
            <div class="container">
                <div class="row g-3 g-sm-4">
                    @forelse($posts as $item)
                        <div class="col-6 col-lg-4">
                            <div class="news">
                                <a class="news__frame" href="{{ route('post_detail',['id' => $item->id, 'slug' => $item->slug, 'ref' => 'Tin tức']) }}">
                                    <img src="{{$item->image}}" alt=""/>
                                </a>
                                <div class="news__body">
                                    <div class="news__info">
                                        <div class="news__time">
                                            <i class="fal fa-clock me-2"></i>
                                            <span>{{ \Carbon\Carbon::parse($item->published_at)->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="news__like">
                                            <i class="fal fa-fw fa-heart"></i>
                                        </div>
                                    </div>
                                    <h3 class="news__title custom-desc">
                                        <a href="{{ route('post_detail',['id' => $item->id, 'slug' => $item->slug, 'ref' => 'Tin tức']) }}" data-tippy-content="{{$item->name}}">
                                            {{ $item->name }}
                                        </a>
                                    </h3>
                                    <div class="news__desc">{{ $item->description }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">Không có tin tức nào để hiển thị.</p>
                        </div>
                    @endforelse
                </div>
                <nav class="d-flex justify-content-center mt-40 mt-lg-50" aria-label="Pagination navigation">
                    {{ $posts->onEachSide(1)->links('pagination::bootstrap-4') }}
                </nav>
            </div>
        </section>
    </div>
@endsection

@push('bottom')

@endpush
