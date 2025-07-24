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
                                    <option>Toàn bộ</option>
                                    @foreach ($list_types as $item )
                                        <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <div class="fw-600 text-uppercase mb-2">Ngành / Lĩnh vực</div>
                                @foreach ($list_industries as $industry)
                                    <div class="mt-2">
                                        <label class="checkbox-styled">
                                            <input class="checkbox-styled__input" type="checkbox" name="industries[]" value="{{ $industry['id'] }}"/><span
                                                    class="checkbox-styled__icon"></span><span>{{ $industry['name'] }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mb-4">
                                <div class="fw-600 text-uppercase mb-2">Địa điểm</div>
                                <select class="form-select">
                                    <option>Chọn Phường/Xã/Thị trấn</option>
                                    @foreach ($list_districts as $item )
                                        <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="button button--block" type="submit">Tìm kiếm</button>
                        </form>
                    </div>
                    <div class="col-lg-9">
                        <div class="row g-20">
                            {{-- @foreach(range(1,12) as $item) --}}
                                <div class="col-6 col-md-4 col-lg-6 col-xl-4">
                                    <div class="project">
                                        <a class="project__frame" href="{{ route('project_detail') }}">
                                            <img src="./images/project-1.jpg" alt=""/></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="{{ route('project_detail') }}">Dự án đầu tư xây dựng cầu Trần Hưng Đạo</a></h3>
                                            <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                                                                                   href="#!"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                            <ul class="project__info">
                                                <li><img class="me-2" src="./images/icon-map-marker.svg" alt=""/><span>Dự án nằm trên địa bàn các quận Hoàn Kiếm (phường Phan Chu Trinh, Chương Dương Độ), quận Hai Bà Trưng (phường Bạch Đằng) và quận Long Biên (phường Long Biên, Bồ Đề), thành phố Hà Nội</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-dimension.svg"
                                                         alt=""/><span>75,5 ha</span></li>
                                                <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4 col-lg-6 col-xl-4">
                                    <div class="project">
                                        <a class="project__frame" href="{{ route('project_detail_cn2') }}">
                                            <img src="./images/design-1_cn2.jpg" alt=""/></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="{{ route('project_detail_cn2') }}">Dự án Cụm công nghiệp CN2</a></h3>
                                            <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                                                                                   href="#!"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                            <ul class="project__info">
                                                <li><img class="me-2" src="./images/icon-map-marker.svg" alt=""/><span>Xã Mai Đình, huyện Sóc Sơn, TP. Hà Nội</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-dimension.svg"
                                                         alt=""/><span>50,5 ha</span></li>
                                                <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4 col-lg-6 col-xl-4">
                                    <div class="project">
                                        <a class="project__frame" href="{{ route('project_detail_tien_duong') }}">
                                            <img src="./images/tienduong.jpg" alt=""/></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="{{ route('project_detail_tien_duong') }}">Dự án đầu tư xây dựng Khu
                                                nhà ở xã hội Tiên Dương 1</a></h3>
                                            <div class="project__overlay"><span>Dự án mới</span><a class="project__like"
                                                                                                   href="#!"><i
                                                            class="fal fa-fw fa-lg fa-heart"></i></a></div>
                                            <ul class="project__info">
                                                <li><img class="me-2" src="./images/icon-map-marker.svg" alt=""/><span>Xã Tiên Dương, huyện Đông Anh, thành phố Hà Nội.</span>
                                                </li>
                                                <li><img class="me-2" src="./images/icon-dimension.svg"
                                                         alt=""/><span>44,5 ha</span></li>
                                                <li><img class="me-2" src="./images/icon-save-money.svg" alt=""/><span>Theo đề xuất</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            {{-- @endforeach --}}
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
