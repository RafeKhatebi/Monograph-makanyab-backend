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

        <style>
            body {
                font-family: 'Inter', sans-serif;
                background: #f9fafb;
                margin: 0;
            }

            .admin-wrapper {
                display: flex;
                height: 100vh;
            }

            .sidebar {
                width: 260px;
                background: #fff;
                box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            }

            .sidebar-header {
                padding: 24px;
                border-bottom: 1px solid #e5e7eb;
            }

            .sidebar-header h1 {
                font-size: 24px;
                font-weight: 700;
                color: #10b981;
                margin: 0;
            }

            .sidebar-header p {
                font-size: 14px;
                color: #6b7280;
                margin: 0;
            }

            .sidebar-nav {
                padding: 16px;
            }

            .nav-item {
                display: flex;
                align-items: center;
                padding: 12px 16px;
                margin-bottom: 8px;
                color: #374151;
                text-decoration: none;
                border-radius: 8px;
                transition: 0.2s;
            }

            .nav-item:hover {
                background: #ecfdf5;
                color: #10b981;
                text-decoration: none;
            }

            .nav-item.active {
                background: #ecfdf5;
                color: #10b981;
            }

            .nav-item span:first-child {
                font-size: 18px;
                margin-right: 12px;
            }

            .nav-divider {
                border-top: 1px solid #e5e7eb;
                margin: 16px 0;
            }

            .main-area {
                flex: 1;
                overflow-y: auto;
            }

            .top-header {
                background: #fff;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                padding: 16px 32px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .top-header h2 {
                font-size: 20px;
                font-weight: 600;
                color: #1f2937;
                margin: 0;
            }

            .user-info {
                display: flex;
                align-items: center;
                gap: 16px;
            }

            .user-badge {
                padding: 4px 12px;
                background: #f3e8ff;
                color: #7c3aed;
                font-size: 12px;
                border-radius: 20px;
            }

            .content-wrapper {
                padding: 32px;
            }

            .alert-box {
                margin: 16px 32px;
                padding: 16px;
                border-radius: 8px;
            }

            .alert-success {
                background: #ecfdf5;
                border: 1px solid #d1fae5;
                color: #065f46;
            }

            .alert-danger {
                background: #fef2f2;
                border: 1px solid #fecaca;
                color: #991b1b;
            }
        </style>

        @stack('styles')
    </head>

    <body>
        <div class="admin-wrapper">
            <aside class="sidebar">
                <div class="sidebar-header">
                    <h1>Makanyab</h1>
                    <p>Admin Panel</p>
                </div>

                <nav class="sidebar-nav">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span>📊</span><span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.places.index') }}"
                        class="nav-item {{ request()->routeIs('admin.places.*') ? 'active' : '' }}">
                        <span>📍</span><span>Places</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                        class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <span>📂</span><span>Categories</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <span>👥</span><span>Users</span>
                    </a>
                    <a href="{{ route('admin.reviews.index') }}"
                        class="nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                        <span>⭐</span><span>Reviews</span>
                    </a>

                    <div class="nav-divider"></div>

                    <a href="{{ route('home') }}" class="nav-item">
                        <span>🏠</span><span>Back to Site</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="nav-item"
                            style="width: 100%; border: none; background: none; cursor: pointer; text-align: left;">
                            <span>🚪</span><span>Logout</span>
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
