@if (empty($project_category))
    <p class="text-center">
        {{ __('app.no_suitable_project') }}
    </p>
@else
    @php
        $locale = app()->getLocale();
        $localeLink = $locale === 'vi' ? 'vn' : $locale;
        $items = collect($project_category);
    @endphp

    {{-- DESKTOP SLIDER (>= 992px) --}}
    <div class="project-complex-slider d-none d-lg-block">
        <div class="project-complex-slider__container swiper-container">
            <div class="swiper-wrapper">
                @php
                    $desktopItems = clone $items;
                    $desktopFirst = $desktopItems->splice(0, 4);
                    $desktopOthers = $desktopItems->chunk(6);
                @endphp
                @if($desktopFirst->isNotEmpty())
                    <div class="swiper-slide">
                        <div class="project-slide-first">
                            @php $largeItem = $desktopFirst->shift(); @endphp
                            @if($largeItem)
                                @include('frontend.home.partials.project_large_card', ['item' => $largeItem, 'locale' => $locale])
                            @endif
                            <div class="project-small-cards-col">
                                @foreach($desktopFirst as $item)
                                    @include('frontend.home.partials.project_small_card', ['item' => $item, 'locale' => $locale])
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                @foreach($desktopOthers as $slideItems)
                    <div class="swiper-slide">
                        <div class="project-slide-grid">
                            @foreach($slideItems as $item)
                                @include('frontend.home.partials.project_small_card', ['item' => $item, 'locale' => $locale])
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- TABLET SLIDER (768px - 991px) --}}
    <div class="project-complex-slider d-none d-md-block d-lg-none">
        <div class="project-complex-slider__container swiper-container">
            <div class="swiper-wrapper">
                @php
                    $tabletItems = clone $items;
                    $tabletFirst = $tabletItems->splice(0, 3);
                    $tabletOthers = $tabletItems->chunk(5);
                @endphp
                @if($tabletFirst->isNotEmpty())
                    <div class="swiper-slide">
                        <div class="project-slide-first project-slide-first--tablet">
                            @php $largeItem = $tabletFirst->shift(); @endphp
                            @if($largeItem)
                                @include('frontend.home.partials.project_large_card', ['item' => $largeItem, 'locale' => $locale])
                            @endif
                            <div class="project-small-cards-col project-small-cards-col--tablet">
                                @foreach($tabletFirst as $item)
                                    @include('frontend.home.partials.project_small_card', ['item' => $item, 'locale' => $locale])
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                @foreach($tabletOthers as $slideItems)
                    <div class="swiper-slide">
                        <div class="project-slide-grid project-slide-grid--tablet">
                            @foreach($slideItems as $item)
                                @include('frontend.home.partials.project_small_card', ['item' => $item, 'locale' => $locale])
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MOBILE SLIDER (< 768px) --}}
    <div class="project-complex-slider d-block d-md-none">
        <div class="project-complex-slider__container swiper-container">
            <div class="swiper-wrapper">
                @php
                    $mobileItems = clone $items;
                    $mobileFirst = $mobileItems->splice(0, 1);
                    $mobileOthers = $mobileItems->chunk(3);
                @endphp
                @if($mobileFirst->isNotEmpty())
                    <div class="swiper-slide">
                        <div class="project-slide-first project-slide-first--mobile">
                            @php $largeItem = $mobileFirst->shift(); @endphp
                            @if($largeItem)
                                @include('frontend.home.partials.project_large_card', ['item' => $largeItem, 'locale' => $locale])
                            @endif
                        </div>
                    </div>
                @endif
                @foreach($mobileOthers as $slideItems)
                    <div class="swiper-slide">
                        <div class="project-small-cards-col project-small-cards-col--mobile" style="height: 100%; display: flex; flex-direction: column; justify-content: center; gap: 16px;">
                            @foreach($slideItems as $item)
                                @include('frontend.home.partials.project_small_card', ['item' => $item, 'locale' => $locale])
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
