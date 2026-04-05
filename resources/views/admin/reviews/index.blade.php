@extends('layouts.admin')

@section('title', 'Manage Reviews')
@section('page-title', 'Reviews')

@section('content')

<div class="bg-white rounded-lg shadow-md">
    <div class="px-6 py-4 border-b">
        <h3 class="text-lg font-semibold text-gray-900">All Reviews ({{ $reviews->total() }})</h3>
    </div>

    <!-- Filters -->
    <div class="px-6 py-4 border-b bg-gray-50">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reviews..." 
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            
            <select name="rating" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">All Ratings</option>
                <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>5 Stars</option>
                <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>4 Stars</option>
                <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>3 Stars</option>
                <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>2 Stars</option>
                <option value="1" {{ request('rating') === '1' ? 'selected' : '' }}>1 Star</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                Filter
            </button>
            <a href="{{ route('admin.reviews.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                Clear
            </a>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Place</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.places.show', $review->place) }}" class="text-gray-900 font-medium hover:text-emerald-600">
                                {{ Str::limit($review->place->name, 30) }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $review->user->name }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="text-yellow-500">{{ str_repeat('⭐', $review->rating) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($review->comment, 50) }}</td>
                        <td class="px-6 py-4 text-xs text-gray-500">{{ $review->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.reviews.show', $review) }}" class="text-blue-600 hover:text-blue-800" title="View">👁️</a>
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline" 
                                    onsubmit="return confirm('Are you sure you want to delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No reviews found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($reviews->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $reviews->links() }}
        </div>
    @endif
</div>

@endsection
