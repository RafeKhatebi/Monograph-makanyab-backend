@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-emerald-600 hover:text-emerald-700">Edit</a>
                    <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:text-gray-700">← Back</a>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <span class="px-3 py-1 {{ $category->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }} text-sm rounded-full">
                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                @if($category->icon)
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Icon</h3>
                    <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }}" class="w-20 h-20 object-cover rounded-lg">
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Slug</h3>
                        <p class="text-gray-900">{{ $category->slug }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Parent Category</h3>
                        <p class="text-gray-900">{{ $category->parent ? $category->parent->name : 'None (Top Level)' }}</p>
                    </div>
                </div>

                @if($category->description)
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Description</h3>
                    <p class="text-gray-900">{{ $category->description }}</p>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Total Places</h3>
                        <p class="text-gray-900">{{ $category->places_count }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Subcategories</h3>
                        <p class="text-gray-900">{{ $category->children_count }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Created</h3>
                        <p class="text-gray-900">{{ $category->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Last Updated</h3>
                        <p class="text-gray-900">{{ $category->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
