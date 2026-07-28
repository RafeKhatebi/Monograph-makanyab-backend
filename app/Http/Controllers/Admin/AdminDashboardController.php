<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Cache stats for 5 minutes to reduce database queries
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_places' => Place::count(),
                'active_places' => Place::where('is_active', true)->count(),
                'pending_places' => Place::where('is_active', false)->count(),
                'verified_places' => Place::where('is_verified', true)->count(),
                'total_users' => User::count(),
                'admin_users' => User::where('role', 'admin')->count(),
                'owner_users' => User::where('role', 'owner')->count(),
                'total_categories' => PlaceCategory::count(),
                'active_categories' => PlaceCategory::where('is_active', true)->count(),
                'total_reviews' => Review::count(),
                'avg_rating' => round(Review::where('is_approved', true)->avg('rating'), 1),
            ];
        });

        // Recent items are not cached (always fresh)
        $stats['recent_places'] = Place::with(['category:id,name,slug', 'user:id,name'])
            ->latest()
            ->take(5)
            ->get();

        $stats['recent_reviews'] = Review::with(['place:id,name,slug', 'service:id,name,slug', 'user:id,name'])
            ->latest()
            ->take(5)
            ->get();

        $stats['recent_users'] = User::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats'));
    }
}
