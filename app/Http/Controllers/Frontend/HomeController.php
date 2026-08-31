<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\Post;
use App\Models\Service;
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
                ->limit(3)
                ->get();
        });

        $featuredServices = Cache::remember('home_featured_services', 600, function () {
            return Service::with(['category:id,name,slug,color_code,icon_name', 'media'])
                ->withCount(['reviews as reviews_count' => fn ($query) => $query->approved()])
                ->withAvg(['reviews as reviews_avg_rating' => fn ($query) => $query->approved()], 'rating')
                ->where('is_active', true)
                ->where('is_verified', true)
                ->orderByDesc('created_at')
                ->limit(3)
                ->get();
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

        $homeStats = [
            'places' => Place::active()->count(),
            'services' => Service::active()->count(),
            'verified' => Place::active()->where('is_verified', true)->count()
                + Service::active()->where('is_verified', true)->count(),
        ];

        return view('pages.home', compact(
            'featuredPlaces',
            'featuredServices',
            'latestPosts',
            'verifiedPlaces',
            'homeStats'
        ));
    }
}
