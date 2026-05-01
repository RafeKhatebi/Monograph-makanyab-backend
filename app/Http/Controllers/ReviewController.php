<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
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
            ->where('is_approved', true)
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
            'is_approved' => false,
        ]);

        $review->load('user:id,name');

        return response()->json($review, 201);
    }

    public function show(Place $place, Review $review): JsonResponse
    {
        if ($review->place_id !== $place->id) {
            return response()->json(['message' => 'Review not found for this place.'], 404);
        }

        $review->load('user:id,name');

        return response()->json($review);
    }

    public function destroy(Request $request, Place $place, Review $review): JsonResponse
    {
        if ($review->place_id !== $place->id) {
            return response()->json(['message' => 'Review not found for this place.'], 404);
        }

        /** @var User $user */
        $user = $request->user();

        if ($user->id !== $review->user_id && ! $user->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $review->delete();

        return response()->json(null, 204);
    }
}
