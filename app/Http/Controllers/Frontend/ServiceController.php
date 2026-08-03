<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceReviewRequest;
use App\Http\Requests\UpdateServiceReviewRequest;
use App\Models\Favorite;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::query()
            ->with(['category', 'media'])
            ->withCount(['reviews as reviews_count' => fn ($query) => $query->where('is_approved', true)])
            ->withAvg(['reviews as reviews_avg_rating' => fn ($query) => $query->where('is_approved', true)], 'rating')
            ->active()
            ->filterSearch($request->query('search'))
            ->filterCategorySlug($request->query('category'))
            ->when($request->city, fn ($q, $v) => $q->where('city', 'like', "%{$v}%"))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->price_level, fn ($q, $v) => $q->where('price_level', $v))
            ->filterVerified($request->boolean('verified'))
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $categories = ServiceCategory::active()->orderBy('name')->get();

        return view('pages.services.index', compact('services', 'categories'));
    }

    public function show(Service $service)
    {
        abort_if(! $service->is_active, 404);

        $service->load([
            'category',
            'media',
            'reviews' => fn ($query) => $query->where('is_approved', true)
                ->with('user:id,name,profile_picture')
                ->latest(),
        ]);
        $isFavorited = Auth::check() && Favorite::where([
            'user_id' => Auth::id(),
            'service_id' => $service->id,
        ])->exists();
        $hasReviewed = Auth::check()
            && Auth::user()->reviews()->where('service_id', $service->id)->exists();

        $similar = Service::with(['category:id,name,slug', 'media'])
            ->withCount(['reviews as reviews_count' => fn ($query) => $query->where('is_approved', true)])
            ->withAvg(['reviews as reviews_avg_rating' => fn ($query) => $query->where('is_approved', true)], 'rating')
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
            $message = 'Removed from favorites.';
        } else {
            Favorite::create([
                'user_id' => $request->user()->id,
                'service_id' => $service->id,
            ]);
            $message = 'Added to favorites.';
        }

        return back()->with('success', $message);
    }

    public function storeReview(StoreServiceReviewRequest $request, Service $service)
    {
        $service->reviews()->create([
            'user_id' => $request->user()->id,
            'rating' => $request->integer('rating'),
            'comment' => $request->validated('comment'),
            'is_approved' => false,
        ]);

        return back()->with('success', 'Review submitted and pending approval.');
    }

    public function updateReview(
        UpdateServiceReviewRequest $request,
        Service $service,
        Review $review
    ) {
        $review->update([...$request->validated(), 'is_approved' => false]);

        return back()->with('success', 'Review updated and returned to the approval queue.');
    }

    public function destroyReview(Service $service, Review $review)
    {
        abort_unless(
            $review->service_id === $service->id
            && ($review->user_id === Auth::id() || Auth::user()?->isAdmin()),
            403
        );

        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
