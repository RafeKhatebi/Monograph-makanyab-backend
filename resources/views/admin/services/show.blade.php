@extends('layouts.admin')

@section('title', 'Service Details')
@section('page-title', 'Service Details')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $service->name }}</h1>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.services.edit', $service) }}" class="text-emerald-600 hover:text-emerald-700">Edit</a>
                        <a href="{{ route('admin.services.index') }}" class="text-gray-600 hover:text-gray-700">Back</a>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        @if($service->is_verified)
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-sm rounded-full">Verified</span>
                        @endif
                        <span
                            class="px-3 py-1 {{ $service->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }} text-sm rounded-full">
                            {{ $service->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Category</h3>
                            <p class="text-gray-900">{{ $service->category->name ?? '-' }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Status</h3>
                            <p class="text-gray-900 capitalize">{{ str_replace('_', ' ', (string) $service->status) }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Price Level</h3>
                            <p class="text-gray-900 capitalize">{{ (string) $service->price_level ?: '-' }}</p>
                        </div>
                    </div>

                    @if($service->tagline)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Tagline</h3>
                            <p class="text-gray-900">{{ $service->tagline }}</p>
                        </div>
                    @endif

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Description</h3>
                        <p class="text-gray-900">{{ $service->description }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Address</h3>
                            <p class="text-gray-900">{{ $service->address }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Location</h3>
                            <p class="text-gray-900">
                                {{ $service->district }}, {{ $service->city }}, {{ $service->province }}, {{ $service->country }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Phone 1</h3>
                            <p class="text-gray-900">{{ $service->phone_1 ?: '-' }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Phone 2</h3>
                            <p class="text-gray-900">{{ $service->phone_2 ?: '-' }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">WhatsApp</h3>
                            <p class="text-gray-900">{{ $service->whatsapp ?: '-' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Website</h3>
                            <p class="text-gray-900">
                                @if($service->website)
                                    <a href="{{ $service->website }}" target="_blank" rel="noopener noreferrer"
                                        class="text-emerald-600 hover:underline">{{ $service->website }}</a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Latitude</h3>
                            <p class="text-gray-900">{{ $service->latitude ?: '-' }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Longitude</h3>
                            <p class="text-gray-900">{{ $service->longitude ?: '-' }}</p>
                        </div>
                    </div>

                    @if($service->media->where('type', 'image')->count() > 0)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-3">Images</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($service->media->where('type', 'image') as $image)
                                    <img src="{{ asset('storage/' . $image->file_path) }}" alt="{{ $service->name }}"
                                        class="w-full h-28 object-cover rounded-lg border border-gray-100">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Created</h3>
                            <p class="text-gray-900">{{ $service->created_at?->format('M d, Y H:i') }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Last Updated</h3>
                            <p class="text-gray-900">{{ $service->updated_at?->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
