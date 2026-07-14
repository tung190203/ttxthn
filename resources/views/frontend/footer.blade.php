<style>
.partners-slider .swiper-wrapper {
    display: flex;
    flex-wrap: nowrap;
}
.partners__item img {
    max-width: 100%;
    object-fit: contain;
}
@media (max-width: 767.98px) {
    .partners__item img {
        height: 40px !important; /* Scale down on mobile */
    }
    .news-links__partners {
        padding-top: 15px !important;
        padding-bottom: 10px !important;
    }
    .newsletter-wrapper {
        padding: 15px 0 !important;
    }
    .newsletter-wrapper h2 {
        font-size: 16px !important;
        margin-bottom: 5px !important;
    }
    .newsletter-wrapper p {
        font-size: 12px !important;
        margin-bottom: 10px !important;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .newsletter-form input {
        padding: 6px 10px !important;
        font-size: 13px !important;
        height: 36px !important;
    }
    .newsletter-form button {
        padding: 0 12px !important;
        font-size: 13px !important;
        height: 36px !important;
    }
    .footer {
        padding-top: 15px !important;
        padding-bottom: 10px !important;
    }
    .footer .row.mb-5 {
        margin-bottom: 15px !important;
    }
    .footer .row {
        margin-bottom: 10px !important;
        display: flex;
        flex-wrap: wrap;
    }
    .footer .mb-4 {
        margin-bottom: 10px !important;
    }
    .footer .col-md-12 {
        width: 100%;
        margin-bottom: 15px !important;
    }
    .footer .col-md-12 img {
        max-height: 40px !important;
        margin-bottom: 8px !important;
    }
    .footer .col-md-12 h3, .footer .col-md-12 h3 p {
        font-size: 13px !important;
        margin-bottom: 8px !important;
        line-height: 1.4 !important;
    }
    .footer .col-md-12 p {
        font-size: 12px !important;
        line-height: 1.4 !important;
        margin-bottom: 5px !important;
    }
    /* Show Quick Links and Contact side-by-side on mobile */
    .footer-col-2 {
        width: 45% !important;
        padding-right: 10px;
    }
    .footer-col-3 {
        width: 55% !important;
    }
    .footer-col-2 h4, .footer-col-3 h4 {
        font-size: 13px !important;
        margin-bottom: 10px !important;
    }
    .footer-col-2 ul, .footer-col-3 ul {
        font-size: 12px !important;
        line-height: 1.5 !important;
    }
    .footer-col-2 li, .footer-col-3 li {
        margin-bottom: 6px !important;
    }
    .footer-col-3 .d-flex {
        margin-top: 10px !important;
    }
    .footer-col-3 .d-flex a {
        font-size: 16px !important;
    }
    .footer .text-center {
        padding-top: 10px !important;
        font-size: 10px !important;
    }
}
@media (max-height: 650px) and (max-width: 767.98px) {
    /* Super compact for very short mobile screens */
    .newsletter-wrapper { padding: 15px 0 !important; }
    .footer { padding-top: 15px !important; }
    .footer .col-lg-5 img { max-height: 35px !important; margin-bottom: 5px !important;}
}
</style>

<div class="newsletter-wrapper" style="background: linear-gradient(135deg, #073A73 0%, #1C5FA6 50%, #33AAFA 100%); padding: 60px 0; flex-grow: 1; display: flex; align-items: center;">
    <div class="container text-center w-100">
        <h2 class="text-white mb-2 font-heading" style="font-size: 32px; font-weight: 700;">{{ __('app.subscribe_project_updates') }}</h2>
        <p class="text-white mb-4" style="font-size: 15px; opacity: 0.9;">{{ __('app.subscribe_newsletter_desc') }}</p>
        <div class="d-flex justify-content-center">
            <form action="#" class="newsletter-form" style="display: flex; max-width: 500px; width: 100%;">
                <input type="email" placeholder="{{ __('app.your_email_address') }}" class="form-control" style="border-radius: 4px 0 0 4px; border: none; padding: 14px 20px; box-shadow: none; font-size: 15px;">
                <button type="button" class="btn fw-bold" style="background-color: #ffc107; color: #000; border-radius: 0 4px 4px 0; font-weight: 700; white-space: nowrap; padding: 0 24px; border: none; font-size: 15px;">
                    {{ __('app.contact') }} <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </form>
        </div>
    </div>
</div>

@if (!empty($setting['banners']))
<div class="news-links__partners pt-5 pb-4" style="background-color: #F4F7FC;">
    <div class="container">
        <div class="partners-slider">
            <div class="partners-slider__container swiper-container">
                <div class="swiper-wrapper">
                    @foreach ($setting['banners'] as $banner)
                    <div class="swiper-slide">
                        <div class="partners__item">
                            <a href="{{ $banner['link'] ?? '#' }}" target="_blank">
                                <img src="{{ $banner['image'] ?? '' }}" alt="" />
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<footer class="footer" style="background-color: #F4F7FC; padding-top: 30px; padding-bottom: 20px; color: #333; position: relative;">
    <div class="container">
        <div class="row mb-5">
            <!-- Column 1 -->
            <div class="col-md-12 col-lg-4 mb-4 mb-lg-0">
                <a href="/" class="d-inline-block mb-3">
                    <img src="{{$setting['logo_footer'] ?? asset('./images/logo.png')}}" alt="Logo" style="max-height: 60px;" />
                </a>
                <h3 class="mb-3" style="font-size: 14px; font-weight: 800; color: #1677ff; text-transform: uppercase; line-height: 1.6; max-width: 320px;">
                    {!! \App\Models\Setting::getSettingByKey('footer_info') !!}
                </h3>
                <p style="font-size: 14px; line-height: 1.6; margin-bottom: 0; color: #333; max-width: 300px;">
                     {!! \App\Models\Setting::getSettingByKey('copyright_notice') !!}
                </p>
            </div>
            
            <!-- Column 2 -->
            <div class="col-md-5 col-lg-4 mb-4 mb-lg-0 footer-col-2">
                <h4 class="mb-4" style="font-size: 15px; font-weight: 800; color: #333; text-transform: uppercase; letter-spacing: 1px;">{{__('app.quick_link') }}</h4>
                <ul class="list-unstyled" style="font-size: 14px; line-height: 2.2;">
                    @foreach($share['main_menu'] as $item)
                    <li><a href="{{ $item['href'] }}" style="color: #333; text-decoration: none;">{{ $item['name'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            
            <!-- Column 3 -->
            <div class="col-md-7 col-lg-4 footer-col-3">
                <h4 class="mb-4" style="font-size: 15px; font-weight: 800; color: #333; text-transform: uppercase; letter-spacing: 1px;">{{ __('app.contact') }}</h4>
                <ul class="list-unstyled" style="font-size: 14px; line-height: 2;">
                    <li class="mb-1"><strong>{{__('app.address')}}:</strong><br> {!! strip_tags(\App\Models\Setting::getSettingByKey('address')) ?? 'Khu liên cơ quan Vân Hồ, 52 Lê Đại Hành, phường Hai Bà Trưng, Hà Nội' !!}</li>
                    <li class="mb-1"><strong>{{__('app.email')}}:</strong> <span style="word-break: break-all;">{{ \App\Models\Setting::getSettingByKey('email') ?? 'ttxtdthtdn_sotc@hanoi.gov.vn' }}</span></li>
                    <li class="mb-1"><strong>{{__('app.website')}}:</strong> <a href="{{$setting['website'] ?? '#'}}" style="color: #333; text-decoration: none; word-break: break-all;">{{$setting['website'] ?? 'https://hotrodoanhnghiep.hanoi.gov.vn'}}</a></li>
                    <li class="mb-3"><strong>{{__('app.phone')}}:</strong> {{ $setting['phone'] ?? '0343.653.999' }}</li>
                </ul>
                <div class="d-flex gap-3 mt-3">
                    <a href="{{ $setting['facebook'] ?? '#' }}" style="color: #0866FF; font-size: 18px;"><i class="fab fa-facebook-f"></i></a>
                    <a href="{{ $setting['youtube'] ?? '#' }}" style="color: #FF0000; font-size: 18px;"><i class="fab fa-youtube"></i></a>
                    <a href="{{ $setting['tiktok'] ?? '#' }}" style="color: #000000; font-size: 18px;"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </div>
        
        <div class="text-center" style="border-top: none; font-size: 13px; color: #333;">
            {{ \App\Models\Setting::getSettingByKey('copyright') ?? '' }}
        </div>
    </div>
</footer>
