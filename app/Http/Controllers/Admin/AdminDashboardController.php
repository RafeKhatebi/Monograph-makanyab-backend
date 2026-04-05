<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Review;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
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
            'avg_rating' => Review::avg('rating'),
            'recent_places' => Place::with(['category', 'user'])->latest()->take(5)->get(),
            'recent_reviews' => Review::with(['place', 'user'])->latest()->take(5)->get(),
            'recent_users' => User::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
