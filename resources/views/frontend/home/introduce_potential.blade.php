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
            <div class="banner__title">Giới thiệu tiềm năng</div>
        </article>
        <section class="section">
            <div class="container">
                <div class="row g-3 g-sm-4">
                    @if ($list_post_potential->isEmpty())
                        <div class="col-12">
                            <p class="text-center fs-2">Chưa có dự án tiềm năng</p>
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
                                        <h3 class="news__title"><a
                                                href="{{ route('new_detail', ['id' => $item->id]) }}">{{ $item->name }}</a></h3>
                                        <div class="news__desc">{{ $item->description }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                {{-- <nav class="d-flex justify-content-center mt-40 mt-xl-60">
                    <ul class="pagination">
                        <li class="page-item disabled"><a class="page-link" href="#!"><i class="fal fa-angle-left"></i></a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#!">1</a></li>
                        <li class="page-item"><a class="page-link" href="#!">2</a></li>
                        <li class="page-item"><a class="page-link" href="#!">3</a></li>
                        <li class="page-item"><a class="page-link" href="#!">4</a></li>
                        <li class="page-item"><a class="page-link" href="#!">5</a></li>
                        <li class="page-item"><a class="page-link" href="#!"><i class="fal fa-angle-right"></i></a></li>
                    </ul>
                </nav> --}}
            </div>
        </section>
    </div>
@endsection

@push('bottom')
@endpush
