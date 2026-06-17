<style>
@media (max-width: 767.98px) {
    .newsletter-wrapper {
        padding: 20px 0 !important;
    }
    .newsletter-wrapper h2 {
        font-size: 18px !important;
    }
    .newsletter-wrapper p {
        font-size: 13px !important;
        margin-bottom: 12px !important;
    }
    .newsletter-form input {
        padding: 8px 12px !important;
        font-size: 13px !important;
    }
    .newsletter-form button {
        padding: 0 16px !important;
        font-size: 13px !important;
    }
    .footer {
        padding-top: 20px !important;
        padding-bottom: 10px !important;
    }
    .footer .row {
        margin-bottom: 10px !important;
        display: flex;
        flex-wrap: wrap;
    }
    .footer .col-lg-5 {
        width: 100%;
        margin-bottom: 15px !important;
    }
    .footer .col-lg-5 img {
        max-height: 45px !important;
        margin-bottom: 10px !important;
    }
    .footer .col-lg-5 h3 {
        font-size: 12px !important;
        margin-bottom: 8px !important;
    }
    .footer .col-lg-5 p {
        font-size: 12px !important;
        line-height: 1.4 !important;
    }
    /* Show Quick Links and Contact side-by-side on mobile */
    .footer .col-lg-3 {
        width: 40%;
        display: block !important;
        padding-right: 10px;
    }
    .footer .col-lg-4 {
        width: 60%;
    }
    .footer .col-lg-3 h4, .footer .col-lg-4 h4 {
        font-size: 13px !important;
        margin-bottom: 10px !important;
    }
    .footer .col-lg-3 ul, .footer .col-lg-4 ul {
        font-size: 12px !important;
        line-height: 1.5 !important;
    }
    .footer .col-lg-3 li, .footer .col-lg-4 li {
        margin-bottom: 6px !important;
    }
    .footer .col-lg-4 .d-flex {
        margin-top: 10px !important;
    }
    .footer .col-lg-4 .d-flex a {
        font-size: 16px !important;
    }
    .footer .text-center.pt-4 {
        padding-top: 10px !important;
        font-size: 11px !important;
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
        <h2 class="text-white mb-2" style="font-size: 32px; font-weight: 700;">{{ __('app.subscribe_project_updates') }}</h2>
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

<footer class="footer" style="background-color: #F4F7FC; padding-top: 60px; padding-bottom: 20px; color: #333; position: relative;">
    <div class="container">
        <div class="row mb-5">
            <!-- Column 1 -->
            <div class="col-lg-5 mb-4 mb-lg-0 pe-lg-5">
                <a href="/" class="d-inline-block mb-3">
                    <img src="{{$setting['logo_footer'] ?? asset('./images/logo.png')}}" alt="Logo" style="max-height: 60px;" />
                </a>
                <h3 class="mb-3" style="font-size: 14px; font-weight: 800; color: #1677ff; text-transform: uppercase; line-height: 1.5;">
                    {!! \App\Models\Setting::getSettingByKey('footer_info') !!}
                </h3>
                <p style="font-size: 14px; line-height: 1.6; margin-bottom: 0; color: #333;">
                     {!! \App\Models\Setting::getSettingByKey('copyright_notice') !!}
                </p>
            </div>
            
            <!-- Column 2 -->
            <div class="col-lg-3 mb-4 mb-lg-0">
                <h4 class="mb-4" style="font-size: 15px; font-weight: 800; color: #333; text-transform: uppercase; letter-spacing: 1px;">{{__('app.quick_link') }}</h4>
                <ul class="list-unstyled" style="font-size: 14px; line-height: 2.2;">
                    @foreach($share['main_menu'] as $item)
                    <li><a href="{{ $item['href'] }}" style="color: #333; text-decoration: none;">{{ $item['name'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            
            <!-- Column 3 -->
            <div class="col-lg-4">
                <h4 class="mb-4" style="font-size: 15px; font-weight: 800; color: #333; text-transform: uppercase; letter-spacing: 1px;">{{ __('app.contact') }}</h4>
                <ul class="list-unstyled" style="font-size: 14px; line-height: 2;">
                    <li class="mb-1"><strong>{{__('app.address')}}:</strong> {!! strip_tags(\App\Models\Setting::getSettingByKey('address')) ?? 'Khu liên cơ quan Vân Hồ, 52 Lê Đại Hành, phường Hai Bà Trưng, Hà Nội' !!}</li>
                    <li class="mb-1"><strong>{{__('app.email')}}:</strong> {{ \App\Models\Setting::getSettingByKey('email') ?? 'ttxtdthtdn_sotc@hanoi.gov.vn' }}</li>
                    <li class="mb-1"><strong>{{__('app.website')}}:</strong> <a href="{{$setting['website'] ?? '#'}}" style="color: #333; text-decoration: none;">{{$setting['website'] ?? 'https://hotrodoanhnghiep.hanoi.gov.vn'}}</a></li>
                    <li class="mb-3"><strong>{{__('app.phone')}}:</strong> {{ $setting['phone'] ?? '0343.653.999' }}</li>
                </ul>
                <div class="d-flex gap-3 mt-3">
                    <a href="{{ $setting['facebook'] ?? '#' }}" style="color: #333; font-size: 18px;"><i class="fab fa-facebook-f"></i></a>
                    <a href="{{ $setting['youtube'] ?? '#' }}" style="color: #333; font-size: 18px;"><i class="fab fa-youtube"></i></a>
                    <a href="{{ $setting['tiktok'] ?? '#' }}" style="color: #333; font-size: 18px;"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </div>
        
        <div class="text-center pt-4" style="border-top: none; font-size: 13px; color: #333;">
            {{ \App\Models\Setting::getSettingByKey('copyright') ?? '' }}
        </div>
    </div>
</footer>
