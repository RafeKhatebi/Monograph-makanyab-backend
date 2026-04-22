@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">

            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_places'] }}</h3>
            <p class="text-sm text-gray-500 uppercase tracking-wide mb-3">Total Places</p>
            <div class="text-xs text-gray-600 mb-4">
                <span class="text-emerald-600 font-medium">{{ $stats['active_places'] }}</span> Active •
                <span class="text-amber-600 font-medium">{{ $stats['pending_places'] }}</span> Pending
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.places.index') }}"
                    class="px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700">View All</a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_users'] }}</h3>
            <p class="text-sm text-gray-500 uppercase tracking-wide mb-3">Total Users</p>
            <div class="text-xs text-gray-600 mb-4">
                <span class="text-emerald-600 font-medium">{{ $stats['admin_users'] }}</span> Admins •
                <span class="text-amber-600 font-medium">{{ $stats['owner_users'] }}</span> Owners
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.users.index') }}"
                    class="px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700">View All</a>

            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">

            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_categories'] }}</h3>
            <p class="text-sm text-gray-500 uppercase tracking-wide mb-3">Categories</p>
            <div class="text-xs text-gray-600 mb-4">
                <span class="text-emerald-600 font-medium">{{ $stats['active_categories'] }}</span> Active
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.categories.index') }}"
                    class="px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700">View All</a>

            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_reviews'] }}</h3>
            <p class="text-sm text-gray-500 uppercase tracking-wide mb-3">Reviews</p>
            <div class="text-xs text-gray-600 mb-4">
                Avg Rating
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.reviews.index') }}"
                    class="px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700">View All</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Recent Places --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Recent Places</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($stats['recent_places'] as $place)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.places.show', $place) }}"
                                        class="text-gray-900 font-medium hover:text-emerald-600">
                                        {{ Str::limit($place->name, 30) }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $place->category->name ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full {{ $place->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $place->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500">No places yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($stats['recent_users'] as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : ($user->role === 'owner' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500">No users yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
