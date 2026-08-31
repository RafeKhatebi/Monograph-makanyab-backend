<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Enums\PlaceStatus;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Service;
use App\Models\ServiceCategory;
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
        $sort = $request->query('sort') ?: ($searchTerm !== '' ? 'relevance' : 'newest');

        $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'province' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'in:place,service,places,services,all'],
            'status' => ['nullable', 'in:'.implode(',', PlaceStatus::values())],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'sort' => ['nullable', 'in:relevance,newest,name_asc,name_desc'],
        ]);

        $rating = $request->filled('rating') ? $request->integer('rating') : null;
        $status = $request->query('status');
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
                ->when(filled($status), fn ($query) => $query->where('status', $status))
                ->filterRatingAtLeast($rating)
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
                ->when(filled($status), fn ($query) => $query->where('status', $status))
                ->filterRatingAtLeast($rating)
                ->filterVerified($verified);
            $table = 'places';
        }

        $this->applySort($resultsQuery, $sort, $searchTerm, $table);

        $queryParams = array_filter([
            'category' => $category,
            'province' => $province,
            'search' => $searchTerm,
            'status' => $status,
            'rating' => $rating,
            'verified' => $verified ? 1 : null,
            'sort' => $sort !== ($searchTerm !== '' ? 'relevance' : 'newest') ? $sort : null,
        ], fn ($value) => filled($value));
        $queryParams['type'] = $selectedType;

        $results = $resultsQuery
            ->paginate(18)
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
            'searchTerm',
            'status',
            'rating',
            'verified',
            'sort'
        ));
    }

    private function normalizeType(mixed $type): string
    {
        return in_array($type, ['service', 'services'], true) ? 'service' : 'place';
    }

    private function applySort($query, string $sort, string $searchTerm, string $table): void
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
