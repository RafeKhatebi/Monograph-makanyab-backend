<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Post;
use App\Models\Service;
use App\Models\ServiceCategory;
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

        $featuredServices = Cache::remember('home_featured_services', 600, function () {
            return Service::with(['category:id,name,slug,color_code,icon_name', 'media'])
                ->withCount(['reviews as reviews_count' => fn ($query) => $query->approved()])
                ->withAvg(['reviews as reviews_avg_rating' => fn ($query) => $query->approved()], 'rating')
                ->where('is_active', true)
                ->where('is_verified', true)
                ->orderByDesc('created_at')
                ->limit(6)
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

        $serviceCategories = Cache::remember('home_service_categories', 600, function () {
            return ServiceCategory::active()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->limit(6)
                ->get(['id', 'name', 'slug', 'icon_name', 'color_code']);
        });

        $latestPosts = Cache::remember('home_latest_posts', 600, function () {
            return Post::query()
                ->where('is_published', true)
                ->where(function ($query) {
                    $query->whereNull('published_at')
                        ->orWhere('published_at', '<=', now());
                })
                ->latest('published_at')
                ->latest()
                ->limit(3)
                ->get(['id', 'title', 'slug', 'image', 'excerpt', 'content', 'published_at', 'created_at']);
        });

        $verifiedPlaces = Cache::remember('home_verified_places', 600, function () {
            return Place::with(['category:id,name,slug', 'media'])
                ->where('is_active', true)
                ->where('is_verified', true)
                ->orderByDesc('updated_at')
                ->limit(3)
                ->get();
        });

        $searchProvinces = Cache::remember('home_search_provinces', 600, function () {
            return collect()
                ->merge(Place::active()->whereNotNull('province')->distinct()->pluck('province'))
                ->merge(Service::active()->whereNotNull('province')->distinct()->pluck('province'))
                ->filter()
                ->map(fn ($province) => trim($province))
                ->filter()
                ->unique()
                ->sort()
                ->take(12)
                ->values();
        });

        if ($searchProvinces->isEmpty()) {
            $searchProvinces = collect(['Kabul', 'Herat', 'Balkh', 'Nangarhar']);
        }

        $homeStats = [
            'places' => Place::active()->count(),
            'services' => Service::active()->count(),
            'verified' => Place::active()->where('is_verified', true)->count()
                + Service::active()->where('is_verified', true)->count(),
        ];

        return view('pages.home', compact(
            'featuredPlaces',
            'featuredServices',
            'categories',
            'serviceCategories',
            'latestPosts',
            'verifiedPlaces',
            'searchProvinces',
            'homeStats'
        ));
    }
}
