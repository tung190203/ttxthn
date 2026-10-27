@php
    $historyTabs = [
        'skin'     => ['icon' => 'fa-palette',        'label' => 'Skin'],
        'panorama' => ['icon' => 'fa-images',         'label' => 'Panorama'],
        'hotspot'  => ['icon' => 'fa-map-marker-alt', 'label' => 'Hotspot'],
    ];
@endphp

<div class="card-header p-0">
    <ul class="nav nav-tabs" role="tablist">
        @foreach ($historyTabs as $key => $item)
            <li class="nav-item">
                <a class="nav-link {{ $tab === $key ? 'active' : '' }}"
                    href="{{ route('backend_vrtour_history_index', [
                        'slt_vrtour' => $selectedVrtour->id,
                        'tab'        => $key,
                        'from_date'  => request('from_date'),
                        'to_date'    => request('to_date'),
                    ]) }}">
                    <i class="fas {{ $item['icon'] }} mr-1"></i> {{ $item['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>