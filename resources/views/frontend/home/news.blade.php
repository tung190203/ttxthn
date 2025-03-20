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
            <img class="banner__bg" src="./images/banner-news.jpg" alt=""/>
            <div class="banner__title">Tin tức</div>
        </article>
        <section class="section">
            <div class="container">
                <div class="row g-3 g-sm-4">
                    <div class="col-6 col-lg-4">
                        <div class="news"><a class="news__frame" href="#!"><img src="./images/news-1.jpg" alt=""/></a>
                            <div class="news__body">
                                <div class="news__info">
                                    <div class="news__time"><i class="fal fa-clock me-2"></i><span>15/04/2024</span>
                                    </div>
                                    <div class="news__like"><i class="fal fa-fw fa-heart"></i></div>
                                </div>
                                <h3 class="news__title"><a href="#!">Tin tức thủ đô mới của Malaysia chuyển về Kuala
                                        Lumper</a></h3>
                                <div class="news__desc">Mỗi một nhiệm vụ cụ thể phải được phân tích, mô tả chi tiết, rõ
                                    ràng; Kế hoạch triển khai phù hợp với phương pháp luận và tiến độ công việc cũ ...
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach(range(1,8) as $item)
                        <div class="col-6 col-lg-4">
                            <div class="news"><a class="news__frame" href="{{ route('new_detail') }}"><img
                                            src="./images/news-1.jpg"
                                            alt=""/></a>
                                <div class="news__body">
                                    <div class="news__info">
                                        <div class="news__time"><i class="fal fa-clock me-2"></i><span>15/04/2024</span>
                                        </div>
                                        <div class="news__like"><i class="fal fa-fw fa-heart"></i></div>
                                    </div>
                                    <h3 class="news__title"><a href="{{ route('new_detail') }}">Tin tức thủ đô mới của
                                            Malaysia chuyển về Kuala
                                            Lumper</a></h3>
                                    <div class="news__desc">Mỗi một nhiệm vụ cụ thể phải được phân tích, mô tả chi tiết,
                                        rõ
                                        ràng; Kế hoạch triển khai phù hợp với phương pháp luận và tiến độ công việc
                                        cũ ...
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <nav class="d-flex justify-content-center mt-40 mt-xl-60">
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
                </nav>
            </div>
        </section>
    </div>
@endsection

@push('bottom')

@endpush
