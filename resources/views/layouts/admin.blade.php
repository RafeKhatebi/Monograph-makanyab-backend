<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Admin') — Makanyab</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/makanyab.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
        @stack('styles')
    </head>

    <body>
        <div class="admin-wrapper">
            <aside class="sidebar">
                <div class="sidebar-header">
                    <h1>Makanyab</h1>
                    <p style="padding:3px;">Admin Panel</p>
                </div>

                <nav class="sidebar-nav">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class=""></span><span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.places.index') }}"
                        class="nav-item {{ request()->routeIs('admin.places.*') ? 'active' : '' }}">
                        <span></span><span>Places</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                        class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <span></span><span>Categories</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <span></span><span>Users</span>
                    </a>
                    <a href="{{ route('admin.reviews.index') }}"
                        class="nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                        <span></span><span>Reviews</span>
                    </a>
                    <a href="{{ route('admin.place-suggestions.index') }}"
                        class="nav-item {{ request()->routeIs('admin.place-suggestions.*') ? 'active' : '' }}">
                        <span></span><span>Place Suggestions</span>
                    </a>
                    <a href="{{ route('admin.service-suggestions.index') }}"
                        class="nav-item {{ request()->routeIs('admin.service-suggestions.*') ? 'active' : '' }}">
                        <span></span><span>Service Suggestions</span>
                    </a>
                    <a href="{{ route('admin.posts.index') }}"
                        class="nav-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                        <span></span><span>Posts</span>
                    </a>

                    <div class="nav-divider"></div>

                    <a href="{{ route('home') }}" class="nav-item">
                        <span></span><span>Back to Site</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="nav-item"
                            style="width: 100%; border: none; background: none; cursor: pointer; text-align: left;">
                            <span></span><span>Logout</span>
                        </button>
                    </form>
                </nav>
            </aside>

            <div class="main-area">
                <header class="top-header">
                    <h2>@yield('page-title', 'Dashboard')</h2>
                    <div class="user-info">
                        <span style="font-size: 14px; color: #6b7280;">{{ auth()->user()->name }}</span>
                        <span class="user-badge">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                </header>

                @if (session('success'))
                    <div class="alert-box alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert-box alert-danger">{{ session('error') }}</div>
                @endif

                <main class="content-wrapper">
                    @yield('content')
                </main>
            </div>
        </div>

        <script src="{{ asset('assets/js/jquery-1.10.2.min.js') }}"></script>
        <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
        @stack('scripts')
    </body>

</html>
