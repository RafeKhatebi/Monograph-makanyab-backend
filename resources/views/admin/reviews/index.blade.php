@extends('layouts.admin')

@section('title', 'Manage Reviews')
@section('page-title', 'Reviews')

@section('content')
    <div class="bg-light rounded h-100 p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">All Reviews ({{ $reviews->total() }})</h6>
        </div>

        <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-2 mb-4">
            <div class="col-md-5">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reviews..." class="form-control">
            </div>
            <div class="col-md-3">
                <select name="rating" class="form-select">
                    <option value="">All Ratings</option>
                    <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>5 Stars</option>
                    <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>4 Stars</option>
                    <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>3 Stars</option>
                    <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>2 Stars</option>
                    <option value="1" {{ request('rating') === '1' ? 'selected' : '' }}>1 Star</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-dark">
                        <th>Place</th>
                        <th>User</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>
                                <a href="{{ route('admin.places.show', $review->place) }}">
                                    {{ Str::limit($review->place->name, 30) }}
                                </a>
                            </td>
                            <td>{{ $review->user->name }}</td>
                            <td>{{ $review->rating }}/5</td>
                            <td>{{ Str::limit($review->comment, 50) }}</td>
                            <td>{{ $review->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No reviews found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="pt-4">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
@endsection
