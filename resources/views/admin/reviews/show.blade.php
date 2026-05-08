@extends('layouts.admin')

@section('title', 'Review Details')
@section('page-title', 'Review Details')

@section('content')
    <div class="bg-light rounded h-100 p-4">
        <div class="bg-white rounded p-4 shadow-sm">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                <h6 class="mb-0">Review Details</h6>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i>Back
                </a>
            </div>

                <div class="space-y-6">
                    <div>
                        <span class="badge {{ $review->is_approved ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $review->is_approved ? 'Approved' : 'Pending Approval' }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Rating</h3>
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-gray-900">{{ $review->rating }}</span>
                            <span class="ml-2 text-yellow-500">⭐</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Place</h3>
                            <a href="{{ route('admin.places.show', $review->place) }}"
                                class="text-emerald-600 hover:underline">
                                {{ $review->place->name }}
                            </a>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">User</h3>
                            <a href="{{ route('admin.users.show', $review->user) }}"
                                class="text-emerald-600 hover:underline">
                                {{ $review->user->name }}
                            </a>
                        </div>
                    </div>

                    @if ($review->comment)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Comment</h3>
                            <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $review->comment }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Created</h3>
                            <p class="text-gray-900">{{ $review->created_at->format('M d, Y H:i') }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Last Updated</h3>
                            <p class="text-gray-900">{{ $review->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t">
                        <div class="d-flex gap-2 mb-3">
                            @if (! $review->is_approved)
                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Approve Review</button>
                                </form>
                            @else
                                <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">Reject Review</button>
                                </form>
                            @endif
                        </div>

                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this review?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Delete Review
                            </button>
                        </form>
                    </div>
                </div>
        </div>
    </div>
@endsection
