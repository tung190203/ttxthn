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
            <img class="banner__bg" src="./images/banner-news.jpg" alt="" />
            <div class="banner__title">Cẩm nang đầu tư</div>
        </article>
        <nav class="project-nav">
            <div class="container">
                <ul class="project-nav__list">
                    @foreach($childCategories as $id => $name)
                        <li>
                            <a 
                                href="{{ route('category', ['slug' => $setting['menu_active'], 'cat_id' => $id]) }}" 
                                class="{{ (int)$selectedCatId === $id ? 'active' : '' }}">
                                {{ $name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </nav>            
        <section class="section pt-40">
            <div class="container">
                <div class="row g-20">
                    <div class="col-lg-3">
                        <form class="aside-form" method="GET" action="{{ route('category',['slug' => $setting['menu_active']]) }}">
                            <div class="mb-4">
                                <div class="input-group">
                                    <input class="form-control" type="text" name="keyword" placeholder="Tìm kiếm"
                                           value="{{ request('keyword') }}">
                                    <div class="input-group-text"><i class="fal fa-fw fa-search"></i></div>
                                </div>
                            </div>
                        
                            <div class="mb-4">
                                <div class="fw-600 text-uppercase mb-2">Bộ lọc danh mục</div>
                                <select class="form-select" name="cat_id">
                                    <option value="">Toàn bộ</option>
                                    @foreach($childCategories as $id => $name)
                                        <option value="{{ $id }}" {{ (int)$selectedCatId === $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <button class="button button--block" type="submit">Tìm kiếm</button>
                        </form>                        
                    </div>
                    <div class="col-lg-9">
                        <div class="row g-20">
                    @if ($list_post_potential->isEmpty())
                        <div class="col-12">
                            <p class="text-center fs-2">Chưa có cẩm nang đầu tư</p>
                        </div>
                    @else
                        @foreach ($list_post_potential as $item)
                            <div class="col-6 col-lg-4">
                                <div class="news"><a class="news__frame"
                                        href="{{ route('post_detail',['id' => $item->id, 'slug' => $item->slug]) }}"><img
                                            src="{{ $item->image --}}" alt="" /></a>
                                    <div class="news__body">
                                        <div class="news__info">
                                            <div class="news__time"><i
                                                    class="fal fa-clock me-2"></i><span>{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="news__like"><i class="fal fa-fw fa-heart"></i></div>
                                        </div>
                                        <h3 class="news__title custom-desc"><a
                                                href="{{ route('post_detail',['id' => $item->id, 'slug' => $item->slug]) }}">{{ $item->name }}</a></h3>
                                        <div class="news__desc">{{ $item->description }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                        </div>
                        <nav class="d-flex justify-content-center mt-40 mt-lg-50" aria-label="Pagination navigation">
                            {{ $list_post_potential->onEachSide(1)->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('bottom')
@endpush
