<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\Review;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function index(Request $request)
    {
        $places = Place::with(['category:id,name,slug,color_code,icon_name', 'media'])
            ->where('is_active', true)
            ->when($request->search, fn ($q, $v) => $q->where(
                fn ($q) => $q->where('name', 'like', "%{$v}%")
                    ->orWhere('tagline', 'like', "%{$v}%")
                    ->orWhere('city', 'like', "%{$v}%")
            ))
            ->when($request->city, fn ($q, $v) => $q->where('city', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->price_level, fn ($q, $v) => $q->where('price_level', $v))
            ->when($request->verified, fn ($q) => $q->where('is_verified', true))
            ->when($request->category, fn ($q, $v) => $q->whereHas(
                'category', fn ($q) => $q->where('slug', $v)
            ))
            ->orderByDesc('created_at')
            ->paginate(12);

        $categories = PlaceCategory::where('is_active', true)->orderBy('name')->get();

        return view('pages.places.index', compact('places', 'categories'));
    }

    public function show(Place $place)
    {
        $place->load([
            'category:id,name,slug,color_code',
            'user:id,name',
            'openingHours',
            'media',
            'reviews' => fn ($q) => $q->where('is_approved', true)
                ->with('user:id,name,profile_picture')
                ->latest(),
        ]);

        $similarPlaces = Place::with('media')
            ->where('place_category_id', $place->place_category_id)
            ->where('id', '!=', $place->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('pages.places.show', compact('place', 'similarPlaces'));
    }

    public function storeReview(Request $request, Place $place)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $place->reviews()->create([
            'user_id'     => auth()->id(),
            'rating'      => $request->rating,
            'comment'     => $request->comment,
            'is_approved' => false,
        ]);

        return back()->with('success', 'Review submitted and pending approval.');
    }
}
