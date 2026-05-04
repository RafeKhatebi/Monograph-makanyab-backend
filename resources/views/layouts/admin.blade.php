<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Makanyab</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('dashmin/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dashmin/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('dashmin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dashmin/css/style.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="{{ route('admin.dashboard') }}" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-map-marked-alt me-2"></i>Makanyab</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="{{ asset('dashmin/img/user.jpg') }}" alt="User"
                            style="width: 40px; height: 40px;">
                        <div
                            class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1">
                        </div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                        <span>{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                </div>

                <div class="navbar-nav w-100">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-item nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('admin.places.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('admin.places.*') ? 'active' : '' }}">
                        <i class="fa fa-map-marker-alt me-2"></i>Places
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fa fa-tags me-2"></i>Place Categories
                    </a>
                    <a href="{{ route('admin.services.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                        <i class="fa fa-briefcase me-2"></i>Services
                    </a>
                    <a href="{{ route('admin.service-categories.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('admin.service-categories.*') ? 'active' : '' }}">
                        <i class="fa fa-layer-group me-2"></i>Service Categories
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fa fa-users me-2"></i>Users
                    </a>
                    <a href="{{ route('admin.reviews.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                        <i class="fa fa-star me-2"></i>Reviews
                    </a>
                    <a href="{{ route('admin.place-suggestions.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('admin.place-suggestions.*') ? 'active' : '' }}">
                        <i class="fa fa-lightbulb me-2"></i>Place Suggestions
                    </a>
                    <a href="{{ route('admin.service-suggestions.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('admin.service-suggestions.*') ? 'active' : '' }}">
                        <i class="fa fa-comment-dots me-2"></i>Service Suggestions
                    </a>
                    <a href="{{ route('admin.posts.index') }}"
                        class="nav-item nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                        <i class="fa fa-newspaper me-2"></i>Posts
                    </a>

                    <hr class="my-2">

                    <a href="{{ route('home') }}" class="nav-item nav-link">
                        <i class="fa fa-globe me-2"></i>Back to Site
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-item nav-link w-100 text-start border-0 bg-transparent">
                            <i class="fa fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <div class="content">
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="{{ route('admin.dashboard') }}" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-map-marked-alt"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="ms-4">
                    <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                </div>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="{{ asset('dashmin/img/user.jpg') }}" alt="User"
                                style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">{{ auth()->user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="{{ route('home') }}" class="dropdown-item">Visit Site</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Log Out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="container-fluid pt-4 px-4">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
            </div>

            <div class="container-fluid px-4 pb-4">
                @yield('content')
            </div>

            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; {{ now()->year }} <a href="{{ route('home') }}">Makanyab</a>. All rights reserved.
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            Admin panel styled with Dashmin template palette
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <script src="{{ asset('assets/js/jquery-1.10.2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('dashmin/lib/chart/chart.min.js') }}"></script>
    <script src="{{ asset('dashmin/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('dashmin/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('dashmin/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('dashmin/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('dashmin/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('dashmin/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('dashmin/js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>
