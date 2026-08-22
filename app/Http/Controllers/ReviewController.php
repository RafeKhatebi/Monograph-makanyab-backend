<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Place;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request, Place $place): JsonResponse
    {
        $reviews = $place->reviews()
            ->approved()
            ->with('user:id,name,profile_picture')
            ->when($request->rating, fn ($q, $v) => $q->where('rating', $v))
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 10));

        return response()->json($reviews);
    }

    public function store(StoreReviewRequest $request, Place $place): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $review = $place->reviews()->create([
            'user_id' => $user->id,
            'rating' => $request->validated('rating'),
            'comment' => $request->validated('comment'),
            'moderation_status' => Review::STATUS_PENDING,
        ]);

        $review->load('user:id,name');

        return response()->json($review, 201);
    }

    public function show(Place $place, Review $review): JsonResponse
    {
        if ($review->place_id !== $place->id) {
            return response()->json(['message' => __('messages.api.review_not_found')], 404);
        }

        $user = request()->user();
        if ((! $review->is_approved || $review->moderation_status !== Review::STATUS_APPROVED)
            && $review->user_id !== $user?->id
            && ! ($user?->isAdmin() ?? false)) {
            return response()->json(['message' => __('messages.api.review_not_found')], 404);
        }

        $review->load('user:id,name');

        return response()->json($review);
    }

    public function update(UpdateReviewRequest $request, Place $place, Review $review): JsonResponse
    {
        $review->update([
            ...$request->validated(),
            'moderation_status' => Review::STATUS_PENDING,
        ]);

        return response()->json($review->load('user:id,name'));
    }

    public function destroy(Request $request, Place $place, Review $review): JsonResponse
    {
        if ($review->place_id !== $place->id) {
            return response()->json(['message' => __('messages.api.review_not_found')], 404);
        }

        /** @var User $user */
        $user = $request->user();

        if ($user->id !== $review->user_id && ! $user->isAdmin()) {
            return response()->json(['message' => __('messages.api.forbidden')], 403);
        }

        $review->delete();

        return response()->json(null, 204);
    }
}
