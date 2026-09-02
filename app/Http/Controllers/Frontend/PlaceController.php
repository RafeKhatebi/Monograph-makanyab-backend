<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Place;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaceController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'status' => ['nullable', 'in:open,closed,temporarily_closed'],
            'price_level' => ['nullable', 'in:low,medium,high,luxury'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
        ]);

        $places = $this->buildPlacesQuery($request)
            ->paginate(18)
            ->withQueryString();

        if ($request->boolean('fragment')) {
            return response()->view('pages.places._cards', [
                'places' => $places,
            ]);
        }

        return view('pages.places.index', compact('places'));
    }

    public function loadMore(Request $request)
    {
        $request->validate([
            'status' => ['nullable', 'in:open,closed,temporarily_closed'],
            'price_level' => ['nullable', 'in:low,medium,high,luxury'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $places = $this->buildPlacesQuery($request)
            ->paginate(18)
            ->withQueryString();

        $html = view('pages.places._cards', ['places' => $places])->render();
        $hasMore = $places->hasMorePages();

        return response()->json([
            'html' => $html,
            'hasMore' => $hasMore,
            'nextPage' => $hasMore ? $places->currentPage() + 1 : null,
        ]);
    }

    private function buildPlacesQuery(Request $request)
    {
        return Place::query()
            ->with(['category:id,name,slug,color_code,icon_name', 'media'])
            ->active()
            ->filterSearch($request->query('search'))
            ->when($request->filled('city'), fn ($q) => $q->where('city', 'like', '%'.$request->city.'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('price_level'), fn ($q) => $q->where('price_level', $request->price_level))
            ->filterRatingAtLeast($request->integer('rating'))
            ->filterOpenNow($request->boolean('open_now'))
            ->filterVerified($request->boolean('verified'))
            ->filterCategorySlug($request->query('category'))
            ->orderByDesc('created_at');
    }

    public function show(Place $place)
    {
        abort_if(! $place->is_active, 404);

        // Select specific columns to reduce query payload
        $place->load([
            'category:id,name,slug,color_code',
            'user:id,name',
            'openingHours:id,place_id,day_of_week,open_time,close_time,is_closed',
            'media:id,mediable_type,mediable_id,file_path,type,is_cover,sort_order',
            'reviews' => fn ($q) => $q->approved()
                ->with('user:id,name,profile_picture')
                ->select('id', 'user_id', 'place_id', 'rating', 'comment', 'created_at', 'is_approved', 'moderation_status')
                ->latest()
                ->limit(10),
        ]);

        $similarPlaces = Place::with(['media:id,mediable_type,mediable_id,file_path,type,is_cover'])
            ->where('place_category_id', $place->place_category_id)
            ->where('id', '!=', $place->id)
            ->active()
            ->limit(4)
            ->get();

        $isFavorited = Auth::check()
            && Auth::user()->favorites()->whereKey($place->id)->exists();

        $hasReviewed = Auth::check()
            && Auth::user()->reviews()->where('place_id', $place->id)->exists();

        return view('pages.places.show', compact('place', 'similarPlaces', 'isFavorited', 'hasReviewed'));
    }

    public function storeReview(StoreReviewRequest $request, Place $place)
    {
        $place->reviews()->create([
            'user_id' => Auth::id(),
            'rating' => $request->validated('rating'),
            'comment' => $request->validated('comment'),
            'moderation_status' => Review::STATUS_PENDING,
        ]);

        return back()->with('success', __('messages.review_submitted'));
    }

    public function updateReview(UpdateReviewRequest $request, Place $place, Review $review)
    {
        $review->update([
            ...$request->validated(),
            'moderation_status' => Review::STATUS_PENDING,
        ]);

        return back()->with('success', __('messages.review_updated'));
    }

    public function destroyReview(Place $place, Review $review)
    {
        abort_unless(
            $review->place_id === $place->id
            && ($review->user_id === Auth::id() || Auth::user()?->isAdmin()),
            403
        );

        $review->delete();

        return back()->with('success', __('messages.review_deleted'));
    }
}
