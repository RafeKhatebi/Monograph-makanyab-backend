<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Makanyab</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        }
                    }
                }
            }
        }
    </script>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg">
            <div class="p-6 border-b">
                <h1 class="text-2xl font-bold text-emerald-600">Makanyab</h1>
                <p class="text-sm text-gray-500">Admin Panel</p>
            </div>
            
            <nav class="p-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 mb-2 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                    <span class="text-lg mr-3">📊</span>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('admin.places.index') }}" class="flex items-center px-4 py-3 mb-2 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 {{ request()->routeIs('admin.places.*') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                    <span class="text-lg mr-3">📍</span>
                    <span>Places</span>
                </a>
                
                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-3 mb-2 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 {{ request()->routeIs('admin.categories.*') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                    <span class="text-lg mr-3">📂</span>
                    <span>Categories</span>
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 mb-2 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 {{ request()->routeIs('admin.users.*') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                    <span class="text-lg mr-3">👥</span>
                    <span>Users</span>
                </a>
                
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-4 py-3 mb-2 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 {{ request()->routeIs('admin.reviews.*') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                    <span class="text-lg mr-3">⭐</span>
                    <span>Reviews</span>
                </a>
                
                <div class="border-t my-4"></div>
                
                <a href="{{ route('home') }}" class="flex items-center px-4 py-3 mb-2 text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600">
                    <span class="text-lg mr-3">🏠</span>
                    <span>Back to Site</span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-3 text-gray-700 rounded-lg hover:bg-red-50 hover:text-red-600">
                        <span class="text-lg mr-3">🚪</span>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm">
                <div class="px-8 py-4 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mx-8 mt-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mx-8 mt-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Page Content -->
            <main class="p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
