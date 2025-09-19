@extends('frontend.index')

@section('content')
    <div class="page__content">
        <!-- main content-->
        <nav class="breadcrumb-wrapper">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="link-unstyled" href="{{ route('home_page') }}">
                            <i class="fal fa-fw fa-home me-2"></i>
                            <span>Trang chủ</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Tìm kiếm</li>
                </ol>
            </div>
        </nav>
        <section class="section pt-10 pb-60">
            <div class="container">
                <h1 class="section__title">Kết quả tìm kiếm</h1>
                <div class="mx-auto mt-4" style="max-width: 800px">
                    <form class="search-field" method="GET" action="{{ route('search') }}">
                        <div class="input-group">
                            <input class="form-control" type="text" name="key" value="{{ $key ?? '' }}"
                                   placeholder="Tìm kiếm..."/>
                            <input type="hidden" name="type" value="{{ $type }}">
                            <button class="input-group-text"><i class="fal fa-fw fa-search"></i></button>
                        </div>
                    </form>
                    <nav class="tags mt-3 mb-4 mb-lg-40">
                        @foreach($list_module_allow_search as $module_key => $module_name)
                            <a class="tags__item @if($module_key == $type)active @endif"
                               href="{{ route('search') }}?key={{ $key }}&type={{ $module_key }}">{{ $module_name }}</a>
                        @endforeach
                    </nav>
                </div>
                <div class="row g-3 g-xl-30">
                    @forelse($results as $result)
                        <div class="col-6 col-lg-4">
                            <article class="news">
                                <a class="news__frame" href="{{ $result->getUrl() }}">
                                    <img src="{{ $result->image }}" alt="{{ $result->name }}"/></a>
                                <div class="news__body">
                                    <h3 class="news__title">
                                        <a href="{{ $result->getUrl() }}">{{ $result->name }}</a>
                                    </h3>
                                    <div class="news__info">
                                        @if(data_get($result, 'category.name'))
                                            <span class="news__tag">{{ data_get($result, 'category.name') }}</span>
                                        @endif
                                        <span class="news__time">{{ $result->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="news__desc">
                                        @if($type == 'destination')
                                            {!! strip_tags($result->info) !!}
                                        @else
                                            {!! strip_tags($result->description) !!}
                                        @endif
                                    </div>
                                    <div class="mt-auto">
                                        <a class="news__link" href="{{ $result->getUrl() }}">Xem thêm</a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @empty
                        <p class="text-center text-danger">Không tìm thấy kết quả, Vui lòng thử với từ khóa khác</p>
                    @endforelse
                </div>
                <nav class="d-flex justify-content-center mt-30">
                    {!! $results->links() !!}
                </nav>
            </div>
        </section>
    </div>
@endsection

@push('bottom')

@endpush
