@extends('frontend.index')

@section('content')
    <div class="page__content">
        <!-- main content-->
        <article class="banner">
            {{-- <div class="banner__breadcrumb">
                <nav>
                    <div class="container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="link-unstyled" href="#!"><i
                                            class="fal fa-home me-2"></i><span>Trang chủ</span></a></li>
                            <li class="breadcrumb-item active">Danh mục dự án đầu tư</li>
                        </ol>
                    </div>
                </nav>
            </div> --}}
            <img class="banner__bg" src="{{ asset('images/thong-tin-chung-banner.jpg') }}" alt=""/>
            <div class="banner__title">{{ __('app.investment_project_list') }}</div>
        </article>
        <nav class="project-nav">
            <div class="container">
                <ul class="project-nav__list">
                    {{-- Tất cả --}}
                    <li>
                        <a 
                            href="{{ route('projects', array_merge(request()->all(), ['is_invest' => null])) }}" 
                            class="{{ request('is_invest') === null ? 'active' : '' }}">
                            {{ __('app.all') }}
                        </a>
                    </li>

                    {{-- Dự án có nhà đầu tư --}}
                    <li>
                        <a 
                            href="{{ route('projects', array_merge(request()->all(), ['is_invest' => 1])) }}" 
                            class="{{ request('is_invest') === '1' ? 'active' : '' }}">
                            {{ __('app.projects_with_investors') }}
                        </a>
                    </li>

                    {{-- Dự án đang kêu gọi đầu tư --}}
                    <li>
                        <a 
                            href="{{ route('projects', array_merge(request()->all(), ['is_invest' => 0])) }}" 
                            class="{{ request('is_invest') === '0' ? 'active' : '' }}">
                            {{ __('app.projects_calling_for_investment') }}
                        </a>
                    </li>
                </ul>
            </div>
        </nav>        
        <section class=" pb-40 pt-40">
            {{-- <img class="texture-7" src="./images/texture-7.png" alt=""> --}}
            <div class="container">
                <div class="row g-20">
                    <div class="col-lg-3">
                        <form class="aside-form" action="{{ route('projects') }}" method="GET">
                            {{-- Giữ lại tab filter khi submit --}}
                            @if(request()->has('is_invest'))
                                <input type="hidden" name="is_invest" value="{{ request('is_invest') }}">
                            @endif

                            <div class="mb-4">
                                <div class="input-group">
                                    <input class="form-control" type="text" name="keyword" value="{{ request('keyword') }}" placeholder="{{ __('app.search_project') }}"/>
                                    <div class="input-group-text"><i class="fal fa-fw fa-search"></i></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="fw-600 text-uppercase mb-2">{{ __('app.project_types') }}</div>
                                <select class="form-select" name="type_id">
                                    <option value="">{{ __('app.all') }}</option>
                                    @foreach ($list_types as $item)
                                        <option value="{{ $item['id'] }}" {{ request('type_id') == $item['id'] ? 'selected' : '' }}>{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <div class="fw-600 text-uppercase mb-2">{{ __('app.industry_field') }}</div>
                                @foreach ($list_industries as $industry)
                                    <div class="mt-2">
                                        <label class="checkbox-styled">
                                            <input class="checkbox-styled__input" type="checkbox" name="industries[]" value="{{ $industry['id'] }}"
                                                {{ is_array(request('industries')) && in_array($industry['id'], request('industries')) ? 'checked' : '' }}>
                                            <span class="checkbox-styled__icon"></span>
                                            <span>{{ $industry['name'] }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-4">
                                <div class="fw-600 text-uppercase mb-2">{{ __('app.locations') }}</div>
                                <select class="form-select" name="district_id">
                                    <option value="">{{ __('app.select_ward_commune') }}</option>
                                    @foreach ($list_districts as $item)
                                        <option value="{{ $item['id'] }}" {{ request('district_id') == $item['id'] ? 'selected' : '' }}>{{ $item['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button class="button button--block" type="submit">{{ __('app.search') }}</button>
                        </form>                        
                    </div>
                    <div class="col-lg-9">
                        <div class="row g-20">
                            @if($projects->isEmpty())
                                <div class="col-12">
                                    <p class="text-center">{{ __('app.no_matching_results') }}</p>
                                </div>
                            @endif
                            @foreach($projects as $item)
                                <div class="col-6 col-md-4 col-lg-6 col-xl-4">
                                    <div class="project">
                                        <a class="project__frame" href="{{ route('project_detail', ['slug' => $item->slug, 'ref' => 'Dự án kêu gọi đầu tư']) }}">
                                            <img src="{{ $item->detail_image ?? asset('/images/project-1.jpg') }}" alt=""/></a>
                                        <div class="project__body">
                                            <h3 class="project__title"><a href="{{ route('project_detail', ['slug' => $item->slug, 'ref' => 'Dự án kêu gọi đầu tư']) }}" data-tippy-content="{{$item->name}}">{{$item->name}}</a></h3>
                                            @if($item->is_invest == 0)
                                                <div class="project__overlay"><span>{{ __('app.projects_calling_for_investment') }}</span>
                                                    <a class="project__like" href="javascript:void(0)" data-id="{{ $item->id }}" data-type="App\Models\Project"><i class="fas fa-fw fa-lg fa-heart {{ $item->is_interested ? 'text-danger' : '' }}"></i></a>
                                                </div>
                                            @else
                                                <div class="project__overlay"><span>{{ __('app.projects_with_investors') }}</span>
                                                    <a class="project__like" href="javascript:void(0)" data-id="{{ $item->id }}" data-type="App\Models\Project"><i class="fas fa-fw fa-lg fa-heart {{ $item->is_interested ? 'text-danger' : '' }}"></i></a>
                                                </div>
                                            @endif
                                            <ul class="project__info">
                                                <li><img class="me-2" src="{{ asset('/images/icon-map-marker.svg') }}" alt=""/><span data-tippy-content="{{ __('app.project_under') }} {{ $item->districts->pluck('name')->join(', ') }}, {{ __('app.hanoi_city') }}">{{ __('app.project_under') }} {{ $item->districts->pluck('name')->join(', ') }}, {{ __('app.hanoi_city') }}</span>
                                                </li>
                                                <li>
                                                    <img class="me-2" src="{{ asset('/images/icon-dimension.svg') }}" alt="" />
                                                    @php
                                                        $locale = app()->getLocale();
                                                        if ($locale === 'vn') {
                                                            $locale = 'vi_VN';
                                                        } elseif ($locale === 'en') {
                                                            $locale = 'en_US';
                                                        }

                                                        $fmt = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
                                                        $fmt->setAttribute(\NumberFormatter::FRACTION_DIGITS, 2);
                                                        $formattedArea = $fmt->format($item->area ?? 0);
                                                    @endphp
                                                    <span>{{ $formattedArea }} {{ $item->unit_type_text ?? '' }}</span>
                                                </li>
                                                <li><img class="me-2" src="{{ asset('/images/icon-save-money.svg') }}" alt=""/><span>{{ number_format($item->price, 0, ',','.' )}} {{ __('app.billion_vnd') }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center mt-40 mt-lg-50">
                            {{ $projects->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('bottom')

@endpush
