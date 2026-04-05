@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">{{ $place->name }}</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.places.edit', $place) }}" class="text-emerald-600 hover:text-emerald-700">Edit</a>
                    <a href="{{ route('admin.places.index') }}" class="text-gray-600 hover:text-gray-700">← Back</a>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    @if($place->is_verified)
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-sm rounded-full">Verified</span>
                    @endif
                    <span class="px-3 py-1 {{ $place->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }} text-sm rounded-full">
                        {{ $place->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Category</h3>
                        <p class="text-gray-900">{{ $place->category->name }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Rating</h3>
                        <p class="text-gray-900">{{ number_format($place->average_rating, 1) }} ⭐ ({{ $place->reviews_count }} reviews)</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Description</h3>
                    <p class="text-gray-900">{{ $place->description }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Address</h3>
                        <p class="text-gray-900">{{ $place->address }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Phone</h3>
                        <p class="text-gray-900">{{ $place->phone_1 ?: 'N/A' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Coordinates</h3>
                        <p class="text-gray-900">{{ $place->latitude }}, {{ $place->longitude }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Website</h3>
                        <p class="text-gray-900">
                            @if($place->website)
                                <a href="{{ $place->website }}" target="_blank" class="text-emerald-600 hover:underline">{{ $place->website }}</a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>

                @if($place->images && count($place->images) > 0)
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Images</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($place->images as $image)
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $place->name }}" class="w-full h-32 object-cover rounded-lg">
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Created</h3>
                        <p class="text-gray-900">{{ $place->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Last Updated</h3>
                        <p class="text-gray-900">{{ $place->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
