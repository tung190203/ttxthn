@extends('frontend.index')

@section('content')
    <div class="page__content">
        <!-- main content-->
        <article class="banner">
            <div class="banner__breadcrumb">
                <nav>
                    <div class="container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="link-unstyled" href="#!"><i
                                            class="fal fa-home me-2"></i><span>Trang chủ</span></a></li>
                            <li class="breadcrumb-item active">Danh mục dự án đầu tư</li>
                        </ol>
                    </div>
                </nav>
            </div>
            <img class="banner__bg" src="./images/banner-project.jpg" alt=""/>
            <div class="banner__title">Danh mục dự án đầu tư</div>
        </article>
        <section class="section pt-40"><img class="texture-7" src="./images/texture-7.png" alt="">
            <div class="container">
                <div class="row g-20">
                    <div class="col-lg-3">
                        <form class="aside-form" action="#!">
                            <div class="mb-4">
                                <div class="input-group">
                                    <input class="form-control" type="text" placeholder="Tìm kiếm dự án">
                                    <div class="input-group-text"><i class="fal fa-fw fa-search"></i></div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="fw-600 text-uppercase mb-2">Loại dự án</div>
                                <select class="form-select">
                                    <option>Chọn Huyện/Thị xã/Thành phố</option>
                                    <option>Chọn Huyện/Thị xã/Thành phố</option>
                                    <option>Chọn Huyện/Thị xã/Thành phố</option>
                                    <option>Chọn Huyện/Thị xã/Thành phố</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <div class="fw-600 text-uppercase mb-2">Ngành / Lĩnh vực</div>
                                <div class="mt-2">
                                    <label class="checkbox-styled">
                                        <input class="checkbox-styled__input" type="checkbox" name="checkbox"/><span
                                                class="checkbox-styled__icon"></span><span>Tất cả</span>
                                    </label>
                                </div>
                                <div class="mt-2">
                                    <label class="checkbox-styled">
                                        <input class="checkbox-styled__input" type="checkbox" name="checkbox"/><span
                                                class="checkbox-styled__icon"></span><span>Cơ sở hạ tầng giao thông</span>
                                    </label>
                                </div>
                                <div class="mt-2">
                                    <label class="checkbox-styled">
                                        <input class="checkbox-styled__input" type="checkbox" name="checkbox"/><span
                                                class="checkbox-styled__icon"></span><span>Bất động sản dân dụng</span>
                                    </label>
                                </div>
                                <div class="mt-2">
                                    <label class="checkbox-styled">
                                        <input class="checkbox-styled__input" type="checkbox" name="checkbox"/><span
                                                class="checkbox-styled__icon"></span><span>Công trình công cộng</span>
                                    </label>
                                </div>
                                <div class="mt-2">
                                    <label class="checkbox-styled">
                                        <input class="checkbox-styled__input" type="checkbox" name="checkbox"/><span
                                                class="checkbox-styled__icon"></span><span>Công trình thương mại</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="fw-600 text-uppercase mb-2">Địa điểm</div>
                                <select class="form-select mb-12">
                                    <option>Chọn Huyện/Thị xã/Thành phố</option>
                                    <option>Chọn Huyện/Thị xã/Thành phố</option>
                                    <option>Chọn Huyện/Thị xã/Thành phố</option>
                                    <option>Chọn Huyện/Thị xã/Thành phố</option>
                                </select>
                                <select class="form-select">
                                    <option>Chọn Phường/Xã/Thị trấn</option>
                                    <option>Chọn Phường/Xã/Thị trấn</option>
                                    <option>Chọn Phường/Xã/Thị trấn</option>
                                    <option>Chọn Phường/Xã/Thị trấn</option>
                                </select>
                            </div>
                            <button class="button button--block" type="submit">Áp dụng</button>
                        </form>
                    </div>
                    <div class="col-lg-9">
                        <div class="row g-20">
                            <div class="col-6 col-md-4 col-lg-6 col-xl-4">
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
                                            <li><img class="me-2" src="./images/icon-dimension.svg"
                                                     alt=""/><span>120 ha</span></li>
                                            <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-6 col-xl-4">
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
                                            <li><img class="me-2" src="./images/icon-dimension.svg"
                                                     alt=""/><span>120 ha</span></li>
                                            <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-6 col-xl-4">
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
                                            <li><img class="me-2" src="./images/icon-dimension.svg"
                                                     alt=""/><span>120 ha</span></li>
                                            <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-6 col-xl-4">
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
                                            <li><img class="me-2" src="./images/icon-dimension.svg"
                                                     alt=""/><span>120 ha</span></li>
                                            <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-6 col-xl-4">
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
                                            <li><img class="me-2" src="./images/icon-dimension.svg"
                                                     alt=""/><span>120 ha</span></li>
                                            <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-6 col-xl-4">
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
                                            <li><img class="me-2" src="./images/icon-dimension.svg"
                                                     alt=""/><span>120 ha</span></li>
                                            <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-6 col-xl-4">
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
                                            <li><img class="me-2" src="./images/icon-dimension.svg"
                                                     alt=""/><span>120 ha</span></li>
                                            <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-6 col-xl-4">
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
                                            <li><img class="me-2" src="./images/icon-dimension.svg"
                                                     alt=""/><span>120 ha</span></li>
                                            <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-6 col-xl-4">
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
                                            <li><img class="me-2" src="./images/icon-dimension.svg"
                                                     alt=""/><span>120 ha</span></li>
                                            <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-6 col-xl-4">
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
                                            <li><img class="me-2" src="./images/icon-dimension.svg"
                                                     alt=""/><span>120 ha</span></li>
                                            <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-6 col-xl-4">
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
                                            <li><img class="me-2" src="./images/icon-dimension.svg"
                                                     alt=""/><span>120 ha</span></li>
                                            <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-6 col-xl-4">
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
                                            <li><img class="me-2" src="./images/icon-dimension.svg"
                                                     alt=""/><span>120 ha</span></li>
                                            <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <nav class="d-flex justify-content-center mt-40 mt-lg-50">
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
                        </nav>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('bottom')

@endpush
