@extends('themes::themelegend.layout')

@php
    use Ophim\Core\Models\Movie;

    $recommendations = Cache::remember('site.movies.recommendations', setting('site_cache_ttl', 5 * 60), function () {
        return Movie::where('is_recommended', true)
            ->limit(get_theme_option('recommendations_limit', 10))
            ->orderBy('updated_at', 'desc')
            ->get();
    });

    $data = Cache::remember('site.movies.latest', setting('site_cache_ttl', 5 * 60), function () {
        $lists = preg_split('/[\n\r]+/', get_theme_option('latest'));
        $data = [];
        foreach ($lists as $list) {
            if (trim($list)) {
                $list = explode('|', $list);
                [$label, $relation, $field, $val, $limit, $link] = $list;
                try {
                    $data[] = [
                        'label' => $label,
                        'data' => Movie::when($relation, function ($query) use ($relation, $field, $val) {
                            $query->whereHas($relation, function ($rel) use ($field, $val) {
                                $rel->where($field, $val);
                            });
                        })
                            ->when(!$relation, function ($query) use ($field, $val) {
                                $query->where($field, $val);
                            })
                            ->limit($limit)
                            ->orderBy('updated_at', 'desc')
                            ->get(),
                        'link' => $link ?: '#',
                    ];
                } catch (\Exception $e) {
                }
            }
        }
        return $data;
    });

    $movie_update_right = Cache::remember('site.movies.movie_update_right', setting('site_cache_ttl', 5 * 60), function () {
        $lists = preg_split('/[\n\r]+/', get_theme_option('movie_update_right'));
        $data = [];
        foreach ($lists as $list) {
            if (trim($list)) {
                $list = explode('|', $list);
                [$label, $relation, $field, $val, $sortKey, $alg, $limit] = $list;
                try {
                    $data[] = [
                        'label' => $label,
                        'link' => '#',
                        'data' => \Ophim\Core\Models\Movie::when($relation, function ($query) use ($relation, $field, $val) {
                            $query->whereHas($relation, function ($rel) use ($field, $val) {
                                $rel->where(array_combine(explode(",", $field), explode(",", $val)));
                            });
                        })
                            ->orderBy($sortKey, $alg)
                            ->limit($limit)
                            ->get(),
                    ];
                } catch (\Exception $e) {}
            }
        }
        return $data;
    });

    $movie_update_left = Cache::remember('site.movies.movie_update_left', setting('site_cache_ttl', 5 * 60), function () {
        $lists = preg_split('/[\n\r]+/', get_theme_option('movie_update_left'));
        $data = [];
        foreach ($lists as $list) {
            if (trim($list)) {
                $list = explode('|', $list);
                [$label, $image_url, $show_more_url] = $list;
                $data[] = [
                    'label' => $label,
                    'image_url' => $image_url,
                    'show_more_url' => $show_more_url
                ];
            }
        }
        return $data;
    });
@endphp

@section('slider_recommended')
    @include('themes::themelegend.inc.slider_recommended')
@endsection

@section('content')
    @include('themes::themelegend.inc.col_movie_update')
    @foreach ($data as $item)
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
@endsection
