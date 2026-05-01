<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Place;
use App\Models\PlaceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class PlaceController extends Controller
{
    public function index(Request $request)
    {
        $places = Place::query()
            ->with(['category:id,name,slug,color_code,icon_name', 'media'])
            ->active()
            ->filterSearch($request->query('search'))
            ->when($request->city, fn ($q, $v) => $q->where('city', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->price_level, fn ($q, $v) => $q->where('price_level', $v))
            ->filterRatingAtLeast($request->integer('rating'))
            ->filterOpenNow($request->boolean('open_now'))
            ->filterVerified($request->boolean('verified'))
            ->filterCategorySlug($request->query('category'))
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $categories = PlaceCategory::active()->orderBy('name')->get();

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
            ->active()
            ->limit(4)
            ->get();

        return view('pages.places.show', compact('place', 'similarPlaces'));
    }

    public function storeReview(StoreReviewRequest $request, Place $place)
    {
        $place->reviews()->create([
            'user_id' => FacadesAuth::id(),
            'rating' => $request->validated('rating'),
            'comment' => $request->validated('comment'),
            'is_approved' => false,
        ]);

        return back()->with('success', 'Review submitted and pending approval.');
    }
}
