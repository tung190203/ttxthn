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
            <img class="pj-banner__bg" src="./images/project_detail_cn2.png" alt="">
            <div class="pj-banner__wrapper custom-wrapper">
                <div class="container">
                    <div class="pj-banner__subtitle text-end">Dự án</div>
                    <div class="pj-banner__title text-end">Cụm công nghiệp CN2</div>
                    <div class="custom_desc">Cụm công nghiệp CN2 (50,5ha), tọa lạc tại xã Mai Đình, huyện Sóc Sơn, Hà Nội,
                        có thời hạn thuê đất đến 50 năm. Dự án ưu tiên thu hút các doanh nghiệp công nghệ cao, sản xuất hiện đại, thân thiện môi trường và các công ty trong lĩnh vực hàng không, dịch vụ sân bay.
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
        <section class="section" id="thong-tin-chung"><img class="section__bg" src="./images/achitect-bg.png" alt="">
            <div class="container">
                <h2 class="section__title">Thông tin chung</h2>
                <div class="mx-auto" style="max-width: 800px;">
                    <p>Cụm công nghiệp CN2 là một mắt xích quan trọng trong định hướng phát triển công nghiệp sạch, công nghệ cao tại cửa ngõ phía Bắc Thủ đô. Dự án không chỉ nằm gần Cảng hàng không quốc tế Nội Bài mà còn được quy hoạch đồng bộ, hạ tầng hoàn thiện, sẵn sàng đón các doanh nghiệp sản xuất quy mô vừa và lớn trong các lĩnh vực công nghệ cao, phụ trợ hàng không, logistics và công nghiệp thân thiện với môi trường.</p>
                    <ul>
                        <li><strong>Tên dự án</strong>: Cụm công nghiệp CN2</li>
                        <li><strong>Ngành/Lĩnh vực</strong>: Công nghiệp - Công nghệ cao - Dịch vụ logistics/hàng không</li>
                        <li><strong>Địa điểm</strong>: Xã Mai Đình, huyện Sóc Sơn, TP. Hà Nội
                        </li>
                        <li><strong>Tổng diện tích</strong>: 50,5 ha
                        </li>
                        <li><strong>Hạ tầng</strong>: Giao thông kết nối thuận tiện, hệ thống điện – nước – viễn thông đầy đủ, đất công nghiệp phân lô sẵn sàng bàn giao</li>
                        <li><strong>Hình thức khai thác</strong>: Cho thuê đất/nhà xưởng với thời hạn tối đa 50 năm</li>
                        <li><strong>Đối tượng thu hút</strong>: Doanh nghiệp sản xuất công nghệ cao, phụ trợ hàng không, logistics, công nghiệp sạch
                        </li>
                        <li><strong>Lợi thế nổi bật</strong>: 
                            <ul>
                                <li>Gần Cảng hàng không quốc tế Nội Bài</li>
                                <li>Thuộc hành lang phát triển công nghiệp Bắc Hà Nội</li>
                                <li>Hưởng chính sách ưu đãi đầu tư của địa phương</li>
                            </ul>
                        </li>
                        <li><strong>Hiện trạng đất</strong>: Đã hoàn thành giải phóng mặt bằng, hạ tầng sẵn sàng khai thác</li>
                        <li><strong>Phù hợp quy hoạch</strong>: Phù hợp Quy hoạch phát triển các cụm công nghiệp TP. Hà Nội đến năm 2030, tầm nhìn 2050
                        </li>
                        <li><strong>Đơn vị phát triển</strong>: Công ty TNHH Hạ tầng và Phát triển Khu công nghiệp ASG (ASGI)</li>
                        <li><strong>Tọa độ</strong>: 21.054458467615497, 105.85733473128039 (liên kết)</li>
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
                                    src="./images/advantage-1_cn2.png" alt=""/></a>
                        <div class="advantage__body">
                            <div class="advantage__index">1</div>
                            <div class="advantage__index-bg">1</div>
                            <div class="advantage__title">Kết nối giao thông</div>
                            <div class="advantage__desc">
                                <p>Cụm công nghiệp CN2 nằm gần sân bay quốc tế Nội Bài (chỉ 5 phút di chuyển), cách trung tâm Hà Nội 30km và cách cảng Hải Phòng khoảng 120km qua QL5 và cao tốc 5B. Dự án kết nối thuận tiện với các tuyến giao thông trọng điểm như tỉnh lộ 131, QL3, QL18, cao tốc Nội Bài – Lào Cai, Nội Bài – Thái Nguyên và Nội Bài – Bắc Ninh – Hạ Long. Từ đây, chỉ mất khoảng 30 phút để đến các khu công nghiệp lớn như Samsung Thái Nguyên và Samsung Bắc Ninh.</p>
                            </div>
                        </div>
                    </div>
                    <div class="advantage mt-20"><a class="advantage__frame" href="#!"><img
                                    src="./images/advantage-2_cn2.png" alt=""/></a>
                        <div class="advantage__body">
                            <div class="advantage__index">2</div>
                            <div class="advantage__index-bg">2</div>
                            <div class="advantage__title">Thuộc quy hoạch Phân khu đô thị Sóc Sơn</div>
                            <div class="advantage__desc">
                                <p>Quy mô nghiên cứu quy hoạch trên diện tích khoảng 561,31 ha với dân số dự kiến đến năm 2030 là 21.150 người;</p>

                                <p>Hình thành khu vực công nghiệp tập trung đa ngành nghề. Trong đó, ưu tiên phát triển công nghiệp điện tử – công nghệ thông tin, cơ khí, sản xuất ô tô, công nghiệp vật liệu mới, hóa dược – mỹ phẩm, dệt may… Đồng thời, đầu tư xây dựng đồng bộ hạ tầng kỹ thuật, hạ tầng xã hội, khu nhà ở công nhân và các công trình phụ trợ phục vụ khu công nghiệp;</p>
                            </div>
                        </div>
                    </div>
                    <div class="advantage mt-20"><a class="advantage__frame" href="#!"><img
                                    src="./images/advantage-3_cn2.png" alt=""/></a>
                        <div class="advantage__body">
                            <div class="advantage__index">3</div>
                            <div class="advantage__index-bg">3</div>
                            <div class="advantage__title">Hạ tầng kỹ thuật và Tiện ích</div>
                            <div class="advantage__desc">
                                <p>Cụm công nghiệp CN2 được đầu tư hạ tầng hiện đại, đồng bộ. Dự án cấp điện từ trạm 110kV Nội Bài (18.000 kVA), cấp nước từ nguồn thành phố với trạm bơm tăng áp công suất 2.022 m³/ngày.đêm. Hệ thống xử lý nước thải đạt tiêu chuẩn A, công suất 1.250 m³/ngày.đêm. Viễn thông kết nối đến từng lô đất. Tiện ích đi kèm gồm văn phòng cho thuê, bãi đỗ xe, khu giao dịch ngân hàng, trưng bày sản phẩm và dịch vụ hỗ trợ cho chuyên gia, quản lý, công nhân.</p>
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
                <iframe src="https://momento360.com/e/u/600d379d5a514ce9b62319f4af501db1"></iframe>
            </div>
            <div class="container">
                <h2 class="section__title text-white">Sa bàn ảo</h2>
            </div>
        </section>
        <section class="section" id="thiet-ke-va-mat-bang">
            <div class="container">
                <h2 class="section__title">Thiết kế & mặt bằng</h2>
                <div class="section__desc">Phương án quy hoạch CN2 được xây dựng đồng bộ, hiện đại, phù hợp định hướng phát triển công nghiệp xanh – công nghệ cao của Hà Nội, đảm <br> bảo hạ tầng hoàn chỉnh, kết nối thuận tiện và khả năng khai thác hiệu quả.
                </div>
                <div class="design-slider">
                    <div class="design-slider__container swiper-container">
                        <div class="swiper-wrapper">
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/design-1_cn2.jpg" alt=""/></div>
                                <div class="design-slider__overlay">
                                    <div class="design-slider__content">Ý tưởng phát triển được hình thành trên cơ sở khảo sát thực tiễn, nhu cầu thị trường và 
                                    bài toán kết nối hạ tầng liên vùng, nhằm tối ưu hiệu quả sử dụng đất và thu hút đầu tư bền vững.
                                    </div>
                                </div>
                            </div>
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/design-2_cn2.jpg" alt=""/></div>
                                <div class="design-slider__overlay">
                                    <div class="design-slider__content">Ý tưởng "Hà Nội bất tận" lấy cảm hứng từ không
                                        gian mênh mông của dòng sông Hồng, với hình ảnh sóng lượn liên tục nối từ hai
                                        bờ, tạo ra biểu tượng vô cực
                                    </div>
                                </div>
                            </div>
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/design-3_cn2.jpg" alt=""/></div>
                                <div class="design-slider__overlay">
                                    <div class="design-slider__content">Ý tưởng "Hà Nội bất tận" lấy cảm hứng từ không
                                        gian mênh mông của dòng sông Hồng, với hình ảnh sóng lượn liên tục nối từ hai
                                        bờ, tạo ra biểu tượng vô cực
                                    </div>
                                </div>
                            </div>
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/design-4_cn2.jpg" alt=""/></div>
                                <div class="design-slider__overlay">
                                    <div class="design-slider__content">Ý tưởng "Hà Nội bất tận" lấy cảm hứng từ không
                                        gian mênh mông của dòng sông Hồng, với hình ảnh sóng lượn liên tục nối từ hai
                                        bờ, tạo ra biểu tượng vô cực
                                    </div>
                                </div>
                            </div>
                            <div class="design-slider__slide swiper-slide">
                                <div class="design-slider__frame"><img src="./images/design-5_cn2.jpg" alt=""/></div>
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
                                <div class="design-thumb-slider__frame"><img src="./images/design-1_cn2.jpg" alt=""/></div>
                            </div>
                            <div class="swiper-slide">
                                <div class="design-thumb-slider__frame"><img src="./images/design-2_cn2.jpg" alt=""/></div>
                            </div>
                            <div class="swiper-slide">
                                <div class="design-thumb-slider__frame"><img src="./images/design-3_cn2.jpg" alt=""/></div>
                            </div>
                            <div class="swiper-slide">
                                <div class="design-thumb-slider__frame"><img src="./images/design-4_cn2.jpg" alt=""/></div>
                            </div>
                            <div class="swiper-slide">
                                <div class="design-thumb-slider__frame"><img src="./images/design-5_cn2.jpg" alt=""/></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section section--light-blue" id="phap-ly">
            <div class="container">
                <h2 class="section__title">Pháp lý</h2>
                <div class="section__desc">Dưới đây là một số quyết định pháp lý quan trọng liên quan đến cụm dự án CN2
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
        // Ẩn phần tử có id "sa-ban-ao" nếu tham số "hide" trong URL là "saban"
        $(document).ready(function () {
            const urlParams = new URLSearchParams(window.location.search);
            const hide = urlParams.get("hide");

            if (hide === "saban") {
            const sabanEl = $("#sa-ban-ao");
            if (sabanEl.length) sabanEl.hide();
            }
        });
    </script>
@endpush
