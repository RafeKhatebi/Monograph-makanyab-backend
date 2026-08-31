<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceReviewRequest;
use App\Http\Requests\UpdateServiceReviewRequest;
use App\Models\Favorite;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'status' => ['nullable', 'in:open,closed,temporarily_closed'],
            'price_level' => ['nullable', 'in:low,medium,high,luxury'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
        ]);

        $services = Service::query()
            ->with(['category', 'media'])
            ->withCount(['reviews as reviews_count' => fn ($query) => $query->approved()])
            ->withAvg(['reviews as reviews_avg_rating' => fn ($query) => $query->approved()], 'rating')
            ->active()
            ->filterSearch($request->query('search'))
            ->filterCategorySlug($request->query('category'))
            ->when($request->filled('city'), fn ($q) => $q->where('city', 'like', '%'.$request->city.'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('price_level'), fn ($q) => $q->where('price_level', $request->price_level))
            ->filterRatingAtLeast($request->integer('rating'))
            ->filterOpenNow($request->boolean('open_now'))
            ->filterVerified($request->boolean('verified'))
            ->orderByDesc('created_at')
            ->paginate(18)
            ->withQueryString();

        return view('pages.services.index', compact('services'));
    }

    public function show(Service $service)
    {
        abort_if(! $service->is_active, 404);

        $service->load([
            'category',
            'media',
            'reviews' => fn ($query) => $query->approved()
                ->with('user:id,name,profile_picture')
                ->latest()
                ->limit(10),
        ]);
        $isFavorited = Auth::check() && Favorite::where([
            'user_id' => Auth::id(),
            'service_id' => $service->id,
        ])->exists();
        $hasReviewed = Auth::check()
            && Auth::user()->reviews()->where('service_id', $service->id)->exists();

        $similar = Service::with(['category:id,name,slug', 'media'])
            ->withCount(['reviews as reviews_count' => fn ($query) => $query->approved()])
            ->withAvg(['reviews as reviews_avg_rating' => fn ($query) => $query->approved()], 'rating')
            ->where('service_category_id', $service->service_category_id)
            ->where('id', '!=', $service->id)
            ->active()
            ->limit(4)
            ->get();

        return view('pages.services.show', compact('service', 'similar', 'isFavorited', 'hasReviewed'));
    }

    public function toggleFavorite(Request $request, Service $service)
    {
        abort_if(! $service->is_active, 404);

        $favorite = Favorite::where([
            'user_id' => $request->user()->id,
            'service_id' => $service->id,
        ])->first();

        if ($favorite) {
            $favorite->delete();
            $message = __('messages.favorite_removed');
        } else {
            Favorite::create([
                'user_id' => $request->user()->id,
                'service_id' => $service->id,
            ]);
            $message = __('messages.favorite_added');
        }

        return back()->with('success', $message);
    }

    public function storeReview(StoreServiceReviewRequest $request, Service $service)
    {
        $service->reviews()->create([
            'user_id' => $request->user()->id,
            'rating' => $request->integer('rating'),
            'comment' => $request->validated('comment'),
            'moderation_status' => Review::STATUS_PENDING,
        ]);

        return back()->with('success', __('messages.review_submitted'));
    }

    public function updateReview(
        UpdateServiceReviewRequest $request,
        Service $service,
        Review $review
    ) {
        $review->update([...$request->validated(), 'moderation_status' => Review::STATUS_PENDING]);

        return back()->with('success', __('messages.review_updated'));
    }

    public function destroyReview(Service $service, Review $review)
    {
        abort_unless(
            $review->service_id === $service->id
            && ($review->user_id === Auth::id() || Auth::user()?->isAdmin()),
            403
        );

        $review->delete();

        return back()->with('success', __('messages.review_deleted'));
    }
}
