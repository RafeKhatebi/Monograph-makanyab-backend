<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SuggestionStatus;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\PlaceSuggestion;
use App\Models\Post;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceSuggestion;
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
            'total_services' => Service::count(),
            'active_services' => Service::where('is_active', true)->count(),
            'inactive_services' => Service::where('is_active', false)->count(),
            'verified_services' => Service::where('is_verified', true)->count(),
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'owner_users' => User::where('role', 'owner')->count(),
            'total_categories' => PlaceCategory::count(),
            'active_categories' => PlaceCategory::where('is_active', true)->count(),
            'total_service_categories' => ServiceCategory::count(),
            'active_service_categories' => ServiceCategory::where('is_active', true)->count(),
            'total_reviews' => Review::count(),
            'pending_reviews' => Review::where('moderation_status', Review::STATUS_PENDING)->count(),
            'avg_rating' => round(Review::approved()->avg('rating'), 1),
            'total_posts' => Post::count(),
            'published_posts' => Post::published()->count(),
            'draft_posts' => Post::where('is_published', false)->count(),
            'unread_contact_messages' => ContactMessage::whereNull('read_at')->whereNull('archived_at')->count(),
            'archived_contact_messages' => ContactMessage::whereNotNull('archived_at')->count(),
            'pending_place_suggestions' => PlaceSuggestion::where('suggestion_status', SuggestionStatus::Pending)->count(),
            'pending_service_suggestions' => ServiceSuggestion::where('suggestion_status', SuggestionStatus::Pending)->count(),
        ];

        $stats['recent_places'] = Place::with(['category:id,name,slug', 'user:id,name'])
            ->latest()
            ->take(5)
            ->get();

        $stats['recent_reviews'] = Review::with(['place:id,name,slug', 'service:id,name,slug', 'user:id,name'])
            ->latest()
            ->take(5)
            ->get();

        $stats['recent_contact_messages'] = ContactMessage::latest()
            ->take(5)
            ->get();

        $stats['recent_users'] = User::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats'));
    }
}
