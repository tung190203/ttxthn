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
            <img class="pj-banner__bg" src="./images/banner-project-detail.jpg" alt="">
            <div class="pj-banner__wrapper">
                <div class="container">
                    <div class="pj-banner__subtitle">Dự án</div>
                    <div class="pj-banner__title">Cầu Trần Hưng Đạo</div>
                    <div class="pj-banner__separator"></div>
                    <div class="pj-banner__desc">Cầu Trần Hưng Đạo với tổng dài khoảng 5,5 km với 6 làn xe cơ giới, bắc
                        qua sông Hồng có tổng vốn đầu tư khoảng 9.000 tỷ đồng vừa được UBND TP.Hà Nội chấp thuận dự án.
                    </div>
                    <div class="pj-banner__icon"><i class="fal fa-arrow-down"></i></div>
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
        <section class="section" id="thong-tin-chung">
            <div class="container">
                <h2 class="section__title">Thông tin chung</h2>
                <div class="mx-auto" style="max-width: 800px;">
                    <p>Cầu Trần Hưng Đạo là một phần quan trọng trong quy hoạch chung xây dựng Hà Nội đến năm 2030, tầm
                        nhìn 2050. Dự án không chỉ góp phần giảm thiểu ùn tắc giao thông mà còn thúc đẩy phát triển các
                        khu đô thị và hạ tầng kinh tế xung quanh, góp phần xây dựng một Hà Nội hiện đại và phát triển
                        bền vững..</p>
                    <ul>
                        <li><strong>Tên dự án</strong>: Dự án đầu tư xây dựng cầu Trần Hưng Đạo</li>
                        <li><strong>Ngành/Lĩnh vực</strong>: Giao thông</li>
                        <li><strong>Địa điểm</strong>: Dự án nằm trên địa bàn các quận Hoàn Kiếm (phường Phan Chu Trinh,
                            Chương Dương Độ), quận Hai Bà Trưng (phường Bạch Đằng) và quận Long Biên (phường Long Biên,
                            Bồ Đề), thành phố Hà Nội
                        </li>
                        <li><strong>Diện tích</strong>: 75,5 ha - <strong>Diện tích xây dựng</strong>: 75,5ha - <strong>Mật
                                độ xây dựng</strong>: ...
                        </li>
                        <li><strong>Vốn đầu tư dự kiến</strong>: 9.000 tỷ đồng</li>
                        <li><strong>Lợi thế nổi bật</strong>: Kết nối trung tâm nội đô với các khu vực lân cận.</li>
                        <li><strong>Mục tiêu dự án</strong>: Kết nối giữa các đường trục chính dọc theo 2 bờ sông Hồng,
                            giảm tải hữu hiệu cho cầu Chương Dương, Long Biên; góp phần từng bước hoàn chỉnh mạng lưới
                            hạ tầng giao thông khung cho thủ đô Hà Nội và phát triển không gian đô thị hiện đại hai bên
                            đầu cầu.
                        </li>
                        <li><strong>Hiện trạng đất</strong>: Đất nhà nước quản lý, đất nông nghiệp</li>
                        <li><strong>Phù hợp quy hoạch</strong>: giao thông vận tải Thủ đô Hà Nội đã được Thủ tướng Chính
                            phủ phê duyệt tại Quyết định số 519/QĐ-TTg ngày 31/3/2016 và các quy hoạch khác có liên
                            quan.
                        </li>
                        <li><strong>Quy mô công trình</strong>: Theo đề xuất của Nhà đầu tư</li>
                        <li><strong>Hình thức lựa chọn nhà đầu tư</strong>: Đấu thầu rộng rãi trong nước để lựa chọn nhà
                            đầu tư thực hiện các dự án
                        </li>
                        <li><strong>Hình thức đầu tư</strong>: Hình thức PPP, loại hợp đồng BOT</li>
                        <li><strong>Đơn vị đề xuất</strong>: Công ty Cổ phần Him Lam</li>
                        <li><strong>Tọa độ</strong>: 21.054458467615497, 105.85733473128039 (liên kết)</li>
                    </ul>
                </div>
            </div>
        </section>
        <section class="section pb-0" id="vi-tri">
            <div class="container">
                <h2 class="section__title">Vị trí</h2><img class="w-100" src="./images/position.jpg" alt="">
            </div>
        </section>
        <section class="section section--light-blue" id="loi-the-noi-bat">
            <div class="container">
                <h2 class="section__title">Lợi thế nổi bật</h2>
                <div>
                    <div class="advantage mt-20"><a class="advantage__frame" href="#!"><img
                                    src="./images/advantage-1.jpg" alt=""/></a>
                        <div class="advantage__body">
                            <div class="advantage__index">0.1</div>
                            <div class="advantage__index-bg">0.1</div>
                            <div class="advantage__title">Kết nối giao thông</div>
                            <div class="advantage__desc">
                                <p>Cầu Trần Hưng Đạo sau khi hoàn thành sẽ giúp kết nối các quận Hoàn Kiếm, Hai Bà Trưng
                                    với Long Biên, giảm ùn tắc giao thông và cải thiện hạ tầng giao thông của thành
                                    phố.</p>
                                <p>Thiết kế hiện đại và thân thiện: Cầu được thiết kế với 6 làn xe cơ giới, 2 làn xe đạp
                                    và vỉa hè dành cho người đi bộ. Thiết kế này không chỉ phục vụ nhu cầu giao thông mà
                                    còn tạo không gian cho người dân đi bộ, ngắm cảnh và nghỉ ngơi.</p>
                            </div>
                        </div>
                    </div>
                    <div class="advantage mt-20"><a class="advantage__frame" href="#!"><img
                                    src="./images/advantage-2.jpg" alt=""/></a>
                        <div class="advantage__body">
                            <div class="advantage__index">0.2</div>
                            <div class="advantage__index-bg">0.2</div>
                            <div class="advantage__title">Biểu tượng văn hoá</div>
                            <div class="advantage__desc">
                                <p>Thiết kế của cầu mang tên “Infinity Hanoi” với hình ảnh những đường cong uốn lượn,
                                    biểu tượng cho sự nhiệt huyết và kết nối giữa lịch sử, hiện tại và tương lai của Hà
                                    Nội.</p>
                            </div>
                        </div>
                    </div>
                    <div class="advantage mt-20"><a class="advantage__frame" href="#!"><img
                                    src="./images/advantage-3.jpg" alt=""/></a>
                        <div class="advantage__body">
                            <div class="advantage__index">0.3</div>
                            <div class="advantage__index-bg">0.3</div>
                            <div class="advantage__title">Thúc đẩy du lịch thủ đô</div>
                            <div class="advantage__desc">
                                <p>Với các làn đường dành cho xe đạp và người đi bộ, cầu Trần Hưng Đạo hứa hẹn sẽ trở
                                    thành một điểm đến hấp dẫn cho du khách, tạo điều kiện cho các hoạt động du lịch và
                                    giải trí trong nội đô và ngoại thành Hà Nội</p>
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
                </div>
            </div>
            <div class="ratio ratio-2x1">
                <iframe src="https://momento360.com/e/u/a9b53aa8f8b0403ba7a4e18243aabc66"></iframe>
            </div>
            <div class="container">
                <h2 class="section__title text-white">Sa bàn ảo</h2>
            </div>
        </section>
        <section class="section" id="thiet-ke-va-mat-bang">
            <div class="container">
                <h2 class="section__title">Thiết kế & mặt bằng</h2>
                <div class="section__desc">Phương án thiết kế và ý tưởng thiết kế của cầu Trần Hưng Đạo mang nhiều ý
                    nghĩa<br>và được phát triển qua các cuộc thi tuyển chọn.
                </div>
                <div class="design-slider">
                    <div class="design-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/design-1.jpg" alt=""/></div>
                                <div class="design-slider__overlay">
                                    <div class="design-slider__content">Ý tưởng "Hà Nội bất tận" lấy cảm hứng từ không
                                        gian mênh mông của dòng sông Hồng, với hình ảnh sóng lượn liên tục nối từ hai
                                        bờ, tạo ra biểu tượng vô cực
                                    </div>
                                </div>
                            </div>
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/design-2.jpg" alt=""/></div>
                                <div class="design-slider__overlay">
                                    <div class="design-slider__content">Ý tưởng "Hà Nội bất tận" lấy cảm hứng từ không
                                        gian mênh mông của dòng sông Hồng, với hình ảnh sóng lượn liên tục nối từ hai
                                        bờ, tạo ra biểu tượng vô cực
                                    </div>
                                </div>
                            </div>
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/design-3.jpg" alt=""/></div>
                                <div class="design-slider__overlay">
                                    <div class="design-slider__content">Ý tưởng "Hà Nội bất tận" lấy cảm hứng từ không
                                        gian mênh mông của dòng sông Hồng, với hình ảnh sóng lượn liên tục nối từ hai
                                        bờ, tạo ra biểu tượng vô cực
                                    </div>
                                </div>
                            </div>
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/design-4.jpg" alt=""/></div>
                                <div class="design-slider__overlay">
                                    <div class="design-slider__content">Ý tưởng "Hà Nội bất tận" lấy cảm hứng từ không
                                        gian mênh mông của dòng sông Hồng, với hình ảnh sóng lượn liên tục nối từ hai
                                        bờ, tạo ra biểu tượng vô cực
                                    </div>
                                </div>
                            </div>
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/design-5.jpg" alt=""/></div>
                                <div class="design-slider__overlay">
                                    <div class="design-slider__content">Ý tưởng "Hà Nội bất tận" lấy cảm hứng từ không
                                        gian mênh mông của dòng sông Hồng, với hình ảnh sóng lượn liên tục nối từ hai
                                        bờ, tạo ra biểu tượng vô cực
                                    </div>
                                </div>
                            </div>
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/design-1.jpg" alt=""/></div>
                                <div class="design-slider__overlay">
                                    <div class="design-slider__content">Ý tưởng "Hà Nội bất tận" lấy cảm hứng từ không
                                        gian mênh mông của dòng sông Hồng, với hình ảnh sóng lượn liên tục nối từ hai
                                        bờ, tạo ra biểu tượng vô cực
                                    </div>
                                </div>
                            </div>
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/design-2.jpg" alt=""/></div>
                                <div class="design-slider__overlay">
                                    <div class="design-slider__content">Ý tưởng "Hà Nội bất tận" lấy cảm hứng từ không
                                        gian mênh mông của dòng sông Hồng, với hình ảnh sóng lượn liên tục nối từ hai
                                        bờ, tạo ra biểu tượng vô cực
                                    </div>
                                </div>
                            </div>
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/design-3.jpg" alt=""/></div>
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
                                <div class="design-thumb-slider__frame"><img src="./images/design-1.jpg" alt=""/></div>
                            </div>
                            <div class="swiper-slide">
                                <div class="design-thumb-slider__frame"><img src="./images/design-2.jpg" alt=""/></div>
                            </div>
                            <div class="swiper-slide">
                                <div class="design-thumb-slider__frame"><img src="./images/design-3.jpg" alt=""/></div>
                            </div>
                            <div class="swiper-slide">
                                <div class="design-thumb-slider__frame"><img src="./images/design-4.jpg" alt=""/></div>
                            </div>
                            <div class="swiper-slide">
                                <div class="design-thumb-slider__frame"><img src="./images/design-5.jpg" alt=""/></div>
                            </div>
                            <div class="swiper-slide">
                                <div class="design-thumb-slider__frame"><img src="./images/design-1.jpg" alt=""/></div>
                            </div>
                            <div class="swiper-slide">
                                <div class="design-thumb-slider__frame"><img src="./images/design-2.jpg" alt=""/></div>
                            </div>
                            <div class="swiper-slide">
                                <div class="design-thumb-slider__frame"><img src="./images/design-3.jpg" alt=""/></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section section--light-blue" id="phap-ly">
            <div class="container">
                <h2 class="section__title">Pháp lý</h2>
                <div class="section__desc">Dưới đây là một số quyết định pháp lý quan trọng liên quan đến dự án cầu Trần
                    Hưng Đạo
                </div>
                <div class="legal-grid"><a class="legal" href="#!"><img class="legal__icon" src="./images/icon-pdf.svg"
                                                                        alt=""/>
                        <div class="legal__body">
                            <div class="legal__title">Quyết định phê duyệt chủ trương đầu tư đã được UBND Thành phố Hà
                                Nội phê duyệt
                            </div>
                        </div>
                    </a><a class="legal" href="#!"><img class="legal__icon" src="./images/icon-doc.svg" alt=""/>
                        <div class="legal__body">
                            <div class="legal__title">Quyết định phê duyệt chủ trương đầu tư đã được UBND Thành phố Hà
                                Nội phê duyệt
                            </div>
                        </div>
                    </a><a class="legal" href="#!"><img class="legal__icon" src="./images/icon-excel.svg" alt=""/>
                        <div class="legal__body">
                            <div class="legal__title">Quyết định phê duyệt chủ trương đầu tư đã được UBND Thành phố Hà
                                Nội phê duyệt
                            </div>
                        </div>
                    </a><a class="legal" href="#!"><img class="legal__icon" src="./images/icon-pdf.svg" alt=""/>
                        <div class="legal__body">
                            <div class="legal__title">Phê duyệt phương án kiến trúc đã được UBND Thành phố Hà Nội phê
                                duyệt
                            </div>
                        </div>
                    </a><a class="legal" href="#!"><img class="legal__icon" src="./images/icon-doc.svg" alt=""/>
                        <div class="legal__body">
                            <div class="legal__title">Quyết định phê duyệt chủ trương đầu tư đã được UBND Thành phố Hà
                                Nội phê duyệt
                            </div>
                        </div>
                    </a><a class="legal" href="#!"><img class="legal__icon" src="./images/icon-excel.svg" alt=""/>
                        <div class="legal__body">
                            <div class="legal__title">Quyết định số 768/QĐ-TTg ngày 06/5/2016 của Thủ tướng Chính phủ
                                phê duyệt điều chỉnh
                            </div>
                        </div>
                    </a><a class="legal" href="#!"><img class="legal__icon" src="./images/icon-pdf.svg" alt=""/>
                        <div class="legal__body">
                            <div class="legal__title">Kế hoạch khởi công đã được UBND Thành phố Hà Nội phê duyệt</div>
                        </div>
                    </a><a class="legal" href="#!"><img class="legal__icon" src="./images/icon-doc.svg" alt=""/>
                        <div class="legal__body">
                            <div class="legal__title">Quyết định số 355/QĐ-TTg ngày 25/02/2013 của Thủ tướng Chính phủ
                                về phê duyệt điều chỉnh ...
                            </div>
                        </div>
                    </a>
                </div>
                <nav class="d-flex justify-content-center mt-40 mt-lg-60"><a class="button" href="#!">Xem thêm</a></nav>
            </div>
        </section>
        <section class="section" id="tin-tuc"><img class="texture-1" src="./images/texture-1.png" alt=""><img class="texture-2"
                                                                                                 src="./images/texture-2.png"
                                                                                                 alt="">
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
    </div>
@endsection

@push('bottom')
    <script>
        $(document).ready(function () {
        $('.project-nav__list a').click(function () {
            $('.project-nav__list a').removeClass('active');
            $(this).addClass('active');
        });
        });
    </script>
@endpush
