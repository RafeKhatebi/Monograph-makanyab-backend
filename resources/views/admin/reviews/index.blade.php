@extends('layouts.admin')

@section('title', 'Manage Reviews')
@section('page-title', 'Reviews')

@section('content')
    <section class="card" aria-label="Reviews Management">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">All Reviews ({{ $reviews->total() }})</h2>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.reviews.index') }}" role="search" aria-label="Filter reviews" class="admin-filter-form">
                <div class="admin-filter-field">
                    <label for="search" class="sr-only">Search reviews</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search reviews..."
                        class="form-control">
                </div>
                <div>
                    <label for="rating" class="sr-only">Filter by rating</label>
                    <select id="rating" name="rating" class="form-select admin-filter-select">
                        <option value="">All Ratings</option>
                        <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>5 Stars</option>
                        <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>4 Stars</option>
                        <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>3 Stars</option>
                        <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>2 Stars</option>
                        <option value="1" {{ request('rating') === '1' ? 'selected' : '' }}>1 Star</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter" aria-hidden="true"></i> Filter
                </button>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">Clear</a>
            </form>

            <div class="admin-table-wrap">
                <table class="table" aria-label="Reviews list">
                    <thead>
                        <tr>
                            <th scope="col">Place / Service</th>
                            <th scope="col">User</th>
                            <th scope="col">Rating</th>
                            <th scope="col">Status</th>
                            <th scope="col">Comment</th>
                            <th scope="col">Date</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td>
                                    @if ($review->place)
                                        <a href="{{ route('admin.places.show', $review->place) }}">
                                            {{ Str::limit($review->place->name, 30) }}
                                        </a>
                                    @elseif ($review->service)
                                        <a href="{{ route('admin.services.show', $review->service) }}">
                                            {{ Str::limit($review->service->name, 30) }}
                                        </a>
                                    @endif
                                </td>
                                <td>{{ $review->user->name }}</td>
                                <td>{{ $review->rating }}/5</td>
                                <td>
                                    <span class="badge {{ $review->is_approved ? 'badge-success' : 'badge-warning' }}">
                                        {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($review->comment, 50) }}</td>
                                <td>{{ $review->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="{{ route('admin.reviews.show', $review) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            aria-label="View review by {{ $review->user->name }}">View</a>
                                        @if (! $review->is_approved)
                                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="admin-action-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    aria-label="Approve review by {{ $review->user->name }}">Approve</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="admin-action-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning"
                                                    aria-label="Reject review by {{ $review->user->name }}">Reject</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this review?');"
                                            class="admin-action-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                aria-label="Delete review by {{ $review->user->name }}">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="admin-empty">
                                    No reviews found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($reviews->hasPages())
                <nav class="admin-pagination" aria-label="Reviews pagination">
                    {{ $reviews->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
