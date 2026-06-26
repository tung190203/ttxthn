<div class="project-small-card">
    <a class="project-small-card__image" href="{{ route('project_detail', ['slug' => $item['slug']]) }}" style="background-image: url('{{ $item['detail_image'] ?? './images/project-1.jpg' }}');"></a>
    <div class="project-small-card__content">
        <div class="project-small-card__category" style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">{{ mb_strtoupper(!empty($item['industry_name']) ? $item['industry_name'] : 'LĨNH VỰC KHÁC') }}</div>
        <h3 class="project-small-card__title">
            <a href="{{ route('project_detail', ['slug' => $item['slug']]) }}" data-tippy-content="{{ $item['name'] }}" style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">
                {{ $item['name'] }}
            </a>
        </h3>
        <div class="project-small-card__stats">
            @php
                $hasPrice = isset($item['price']) && $item['price'] !== '';
                $priceFormatted = $hasPrice ? ($locale !== 'en' ? number_format($item['price'], 0, ',', '.') : number_format($item['price'], 0, '.', ',')) . ' ' . __('app.billion_vnd') : __('app.updating');
                
                $hasArea = isset($item['area']) && $item['area'] !== '';
                $areaFormatted = $hasArea ? formatDecimalByLocale($item['area']) . ' ' . ($item['unit'] ?? '') : __('app.updating');
                
                $areaLabel = __('app.area');
                if (isset($item['unit']) && $item['unit'] === 'km') {
                    $areaLabel = __('app.length');
                }
            @endphp
            <div class="stat-item">
                <strong>{{ $priceFormatted }}</strong>
                <span>{{ __('app.total_capital') }}</span>
            </div>
            <div class="stat-item">
                <strong>{{ $areaFormatted }}</strong>
                <span>{{ $areaLabel }}</span>
            </div>
        </div>
    </div>
</div>
