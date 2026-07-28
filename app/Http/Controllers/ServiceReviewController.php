<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceReviewRequest;
use App\Http\Requests\UpdateServiceReviewRequest;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceReviewController extends Controller
{
    public function index(Request $request, Service $service): JsonResponse
    {
        return response()->json(
            $service->reviews()
                ->where('is_approved', true)
                ->with('user:id,name,profile_picture')
                ->latest()
                ->paginate(min(max($request->integer('per_page', 10), 1), 50))
        );
    }

    public function store(StoreServiceReviewRequest $request, Service $service): JsonResponse
    {
        $review = $service->reviews()->create([
            'user_id' => $request->user()->id,
            'rating' => $request->integer('rating'),
            'comment' => $request->validated('comment'),
            'is_approved' => false,
        ]);

        return response()->json($review->load('user:id,name'), 201);
    }

    public function update(
        UpdateServiceReviewRequest $request,
        Service $service,
        Review $review
    ): JsonResponse {
        $review->update([...$request->validated(), 'is_approved' => false]);

        return response()->json($review->load('user:id,name'));
    }

    public function destroy(Request $request, Service $service, Review $review): JsonResponse
    {
        abort_if($review->service_id !== $service->id, 404);
        abort_unless(
            $review->user_id === $request->user()->id || $request->user()->isAdmin(),
            403
        );

        $review->delete();

        return response()->json(null, 204);
    }
}
