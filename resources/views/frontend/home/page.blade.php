@extends('frontend.index')

@section('content')
    <div class="page__content">
        <section class="section section--page">
            <img class="section__bg" src="{{ asset('images/rule-bg.jpg') }}" alt="rule background">
            <div class="section__backdrop"></div>
            <div class="container">
                <div class="section__inner">
                    <div class="section__header">
                        <div class="section__title text-white">{{ $page->name }}</div>
                        <div class="section__subtitle text-white">“LÊN XE SANG, ĐÓN NGÀN LỘC”</div>
                    </div>
                    <div class="section__body">
                        <div class="section__content text-white fix-responsive">
                            {!! $page->content !!}
                        </div>
                    </div>

                    @include('frontend.footer_2')

                </div>
            </div>
        </section>
    </div>
@endsection

@push('bottom')

@endpush
