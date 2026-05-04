@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.users.edit', $user) }}"
                            class="text-emerald-600 hover:text-emerald-700">Edit</a>
                        <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-700">← Back</a>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <span
                            class="px-3 py-1 {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : ($user->role === 'owner' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }} text-sm rounded-full">
                            {{ ucfirst($user->role) }}
                        </span>
                        <span
                            class="px-3 py-1 {{ $user->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }} text-sm rounded-full">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Email</h3>
                            <p class="text-gray-900">{{ $user->email }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Email Verified</h3>
                            <p class="text-gray-900">{{ $user->email_verified_at ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Total Reviews</h3>
                            <p class="text-gray-900 text-2xl font-bold">{{ $user->reviews_count }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Total Favorites</h3>
                            <p class="text-gray-900 text-2xl font-bold">{{ $user->favorites_count }}</p>
                        </div>

                        @if ($user->role === 'owner')
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-1">Owned Places</h3>
                                <p class="text-gray-900 text-2xl font-bold">{{ $user->places_count }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Joined</h3>
                            <p class="text-gray-900">{{ $user->created_at->format('M d, Y H:i') }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Last Updated</h3>
                            <p class="text-gray-900">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
