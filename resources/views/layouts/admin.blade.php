<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ config('locales.'.app()->getLocale().'.direction', 'ltr') }}"
    data-open-menu="{{ __('navigation.open_menu') }}" data-close-menu="{{ __('navigation.close_menu') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta-description', 'Makanyab Admin Panel')">
    <title>@yield('title', 'Admin') - Makanyab</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/branding/makanyab-app-icon.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Naskh+Arabic:wght@400;500;600;700&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <link href="{{ asset('assets/css/variables.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/ui-system.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-utilities.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-layout.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/rtl.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/responsive-overrides.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/design-system.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
    <!-- Skip to content link -->
    <a href="#main-content" class="skip-link">{{ __('common.skip_to_content') }}</a>

    <div class="admin-shell">
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()" aria-hidden="true"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar" role="complementary" aria-label="{{ __('admin.navigation.aria') }}">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand" aria-label="Makanyab Admin Dashboard">
                <img class="sidebar-brand-icon" src="{{ asset('assets/img/branding/makanyab-app-icon.svg') }}" alt="" aria-hidden="true">
                <div class="sidebar-brand-text">Makanyab</div>
            </a>

            <nav class="sidebar-nav" aria-label="{{ __('admin.navigation.aria') }}">
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.dashboard') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-tachometer-alt" aria-hidden="true"></i>
                    {{ __('admin.navigation.dashboard') }}
                </a>
                <a href="{{ route('admin.places.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.places.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.places.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-map-marker-alt" aria-hidden="true"></i>
                    {{ __('admin.navigation.places') }}
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.categories.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-tags" aria-hidden="true"></i>
                    {{ __('admin.navigation.place_categories') }}
                </a>
                <a href="{{ route('admin.services.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.services.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-briefcase" aria-hidden="true"></i>
                    {{ __('admin.navigation.services') }}
                </a>
                <a href="{{ route('admin.service-categories.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.service-categories.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.service-categories.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-layer-group" aria-hidden="true"></i>
                    {{ __('admin.navigation.service_categories') }}
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.users.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-users" aria-hidden="true"></i>
                    {{ __('admin.navigation.users') }}
                </a>
                <a href="{{ route('admin.reviews.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.reviews.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    {{ __('admin.navigation.reviews') }}
                </a>
                <a href="{{ route('admin.contact-messages.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.contact-messages.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                    {{ __('admin.navigation.contact_messages') }}
                </a>
                <a href="{{ route('admin.place-suggestions.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.place-suggestions.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.place-suggestions.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-lightbulb" aria-hidden="true"></i>
                    {{ __('admin.navigation.place_suggestions') }}
                </a>
                <a href="{{ route('admin.service-suggestions.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.service-suggestions.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.service-suggestions.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-comment-dots" aria-hidden="true"></i>
                    {{ __('admin.navigation.service_suggestions') }}
                </a>
                <a href="{{ route('admin.posts.index') }}"
                    class="sidebar-nav-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}"
                    {{ request()->routeIs('admin.posts.*') ? 'aria-current="page"' : '' }}>
                    <i class="fa fa-newspaper" aria-hidden="true"></i>
                    {{ __('admin.navigation.posts') }}
                </a>

                <div class="sidebar-nav-divider" role="separator"></div>

                <a href="{{ route('home') }}" class="sidebar-nav-item">
                    <i class="fa fa-globe" aria-hidden="true"></i>
                    {{ __('admin.navigation.back_to_site') }}
                </a>

                <form method="POST" action="{{ route('logout') }}" aria-label="Logout">
                    @csrf
                    <button type="submit" class="sidebar-nav-item sidebar-logout">
                        <i class="fa fa-sign-out-alt" aria-hidden="true"></i>
                        {{ __('admin.navigation.logout') }}
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <header class="admin-header" role="banner">
                <div class="admin-header-inner">
                    <button class="sidebar-toggler" onclick="toggleSidebar()" aria-label="{{ __('navigation.open_menu') }}" aria-expanded="false" aria-controls="sidebar">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                        <span class="sr-only">Toggle menu</span>
                    </button>
                    <h1 class="admin-header-title" id="page-title">@yield('page-title', __('admin.navigation.dashboard'))</h1>
                </div>

                <div class="user-dropdown">
                    @include('partials.language-switcher')
                    <button type="button" class="user-dropdown-toggle" onclick="toggleUserDropdown()" aria-expanded="false" aria-haspopup="true" aria-label="{{ __('admin.navigation.user_menu') }}">
                        <span>{{ auth()->user()->name }}</span>
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="user-dropdown-menu" id="userDropdown" role="menu">
                        <form method="POST" action="{{ route('logout') }}" role="none">
                            @csrf
                            <button type="submit" class="user-dropdown-item" role="menuitem">
                                <i class="fa fa-sign-out-alt" aria-hidden="true"></i> {{ __('admin.navigation.logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <div class="admin-alert-region" aria-live="polite">
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
                <div class="admin-alert-region" role="alert" aria-live="assertive">
                    <div class="flash-message flash-error">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                        <div>
                            <strong>{{ __('admin.errors') }}</strong>
                            <ul>
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
                <span>&copy; {{ now()->year }} <a href="{{ route('home') }}">Makanyab</a>. {{ __('admin.all_rights') }}</span>
                <span>{{ __('admin.panel') }}</span>
            </footer>
        </div>
    </div>

    @php
        $appTranslations = [
            'mediaCover' => __('common.media.cover'),
            'mediaSetCover' => __('common.media.set_cover'),
            'mediaMoveEarlier' => __('common.media.move_earlier'),
            'mediaMoveLater' => __('common.media.move_later'),
            'mediaRemoveFile' => __('common.media.remove_file'),
            'remove' => __('common.actions.remove'),
            'saving' => __('common.media.saving'),
        ];
    @endphp
    <script>
        window.AppTranslations = Object.assign(window.AppTranslations || {}, @json($appTranslations));
    </script>
    <script src="{{ asset('assets/js/admin-layout.js') }}"></script>
    @stack('scripts')
</body>

</html>
