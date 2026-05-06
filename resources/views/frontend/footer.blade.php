<section class="section section--bg-pattern">
    <div class="container">
        <h2 class="section__title text-white mb-4 text-uppercase font-baijam">{{ __('app.subscribe_project_info') }}</h2>
        @php
            $locale = app()->getLocale() === 'vi' ? 'vn' : app()->getLocale();
        @endphp

        <div class="text-center">
            <a class="button" href="{{ url($locale . '/' . __('app.contact_link')) }}">
                {{ __('app.subscrible_text') }}
            </a>
        </div>
    </div>
</section>
<footer class="footer">
    <div class="footer__inner">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mb-40 mb-lg-0 pe-lg-4">
                    <a class="footer__logo mb-3 d-inline-block" href="/">
                        <img src="{{$setting['logo_footer'] ?? ''}}" alt="" />
                    </a>
                    <div class="footer__content custom-text-footer mt-2" style="font-size: 13px; color: #244F9E; font-weight: 700;">
                        {!! \App\Models\Setting::getSettingByKey('footer_info') !!}
                    </div>
                    <div class="footer__content custom-text-footer text-white mt-3" style="font-size: 12px; opacity: 0.7;">
                        {!! \App\Models\Setting::getSettingByKey('copyright_notice') !!}
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-40 mb-md-0">
                    <div class="footer__title mb-4" style="font-size: 14px; letter-spacing: 1px; color: #8e9aab;">{{ __('app.quick_links') }}</div>
                    <ul class="f-links">
                        <li><a href="{{ url($locale ?? 'vn' . '/') }}">{{ __('app.home') }}</a></li>
                        <li><a href="{{ url($locale ?? 'vn' . '/' . __('app.investment_guide_link')) }}">{{ __('app.investment_guide') }}</a></li>
                        <li><a href="{{ url($locale ?? 'vn' . '/' . __('app.project_link')) }}">{{ __('app.investment_projects') }}</a></li>
                        <li><a href="{{ url($locale ?? 'vn' . '/' . __('app.news_link')) }}">{{ __('app.news') }}</a></li>
                        <li><a href="{{ url($locale ?? 'vn' . '/' . __('app.contact_link')) }}">{{ __('app.contact') }}</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="footer__title mb-4" style="font-size: 14px; letter-spacing: 1px; color: #8e9aab;">{{ __('app.contact') }}</div>
                    <ul class="f-contact-custom mb-4">
                        <li class="d-flex align-items-start">
                            <strong style="min-width: 58px;">{{ __('app.address') }}:</strong>
                            <span>{!! strip_tags(\App\Models\Setting::getSettingByKey('address')) !!}</span>
                        </li>
                        <li class="d-flex align-items-start">
                            <strong style="min-width: 50px;">Email:</strong>
                            <a href="mailto:{{ $setting['email'] ?? 'invest@hanoi.gov.vn' }}">{{ $setting['email'] ?? 'invest@hanoi.gov.vn' }}</a>
                        </li>
                        <li class="d-flex align-items-start">
                            <strong style="min-width: 68px;">Website:</strong>
                            <a href="{{$setting['website'] ?? ''}}" target="_blank">{{$setting['website'] ?? ''}}</a>
                        </li>
                        <li class="d-flex align-items-start">
                            <strong style="min-width: 83px;">Điện thoại:</strong>
                            <a href="tel:{{ preg_replace('/\D+/', '', $setting['phone'] ?? '') }}">{{ $setting['phone'] ?? '' }}</a>
                        </li>
                    </ul>
                    <ul class="f-social-custom">
                        <li><a href="{{ $setting['facebook'] ?? '' }}"><img src="{{asset('./images/icon-facebook.svg')}}" alt="" /></a></li>
                        <li><a href="#!"><img src="{{asset('./images/icon-youtube.svg')}}" alt="" /></a></li>
                        <li><a href="#!"><img src="{{asset('./images/icon-tik-tok.svg')}}" alt="" /></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer__copyright">
        <div class="container">
            {!! \App\Models\Setting::getSettingByKey('copyright') !!}
        </div>
    </div>
</footer>
