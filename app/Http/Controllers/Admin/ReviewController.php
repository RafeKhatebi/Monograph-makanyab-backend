<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'status' => ['nullable', Rule::in([
                Review::STATUS_PENDING,
                Review::STATUS_APPROVED,
                Review::STATUS_REJECTED,
            ])],
            'target' => ['nullable', Rule::in(['place', 'service'])],
        ]);

        $query = Review::with(['place', 'service', 'user']);

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('status')) {
            $query->where('moderation_status', $request->status);
        }

        if ($request->query('target') === 'place') {
            $query->whereNotNull('place_id');
        }

        if ($request->query('target') === 'service') {
            $query->whereNotNull('service_id');
        }

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->whereHas('place', function ($query) use ($request) {
                    $query->where('name', 'like', '%'.$request->search.'%');
                })->orWhereHas('service', function ($query) use ($request) {
                    $query->where('name', 'like', '%'.$request->search.'%');
                });
            });
        }

        $reviews = $query->latest()->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load(['place', 'service', 'user']);

        return view('admin.reviews.show', compact('review'));
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    public function approve(Review $review)
    {
        $review->markApproved();

        return back()->with('success', 'Review approved successfully.');
    }

    public function reject(Review $review)
    {
        $review->markRejected();

        return back()->with('success', 'Review rejected successfully.');
    }
}
