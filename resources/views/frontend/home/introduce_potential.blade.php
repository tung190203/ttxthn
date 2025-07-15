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
        <section class="section pt-40">
            <div class="container">
                <div class="row g-20">
                    <div class="col-lg-3">
                        <form class="aside-form" action="#!">
                            <div class="mb-4">
                                <div class="input-group">
                                    <input class="form-control" type="text" placeholder="Tìm kiếm">
                                    <div class="input-group-text"><i class="fal fa-fw fa-search"></i></div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="fw-600 text-uppercase mb-2">Bộ lọc</div>
                                <select class="form-select">
                                    <option>Toàn bộ</option>
                                    <option>Giới thiệu tiềm năng</option>
                                    <option>Chính sách, ưu đãi đầu tư</option>
                                    <option>Thủ tục, quy trình đầu tư</option>
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
                                        href="{{ route('new_detail', ['id' => $item->id]) }}"><img
                                            src="./images/news/potential-{{ $loop->iteration }}.jpg" alt="" /></a>
                                    <div class="news__body">
                                        <div class="news__info">
                                            <div class="news__time"><i
                                                    class="fal fa-clock me-2"></i><span>{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            <div class="news__like"><i class="fal fa-fw fa-heart"></i></div>
                                        </div>
                                        <h3 class="news__title custom-desc"><a
                                                href="{{ route('new_detail', ['id' => $item->id]) }}">{{ $item->name }}</a></h3>
                                        <div class="news__desc">{{ $item->description }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                        </div>
                        {{-- <nav class="d-flex justify-content-center mt-40 mt-lg-50">
                            <ul class="pagination">
                                <li class="page-item disabled"><a class="page-link" href="#!"><i
                                                class="fal fa-angle-left"></i></a></li>
                                <li class="page-item active"><a class="page-link" href="#!">1</a></li>
                                <li class="page-item"><a class="page-link" href="#!">2</a></li>
                                <li class="page-item"><a class="page-link" href="#!">3</a></li>
                                <li class="page-item"><a class="page-link" href="#!">4</a></li>
                                <li class="page-item"><a class="page-link" href="#!">5</a></li>
                                <li class="page-item"><a class="page-link" href="#!"><i class="fal fa-angle-right"></i></a>
                                </li>
                            </ul>
                        </nav> --}}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('bottom')
@endpush
