<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $showPlaces = $request->type !== 'services';
        $showServices = $request->type !== 'places';

        $places = null;
        $services = null;

        if ($showPlaces) {
            $places = Place::query()
                ->with(['category', 'media'])
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
                ->latest()
                ->paginate(8)
                ->withQueryString();
        }

        if ($showServices) {
            $services = Service::query()
                ->with(['category', 'media'])
                ->active()
                ->filterSearch($request->query('search'))
                ->filterCategorySlug($request->query('service_category'))
                ->when($request->filled('city'), fn ($q) => $q->where('city', 'like', '%'.$request->city.'%'))
                ->when($request->filled('province'), fn ($q) => $q->where('province', 'like', '%'.$request->province.'%'))
                ->when($request->filled('district'), fn ($q) => $q->where('district', 'like', '%'.$request->district.'%'))
                ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
                ->when($request->filled('price_level'), fn ($q) => $q->where('price_level', $request->price_level))
                ->filterOpenNow($request->boolean('open_now'))
                ->filterVerified($request->boolean('verified'))
                ->latest()
                ->paginate(8)
                ->withQueryString();
        }

        $placeCategories = PlaceCategory::active()
            ->orderBy('name')
            ->get();

        $serviceCategories = ServiceCategory::active()
            ->orderBy('name')
            ->get();

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
