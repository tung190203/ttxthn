@extends('frontend.index')

@section('content')
    <div class="page__content">
        <!-- main content-->
        <article class="banner d-block px-0">
            <div class="banner__breadcrumb">
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
            <img class="banner__bg" src="./images/banner-project.jpg" alt="">
            <div class="container text-end">
                <div class="position-relative" style="z-index: 100;">
                    <label class="upload-img"><i class="fal fa-camera"></i>
                        <input type="file" accept="image/png, image/jpeg"/>
                    </label>
                </div>
            </div>
        </article>
        <section class="acc-info">
            <div class="container">
                <div class="acc-info__header">
                    <div class="acc-info__left">
                        <div class="acc-info__avatar"><img src="./images/avatar-placeholder.png" alt=""></div>
                        <label class="upload-img acc-info__upload"><i class="fal fa-camera"></i>
                            <input type="file" accept="image/png, image/jpeg"/>
                        </label>
                    </div>
                    <div class="acc-info__right">
                        <div class="acc-info__name">Nguyễn Thùy Dương</div>
                        <div class="acc-info__phone"><i class="fal fa-phone me-2"></i><a href="tel:(7657) 876 - 8988">(7657)
                                876 - 8988</a></div>
                    </div>
                </div>
                <div class="acc-info__form">
                    <div class="row gx-4 gy-30">
                        <div class="col-md-6 col-xl-4">
                            <label class="form-label">Họ và Tên</label>
                            <input class="form-control" type="text">
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <label class="form-label">Địa chỉ Email</label>
                            <input class="form-control" type="email">
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <label class="form-label">Số điện thoại</label>
                            <input class="form-control" type="text">
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <label class="form-label">Địa chỉ</label>
                            <input class="form-control" type="text">
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <label class="form-label">Mật khẩu</label>
                            <input class="form-control" type="password">
                        </div>
                    </div>
                    <div class="text-center mt-30">
                        <button class="button" type="submit">Chỉnh sửa</button>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <h2 class="section__title mb-3">Dự án quan tâm</h2>
                <ul class="project-nav__list mb-30">
                    <li><a class="active" href="#!">Tất cả</a></li>
                    <li><a href="#!">Cơ sở hạ tầng giao thông</a></li>
                    <li><a href="#!">Bất động sản dân dụng</a></li>
                    <li><a href="#!">Công trình công cộng</a></li>
                    <li><a href="#!">Công trình thương mại</a></li>
                </ul>
                <form class="pj-search mb-60" style="padding: 0; background: 0;">
                    <div class="input-group">
                        <input class="form-control" type="text" placeholder="Tìm kiếm">
                        <div class="input-group-text"><i class="fal fa-lg fa-search"></i></div>
                    </div>
                </form>
                <div class="news-slider">
                    <div class="news-slider__nav">
                        <div class="news-slider__prev"><i class="fal fa-fw fa-lg fa-angle-left"></i></div>
                        <div class="news-slider__next"><i class="fal fa-fw fa-lg fa-angle-right"></i></div>
                    </div>
                    <div class="news-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            @foreach(range(1,6) as $item)
                                <div class="swiper-slide">
                                    <div>
                                        <div class="project"><a class="project__frame"
                                                                href="{{ route('project_detail') }}"><img
                                                        src="./images/project-1.jpg" alt=""/></a>
                                            <div class="project__body">
                                                <h3 class="project__title"><a href="{{ route('project_detail') }}">Dự án
                                                        Khu công nghệ cao Láng -
                                                        Hoà
                                                        Lạc</a></h3>
                                                <div class="project__overlay"><span>Dự án mới</span><a
                                                            class="project__like"
                                                            href="{{ route('project_detail') }}"><i
                                                                class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                                <ul class="project__info">
                                                    <li><img class="me-2" src="./images/icon-map-marker.svg"
                                                             alt=""/><span>Donec venenatis fringilla augue at ...</span>
                                                    </li>
                                                    <li><img class="me-2" src="./images/icon-dimension.svg"
                                                             alt=""/><span>120 ha</span>
                                                    </li>
                                                    <li><img class="me-2" src="./images/icon-save-money.svg"
                                                             alt=""/><span>Theo đề xuất</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-20">
                                        <div class="project"><a class="project__frame"
                                                                href="{{ route('project_detail') }}"><img
                                                        src="./images/project-1.jpg" alt=""/></a>
                                            <div class="project__body">
                                                <h3 class="project__title"><a href="{{ route('project_detail') }}">Dự án
                                                        Khu công nghệ cao Láng -
                                                        Hoà
                                                        Lạc</a></h3>
                                                <div class="project__overlay"><span>Dự án mới</span><a
                                                            class="project__like"
                                                            href="{{ route('project_detail') }}"><i
                                                                class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                                <ul class="project__info">
                                                    <li><img class="me-2" src="./images/icon-map-marker.svg"
                                                             alt=""/><span>Donec venenatis fringilla augue at ...</span>
                                                    </li>
                                                    <li><img class="me-2" src="./images/icon-dimension.svg"
                                                             alt=""/><span>120 ha</span>
                                                    </li>
                                                    <li><img class="me-2" src="./images/icon-save-money.svg"
                                                             alt=""/><span>Theo đề xuất</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="#!">Xem thêm</a></nav>
            </div>
        </section>
        <section class="section section--bg-pattern">
            <div class="container">
                <div class="counter">
                    <div class="counter__item">
                        <div class="counter__icon"><img src="./images/counter-1.svg" alt=""/></div>
                        <div class="counter__number">36</div>
                        <div class="counter__title">Tổng số dự án</div>
                    </div>
                    <div class="counter__item">
                        <div class="counter__icon"><img src="./images/counter-2.svg" alt=""/></div>
                        <div class="counter__number">10K+</div>
                        <div class="counter__title">Tổng vốn đầu tư</div>
                    </div>
                    <div class="counter__item">
                        <div class="counter__icon"><img src="./images/counter-3.svg" alt=""/></div>
                        <div class="counter__number">15</div>
                        <div class="counter__title">Lĩnh vực</div>
                    </div>
                    <div class="counter__item">
                        <div class="counter__icon"><img src="./images/counter-4.svg" alt=""/></div>
                        <div class="counter__number">20</div>
                        <div class="counter__title">Tổng số nhà đầu tư</div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section"><img class="texture-1" src="./images/texture-1.png" alt=""/><img class="texture-2"
                                                                                                  src="./images/texture-2.png"
                                                                                                  alt=""/>
            <div class="container">
                <h2 class="section__title">Tin tức quan tâm</h2>
                <div class="news-slider">
                    <div class="news-slider__nav">
                        <div class="news-slider__prev"><i class="fal fa-fw fa-lg fa-angle-left"></i></div>
                        <div class="news-slider__next"><i class="fal fa-fw fa-lg fa-angle-right"></i></div>
                    </div>
                    <div class="news-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            @foreach(range(1,6) as $item)
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
                            @endforeach
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
