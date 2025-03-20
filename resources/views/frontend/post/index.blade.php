@extends('frontend.index')

@section('content')

    <section class="pages-breadcumb">
        <div class="container">
            <div class="row">
                <div class="col-md-12"><h1>{{ $category->name }}</h1>
                    <p class="breadCumb">
                        <a href="{{ route('home_page') }}">Home
                        </a> &gt; {{ $category->name }}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="main-content content-section blog-post-section">
        <div class="container">
            <div class="row">
                <div class="article">
                    <div class="col-md-9 col-sm-12 col-xs-12 pull-right">
                        <div dusk="newsletter-signup"
                             class="tw-flex tw-flex-col sm:tw-flex-row tw-items-center tw-rounded-sm tw-bg-blue tw-w-full tw-p-4 tw-mb-4">
                            <h2 class="tw-text-white sm:tw-w-1/4">Get Todays Top Offers</h2>
                            <form action="#!" method="POST"
                                  class="tw-flex tw-flex-col tw-w-full sm:tw-w-3/4">
                                <div class="tw-flex">
                                    <input name="email" type="text" placeholder="Enter Your Email..."
                                           class="tw-flex-grow tw-rounded-l-sm tw-text-lg tw-text-grey-darker tw-min-w-0 tw-p-4">
                                    <input dusk="newsletter-submit" type="button" value="Sign Up"
                                           class="tw-rounded-r-sm tw-text-grey-lightest tw-bg-grey-darkest tw-px-4 tw-py-2">
                                </div>
                                <a href="#!"
                                   class="tw-self-end tw-text-xs tw-text-white tw-mt-1">By Signing Up, you agree to our
                                    terms of service</a>
                            </form>
                        </div>
                        @foreach($posts as $post)
                            <div class="blog-post-styleOne">
                                <div class="col-md-3 col-sm-3">
                                    <img src="{{ $post->image }}"
                                         alt="new-log1" loading="lazy" class="img-responsive img-thumb">
                                </div>
                                <div class="col-md-9 col-sm-9">
                                    <h2 class="title">
                                        <a href="{{ $post->getUrl() }}">{{ $post->name }}</a>
                                    </h2>
                                    <p class="post-info">{{ $post->created_at->format('F m, Y') }} by
                                        <span>Admin</span>
                                    </p>
                                    <p class="post-excerpt">
                                        {{ strip_tags($post->description) }}
                                    </p>
                                    <a href="{{ $post->getUrl() }}" class="read-more">Read More</a>
                                    <hr class="line-hr">
                                    <div class="blog-post-share"><p class="share-post">SHARE ARTICLE</p>
                                        <ul class="list-inline social-buttons">
                                            <li>
                                                <a href="https://www.facebook.com/sharer.php?t=&amp;u={{ $post->getUrl() }}">
                                                    <i class="fa fa-facebook"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://twitter.com/intent/tweet?text={{ $post->name }}+-{{ $post->getUrl() }}&amp;source=webclient">
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
                        @endforeach
                        <div class="col-md-12" style="text-align: center; padding: 12px 0px;">
                            {!! $posts->links() !!}
                        </div>
                    </div>
                </div>
                <aside>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="sidebar-widget">
                            <div class="widget-content"><h3 class="widget-title">About {{ $setting['site_name'] }}</h3>
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

@push('bottom')
@endpush