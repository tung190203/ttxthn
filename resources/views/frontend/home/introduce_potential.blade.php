@extends('frontend.index')

@section('content')
    <div class="page__content">
        <!-- main content-->
        <article class="banner">
            <div class="banner__breadcrumb">
                <nav>
                    <div class="container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active"></li>
                        </ol>
                    </div>
                </nav>
            </div>
            <img class="banner__bg" src="{{ asset('images/banner-project.jpg') }}" alt="" />
            <div class="banner__title">{{ __('app.investment_guide') }}</div>
        </article>
        <nav class="project-nav">
            <div class="container">
                <ul class="project-nav__list">
                    <li>
                        <a 
                            href="{{ route('category', array_merge(['slug' => $setting['menu_active']], request()->only('keyword'), ['cat_id' => null])) }}" 
                            class="{{ empty($selectedCatId) ? 'active' : '' }}">
                            {{ __('app.all') }}
                        </a>
                    </li>
                    @foreach($childCategories as $id => $name)
                        <li>
                            <a 
                                href="{{ route('category', array_merge(['slug' => $setting['menu_active']], request()->only('keyword'), ['cat_id' => $id])) }}" 
                                class="{{ (int)$selectedCatId === $id ? 'active' : '' }}">
                                {{ $name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </nav>            
        <div class="pt-40">
            <div class="container">
                <div class="row g-20">
                    <div class="col-lg-3">
                        <form class="aside-form" method="GET" action="{{ route('category',['slug' => $setting['menu_active']]) }}">
                            <div class="mb-4">
                                <div class="input-group">
                                    <input class="form-control" type="text" name="keyword" placeholder="{{ __('app.search') }}"
                                           value="{{ request('keyword') }}">
                                    <div class="input-group-text"><i class="fal fa-fw fa-search"></i></div>
                                </div>
                            </div>
                        
                            <div class="mb-4">
                                <div class="fw-600 text-uppercase mb-2">{{ __('app.category_filter') }}</div>
                                <select class="form-select" name="cat_id">
                                    <option value="">{{ __('app.all') }}</option>
                                    @foreach($childCategories as $id => $name)
                                        <option value="{{ $id }}" {{ (int)$selectedCatId === $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <button class="button button--block" type="submit">{{ __('app.search') }}</button>
                        </form>                        
                    </div>
                    <div class="col-lg-9">
                        <div class="row g-20">
                    @if ($list_investment->isEmpty())
                        <div class="col-12">
                            <p class="text-center fs-2">
                                {{ __('app.no_investment_guide') }}
                            </p>
                        </div>
                    @else
                        @foreach ($list_investment as $item)
                            <div class="col-6 col-lg-4">
                                <div class="news"><a class="news__frame"
                                        href="{{ route('investment_guide_detail',['id' => $item->id, 'slug' => $item->slug, 'ref' => 'app.investment_guide']) }}"><img
                                            src="{{ $item->image }}" alt="" /></a>
                                    <div class="news__body">
                                        <div class="news__info">
                                            <div class="news__time"><i
                                                    class="fal fa-clock me-2"></i><span>{{ \Carbon\Carbon::parse($item->published_at)->format('d/m/Y') }}</span>
                                            </div>
                                            <a class="news__like" href="javascript:void(0)"
                                            data-id="{{ $item->id }}" data-type="App\Models\InvestmentGuide">
                                             <i class="fas fa-fw fa-heart {{ $item->is_interested ? 'text-danger' : '' }}"></i>
                                         </a>
                                        </div>
                                        <h3 class="news__title custom-desc"><a
                                                href="{{ route('investment_guide_detail',['id' => $item->id, 'slug' => $item->slug, 'ref' => 'app.investment_guide']) }}" data-tippy-content="{{$item->name}}">{{ $item->name }}</a></h3>
                                        <div class="news__desc">{{ $item->description }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                        </div>
                        <nav class="d-flex justify-content-center mt-40 mt-lg-50" aria-label="Pagination navigation">
                            {{ $list_investment->onEachSide(1)->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom')
@endpush
