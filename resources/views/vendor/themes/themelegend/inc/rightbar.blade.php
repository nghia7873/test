@foreach ($tops as $top)
    <div class="right-box top-film-week">
        <h2 class="right-box-header star-icon"><span>{{ $top['label'] }}</span></a></h2>
        <div class="right-box-content">
            <ul class="list-top-movie" id="list-top-film-week">
                @foreach ($top['data'] ?? [] as $movie)
                    <li class="list-top-movie-item" id="list-top-movie-item-1">
                        <a class="list-top-movie-link" title="{{ $movie->name }}"
                            href="{{ $movie->getUrl() }}">
                            <div class="list-top-movie-item-thumb">
                                <img class="blur-up img-responsive lazyautosizes lazyloaded" data-sizes="auto" data-src="{{ $movie->getThumbUrl() }}" alt="{{ $movie->name }}" title="{{ $movie->name }}" src="{{ $movie->getThumbUrl() }}">
                            </div>
                            <div class="list-top-movie-item-info">
                                <span class="list-top-movie-item-vn">{{ $movie->name }}</span>
                                <span class="list-top-movie-item-view">{{ $movie->view_total }} ยอดวิว</span>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="clear"></div>
@endforeach
