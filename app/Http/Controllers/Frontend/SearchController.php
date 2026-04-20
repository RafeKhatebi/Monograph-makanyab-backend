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
            $placeQuery = Place::with(['category', 'media']);

            if ($request->filled('search')) {
                $placeQuery->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                        ->orWhere('description', 'like', "%{$request->search}%");
                });
            }

            if ($request->filled('place_category')) {
                $placeQuery->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->place_category);
                });
            }

            if ($request->filled('city')) {
                $placeQuery->where('city', 'like', "%{$request->city}%");
            }

            if ($request->filled('province')) {
                $placeQuery->where('province', 'like', "%{$request->province}%");
            }

            if ($request->filled('district')) {
                $placeQuery->where('district', 'like', "%{$request->district}%");
            }

            if ($request->filled('status')) {
                $placeQuery->where('status', $request->status);
            }

            if ($request->filled('price_level')) {
                $placeQuery->where('price_level', $request->price_level);
            }

            if ($request->filled('verified')) {
                $placeQuery->where('is_verified', 1);
            }

            $places = $placeQuery->latest()->paginate(8)->withQueryString();
        }

        if ($showServices) {
            $serviceQuery = Service::with(['category', 'media']);

            if ($request->filled('search')) {
                $serviceQuery->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                        ->orWhere('description', 'like', "%{$request->search}%");
                });
            }

            if ($request->filled('service_category')) {
                $serviceQuery->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->service_category);
                });
            }

            if ($request->filled('city')) {
                $serviceQuery->where('city', 'like', "%{$request->city}%");
            }

            if ($request->filled('province')) {
                $serviceQuery->where('province', 'like', "%{$request->province}%");
            }

            if ($request->filled('district')) {
                $serviceQuery->where('district', 'like', "%{$request->district}%");
            }

            if ($request->filled('status')) {
                $serviceQuery->where('status', $request->status);
            }

            if ($request->filled('price_level')) {
                $serviceQuery->where('price_level', $request->price_level);
            }

            if ($request->filled('verified')) {
                $serviceQuery->where('is_verified', 1);
            }

            $services = $serviceQuery->latest()->paginate(8)->withQueryString();
        }

        $placeCategories = PlaceCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        $serviceCategories = ServiceCategory::where('is_active', true)
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