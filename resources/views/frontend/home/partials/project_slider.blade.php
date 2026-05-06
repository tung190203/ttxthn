@if (empty($project_category))
    <p class="text-center">
        {{ __('app.no_suitable_project') }}
    </p>
@else
    <div class="news-slider">
        <div class="news-slider__container swiper-container">
            <div class="swiper-wrapper">
                @foreach ($project_category as $item)
                    <div class="swiper-slide">
                        <div class="project-card-custom">
                            <a class="project-card__image" href="{{ route('project_detail', ['slug' => $item['slug']]) }}">
                                <img src="{{ $item['detail_image'] ?? './images/project-1.jpg' }}" alt="{{ $item['name'] }}" />
                            </a>
                            <a class="project__like project-card__like-btn" href="javascript:void(0)" data-id="{{ $item['id'] }}" data-type="App\Models\Project">
                                <i class="fas fa-fw fa-heart {{ !empty($item['is_interested']) ? 'text-danger' : '' }}"></i>
                            </a>
                            <div class="project-card__content">
                                <div class="project-card__category">
                                    {{ $item['industry_name'] ?? '' }}
                                </div>
                                <h3 class="project-card__title">
                                    <a href="{{ route('project_detail', ['slug' => $item['slug']]) }}" data-tippy-content="{{ $item['name'] }}">
                                        {{ $item['name'] }}
                                    </a>
                                </h3>

                                @php
                                    $locale = app()->getLocale();
                                    if ($locale === 'vn') { $locale = 'vi_VN'; } 
                                    elseif ($locale === 'en') { $locale = 'en_US'; }

                                    $fmt = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
                                    $fmt->setAttribute(\NumberFormatter::FRACTION_DIGITS, 2);
                                    $formattedArea = $fmt->format($item['area'] ?? 0);
                                    
                                    $formattedPrice = number_format($item['price'] ?? 0, 0, ',', '.');
                                    if (app()->getLocale() == 'en') {
                                        $formattedPrice = number_format($item['price'] ?? 0, 0, '.', ',');
                                    }
                                @endphp

                                <div class="project-card__info">
                                    <div class="info-item">
                                        <span class="info-value">{{ $formattedPrice }} {{ __('app.billion_vnd') }}</span>
                                        <span class="info-label">{{ __('app.investment_scale') }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-value">{{ $formattedArea }} {{ $item['unit'] ?? '' }}</span>
                                        <span class="info-label">{{ __('app.area') }}</span>
                                    </div>
                                    @if(!empty($item['completion_year']))
                                    <div class="info-item">
                                        <span class="info-value">{{ $item['completion_year'] }}</span>
                                        <span class="info-label">{{ __('app.completed') }}</span>
                                    </div>
                                    @endif
                                </div>
                                <a href="{{ route('project_detail', ['slug' => $item['slug']]) }}" class="project-card__arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="7" y1="17" x2="17" y2="7"></line>
                                        <polyline points="7 7 17 7 17 17"></polyline>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @php
        $locale = app()->getLocale() === 'vi' ? 'vn' : app()->getLocale();
    @endphp
    <nav class="d-flex justify-content-center mt-40 mt-lg-60">
        <a class="button" href="{{ url($locale . '/' . __('app.projects_link') ) }}" style="text-transform: capitalize;font-size:14px">{{ __('app.view_more') }}</a>
    </nav>
@endif
