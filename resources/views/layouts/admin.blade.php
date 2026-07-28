<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta-description', 'Makanyab Admin Panel')">
    <title>@yield('title', 'Admin') - Makanyab</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <link href="{{ asset('assets/css/variables.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-utilities.css') }}" rel="stylesheet">
    <style>
        /* Skip to content link */
        .skip-link {
            position: absolute;
            top: -100%;
            left: 0;
            background: var(--color-primary);
            color: var(--color-white);
            padding: 8px 16px;
            z-index: 10000;
            font-weight: var(--font-weight-semibold);
            border-radius: 0 0 var(--radius-md) 0;
            text-decoration: none;
            transition: top 0.2s;
        }

        .skip-link:focus {
            top: 0;
        }

        /* Focus styles */
        :focus-visible {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
        }

        .sidebar-toggler {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--color-gray-600);
            cursor: pointer;
            padding: 8px;
            border-radius: var(--radius-md);
        }

        .sidebar-toggler:hover {
            background-color: var(--color-gray-100);
        }

        @media (max-width: 991px) {
            .sidebar-toggler {
                display: block;
            }
        }

        .user-dropdown {
            position: relative;
        }

        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: background-color var(--transition-fast);
            background: none;
            border: none;
            font-size: var(--font-size-sm);
            font-weight: var(--font-weight-medium);
            color: var(--color-gray-700);
        }

        .user-dropdown-toggle:hover {
            background-color: var(--color-gray-100);
        }

        .user-dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 4px;
            background-color: var(--color-white);
            border: 1px solid var(--color-gray-200);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            min-width: 160px;
            display: none;
            z-index: 1000;
        }

        .user-dropdown-menu.show {
            display: block;
        }

        .user-dropdown-item {
            display: block;
            width: 100%;
            padding: 10px 16px;
            font-size: var(--font-size-sm);
            color: var(--color-gray-700);
            text-decoration: none;
            border: none;
            background: none;
            cursor: pointer;
            text-align: left;
        }

        .user-dropdown-item:hover {
            background-color: var(--color-gray-50);
            color: var(--color-primary);
        }

        .flash-message {
            padding: 12px 16px;
            border-radius: var(--radius-md);
            margin-bottom: var(--space-4);
            font-size: var(--font-size-sm);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .flash-success {
            background-color: var(--color-success-bg);
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .flash-error {
            background-color: var(--color-danger-bg);
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        /* Screen reader only */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Skip to content link -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <div style="display: flex; min-height: 100vh;">
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()" aria-hidden="true"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar" role="complementary" aria-label="Admin navigation">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand" aria-label="Makanyab Admin Dashboard">
                <div class="sidebar-brand-icon" aria-hidden="true">
                    <i class="fa fa-map-marked-alt"></i>
                </div>
                <div class="sidebar-brand-text">Makanyab</div>
            </a>

            <nav class="sidebar-nav" aria-label="Admin menu">
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.dashboard') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-tachometer-alt" aria-hidden="true"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.places.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.places.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.places.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-map-marker-alt" aria-hidden="true"></i>
                    Places
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.categories.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-tags" aria-hidden="true"></i>
                    Place Categories
                </a>
                <a href="{{ route('admin.services.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.services.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-briefcase" aria-hidden="true"></i>
                    Services
                </a>
                <a href="{{ route('admin.service-categories.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.service-categories.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.service-categories.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-layer-group" aria-hidden="true"></i>
                    Service Categories
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.users.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-users" aria-hidden="true"></i>
                    Users
                </a>
                <a href="{{ route('admin.reviews.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.reviews.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    Reviews
                </a>
                <a href="{{ route('admin.place-suggestions.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.place-suggestions.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.place-suggestions.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-lightbulb" aria-hidden="true"></i>
                    Place Suggestions
                </a>
                <a href="{{ route('admin.service-suggestions.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.service-suggestions.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.service-suggestions.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-comment-dots" aria-hidden="true"></i>
                    Service Suggestions
                </a>
                <a href="{{ route('admin.posts.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.posts.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-newspaper" aria-hidden="true"></i>
                    Posts
                </a>

                <div class="sidebar-nav-divider" role="separator"></div>

                <a href="{{ route('home') }}" class="sidebar-nav-item">
                    <i class="fa fa-globe" aria-hidden="true"></i>
                    Back to Site
                </a>

                <form method="POST" action="{{ route('logout') }}" aria-label="Logout">
                    @csrf
                    <button type="submit" class="sidebar-nav-item" style="width: 100%; text-align: left;">
                        <i class="fa fa-sign-out-alt" aria-hidden="true"></i>
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div style="flex: 1; display: flex; flex-direction: column; min-width: 0;">
            <!-- Header -->
            <header class="admin-header" role="banner">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <button class="sidebar-toggler" onclick="toggleSidebar()" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="sidebar">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                        <span class="sr-only">Toggle menu</span>
                    </button>
                    <h1 class="admin-header-title" id="page-title">@yield('page-title', 'Dashboard')</h1>
                </div>

                <div class="user-dropdown">
                    <button type="button" class="user-dropdown-toggle" onclick="toggleUserDropdown()" aria-expanded="false" aria-haspopup="true" aria-label="User menu">
                        <span>{{ auth()->user()->name }}</span>
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="user-dropdown-menu" id="userDropdown" role="menu">
                        <form method="POST" action="{{ route('logout') }}" role="none">
                            @csrf
                            <button type="submit" class="user-dropdown-item" role="menuitem">
                                <i class="fa fa-sign-out-alt" style="margin-right: 8px;" aria-hidden="true"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <div style="padding: 0 var(--space-6);" aria-live="polite">
                @if (session('success'))
                    <div class="flash-message flash-success" role="status">
                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="flash-message flash-error" role="alert">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div style="padding: 0 var(--space-6);" role="alert" aria-live="assertive">
                    <div class="flash-message flash-error">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                        <div>
                            <strong>Please fix the following errors:</strong>
                            <ul style="margin: 4px 0 0 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main class="admin-content" id="main-content" role="main" aria-labelledby="page-title">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="admin-footer" role="contentinfo">
                <span>&copy; {{ now()->year }} <a href="{{ route('home') }}">Makanyab</a>. All rights reserved.</span>
                <span>Admin Panel</span>
            </footer>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggler = document.querySelector('.sidebar-toggler');
            const isOpen = sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            toggler.setAttribute('aria-expanded', isOpen);

            // Trap focus in sidebar when open on mobile
            if (isOpen) {
                sidebar.querySelector('a').focus();
            }
        }

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            const toggle = document.querySelector('.user-dropdown-toggle');
            const isOpen = dropdown.classList.toggle('show');
            toggle.setAttribute('aria-expanded', isOpen);

            if (isOpen) {
                dropdown.querySelector('button, a').focus();
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userDropdown');
            const toggle = document.querySelector('.user-dropdown-toggle');
            if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });

        // Close dropdown on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const dropdown = document.getElementById('userDropdown');
                const toggle = document.querySelector('.user-dropdown-toggle');
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.focus();
                }
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
