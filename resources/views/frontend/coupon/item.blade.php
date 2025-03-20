<article class="news">
    <a class="news__frame" href="{{ $post->getUrl() }}">
        <img src="{{ $post->image }}" alt="{{ $post->mame }}"/>
    </a>
    <div class="news__body">
        <h3 class="news__title">
            <a href="{{ $post->getUrl() }}">
                {{ $post->mame }}
            </a>
        </h3>
        <div class="news__info">
            <span class="news__tag">{{ $post->category->name }}</span>
            <span class="news__time">
                {{ $post->created_at->format('d/m/Y') }}
            </span>
        </div>
        <div class="news__desc">
            {!! $post->description !!}
        </div>
        <div class="mt-auto">
            <a class="news__link" href="{{ $post->getUrl() }}">Xem thêm</a>
        </div>
    </div>
</article>