<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = trim((string) $request->query('search', ''));
        $locationTerm = trim((string) $request->query('location', ''));
        $request->merge([
            'search' => $searchTerm,
            'location' => $locationTerm,
        ]);

        $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'place_category' => ['nullable', 'string', 'max:255'],
            'service_category' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'in:all,places,services'],
            'status' => ['nullable', 'in:open,closed,temporarily_closed'],
            'price_level' => ['nullable', 'in:low,medium,high,luxury'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'sort' => ['nullable', 'in:relevance,newest,name_asc,name_desc'],
        ]);

        $sort = $request->query('sort') ?: ($searchTerm !== '' ? 'relevance' : 'newest');
        $hasSearchFilters = $request->hasAny([
            'search',
            'location',
            'city',
            'province',
            'district',
            'place_category',
            'service_category',
            'status',
            'price_level',
            'rating',
            'open_now',
            'verified',
            'sort',
            'type',
        ]);
        $selectedType = in_array($request->query('type'), ['places', 'services'], true)
            ? $request->query('type')
            : 'places';
        $showPlaces = ! $hasSearchFilters || $selectedType !== 'services';
        $showServices = ! $hasSearchFilters || $selectedType === 'services';

        $places = null;
        $services = null;

        if ($showPlaces) {
            $placesQuery = Place::query()
                ->with(['category:id,name,slug', 'media'])
                ->active()
                ->filterSearch($request->query('search'))
                ->filterCategorySlug($request->query('place_category'))
                ->when($request->filled('location'), fn ($q) => $this->applyLocationFilter($q, $request->query('location')))
                ->when($request->filled('city'), fn ($q) => $q->where('city', 'like', '%'.$request->city.'%'))
                ->when($request->filled('province'), fn ($q) => $q->where('province', 'like', '%'.$request->province.'%'))
                ->when($request->filled('district'), fn ($q) => $q->where('district', 'like', '%'.$request->district.'%'))
                ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
                ->when($request->filled('price_level'), fn ($q) => $q->where('price_level', $request->price_level))
                ->filterRatingAtLeast($request->integer('rating'))
                ->filterOpenNow($request->boolean('open_now'))
                ->filterVerified($request->boolean('verified'));

            $this->applySort($placesQuery, $sort, $searchTerm, 'places');

            $places = $placesQuery
                ->paginate(20, ['*'], 'places_page')
                ->withQueryString();
        }

        if ($showServices) {
            $servicesQuery = Service::query()
                ->with(['category:id,name,slug', 'media'])
                ->withCount(['reviews as reviews_count' => fn ($query) => $query->approved()])
                ->withAvg(['reviews as reviews_avg_rating' => fn ($query) => $query->approved()], 'rating')
                ->active()
                ->filterSearch($request->query('search'))
                ->filterCategorySlug($request->query('service_category'))
                ->when($request->filled('location'), fn ($q) => $this->applyLocationFilter($q, $request->query('location')))
                ->when($request->filled('city'), fn ($q) => $q->where('city', 'like', '%'.$request->city.'%'))
                ->when($request->filled('province'), fn ($q) => $q->where('province', 'like', '%'.$request->province.'%'))
                ->when($request->filled('district'), fn ($q) => $q->where('district', 'like', '%'.$request->district.'%'))
                ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
                ->when($request->filled('price_level'), fn ($q) => $q->where('price_level', $request->price_level))
                ->filterRatingAtLeast($request->integer('rating'))
                ->filterOpenNow($request->boolean('open_now'))
                ->filterVerified($request->boolean('verified'));

            $this->applySort($servicesQuery, $sort, $searchTerm, 'services');

            $services = $servicesQuery
                ->paginate(20, ['*'], 'services_page')
                ->withQueryString();
        }

        // Cache categories for 30 minutes (rarely change)
        $placeCategories = Cache::remember('active_place_categories', 1800, function () {
            return PlaceCategory::active()->orderBy('name')->get();
        });

        $serviceCategories = Cache::remember('active_service_categories', 1800, function () {
            return ServiceCategory::active()->orderBy('name')->get();
        });

        return view('pages.search.index', compact(
            'places',
            'services',
            'placeCategories',
            'serviceCategories',
            'showPlaces',
            'showServices',
            'selectedType',
            'hasSearchFilters'
        ));
    }

    private function applyLocationFilter(Builder $query, string $location): void
    {
        $query->where(function (Builder $query) use ($location) {
            $query->where('city', 'like', '%'.$location.'%')
                ->orWhere('province', 'like', '%'.$location.'%')
                ->orWhere('district', 'like', '%'.$location.'%')
                ->orWhere('address', 'like', '%'.$location.'%');
        });
    }

    private function applySort(Builder $query, string $sort, string $searchTerm, string $table): void
    {
        if ($sort === 'name_asc') {
            $query->orderBy("{$table}.name");

            return;
        }

        if ($sort === 'name_desc') {
            $query->orderByDesc("{$table}.name");

            return;
        }

        if ($sort === 'relevance' && $searchTerm !== '') {
            $query->orderByRaw(
                "case
                    when {$table}.name = ? then 0
                    when {$table}.name like ? then 1
                    when {$table}.city like ? or {$table}.district like ? or {$table}.province like ? then 2
                    else 3
                end",
                [$searchTerm, $searchTerm.'%', $searchTerm.'%', $searchTerm.'%', $searchTerm.'%']
            );
        }

        $query->orderByDesc("{$table}.created_at");
    }
}
