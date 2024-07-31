@extends('themes::themelegend.layout')

@php
    $years = Cache::remember('all_years', \Backpack\Settings\app\Models\Setting::get('site_cache_ttl', 5 * 60), function () {
        return \Ophim\Core\Models\Movie::select('publish_year')
            ->distinct()
            ->pluck('publish_year')
            ->sortDesc();
    });
@endphp

@section('breadcrumb')
    <ol class="breadcrumb" itemScope itemType="https://schema.org/BreadcrumbList">
        <li itemProp="itemListElement" itemScope itemType="http://schema.org/ListItem">
            <a class="" itemProp="item" title="{{ __('auth.watch_movie') }}" href="/">
                <span class="" itemProp="name">
                {{ __('auth.watch_movie') }}
                </span>
                <meta itemProp="position" content="1" />
            </a>
        </li>
        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
            <a class="" itemprop="item"
                href="/danh-sach/{{ $currentMovie->type == 'single' ? 'phim-le' : 'phim-bo' }}"
                title="{{ $currentMovie->type == 'single' ? __('auth.single_movie') : __('auth.series') }}">
                <span itemprop="name">
                    {{ $currentMovie->type == 'single' ? __('auth.single_movie') : __('auth.series') }}
                </span>
            </a>
            <meta itemprop="position" content="2">
        </li>

        @foreach ($currentMovie->regions as $region)
            <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                <a class="" itemprop="item" href="{{ $region->getUrl() }}" title="{{ $region->name }}">
                    <span itemprop="name">
                        {{ $region->name }}
                    </span>
                </a>
                <meta itemprop="position" content="3">
            </li>
        @endforeach
        @foreach ($currentMovie->categories as $category)
            <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                <a class="" itemprop="item" href="{{ $category->getUrl() }}" title="{{ $category->name }}">
                    <span itemprop="name">
                        {{ $category->name }}
                    </span>
                </a>
                <meta itemprop="position" content="3">
            </li>
        @endforeach
        <li class="" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
            <a itemprop="item" href="{{ $currentMovie->getUrl() }}" title="{{ $currentMovie->name }}">
                <span class="breadcrumb_last" itemprop="name">
                    {{ $currentMovie->name }}
                </span>
            </a>
            <meta itemprop="position" content="4">
        </li>
    </ol>

    <div class="clear"></div>
@endsection

@section('content')
    @if ($currentMovie->notify && $currentMovie->notify != '')
        <div class="block-wrapper page-single block-note">
            Thông báo: <span class="text-danger">{{ strip_tags($currentMovie->notify) }}</span>
        </div>
    @endif
    @if ($currentMovie->showtimes && $currentMovie->showtimes != '')
        <div class="block-wrapper page-single block-note">
            Lịch chiếu: <span class="text-info">{!! $currentMovie->showtimes !!}</span>
        </div>
    @endif
    <div class="block-wrapper page-single" itemscope itemtype="https://schema.org/Movie">
        <div class="movie-info">
            <div class="block-movie-info movie-info-box">
                <div class="row">
                    <div class="col-6 movie-detail">
                        <h1 class="movie-title">
                            <span class="title-1" itemprop="name">{{ $currentMovie->name }}</span>
                            <span class="title-2" itemprop="name">{{ $currentMovie->origin_name }}</span>
                            @if ($currentMovie->publish_year)
                                <span class="title-year">({{ $currentMovie->publish_year }})</span>
                            @endif
                        </h1>
                        <div class="movie-meta-info">
                            <dl class="movie-dl">
                                <dt class="movie-dt">สถานะ:</dt>
                                <dd class="movie-dd status"> {{ $currentMovie->episode_current }}</dd><br />
                                <dt class="movie-dt">ผู้กำกับ:</dt>
                                <dd class="movie-dd dd-cat">
                                    {!! $currentMovie->directors->map(function ($director) {
                                            return '<a href="' .
                                                $director->getUrl() .
                                                '" tite="director ' .
                                                $director->name .
                                                '" class="director">' .
                                                $director->name .
                                                '</a>';
                                        })->implode(', ') !!}
                                </dd><br />
                                <dt class="movie-dt">ประเทศ:</dt>
                                <dd class="movie-dd dd-cat">
                                    {!! $currentMovie->regions->map(function ($region) {
                                            return '<a href="' . $region->getUrl() . '" title="' . $region->name . '">' . $region->name . '</a>';
                                        })->implode(', ') !!}
                                </dd><br />
                                <dt class="movie-dt">ปี:</dt>
                                <dd class="movie-dd">{{ $currentMovie->publish_year }}
                                </dd><br />
                                <dt class="movie-dt">ระยะเวลา:</dt>
                                <dd class="movie-dd">{{ $currentMovie->episode_time }}</dd><br />
                                <dt class="movie-dt">จำนวนตอน:</dt>
                                <dd class="movie-dd">{{ $currentMovie->episode_total }}</dd><br />
                                <dt class="movie-dt">คุณภาพ:</dt>
                                <dd class="movie-dd">{{ $currentMovie->quality }}</dd><br />
                                <dt class="movie-dt">ภาษา:</dt>
                                <dd class="movie-dd">{{ $currentMovie->language }}</dd><br />
                                <dt class="movie-dt">ประเภท:</dt>
                                <dd class="movie-dd dd-cat">
                                    {!! $currentMovie->categories->map(function ($category) {
                                            return '<a href="' .
                                                $category->getUrl() .
                                                '" title="' .
                                                $category->name .
                                                '" rel="category tag">' .
                                                $category->name .
                                                '</a>';
                                        })->implode(', ') !!}
                                </dd><br />
                                <dt class="movie-dt">จำนวนผู้เข้าชม:</dt>
                                <dd class="movie-dd"> {{ $currentMovie->view_total }} </dd><br />
                            </dl>
                            <div class="clear"></div>
                        </div>
                        <div class="box-rating">
                            <input id="hint_current" type="hidden" value="">
                            <input id="score_current" type="hidden"
                                value="{{$currentMovie->getRatingStar()}}">
                            <p>{{ __('auth.rate_movie')}} <span
                                    class="num-rating">({{$currentMovie->getRatingStar()}} คะแนน /
                                    {{$currentMovie->getRatingCount()}} โหวต)</span></p>
                            <div id="star" data-score="{{$currentMovie->getRatingStar()}}"
                                style="cursor: pointer;"></div>
                            <span id="hint"></span>
                            <img class="hidden" itemprop="thumbnailUrl" src="{{ $currentMovie->getThumbUrl() }}"
                                alt="{{ $currentMovie->name }}">
                            <img class="hidden" itemprop="image" src="{{ $currentMovie->getThumbUrl() }}"
                                alt="{{ $currentMovie->name }}">
                            <span class="hidden" itemprop="aggregateRating" itemscope
                                itemtype="http://schema.org/AggregateRating">
                                <span
                                    itemprop="ratingValue">{{$currentMovie->getRatingStar()}}</span>
                                <meta itemprop="ratingcount" content="{{$currentMovie->getRatingCount()}}">
                                <meta itemprop="bestRating" content="10" />
                                <meta itemprop="worstRating" content="1" />
                            </span>
                        </div>
                    </div>
                    <div class="col-6 movie-image">
                        <div class="movie-l-img">
                            <img alt="{{ $currentMovie->name }} - {{ $currentMovie->origin_name }}"
                                src="{{ $currentMovie->getThumbUrl() }}" /></a>
                            <ul class="btn-block">
                                <li class="item" itemprop="potentialAction" itemscope
                                    itemtype="http://schema.org/WatchAction">
                                    @if ($currentMovie->trailer_url)
                                        <a tite="Trailer phim {{ $currentMovie->name }}" onclick="trailer();"
                                            class="btn btn-primary btn-film-trailer">
                                            Trailer
                                        </a>
                                    @endif

                                    @if (!$currentMovie->is_copyright && count($currentMovie->episodes) && $currentMovie->episodes[0]['link'] != '')
                                        <a itemprop="target" id="btn-film-watch" class="btn btn-green btn"
                                            title="{{ __('auth.watch_movie') }} {{ $currentMovie->name }}"
                                            href="{{ $currentMovie->episodes->sortBy([['server', 'asc']])->groupBy('server')->first()->sortByDesc('name', SORT_NATURAL)->groupBy('name')->last()->sortByDesc('type')->first()->getUrl() }}">
                                            <i class="fa fa-film" aria-hidden="true"></i> {{ __('auth.watch_movie') }}
                                        </a>
                                    @else
                                        <div>{{ __('auth.updating') }}</div>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            @if ($currentMovie->type === 'series' &&
                !$currentMovie->is_copyright &&
                count($currentMovie->episodes) &&
                $currentMovie->episodes[0]['link'] != '')
                <div class="latest-episode"><span class="heading">{{ __('auth.episode_new') }}: </span>
                    @php
                        $currentMovie->episodes
                            ->sortBy([['name', 'desc'], ['type', 'desc']])
                            ->sortByDesc('name', SORT_NATURAL)
                            ->unique('name')
                            ->take(5)
                            ->map(function ($episode) {
                                echo '<a href="' . $episode->getUrl() . '">' . $episode->name . '</a>';
                            });
                    @endphp
                </div>
            @endif

            @if (count($currentMovie->actors))
                <div class="block-actors">
                    <h2 class="movie-detail-h2">นักแสดง</h2>
                    <ul class="row" id="list_actor_carousel">
                        {!! $currentMovie->actors->map(function ($actor) {
                                return '<li itemprop="actor" itemscope itemtype="http://schema.org/Person"><a class="actor-profile-item" title="Actors ' .
                                    $actor->name .
                                    '" href="' .
                                    $actor->getUrl() .
                                    '"><div class="actor-image" style="background-image:url(\'/themes/legend/image/cast-image.png\')"></div>
                                    <div class="actor-name"><span class="actor-name-a" itemprop="name">' .
                                    $actor->name .
                                    '</span></div></a></li>';
                            })->implode('') !!}
                    </ul>
                    <div class="clear"></div><a id="prevActor" class="prev" rel="nofollow"><span
                            class="arrow-icon left"></span></a><a id="nextActor" class="next" rel="nofollow"><span
                            class="arrow-icon right"></span></a>
                </div>
            @endif

            <blockquote class="block-movie-content" id="film-content-wrapper">
                <h2 class="movie-detail-h2">{{ __('auth.content_movie') }}:</h2>
                <div class="fb-like like-at-content" style="display: flex !important;justify-content: right;"
                    data-width="140" data-layout="button_count" data-action="like" data-show-faces="false"
                    data-share="true"></div>
                <div class="content" id="film-content" data-href="{{ $currentMovie->getUrl() }}">
                    <span id="{{ $currentMovie->getUrl() }}" itemscope itemtype="http://schema.org/Review"
                        itemprop="review">
                        <h2> {{ $currentMovie->name }}, {{ $currentMovie->origin_name }}
                            {{ $currentMovie->publish_year }} {{ $currentMovie->quality }} {{ $currentMovie->language }}
                        </h2>
                        @if ($currentMovie->content)
                            <p>{!! $currentMovie->content !!}</p>
                        @else
                            <p>{{ __('auth.updating') }}</p>
                        @endif
                    </span>
                </div>
            </blockquote>

            @if ($currentMovie->trailer_url)
                <div id="trailer" class="block-tags">
                    <h3 class="movie-detail-h3">Trailer {{ $currentMovie->name }} - {{ $currentMovie->origin_name }}
                        ({{ $currentMovie->publish_year }})</h3>
                    <iframe sandbox="allow-forms allow-scripts allow-same-origin" type="text/html" width="100%"
                        height="350" src="{{ $currentMovie->trailer_url }}" frameborder="0"></iframe>
                </div>
            @endif

            <div class="block-tags">
                <h3 class="movie-detail-h3">แท็ก:</h3>

                <ul class="tag-list" itemprop="keywords" style="font-size:12px;">
                    @foreach ($currentMovie->tags as $tag)
                        <li class="tag-item"><a href="{{ $tag->getUrl() }}"
                                title="{{ $tag->name }}">{{ $tag->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="block-wrapper page-single block-comments" id="block-comment">
        <h4>{{ __('auth.comment_on_movie') }}</h4>
        <div style="width: 100%; background-color: #fff">
            <div class="fb-comments" data-href="{{ $currentMovie->getUrl() }}" data-width="100%" data-colorscheme="dark"
                data-numposts="5" data-order-by="reverse_time" data-lazy="true"></div>
        </div>
    </div>

    <div class="clear"></div>
    <div class="movie-list-index related-box">
        <h2 class="header-list-index">
            <span class="title-list-index">{{ __('auth.may_like')}}</span>
        </h2>
        <ul class="list-movie">
            @foreach ($movie_related ?? [] as $movie)
                <li class="movie-item">
                    <a class="block-wrapper" title="{{ $movie->name }}" href="{{ $movie->getUrl() }}">
                        <div class="movie-thumbnail"
                            style="background:url({{ $movie->getThumbUrl() }}); background-size: cover;">
                        </div>
                        <div class="movie-meta">
                            <span class="movie-title-1">{{ $movie->name }}</span>
                            <span class="movie-title-2">{{ $movie->origin_name }}</span>
                            <span class="movie-title-chap">{{ $movie->publish_year }}</span>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="clear"></div>
    <div class="clear"></div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function trailer() {
            $('#trailer').fadeIn('slow');
            $('html, body').animate({
                scrollTop: $("#trailer").offset().top
            }, 500);
        }
    </script>

    <script type="text/javascript">
        var URL_POST_RATING = '{{ route('movie.rating', ['movie' => $currentMovie->slug]) }}';
    </script>
    <script type="text/javascript" src="/themes/legend/js/jquery.raty.js"></script>
    <script type="text/javascript" src="/themes/legend/js/public.film.js"></script>
    <script type="text/javascript" src="/themes/legend/js/film-info.js"></script>
    <script type="text/javascript" src="/themes/legend/js/fx/util.js"></script>
    {!! setting('site_scripts_facebook_sdk') !!}
@endpush
