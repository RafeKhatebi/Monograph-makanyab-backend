@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Review Details</h1>
                    <a href="{{ route('admin.reviews.index') }}" class="text-gray-600 hover:text-gray-700">← Back</a>
                </div>

                <div class="space-y-6">
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
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this review?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Delete Review
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
