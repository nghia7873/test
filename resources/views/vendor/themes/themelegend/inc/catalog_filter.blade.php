<div class="list-movie-filter" style="margin-bottom: 10px;">
    <div class="list-movie-filter-main">
        <form id="form-filter" class="form-inline" method="GET">
            <div class="list-movie-filter-item">
                <label for="filter-sort">{{ __('auth.sort') }}</label>
                    <select class="form-control" id="sort" name="filter[sort]" form="form-search">
                        <option value="">{{ __('auth.sort') }}</option>
                        <option value="update" @if (isset(request('filter')['sort']) && request('filter')['sort'] == 'update') selected @endif>{{ __('auth.update_time') }}</option>
                        <option value="create" @if (isset(request('filter')['sort']) && request('filter')['sort'] == 'create') selected @endif>{{ __('auth.post_time') }}</option>
                        <option value="year" @if (isset(request('filter')['sort']) && request('filter')['sort'] == 'year') selected @endif>{{ __('auth.production_year') }}</option>
                        <option value="view" @if (isset(request('filter')['sort']) && request('filter')['sort'] == 'view') selected @endif>{{ __('auth.views') }}</option>
                    </select>
            </div>
            <div class="list-movie-filter-item">
                <label for="filter-sort">{{ __('auth.format') }}</label>
                    <select class="form-control" id="type" name="filter[type]" form="form-search">
                        <option value="">{{ __('auth.all_formats') }}</option>
                        <option value="series" @if (isset(request('filter')['type']) && request('filter')['type'] == 'series') selected @endif>{{ __('auth.series') }}</option>
                        <option value="single" @if (isset(request('filter')['type']) && request('filter')['type'] == 'single') selected @endif>{{ __('auth.single_movie') }}</option>
                    </select>
            </div>
            <div class="list-movie-filter-item">
                <label for="filter-sort">{{ __('auth.country') }}</label>
                    <select class="form-control" name="filter[region]" form="form-search">
                        <option value="">{{ __('auth.all_countries') }}</option>
                        @foreach (\Ophim\Core\Models\Region::fromCache()->all() as $item)
                            <option value="{{ $item->id }}" @if ((isset(request('filter')['region']) && request('filter')['region'] == $item->id) ||
                                (isset($region) && $region->id == $item->id)) selected @endif>
                                {{ $item->name }}</option>
                        @endforeach
                   </select>
            </div>
            <div class="list-movie-filter-item">
                <label for="filter-sort">{{ __('auth.year') }}</label>
                    <select class="form-control" name="filter[year]" form="form-search">
                        <option value="">{{ __('auth.all_years') }}</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}" @if (isset(request('filter')['year']) && request('filter')['year'] == $year) selected @endif>
                                {{ $year }}</option>
                        @endforeach
                    </select>
            </div>

            <div class="list-movie-filter-item">
                <label for="filter-sort">{{ __('auth.genre') }}</label>
                    <select class="form-control" id="category" name="filter[category]" form="form-search">
                        <option value="">{{ __('auth.all_genres') }}</option>
                        @foreach (\Ophim\Core\Models\Category::fromCache()->all() as $item)
                            <option value="{{ $item->id }}" @if ((isset(request('filter')['category']) && request('filter')['category'] == $item->id) ||
                                (isset($category) && $category->id == $item->id)) selected @endif>
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
            </div>
            <button type="submit" form="form-search" class="btn btn-red btn-filter-movie"><span>{{ __('auth.filter_movies') }}</span></button>

            <div class="clear"></div>
        </form>
    </div>
</div>
