<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Enums\PlaceStatus;
use App\Enums\PriceLevel;
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
        $selectedType = $this->normalizeType($request->query('type'));
        $category = trim((string) $request->query('category', ''));
        $province = trim((string) $request->query('province', ''));
        $locationTerm = trim((string) $request->query('location', ''));
        $sort = $request->query('sort') ?: ($searchTerm !== '' ? 'relevance' : 'newest');

        $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:120'],
            'province' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'in:place,service,places,services,all'],
            'status' => ['nullable', 'in:'.implode(',', PlaceStatus::values())],
            'price_level' => ['nullable', 'in:'.implode(',', PriceLevel::values())],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'sort' => ['nullable', 'in:relevance,newest,name_asc,name_desc'],
        ]);

        $rating = $request->filled('rating') ? $request->integer('rating') : null;
        $status = $request->query('status');
        $priceLevel = $request->query('price_level');
        $openNow = $request->boolean('open_now');
        $verified = $request->boolean('verified');

        if ($selectedType === 'service') {
            $resultsQuery = Service::query()
                ->with(['category:id,name,slug', 'media'])
                ->withCount(['reviews as reviews_count' => fn ($query) => $query->approved()])
                ->withAvg(['reviews as reviews_avg_rating' => fn ($query) => $query->approved()], 'rating')
                ->active()
                ->filterSearch($searchTerm)
                ->filterCategorySlug($category)
                ->when($province !== '', fn ($query) => $query->where('province', $province))
                ->when($locationTerm !== '', fn ($query) => $this->applyLocationFilter($query, $locationTerm))
                ->when(filled($status), fn ($query) => $query->where('status', $status))
                ->when(filled($priceLevel), fn ($query) => $query->where('price_level', $priceLevel))
                ->filterRatingAtLeast($rating)
                ->filterOpenNow($openNow)
                ->filterVerified($verified);
            $table = 'services';
        } else {
            $resultsQuery = Place::query()
                ->with(['category:id,name,slug', 'media'])
                ->withCount(['reviews as reviews_count' => fn ($query) => $query->approved()])
                ->withAvg(['reviews as reviews_avg_rating' => fn ($query) => $query->approved()], 'rating')
                ->active()
                ->filterSearch($searchTerm)
                ->filterCategorySlug($category)
                ->when($province !== '', fn ($query) => $query->where('province', $province))
                ->when($locationTerm !== '', fn ($query) => $this->applyLocationFilter($query, $locationTerm))
                ->when(filled($status), fn ($query) => $query->where('status', $status))
                ->when(filled($priceLevel), fn ($query) => $query->where('price_level', $priceLevel))
                ->filterRatingAtLeast($rating)
                ->filterOpenNow($openNow)
                ->filterVerified($verified);
            $table = 'places';
        }

        $this->applySort($resultsQuery, $sort, $searchTerm, $table);

        $queryParams = array_filter([
            'category' => $category,
            'province' => $province,
            'location' => $locationTerm,
            'search' => $searchTerm,
            'status' => $status,
            'price_level' => $priceLevel,
            'rating' => $rating,
            'open_now' => $openNow ? 1 : null,
            'verified' => $verified ? 1 : null,
            'sort' => $sort !== ($searchTerm !== '' ? 'relevance' : 'newest') ? $sort : null,
        ], fn ($value) => filled($value));
        $queryParams['type'] = $selectedType;

        $results = $resultsQuery
            ->paginate(20)
            ->appends($queryParams);

        $placeCategories = Cache::remember('active_place_categories', 1800, function () {
            return PlaceCategory::active()->orderBy('name')->get(['id', 'name', 'slug', 'icon_name']);
        });

        $serviceCategories = Cache::remember('active_service_categories', 1800, function () {
            return ServiceCategory::active()->orderBy('name')->get(['id', 'name', 'slug', 'icon_name']);
        });

        $provinces = Cache::remember('discover_provinces', 1800, function () {
            return collect()
                ->merge(Place::active()->whereNotNull('province')->distinct()->pluck('province'))
                ->merge(Service::active()->whereNotNull('province')->distinct()->pluck('province'))
                ->map(fn ($province) => trim((string) $province))
                ->filter()
                ->unique()
                ->sort()
                ->values();
        });

        return view('pages.search.index', compact(
            'results',
            'placeCategories',
            'serviceCategories',
            'provinces',
            'selectedType',
            'category',
            'province',
            'locationTerm',
            'searchTerm',
            'status',
            'priceLevel',
            'rating',
            'openNow',
            'verified',
            'sort'
        ));
    }

    private function normalizeType(mixed $type): string
    {
        return in_array($type, ['service', 'services'], true) ? 'service' : 'place';
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
