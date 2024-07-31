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

        <li class="" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
            <a itemprop="item" href="{{ url()->current() }}" title="{{  __('auth.movie_list') }}">
                <span class="breadcrumb_last" itemprop="name">
                    {{  __('auth.movie_list') }}
                </span>
            </a>
            <meta itemprop="position" content="2">
        </li>
    </ol>

    <div class="clear"></div>
@endsection

@section('catalog_filter')
    @include('themes::themelegend.inc.catalog_filter')
@endsection

@section('content')
    <div class="movie-list-index home-v2">
        <h1 class="header-list-index">
            <span class="title-list-index">{{ str_replace(["Phim thể loại", "Phim quốc gia", "Diễn viên", "Đạo diễn", "Tags", "Danh sách", "Tìm kiếm phim: "], '', $section_name) }}</span>
        </h1>
        @if (count($data))
            <ul class="last-film-box">
                @foreach ($data ?? [] as $movie)
                    <li>
                        <a class="movie-item m-block" href="{{ $movie->getUrl() }}" title="{{ $movie->name ?? '' }}">
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
        @else
            <div class="flex flex-row flex-wrap flex-grow h-50 mt-10">
                <p class="w-full text-center text-white">{{ __('auth.no_content')}}</p>
            </div>
        @endif
    </div>
    <div class="clear"></div>

    {{ $data->appends(request()->all())->links('themes::themelegend.inc.pagination') }}

@endsection
