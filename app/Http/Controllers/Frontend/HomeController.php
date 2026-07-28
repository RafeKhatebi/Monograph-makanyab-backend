<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlaceCategory;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Cache featured places for 10 minutes
        $featuredPlaces = Cache::remember('home_featured_places', 600, function () {
            return Place::with(['category:id,name,slug,color_code,icon_name', 'media'])
                ->where('is_active', true)
                ->where('is_verified', true)
                ->orderByDesc('created_at')
                ->limit(7)
                ->get();
        });

        // Cache categories for 10 minutes
        $categories = Cache::remember('home_categories', 600, function () {
            return PlaceCategory::withCount('places')
                ->where('is_active', true)
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->limit(6)
                ->get();
        });

        return view('pages.home', compact('featuredPlaces', 'categories'));
    }
}
