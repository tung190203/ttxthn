@extends('frontend.index')

@section('content')
    <div class="page__content">
        <!-- main content-->
        <article class="banner">
            <img class="banner__bg" src="{{ asset('images/thong-tin-chung-banner.jpg') }}" alt="" />
            <div class="banner__title">{{ __('app.investment_products') }}</div>
        </article>

        <section class=" pb-40 pt-40">
            <div class="container">
                <div class="row g-20">
                    <div class="col-lg-3">
                        <form class="aside-form" action="{{ route('industrial_projects') }}" method="GET">
                            <div class="mb-4">
                                <div class="input-group">
                                    <input class="form-control" type="text" name="keyword" value="{{ request('keyword') }}"
                                        placeholder="{{ __('app.search_project') }}" />
                                    <div class="input-group-text"><i class="fal fa-fw fa-search"></i></div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="fw-600 text-uppercase mb-2">{{ __('app.project_name') }}</div>

                                <select class="form-select" name="project_id">
                                    <option value="">{{ __('app.all') }}</option>
                                    @foreach ($projects as $id => $project)
                                        <option value="{{ $id }}" {{ request('project_id') == $id ? 'selected' : '' }}>
                                            {{ $project }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <div class="fw-600 text-uppercase mb-2">{{ __('app.status_legend') }}</div>

                                @foreach ($investmentStatuses as $s)
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" name="status[]" id="status_{{ $s->id }}"
                                            value="{{ $s->id }}" @checked(in_array((string) $s->id, $selectedStatuses))>
                                        <label class="form-check-label d-flex align-items-center" for="status_{{ $s->id }}">
                                            <span class="d-inline-block me-2"
                                                style="width:14px;height:14px;border-radius:3px;background-color: {{ $s->color_code }}"></span>
                                            {{ $s->name }}
                                        </label>
                                    </div>
                                @endforeach

                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" name="status[]" id="status_none"
                                        value="none" @checked(in_array('none', $selectedStatuses))>
                                    <label class="form-check-label d-flex align-items-center" for="status_none">
                                        <span class="d-inline-block me-2"
                                            style="width:14px;height:14px;border-radius:3px;background-color:#9CA3AF"></span>
                                        {{ __('app.status_updating') }}
                                    </label>
                                </div>
                            </div>
                            <button class="button button--block" type="submit">{{ __('app.search') }}</button>
                        </form>
                    </div>
                    <div class="col-lg-9">
                        <div class="row g-20">
                            @if($industrialProjects->isEmpty())
                                <div class="col-12">
                                    <p class="text-center">{{ __('app.no_matching_results') }}</p>
                                </div>
                            @endif
                            @foreach($industrialProjects as $item)
                                <div class="col-6 col-md-4 col-lg-6 col-xl-4">
                                    <div class="project"
                                        style="box-shadow: 0 -3px 8px rgba(11, 11, 11, 0.1); overflow: hidden;">
                                        <a class="project__frame" target="_blank" href="{{ $item->link }}">
                                            <img src="{{ $item->hotspots->url ?? asset('/images/project-1.jpg') }}"
                                                style="object-fit: scale-down" alt="" /></a>
                                        <div class="project__body">
                                            @php
                                                $status = optional($item->hotspots)->investmentProductStatus;
                                            @endphp
                                            <div class="mb-2">
                                                @if ($status)
                                                    <span class="d-inline-flex align-items-center px-2 py-1 rounded"
                                                        style="background-color: {{ $status->color_code }}; color:#fff; font-size:12px; font-weight:600; line-height:1.2">
                                                        {{ $status->name }}
                                                    </span>
                                                @else
                                                    <span class="d-inline-flex align-items-center px-2 py-1 rounded"
                                                        style="background-color:#9CA3AF; color:#fff; font-size:12px; font-weight:600; line-height:1.2">
                                                        {{ __('app.status_updating') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <h3 class="project__title" data-tippy-content="{{$item->intended_use}}"><a
                                                    href="{{ $item->link }}" target="_blank">{{$item->intended_use}}</a></h3>
                                            <ul class="project__info">
                                                <li>
                                                    <span
                                                        data-tippy-content="{{ __('app.project_under') }}: {{ $item->project->name }}">
                                                        <i class="fal fa-fw fa-map-marker-alt me-1"></i>
                                                        {{ __('app.project_under') }}: {{ $item->project->name }}
                                                    </span>
                                                </li>
                                                <li>
                                                    <span>
                                                        <i class="fal fa-fw fa-hashtag me-1"></i>
                                                        {{ __('app.code') }}:
                                                        {{ \Illuminate\Support\Str::after($item->code, 'cmss_') }}
                                                    </span>
                                                </li>
                                                @if (!is_null($item->acreage))
                                                    <li>
                                                        <span>
                                                            <i class="fal fa-fw fa-ruler-combined me-1"></i>
                                                            {{ __('app.area') }}:
                                                            {{ rtrim(rtrim(number_format($item->acreage, 4, ',', '.'), '0'), ',') }}
                                                            {{ $item->unit == 1 ? 'km²' : 'ha' }}
                                                        </span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center mt-40 mt-lg-50">
                            {{ $industrialProjects->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('bottom')

@endpush