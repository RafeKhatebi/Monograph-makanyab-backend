@extends('layouts.admin')

@section('title', 'Manage Places')
@section('page-title', 'Places')

@section('content')

<div class="bg-white rounded-lg shadow-md">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900">All Places ({{ $places->total() }})</h3>
        <a href="{{ route('admin.places.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
            + Add New Place
        </a>
    </div>

    <!-- Filters -->
    <div class="px-6 py-4 border-b bg-gray-50">
        <form method="GET" action="{{ route('admin.places.index') }}" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search places..." 
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            
            <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <select name="is_verified" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">All Verification</option>
                <option value="1" {{ request('is_verified') === '1' ? 'selected' : '' }}>Verified</option>
                <option value="0" {{ request('is_verified') === '0' ? 'selected' : '' }}>Not Verified</option>
            </select>

            <select name="is_active" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">All Status</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                Filter
            </button>
            <a href="{{ route('admin.places.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                Clear
            </a>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reviews</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($places as $place)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <span class="font-medium text-gray-900">{{ $place->name }}</span>
                                @if($place->is_verified)
                                    <span class="ml-2 text-emerald-600" title="Verified">✓</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $place->category->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($place->address, 30) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $place->reviews_count }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ number_format($place->reviews_avg_rating ?? 0, 1) }} ⭐
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $place->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $place->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.places.show', $place) }}" class="text-blue-600 hover:text-blue-800" title="View">👁️</a>
                                <a href="{{ route('admin.places.edit', $place) }}" class="text-emerald-600 hover:text-emerald-800" title="Edit">✏️</a>
                                <form action="{{ route('admin.places.destroy', $place) }}" method="POST" class="inline" 
                                    onsubmit="return confirm('Are you sure you want to delete this place?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">No places found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($places->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $places->links() }}
        </div>
    @endif
</div>

@endsection
