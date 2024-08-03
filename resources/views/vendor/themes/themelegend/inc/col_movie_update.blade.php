@foreach ($movie_update_right as $key => $item)
<div class="movie-list-index home-v2">
    <h2 class="header-list-index">
        <span class="title-list-index">{{ $item['label'] }}</span>
        @if ($item['link'] && $item['link'] != '#')
            <a class="more-list-index" href="{{ $item['link'] }}" title="{{ $item['label'] }}">{{ __('auth.view_all') }}</a>
        @endif
    </h2>
    <div class="last-film-box-wrapper">
        <ul class="last-film-box" id="movie-last-theater">
            @foreach ($item['data'] as $movie)
                <li>
                    <a class="movie-item m-block" href="{{ $movie->getUrl() }}"
                        title="{{ $movie->name }} - {{ $movie->origin_name }} ({{ $movie->publish_year }})">
                        <div class="block-wrapper">
                            <div class="movie-thumbnail ratio-box ratio-3_4">
                                <div class="public-film-item-thumb ratio-content">
                                    <img class="blur-up img-responsive lazyautosizes lazyloaded" data-sizes="auto" data-src="{{ $movie->getThumbUrl() }}" alt="{{ $movie->name }}" title="{{ $movie->name }}" src="{{ $movie->getThumbUrl() }}">
                                </div>
                            </div>
                            <div class="movie-meta">
                                <div class="movie-title-1">{{ $movie->name }}</div><span
                                    class="movie-title-2">{{ $movie->origin_name }}
                                    ({{ $movie->publish_year }})
                                </span>
                                <span class="ribbon"> {{ $movie->quality }} <span>|</span> {{ $movie->language }} </span>
                                @if ($movie->type == 'series')
                                    <span class="ribbon-right">EP 1-{{ $movie->episode_current }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
<div class="clearfix"></div>
@endforeach
