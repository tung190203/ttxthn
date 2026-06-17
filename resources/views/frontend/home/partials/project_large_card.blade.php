<div class="project-large-card">
    <div class="project-large-card__bg" style="background-image: url('{{ $item['detail_image'] ?? './images/project-1.jpg' }}');"></div>
    <div class="project-large-card__overlay"></div>
    <a class="project-large-card__link" href="{{ route('project_detail', ['slug' => $item['slug'] ?? '']) }}"></a>
    <div class="project-large-card__content">
        <div class="project-large-card__badge">{{ mb_strtoupper($item['industry_name'] ?? 'LĨNH VỰC KHÁC') }}</div>
        <h3 class="project-large-card__title">{{ $item['name'] ?? '' }}</h3>
        <div class="project-large-card__stats">
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
