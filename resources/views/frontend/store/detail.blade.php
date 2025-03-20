@extends('frontend.index')

@section('content')

    <section class="pages-breadcumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12"><h1>{{ $post->name }}</h1>
                    <p class="breadCumb">
                        <a href="{{ route('home_page') }}">Home</a> &gt;
                        <a href="{{ $category->getUrl() }}">{{ $category->name }}</a> &gt;
                        {{ $post->name }}</p>
                </div>
            </div>
        </div>
    </section>
    <section class="main-content content-section blog-post-section">
        <div class="container">
            <div class="row">
                <div class="article">
                    <div class="col-md-9 col-sm-12 col-xs-12 pull-right">
                        <div class="blog-post-full-style">
                            <div class="col-md-12 col-sm-12">
                                <h2 class="title">{{ $post->name }}</h2>
                                <p class="post-info">{{ $post->created_at->format('F d, Y') }} by <span>Admin</span></p>
                                <div class="fix-responsive">
                                    {!! $post->content !!}
                                </div>

                                <hr class="line-hr">
                                <div class="tw-bg-white tw-p-8 tw-pt-4 tw-mb-4">
                                    <div class="tw-flex tw-flex-wrap">
                                        @foreach(range(1,6) as $store)
                                            <a href="#!" class="tw-text-blue tw-w-1/2 sm:tw-w-1/3 tw-mb-4">AliExpress
                                                Promos</a>
                                        @endforeach
                                    </div>
                                </div>
                                <hr class="line-hr">
                                <div class="blog-post-share"><p class="share-post">SHARE ARTICLE</p>
                                    <ul class="list-inline social-buttons">
                                        <li>
                                            <a href="https://www.facebook.com/sharer.php?t=&amp;u={{ $post->getUrl() }}">
                                                <i class="fa fa-facebook"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://twitter.com/intent/tweet?text={{ $post->name }}{{ $post->getUrl() }}&amp;source=webclient">
                                                <i class="fa fa-twitter"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://plus.google.com/share?url={{ $post->getUrl() }}">
                                                <i class="fa fa-google-plus"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <aside>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        {{--                        <div class="sidebar-widget">--}}
                        {{--                            <div class="widget-content">--}}
                        {{--                                <h3 class="widget-title">Topics</h3>--}}
                        {{--                                <ul class="sidebar-list">--}}
                        {{--                                    <li><a href="#!">About Us</a></li>--}}
                        {{--                                </ul>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        <div class="sidebar-widget">
                            <div class="widget-content">
                                <h3 class="widget-title">About {{ $setting['site_name'] }}</h3>
                                <div>
                                    {!! $setting['about_us'] !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

@endsection
