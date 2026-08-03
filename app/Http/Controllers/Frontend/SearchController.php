<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
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
        $request->validate([
            'type' => ['nullable', 'in:all,places,services'],
            'status' => ['nullable', 'in:open,closed,temporarily_closed'],
            'price_level' => ['nullable', 'in:low,medium,high,luxury'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'sort' => ['nullable', 'in:newest,name_asc,name_desc'],
        ]);

        $showPlaces = $request->type !== 'services';
        $showServices = $request->type !== 'places';

        $places = null;
        $services = null;

        if ($showPlaces) {
            $places = Place::query()
                ->with(['category:id,name,slug', 'media'])
                ->active()
                ->filterSearch($request->query('search'))
                ->filterCategorySlug($request->query('place_category'))
                ->when($request->filled('city'), fn ($q) => $q->where('city', 'like', '%'.$request->city.'%'))
                ->when($request->filled('province'), fn ($q) => $q->where('province', 'like', '%'.$request->province.'%'))
                ->when($request->filled('district'), fn ($q) => $q->where('district', 'like', '%'.$request->district.'%'))
                ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
                ->when($request->filled('price_level'), fn ($q) => $q->where('price_level', $request->price_level))
                ->filterRatingAtLeast($request->integer('rating'))
                ->filterOpenNow($request->boolean('open_now'))
                ->filterVerified($request->boolean('verified'))
                ->when(
                    $request->query('sort') === 'name_asc',
                    fn ($query) => $query->orderBy('name'),
                    fn ($query) => $query->when(
                        $request->query('sort') === 'name_desc',
                        fn ($query) => $query->orderByDesc('name'),
                        fn ($query) => $query->latest()
                    )
                )
                ->paginate(8, ['*'], 'places_page')
                ->withQueryString();
        }

        if ($showServices) {
            $services = Service::query()
                ->with(['category:id,name,slug', 'media'])
                ->withCount(['reviews as reviews_count' => fn ($query) => $query->where('is_approved', true)])
                ->withAvg(['reviews as reviews_avg_rating' => fn ($query) => $query->where('is_approved', true)], 'rating')
                ->active()
                ->filterSearch($request->query('search'))
                ->filterCategorySlug($request->query('service_category'))
                ->when($request->filled('city'), fn ($q) => $q->where('city', 'like', '%'.$request->city.'%'))
                ->when($request->filled('province'), fn ($q) => $q->where('province', 'like', '%'.$request->province.'%'))
                ->when($request->filled('district'), fn ($q) => $q->where('district', 'like', '%'.$request->district.'%'))
                ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
                ->when($request->filled('price_level'), fn ($q) => $q->where('price_level', $request->price_level))
                ->filterRatingAtLeast($request->integer('rating'))
                ->filterOpenNow($request->boolean('open_now'))
                ->filterVerified($request->boolean('verified'))
                ->when(
                    $request->query('sort') === 'name_asc',
                    fn ($query) => $query->orderBy('name'),
                    fn ($query) => $query->when(
                        $request->query('sort') === 'name_desc',
                        fn ($query) => $query->orderByDesc('name'),
                        fn ($query) => $query->latest()
                    )
                )
                ->paginate(8, ['*'], 'services_page')
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
            'showServices'
        ));
    }
}
