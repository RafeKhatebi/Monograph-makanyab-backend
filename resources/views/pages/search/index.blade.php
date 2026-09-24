@extends('layouts.app')

@section('title', __('search.title'))

@section('content')
    @php
        $activeCategories = $selectedType === 'service' ? $serviceCategories : $placeCategories;
        $resultLabel = $selectedType === 'service' ? __('search.services') : __('search.places');
        $hasAdvancedFilters = filled($status) || filled($rating) || $verified || ! in_array($sort, ['relevance', 'newest'], true);
        $hasFilters = filled($searchTerm) || filled($category) || filled($province) || $hasAdvancedFilters;
        $statusOptions = ['open', 'closed', 'temporarily_closed'];
        $sortOptions = [
            'relevance' => __('search.most_relevant'),
            'newest' => __('search.newest'),
            'name_asc' => __('search.name_az'),
            'name_desc' => __('search.name_za'),
        ];
    @endphp

    <section class="discover-hero">
        <div class="container">
            <div class="discover-hero__content">
                <h1 class="discover-hero__title">{{ __('search.title') }}</h1>
                <p class="discover-hero__text">{{ __('search.discover_intro') }}</p>
            </div>

            <nav class="discover-type" aria-label="{{ __('search.search_in') }}">
                @foreach (['place' => __('search.places'), 'service' => __('search.services')] as $typeValue => $typeLabel)
                    @php
                        $typeUrlParams = request()->except(['category', 'page']);
                        $typeUrlParams['type'] = $typeValue;
                    @endphp
                    <a href="{{ route('search.index', $typeUrlParams) }}"
                        class="discover-type__link {{ $selectedType === $typeValue ? 'is-active' : '' }}"
                        aria-current="{{ $selectedType === $typeValue ? 'true' : 'false' }}">
                        {{ $typeLabel }}
                    </a>
                @endforeach
            </nav>

            <form action="{{ route('search.index') }}" method="GET" class="discover-panel" data-discover-form>
                <input type="hidden" name="type" value="{{ $selectedType }}">
                <div class="discover-grid discover-grid--primary">
                    <div class="discover-field discover-field--keyword">
                        <label for="discover-keyword" class="search-field-label">{{ __('search.what') }}</label>
                        <div class="search-input-wrap">
                            <i class="fa fa-search" aria-hidden="true"></i>
                            <input id="discover-keyword" type="search" name="search" value="{{ $searchTerm }}"
                                placeholder="{{ __('search.keyword_placeholder') }}"
                                class="search-input search-input--icon" maxlength="100" autocomplete="off">
                        </div>
                    </div>

                    <div class="discover-field discover-field--province">
                        <label for="discover-province" class="search-field-label">{{ __('search.province') }}</label>
                        <select id="discover-province" name="province" class="search-select">
                            <option value="">{{ __('search.all_provinces') }}</option>
                            @foreach ($provinces as $provinceOption)
                                <option value="{{ $provinceOption }}" @selected($province === $provinceOption)>{{ $provinceOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="discover-field">
                        <label for="discover-category" class="search-field-label">{{ __('search.category') }}</label>
                        <select id="discover-category" name="category" class="search-select" data-discover-category>
                            <option value="">{{ __('search.category_all') }}</option>
                            @foreach ($activeCategories as $cat)
                                <option value="{{ $cat->slug }}" @selected($category === $cat->slug)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="discover-actions">
                        <button type="submit" class="mk-button mk-button--primary discover-submit">
                            <i class="fa fa-search" aria-hidden="true"></i>
                            {{ __('common.actions.search') }}
                        </button>
                        @if ($hasFilters)
                            <a href="{{ route('search.index') }}" class="discover-reset" aria-label="{{ __('search.reset') }}" title="{{ __('search.reset') }}">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="discover-more">
                    <div class="discover-more__header">
                        <span>{{ __('search.filters') }}</span>
                    </div>
                    <div class="discover-grid discover-grid--secondary">
                        <div class="discover-field">
                            <label for="discover-status" class="search-field-label">{{ __('search.status') }}</label>
                            <select id="discover-status" name="status" class="search-select">
                                <option value="">{{ __('search.any_status') }}</option>
                                @foreach ($statusOptions as $statusOption)
                                    <option value="{{ $statusOption }}" @selected($status === $statusOption)>
                                        {{ __('common.status.' . $statusOption) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="discover-field">
                            <label for="discover-rating" class="search-field-label">{{ __('search.rating') }}</label>
                            <select id="discover-rating" name="rating" class="search-select">
                                <option value="">{{ __('search.any_rating') }}</option>
                                @for ($stars = 5; $stars >= 1; $stars--)
                                    <option value="{{ $stars }}" @selected((int) $rating === $stars)>
                                        {{ __('search.rating_min_plural', ['count' => $stars]) }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="discover-field">
                            <label for="discover-sort" class="search-field-label">{{ __('search.sort') }}</label>
                            <select id="discover-sort" name="sort" class="search-select">
                                @foreach ($sortOptions as $sortValue => $sortLabel)
                                    <option value="{{ $sortValue }}" @selected($sort === $sortValue)>{{ $sortLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="discover-toggles">
                        <label class="discover-toggle">
                            <input type="checkbox" name="verified" value="1" @checked($verified)>
                            <span>{{ __('search.verified') }}</span>
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section class="mk-page-section mk-page-section--compact">
        <div class="container">
            <div class="discover-results-header">
                <div>
                    <span class="discover-results-header__label">{{ $resultLabel }}</span>
                    <h2>{{ __('search.results_count', ['count' => $results->total()]) }}</h2>
                </div>
                @if ($hasFilters)
                    <p>
                        @if ($searchTerm)
                            <span>{{ __('search.for_keyword') }} "{{ $searchTerm }}"</span>
                        @endif
                        @if ($province)
                            <span>{{ __('search.in_location') }} {{ $province }}</span>
                        @endif
                    </p>
                @endif
            </div>

            <div class="row">
                @forelse ($results as $item)
                    @if ($selectedType === 'service')
                        <x-service-card :service="$item" />
                    @else
                        @include('components.place-card', ['place' => $item])
                    @endif
                @empty
                    <div class="col-md-12">
                        <div class="mk-card mk-card--empty">
                            <div class="mk-empty-icon"><i class="fa fa-search" aria-hidden="true"></i></div>
                            <h3 class="mk-heading mk-heading--md">{{ __('search.no_results') }}</h3>
                            <p class="mk-text mk-text--muted mk-stack-sm">{{ __('search.no_results_help') }}</p>
                            <a href="{{ route('search.index') }}" class="mk-button mk-button--primary mk-button--md">
                                {{ __('search.reset_search') }}
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($results->hasPages())
                <div class="mk-pagination-wrap">{{ $results->links() }}</div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        window.DiscoverCategories = {
            place: @json($placeCategories->map(fn ($category) => ['name' => $category->name, 'slug' => $category->slug])->values()),
            service: @json($serviceCategories->map(fn ($category) => ['name' => $category->name, 'slug' => $category->slug])->values()),
            placeholder: @json(__('search.category_all')),
        };
    </script>
@endpush
