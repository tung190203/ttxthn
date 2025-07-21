@extends('frontend.index')

@section('content')
    <div class="page__content">
        <!-- main content-->
        <section class="pj-banner">
            <nav>
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="link-unstyled" href="/"><i
                                    class="fal fa-home me-2"></i><span>Trang chủ</span></a></li>
                        <li class="breadcrumb-item active">Danh mục dự án đầu tư</li>
                    </ol>
                </div>
            </nav>
            <img class="pj-banner__bg" src="./images/tienduong.jpg" alt="">
            <div class="pj-banner__wrapper custom-wrapper">
                <div class="container">
                    <div class="pj-banner__subtitle text-start">Dự án</div>
                    <div class="pj-banner__title text-start">Khu nhà ở xã hội Tiên Dương 1</div>
                    <div class="d-flex justify-content-end">
                        <div class="custom_desc">Khu nhà ở xã hội Tiên Dương 1 tọa lạc tại xã Tiên Dương, huyện Đông Anh, Hà Nội, có quy mô khoảng 44,59 ha, được quy hoạch đồng bộ hạ tầng kỹ thuật và xã hội. Dự án nhằm hiện thực hóa mục tiêu xây dựng 1 triệu căn nhà ở xã hội, cung cấp khoảng 3.530 căn hộ chung cư và 99 căn thấp tầng, phục vụ người thu nhập thấp, công nhân khu công nghiệp, góp phần phát triển đô thị và kinh tế khu vực phía Bắc Thủ đô.
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <nav class="project-nav">
            <div class="container">
                <ul class="project-nav__list">
                    <li><a class="active" href="#thong-tin-chung">Thông tin chung</a></li>
                    <li><a href="#vi-tri">Vị trí</a></li>
                    <li><a href="#loi-the-noi-bat">Lợi thế nổi bật</a></li>
                    <li><a href="#sa-ban-ao">Sa bàn ảo</a></li>
                    <li><a href="#thiet-ke-va-mat-bang">Thiết kế & mặt bằng</a></li>
                    <li><a href="#phap-ly">Pháp lý</a></li>
                    <li><a href="#tin-tuc">Tin tức</a></li>
                </ul>
            </div>
        </nav>
        <section class="section" id="thong-tin-chung"><img class="section__bg" src="./images/achitect-bg.png"
                alt="">
                <div class="container">
                    <h2 class="section__title">Thông tin chung</h2>
                    <div class="mx-auto" style="max-width: 800px;">
                        <ul>
                            <li><strong>Tên dự án</strong>: Khu nhà ở xã hội Tiên Dương 1</li>
                            <li><strong>Ngành/Lĩnh vực</strong>: Nhà ở xã hội, đô thị, thương mại – dịch vụ</li>
                            <li><strong>Địa điểm</strong>: Xã Tiên Dương, huyện Đông Anh, Hà Nội</li>
                            <li><strong>Tổng diện tích</strong>: 44,59 ha (tương đương 445.886 m², không bao gồm 1.359 m² đất nhà ở hiện có)</li>
                            <li><strong>Quy mô xây dựng</strong>: 
                                <ul>
                                    <li>Khoảng 3.530 căn hộ chung cư</li>
                                    <li>99 căn nhà ở thấp tầng liền kề</li>
                                    <li>Quy mô dân số khoảng 12.465 người</li>
                                    <li>Diện tích xây dựng nhà ở xã hội: 131.975 m²</li>
                                    <li>Diện tích xây dựng nhà ở thương mại: 33.280 m²</li>
                                    <li>Diện tích công trình dịch vụ – thương mại: 34.276 m²</li>
                                </ul>
                            </li>
                            <li><strong>Hạ tầng kỹ thuật và xã hội</strong>:
                                <ul>
                                    <li>Đầu tư hạ tầng kỹ thuật đồng bộ, bàn giao cho Thành phố quản lý</li>
                                    <li>Các công trình giáo dục: 3 trường học (mầm non, tiểu học, THCS)</li>
                                    <li>2 bãi đỗ xe tập trung</li>
                                    <li>Hệ thống giao thông khu vực, cây xanh, thể dục thể thao</li>
                                    <li>Trạm xử lý nước thải, trạm biến áp</li>
                                </ul>
                            </li>
                            <li><strong>Hình thức khai thác</strong>: Đấu thầu lựa chọn nhà đầu tư. Nhà đầu tư được miễn tiền sử dụng đất, tiền thuê đất theo quy định pháp luật</li>
                            <li><strong>Tiến độ thực hiện</strong>: Từ năm 2024 đến năm 2030</li>
                            <li><strong>Thời hạn hoạt động</strong>: 50 năm kể từ ngày có quyết định giao/cho thuê/chuyển mục đích sử dụng đất</li>
                            <li><strong>Tổng vốn đầu tư</strong>: Khoảng 9.307,422 tỷ đồng
                                <ul>
                                    <li>Chi phí thực hiện dự án: khoảng 8.690,426 tỷ đồng</li>
                                    <li>Chi phí bồi thường, giải phóng mặt bằng: khoảng 616,996 tỷ đồng</li>
                                </ul>
                            </li>
                            <li><strong>Cơ quan quản lý</strong>: UBND Thành phố Hà Nội, Sở Xây dựng Hà Nội, Sở Kế hoạch và Đầu tư Hà Nội</li>
                        </ul>
                    </div>
                </div>
                
        </section>
        <section class="section pb-0" id="vi-tri">
            <div class="container">
                <h2 class="section__title">Vị trí</h2><img class="w-100" src="./images/position_cn2.png" alt="">
            </div>
        </section>
        <section class="section section--light-blue" id="loi-the-noi-bat">
            <div class="container">
                <h2 class="section__title">Lợi thế nổi bật</h2>
                <div>
                    <div class="advantage mt-20"><a class="advantage__frame" href="#!"><img
                        src="./images/tienduong.jpg" alt="" /></a>
                <div class="advantage__body">
                    <div class="advantage__index">1</div>
                    <div class="advantage__index-bg">1</div>
                    <div class="advantage__title">Vị trí kết nối thuận tiện</div>
                    <div class="advantage__desc">
                        <p>Dự án nằm tại xã Tiên Dương, huyện Đông Anh – khu vực đang phát triển mạnh về hạ tầng và đô thị, kết nối dễ dàng với trung tâm Hà Nội qua cầu Nhật Tân và đường Võ Nguyên Giáp. Gần các tuyến giao thông huyết mạch như QL3, cao tốc Nhật Tân – Nội Bài, thuận tiện di chuyển đến sân bay, các khu công nghiệp lớn và trung tâm hành chính mới của Thành phố.</p>
                    </div>
                </div>
            </div>
            
            <div class="advantage mt-20"><a class="advantage__frame" href="#!"><img
                src="./images/advantage-2_cn2.png" alt="" /></a>
        <div class="advantage__body">
            <div class="advantage__index">2</div>
            <div class="advantage__index-bg">2</div>
            <div class="advantage__title">Phù hợp quy hoạch phát triển Thủ đô</div>
            <div class="advantage__desc">
                <p>Dự án là một trong những mô hình thí điểm khu nhà ở xã hội tập trung theo Quy hoạch chi tiết tỷ lệ 1/500 đã được UBND Thành phố phê duyệt. Đây là bước cụ thể hóa Đề án phát triển 1 triệu căn nhà ở xã hội giai đoạn 2021–2030, góp phần hoàn thiện mạng lưới đô thị, cải thiện chất lượng sống và đảm bảo an sinh xã hội tại khu vực cửa ngõ phía Bắc Hà Nội.</p>
            </div>
        </div>
    </div>
    
    <div class="advantage mt-20"><a class="advantage__frame" href="#!"><img
        src="./images/advantage-3_cn2.png" alt="" /></a>
<div class="advantage__body">
    <div class="advantage__index">3</div>
    <div class="advantage__index-bg">3</div>
    <div class="advantage__title">Hạ tầng đồng bộ, đầy đủ tiện ích</div>
    <div class="advantage__desc">
        <p>Dự án được quy hoạch đồng bộ với hệ thống giao thông nội bộ, trường học các cấp (mầm non, tiểu học, THCS), công viên cây xanh, trạm xử lý nước thải, bãi đỗ xe, cùng các công trình thương mại – dịch vụ phục vụ cư dân. Sau khi hoàn thành, toàn bộ hạ tầng kỹ thuật sẽ được bàn giao cho thành phố quản lý theo đúng quy định.</p>
    </div>
</div>
</div>

                </div>
            </div>
        </section>
        <section class="position-relative" id="sa-ban-ao">
            <div class="section section--overlay">
                <div class="container">
                    <h2 class="section__title text-white">Sa bàn ảo</h2>
                    <div class="mt-3">
                        <a href="https://fir-tour2.web.app/cn2/" class="btn btn-warning text-white custom-btn-vrtour" target="_blank" rel="noopener noreferrer">
                            Xem VR Tour
                        </a>
                    </div>
                </div>
            </div>
            <div class="ratio ratio-2x1">
                <iframe src="https://momento360.com/e/u/52c80192fe8f4698af6af88234ed673c" frameborder="0" allowfullscreen allow="fullscreen"></iframe>
            </div>
            <div class="container">
                <h2 class="section__title text-white">Sa bàn ảo</h2>
            </div>
        </section>
        <section class="section" id="thiet-ke-va-mat-bang">
            <div class="container">
                <h2 class="section__title">Thiết kế & mặt bằng</h2>
                <div class="section__desc">Phương án quy hoạch CN2 được xây dựng đồng bộ, hiện đại, phù hợp định hướng phát
                    triển công nghiệp xanh – công nghệ cao của Hà Nội, đảm <br> bảo hạ tầng hoàn chỉnh, kết nối thuận tiện
                    và khả năng khai thác hiệu quả.
                </div>
                <div class="design-slider">
                    <div class="design-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/tienduong.jpg" alt="" />
                                </div>
                                <div class="design-slider__overlay">
                                    <div class="design-slider__content">Ý tưởng phát triển được hình thành trên cơ sở khảo
                                        sát thực tiễn, nhu cầu thị trường và
                                        bài toán kết nối hạ tầng liên vùng, nhằm tối ưu hiệu quả sử dụng đất và thu hút đầu
                                        tư bền vững.
                                    </div>
                                </div>
                            </div>
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/tienduong-1.png" alt="" />
                                </div>
                                <div class="design-slider__overlay">
                                    <div class="design-slider__content">Ý tưởng "Hà Nội bất tận" lấy cảm hứng từ không
                                        gian mênh mông của dòng sông Hồng, với hình ảnh sóng lượn liên tục nối từ hai
                                        bờ, tạo ra biểu tượng vô cực
                                    </div>
                                </div>
                            </div>
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/tienduong-2.png" alt="" />
                                </div>
                                <div class="design-slider__overlay">
                                    <div class="design-slider__content">Ý tưởng "Hà Nội bất tận" lấy cảm hứng từ không
                                        gian mênh mông của dòng sông Hồng, với hình ảnh sóng lượn liên tục nối từ hai
                                        bờ, tạo ra biểu tượng vô cực
                                    </div>
                                </div>
                            </div>
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/design-4_cn2.jpg" alt="" />
                                </div>
                                <div class="design-slider__overlay">
                                    <div class="design-slider__content">Ý tưởng "Hà Nội bất tận" lấy cảm hứng từ không
                                        gian mênh mông của dòng sông Hồng, với hình ảnh sóng lượn liên tục nối từ hai
                                        bờ, tạo ra biểu tượng vô cực
                                    </div>
                                </div>
                            </div>
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/advantage-2_cn2.png" alt="" />
                                </div>
                                <div class="design-slider__overlay">
                                    <div class="design-slider__content">Ý tưởng "Hà Nội bất tận" lấy cảm hứng từ không
                                        gian mênh mông của dòng sông Hồng, với hình ảnh sóng lượn liên tục nối từ hai
                                        bờ, tạo ra biểu tượng vô cực
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="design-thumb-slider mt-3 mt-lg-20">
                    <div class="design-thumb-slider__prev"><i class="fal fa-lg fa-angle-left"></i></div>
                    <div class="design-thumb-slider__next"><i class="fal fa-lg fa-angle-right"></i></div>
                    <div class="design-thumb-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="design-thumb-slider__frame"><img src="./images/tienduong.jpg"
                                        alt="" /></div>
                            </div>
                            <div class="swiper-slide">
                                <div class="design-thumb-slider__frame"><img src="./images/tienduong-1.png"
                                        alt="" /></div>
                            </div>
                            <div class="swiper-slide">
                                <div class="design-thumb-slider__frame"><img src="./images/tienduong-2.png"
                                        alt="" /></div>
                            </div>
                            <div class="swiper-slide">
                                <div class="design-thumb-slider__frame"><img src="./images/design-4_cn2.jpg"
                                        alt="" /></div>
                            </div>
                            <div class="swiper-slide">
                                <div class="design-thumb-slider__frame"><img src="./images/advantage-2_cn2.png"
                                        alt="" /></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section section--light-blue" id="phap-ly">
            <div class="container">
                <h2 class="section__title">Pháp lý</h2>
                <div class="section__desc">Dưới đây là một số quyết định pháp lý quan trọng liên quan đến cụm dự án Khu nhà ở xã hội Tiên Dương 1
                </div>
                <div class="legal-grid"><a class="legal" href="#!"><img class="legal__icon"
                            src="./images/icon-pdf.svg" alt="" />
                        <div class="legal__body">
                            <div class="legal__title">Quyết định phê duyệt chủ trương đầu tư đã được UBND Thành phố Hà
                                Nội phê duyệt
                            </div>
                        </div>
                    </a><a class="legal" href="#!"><img class="legal__icon" src="./images/icon-doc.svg"
                            alt="" />
                        <div class="legal__body">
                            <div class="legal__title">Quyết định phê duyệt chủ trương đầu tư đã được UBND Thành phố Hà
                                Nội phê duyệt
                            </div>
                        </div>
                    </a><a class="legal" href="#!"><img class="legal__icon" src="./images/icon-excel.svg"
                            alt="" />
                        <div class="legal__body">
                            <div class="legal__title">Quyết định phê duyệt chủ trương đầu tư đã được UBND Thành phố Hà
                                Nội phê duyệt
                            </div>
                        </div>
                    </a><a class="legal" href="#!"><img class="legal__icon" src="./images/icon-pdf.svg"
                            alt="" />
                        <div class="legal__body">
                            <div class="legal__title">Phê duyệt phương án kiến trúc đã được UBND Thành phố Hà Nội phê
                                duyệt
                            </div>
                        </div>
                    </a><a class="legal" href="#!"><img class="legal__icon" src="./images/icon-doc.svg"
                            alt="" />
                        <div class="legal__body">
                            <div class="legal__title">Quyết định phê duyệt chủ trương đầu tư đã được UBND Thành phố Hà
                                Nội phê duyệt
                            </div>
                        </div>
                    </a><a class="legal" href="#!"><img class="legal__icon" src="./images/icon-excel.svg"
                            alt="" />
                        <div class="legal__body">
                            <div class="legal__title">Quyết định số 768/QĐ-TTg ngày 06/5/2016 của Thủ tướng Chính phủ
                                phê duyệt điều chỉnh
                            </div>
                        </div>
                    </a><a class="legal" href="#!"><img class="legal__icon" src="./images/icon-pdf.svg"
                            alt="" />
                        <div class="legal__body">
                            <div class="legal__title">Kế hoạch khởi công đã được UBND Thành phố Hà Nội phê duyệt</div>
                        </div>
                    </a><a class="legal" href="#!"><img class="legal__icon" src="./images/icon-doc.svg"
                            alt="" />
                        <div class="legal__body">
                            <div class="legal__title">Quyết định số 355/QĐ-TTg ngày 25/02/2013 của Thủ tướng Chính phủ
                                về phê duyệt điều chỉnh ...
                            </div>
                        </div>
                    </a>
                </div>
                <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="#!">Xem thêm</a>
                </nav>
            </div>
        </section>
        <section class="section" id="tin-tuc"><img class="texture-1" src="./images/texture-1.png" alt=""><img
                class="texture-2" src="./images/texture-2.png" alt="">
            <div class="container">
                <h2 class="section__title">Tin tức</h2>
                <div class="news-slider">
                    <div class="news-slider__nav">
                        <div class="news-slider__prev"><i class="fal fa-fw fa-lg fa-angle-left"></i></div>
                        <div class="news-slider__next"><i class="fal fa-fw fa-lg fa-angle-right"></i></div>
                    </div>
                    <div class="news-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            @foreach ($posts as $item)
                                <div class="swiper-slide">
                                    <div class="news"><a class="news__frame"
                                            href="{{ route('post_detail',['id' => $item->id, 'slug' => $item->slug]) }}"><img
                                                src="./images/news/new-{{ $loop->iteration }}.jpg" alt="" /></a>
                                        <div class="news__body">
                                            <div class="news__info">
                                                <div class="news__time"><i
                                                        class="fal fa-clock me-2"></i><span>{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                                <div class="news__like"><i class="fal fa-fw fa-heart"></i></div>
                                            </div>
                                            <h3 class="news__title  custom-desc"><a
                                                    href="{{ route('post_detail',['id' => $item->id, 'slug' => $item->slug]) }}">{{ $item->name }}</a>
                                            </h3>
                                            <div class="news__desc">{{ $item->description }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="#!">Xem thêm</a>
                </nav>
            </div>
        </section>
    </div>
@endsection

@push('bottom')
    <script>
        $(document).ready(function() {
            $('.project-nav__list a').click(function() {
                $('.project-nav__list a').removeClass('active');
                $(this).addClass('active');
            });
        });
        // Ẩn phần tử có id "sa-ban-ao" nếu tham số "hide" trong URL là "saban"
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const hide = urlParams.get("hide");

            if (hide === "saban") {
                const sabanEl = $("#sa-ban-ao");
                if (sabanEl.length) sabanEl.hide();
            }
        });
    </script>
@endpush
