@foreach($investment_guides as $index => $guide)
<!-- Item {{ $index + 1 }} -->
<div class="policy-item border-bottom py-3" style="border-color: rgba(255,255,255,0.2) !important;">
    <div class="policy-header d-flex justify-content-between align-items-center {{ $index == 0 ? 'expanded' : '' }}" style="cursor: pointer;" onclick="togglePolicy(this)">
        <div class="d-flex align-items-center">
            <span class="policy-num me-3" style="color: #F4C430; font-weight: bold; font-size: 16px; min-width: 25px;">{{ sprintf('%02d', $index + 1) }}</span>
            <h4 class="policy-item-title mb-0" style="font-size: 16px; font-weight: 600; text-transform: uppercase;">{{ $guide->name }}</h4>
        </div>
        <span class="policy-icon" style="color: rgba(255,255,255,0.5); font-size: 20px;">{{ $index == 0 ? '-' : '+' }}</span>
    </div>
    <div class="policy-body mt-2" style="display: {{ $index == 0 ? 'block' : 'none' }}; padding-left: 40px;">
        <a href="{{ $guide->getUrl() }}" style="color: rgba(255,255,255,0.8); text-decoration: none; display: block; font-size: 14px; line-height: 1.5;">
            {{ $guide->description ? \Illuminate\Support\Str::limit(strip_tags($guide->description), 150) : 'Xem chi tiết thông tin về chính sách và ưu đãi đầu tư...' }}
        </a>
    </div>
</div>
@endforeach
