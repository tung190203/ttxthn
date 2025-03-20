@extends('frontend.index')

@section('content')
    <div class="page__content">
        <!-- main content-->
        <section class="banner-home" style="background-color: #FFFFFF; padding-bottom: 70px;">
            <div class="container"><img class="w-100" src="./images/world-map.jpg" alt=""></div>
        </section>
        <form class="pj-search">
            <div class="container">
                <div class="pj-search__body">
                    <div class="pj-search__top">
                        <div class="pj-search__col">
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="Nhập tên dự án">
                                <div class="input-group-text"><i class="fal fa-lg fa-search"></i></div>
                            </div>
                        </div>
                        <div class="pj-search__col">
                            <button class="pj-search__btn" type="submit">Tìm kiếm</button>
                        </div>
                    </div>
                    <div class="pj-search__bottom">
                        <div class="pj-search__col">
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="Nhập tên dự án">
                                <div class="input-group-text"><i class="fal fa-lg fa-map-marker-alt"></i></div>
                            </div>
                        </div>
                        <div class="pj-search__col">
                            <select class="form-select">
                                <option>Loại dự án</option>
                                <option>Cơ sở hạ tầng giao thông</option>
                                <option>Bất động sản dân dụng</option>
                                <option>Công trình công cộng</option>
                                <option>Công trình thương mại</option>
                            </select>
                        </div>
                        <div class="pj-search__col">
                            <select class="form-select">
                                <option>Ngành/Lĩnh vực</option>
                                <option>Cầu</option>
                                <option>Đường bộ</option>
                                <option>Đường cao tốc</option>
                                <option>Đường sắt</option>
                                <option>Hầm đường bộ</option>
                            </select>
                        </div>
                        <div class="pj-search__col">
                            <div class="range-input">
                                <div class="range-input__content">
                                    <div class="range-input__label">Quy mô vốn đầu tư</div>
                                    <div class="range-input__price">0đ</div>
                                </div>
                                <input class="range-input__input" type="range" value="0" min="0" max="100000000"
                                       step="1000000">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <section class="section">
            <div class="container">
                <h2 class="section__title mb-3">Danh mục đầu tư</h2>
                <ul class="project-nav__list mb-60">
                    <li><a class="active" href="#!">Tất cả</a></li>
                    <li><a href="#!">Cơ sở hạ tầng giao thông</a></li>
                    <li><a href="#!">Bất động sản dân dụng</a></li>
                    <li><a href="#!">Công trình công cộng</a></li>
                    <li><a href="#!">Công trình thương mại</a></li>
                </ul>
                <div class="news-slider">
                    <div class="news-slider__nav">
                        <div class="news-slider__prev"><i class="fal fa-fw fa-lg fa-angle-left"></i></div>
                        <div class="news-slider__next"><i class="fal fa-fw fa-lg fa-angle-right"></i></div>
                    </div>
                    <div class="news-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div>
                                    <div class="project"><a class="project__frame" href="#!"><img
                                                    src="./images/project-1.jpg" alt=""/></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="#!">Dự án Khu công nghệ cao Láng - Hoà
                                                    Lạc</a></h3>
                                            <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                                                                                   href="#!"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                            <ul class="project__info">
                                                <li><img class="me-2" src="./images/icon-map-marker.svg" alt=""/><span>Donec venenatis fringilla augue at ...</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-dimension.svg" alt=""/><span>120 ha</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-20">
                                    <div class="project"><a class="project__frame" href="#!"><img
                                                    src="./images/project-1.jpg" alt=""/></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="#!">Dự án Khu công nghệ cao Láng - Hoà
                                                    Lạc</a></h3>
                                            <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                                                                                   href="#!"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                            <ul class="project__info">
                                                <li><img class="me-2" src="./images/icon-map-marker.svg" alt=""/><span>Donec venenatis fringilla augue at ...</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-dimension.svg" alt=""/><span>120 ha</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div>
                                    <div class="project"><a class="project__frame" href="#!"><img
                                                    src="./images/project-1.jpg" alt=""/></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="#!">Dự án Khu công nghệ cao Láng - Hoà
                                                    Lạc</a></h3>
                                            <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                                                                                   href="#!"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                            <ul class="project__info">
                                                <li><img class="me-2" src="./images/icon-map-marker.svg" alt=""/><span>Donec venenatis fringilla augue at ...</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-dimension.svg" alt=""/><span>120 ha</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-20">
                                    <div class="project"><a class="project__frame" href="#!"><img
                                                    src="./images/project-1.jpg" alt=""/></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="#!">Dự án Khu công nghệ cao Láng - Hoà
                                                    Lạc</a></h3>
                                            <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                                                                                   href="#!"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                            <ul class="project__info">
                                                <li><img class="me-2" src="./images/icon-map-marker.svg" alt=""/><span>Donec venenatis fringilla augue at ...</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-dimension.svg" alt=""/><span>120 ha</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div>
                                    <div class="project"><a class="project__frame" href="#!"><img
                                                    src="./images/project-1.jpg" alt=""/></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="#!">Dự án Khu công nghệ cao Láng - Hoà
                                                    Lạc</a></h3>
                                            <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                                                                                   href="#!"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                            <ul class="project__info">
                                                <li><img class="me-2" src="./images/icon-map-marker.svg" alt=""/><span>Donec venenatis fringilla augue at ...</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-dimension.svg" alt=""/><span>120 ha</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-20">
                                    <div class="project"><a class="project__frame" href="#!"><img
                                                    src="./images/project-1.jpg" alt=""/></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="#!">Dự án Khu công nghệ cao Láng - Hoà
                                                    Lạc</a></h3>
                                            <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                                                                                   href="#!"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                            <ul class="project__info">
                                                <li><img class="me-2" src="./images/icon-map-marker.svg" alt=""/><span>Donec venenatis fringilla augue at ...</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-dimension.svg" alt=""/><span>120 ha</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div>
                                    <div class="project"><a class="project__frame" href="#!"><img
                                                    src="./images/project-1.jpg" alt=""/></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="#!">Dự án Khu công nghệ cao Láng - Hoà
                                                    Lạc</a></h3>
                                            <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                                                                                   href="#!"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                            <ul class="project__info">
                                                <li><img class="me-2" src="./images/icon-map-marker.svg" alt=""/><span>Donec venenatis fringilla augue at ...</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-dimension.svg" alt=""/><span>120 ha</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-20">
                                    <div class="project"><a class="project__frame" href="#!"><img
                                                    src="./images/project-1.jpg" alt=""/></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="#!">Dự án Khu công nghệ cao Láng - Hoà
                                                    Lạc</a></h3>
                                            <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                                                                                   href="#!"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                            <ul class="project__info">
                                                <li><img class="me-2" src="./images/icon-map-marker.svg" alt=""/><span>Donec venenatis fringilla augue at ...</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-dimension.svg" alt=""/><span>120 ha</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div>
                                    <div class="project"><a class="project__frame" href="#!"><img
                                                    src="./images/project-1.jpg" alt=""/></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="#!">Dự án Khu công nghệ cao Láng - Hoà
                                                    Lạc</a></h3>
                                            <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                                                                                   href="#!"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                            <ul class="project__info">
                                                <li><img class="me-2" src="./images/icon-map-marker.svg" alt=""/><span>Donec venenatis fringilla augue at ...</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-dimension.svg" alt=""/><span>120 ha</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-20">
                                    <div class="project"><a class="project__frame" href="#!"><img
                                                    src="./images/project-1.jpg" alt=""/></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="#!">Dự án Khu công nghệ cao Láng - Hoà
                                                    Lạc</a></h3>
                                            <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                                                                                   href="#!"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                            <ul class="project__info">
                                                <li><img class="me-2" src="./images/icon-map-marker.svg" alt=""/><span>Donec venenatis fringilla augue at ...</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-dimension.svg" alt=""/><span>120 ha</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div>
                                    <div class="project"><a class="project__frame" href="#!"><img
                                                    src="./images/project-1.jpg" alt=""/></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="#!">Dự án Khu công nghệ cao Láng - Hoà
                                                    Lạc</a></h3>
                                            <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                                                                                   href="#!"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                            <ul class="project__info">
                                                <li><img class="me-2" src="./images/icon-map-marker.svg" alt=""/><span>Donec venenatis fringilla augue at ...</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-dimension.svg" alt=""/><span>120 ha</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-20">
                                    <div class="project"><a class="project__frame" href="#!"><img
                                                    src="./images/project-1.jpg" alt=""/></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="#!">Dự án Khu công nghệ cao Láng - Hoà
                                                    Lạc</a></h3>
                                            <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                                                                                   href="#!"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                            <ul class="project__info">
                                                <li><img class="me-2" src="./images/icon-map-marker.svg" alt=""/><span>Donec venenatis fringilla augue at ...</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-dimension.svg" alt=""/><span>120 ha</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                <h2 class="section__title">Tin tức</h2>
                <div class="news-slider">
                    <div class="news-slider__nav">
                        <div class="news-slider__prev"><i class="fal fa-fw fa-lg fa-angle-left"></i></div>
                        <div class="news-slider__next"><i class="fal fa-fw fa-lg fa-angle-right"></i></div>
                    </div>
                    <div class="news-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="news"><a class="news__frame" href="#!"><img src="./images/news-1.jpg"
                                                                                        alt=""/></a>
                                    <div class="news__body">
                                        <div class="news__info">
                                            <div class="news__time"><i
                                                        class="fal fa-clock me-2"></i><span>15/04/2024</span></div>
                                            <div class="news__like"><i class="fal fa-fw fa-heart"></i></div>
                                        </div>
                                        <h3 class="news__title"><a href="#!">Tin tức thủ đô mới của Malaysia chuyển về
                                                Kuala Lumper</a></h3>
                                        <div class="news__desc">Mỗi một nhiệm vụ cụ thể phải được phân tích, mô tả chi
                                            tiết, rõ ràng; Kế hoạch triển khai phù hợp với phương pháp luận và tiến độ
                                            công việc cũ ...
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="news"><a class="news__frame" href="#!"><img src="./images/news-1.jpg"
                                                                                        alt=""/></a>
                                    <div class="news__body">
                                        <div class="news__info">
                                            <div class="news__time"><i
                                                        class="fal fa-clock me-2"></i><span>15/04/2024</span></div>
                                            <div class="news__like"><i class="fal fa-fw fa-heart"></i></div>
                                        </div>
                                        <h3 class="news__title"><a href="#!">Tin tức thủ đô mới của Malaysia chuyển về
                                                Kuala Lumper</a></h3>
                                        <div class="news__desc">Mỗi một nhiệm vụ cụ thể phải được phân tích, mô tả chi
                                            tiết, rõ ràng; Kế hoạch triển khai phù hợp với phương pháp luận và tiến độ
                                            công việc cũ ...
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="news"><a class="news__frame" href="#!"><img src="./images/news-1.jpg"
                                                                                        alt=""/></a>
                                    <div class="news__body">
                                        <div class="news__info">
                                            <div class="news__time"><i
                                                        class="fal fa-clock me-2"></i><span>15/04/2024</span></div>
                                            <div class="news__like"><i class="fal fa-fw fa-heart"></i></div>
                                        </div>
                                        <h3 class="news__title"><a href="#!">Tin tức thủ đô mới của Malaysia chuyển về
                                                Kuala Lumper</a></h3>
                                        <div class="news__desc">Mỗi một nhiệm vụ cụ thể phải được phân tích, mô tả chi
                                            tiết, rõ ràng; Kế hoạch triển khai phù hợp với phương pháp luận và tiến độ
                                            công việc cũ ...
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="news"><a class="news__frame" href="#!"><img src="./images/news-1.jpg"
                                                                                        alt=""/></a>
                                    <div class="news__body">
                                        <div class="news__info">
                                            <div class="news__time"><i
                                                        class="fal fa-clock me-2"></i><span>15/04/2024</span></div>
                                            <div class="news__like"><i class="fal fa-fw fa-heart"></i></div>
                                        </div>
                                        <h3 class="news__title"><a href="#!">Tin tức thủ đô mới của Malaysia chuyển về
                                                Kuala Lumper</a></h3>
                                        <div class="news__desc">Mỗi một nhiệm vụ cụ thể phải được phân tích, mô tả chi
                                            tiết, rõ ràng; Kế hoạch triển khai phù hợp với phương pháp luận và tiến độ
                                            công việc cũ ...
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="news"><a class="news__frame" href="#!"><img src="./images/news-1.jpg"
                                                                                        alt=""/></a>
                                    <div class="news__body">
                                        <div class="news__info">
                                            <div class="news__time"><i
                                                        class="fal fa-clock me-2"></i><span>15/04/2024</span></div>
                                            <div class="news__like"><i class="fal fa-fw fa-heart"></i></div>
                                        </div>
                                        <h3 class="news__title"><a href="#!">Tin tức thủ đô mới của Malaysia chuyển về
                                                Kuala Lumper</a></h3>
                                        <div class="news__desc">Mỗi một nhiệm vụ cụ thể phải được phân tích, mô tả chi
                                            tiết, rõ ràng; Kế hoạch triển khai phù hợp với phương pháp luận và tiến độ
                                            công việc cũ ...
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="news"><a class="news__frame" href="#!"><img src="./images/news-1.jpg"
                                                                                        alt=""/></a>
                                    <div class="news__body">
                                        <div class="news__info">
                                            <div class="news__time"><i
                                                        class="fal fa-clock me-2"></i><span>15/04/2024</span></div>
                                            <div class="news__like"><i class="fal fa-fw fa-heart"></i></div>
                                        </div>
                                        <h3 class="news__title"><a href="#!">Tin tức thủ đô mới của Malaysia chuyển về
                                                Kuala Lumper</a></h3>
                                        <div class="news__desc">Mỗi một nhiệm vụ cụ thể phải được phân tích, mô tả chi
                                            tiết, rõ ràng; Kế hoạch triển khai phù hợp với phương pháp luận và tiến độ
                                            công việc cũ ...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="#!">Xem thêm</a></nav>
            </div>
        </section>
        <section class="section section--medium-blue">
            <div class="container">
                <h2 class="section__title text-white">Đối tác</h2>
                <div class="partners">
                    <div class="partners__item"><img src="./images/partner-1.png" alt=""></div>
                    <div class="partners__item"><img src="./images/partner-1.png" alt=""></div>
                    <div class="partners__item"><img src="./images/partner-1.png" alt=""></div>
                    <div class="partners__item"><img src="./images/partner-1.png" alt=""></div>
                    <div class="partners__item"><img src="./images/partner-1.png" alt=""></div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('bottom')

@endpush
