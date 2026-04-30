<nav class="mk-nav">
    <div class="container">
        <div class="mk-inner">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="mk-logo">
                <div class="mk-logo-icon">M</div>
                <span class="mk-logo-text">Makan<span>yab</span></span>
            </a>

            {{-- Desktop Nav --}}
            <ul class="mk-links">

                {{-- Home --}}
                <li>
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                        <i></i> Home
                    </a>
                </li>
                {{-- search --}}
                <li>
                    <a href="{{ route('search.index') }}"
                        class="{{ request()->routeIs('search.index') ? 'active' : '' }}">
                        <i></i> Search
                    </a>
                </li>
                <li>
                    <a href="{{ route('place-suggestions.create') }}"
                        class="{{ request()->routeIs('place-suggestions.*') ? 'active' : '' }}">
                        <i></i> Suggest
                    </a>
                </li>
                {{-- Discover dropdown --}}
                <li
                    class="mk-dd-item {{ request()->routeIs('places.*') || request()->routeIs('services.*') || request()->routeIs('categories.*') || request()->routeIs('service-categories.*') ? 'open-default' : '' }}">
                    <a
                        class="{{ request()->routeIs('places.*') || request()->routeIs('services.*') || request()->routeIs('categories.*') || request()->routeIs('service-categories.*') ? 'active' : '' }}">
                        <i></i> Discover <i class="fa fa-chevron-down mk-caret"></i>
                    </a>
                    <div class="mk-dd">
                        <a href="{{ route('places.index') }}">
                            <i class="fa fa-map-marker"></i> Places
                        </a>
                        <a href="{{ route('categories.index') }}">
                            <i class="fa fa-th-large"></i> Place Categories
                        </a>
                        <div class="mk-dd-divider"></div>
                        <a href="{{ route('services.index') }}">
                            <i class="fa fa-briefcase"></i> Services
                        </a>
                        <a href="{{ route('service-categories.index') }}">
                            <i class="fa fa-list-alt"></i> Service Categories
                        </a>
                    </div>
                </li>


                {{-- Company links --}}

                <li>
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">
                        <i></i> About
                    </a>
                </li>
                <li>
                    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                        <i></i> Contact
                    </a>
                </li>
                <li>
                    <a href="{{ route('posts.index') }}" class="{{ request()->routeIs('posts.*') ? 'active' : '' }}">
                        <i></i> Blog
                    </a>
                </li>

            </ul>

            {{-- Auth --}}
            <div class="mk-auth">
                @guest
                    <a href="{{ route('login') }}" class="mk-btn-login">Log In</a>
                    <a href="{{ route('register') }}" class="mk-btn-signup">Sign Up</a>
                @else
                    @if (!auth()->user()->isAdmin())
                        <a href="{{ route('favorites.index') }}"
                            style="padding:8px 10px;border-radius:8px;color:#374151;text-decoration:none;font-size:18px;transition:color .2s;"
                            title="Favorites">
                            <i class="fa fa-heart-o"></i>
                        </a>
                    @endif
                    <div class="mk-user-menu" id="mk-user-menu">
                        <div class="mk-user-trigger" id="mk-user-trigger">
                            <div class="mk-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            <span>{{ explode(' ', auth()->user()->name)[0] }}</span>
                            <i class="fa fa-chevron-down" style="font-size:11px;color:#9CA3AF;"></i>
                        </div>
                        <div class="mk-user-dd">
                            <a href="{{ route('profile.index') }}">
                                <i class="fa fa-user"></i> My Profile
                            </a>
                            @if (!auth()->user()->isAdmin())
                                <a href="{{ route('favorites.index') }}">
                                    <i class="fa fa-heart"></i> Favorites
                                </a>
                            @endif
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}">
                                    <i class="fa fa-dashboard"></i> Admin Panel
                                </a>
                            @endif
                            <div class="mk-divider"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="mk-danger">
                                    <i class="fa fa-sign-out"></i> Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            {{-- Hamburger --}}
            <button class="mk-hamburger" id="mk-hamburger" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>

        </div>
    </div>

    {{-- Mobile Drawer --}}
    <div class="mk-mobile" id="mk-mobile">
        <div class="container">

            {{-- Mobile Search --}}
            <div style="padding:10px 20px 14px;">
                <form action="{{ route('search.index') }}" method="GET" style="display:flex;gap:8px;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search places..."
                        style="flex:1;height:42px;padding:0 14px;border:1.5px solid #D1D5DB;border-radius:8px;font-size:14px;outline:none;">
                    <button type="submit"
                        style="height:42px;padding:0 16px;background:#10B981;color:#fff;border:none;border-radius:8px;font-weight:700;cursor:pointer;">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="mk-mobile-divider"></div>

            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fa fa-home"></i> Home
            </a>

            {{-- Discover accordion --}}
            <button class="mk-mobile-group-btn" id="mob-discover-btn">
                <span style="display:flex;align-items:center;gap:10px;">
                    <i class="fa fa-compass" style="width:18px;color:#10B981;"></i> Discover
                </span>
                <i class="fa fa-chevron-down mk-caret"></i>
            </button>
            <div class="mk-mobile-sub" id="mob-discover">
                <a href="{{ route('places.index') }}" class="{{ request()->routeIs('places.*') ? 'active' : '' }}">
                    <i class="fa fa-map-marker"></i> Places
                </a>
                <a href="{{ route('categories.index') }}"
                    class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="fa fa-th-large"></i> Place Categories
                </a>
                <a href="{{ route('services.index') }}"
                    class="{{ request()->routeIs('services.*') ? 'active' : '' }}">
                    <i class="fa fa-briefcase"></i> Services
                </a>
                <a href="{{ route('service-categories.index') }}"
                    class="{{ request()->routeIs('service-categories.*') ? 'active' : '' }}">
                    <i class="fa fa-list-alt"></i> Service Categories
                </a>
            </div>


            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">
                <i></i> About
            </a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                <i class="fa fa-envelope"></i> Contact
            </a>
            <a href="{{ route('posts.index') }}" class="{{ request()->routeIs('posts.*') ? 'active' : '' }}">
                <i class="fa fa-newspaper-o"></i> Blog
            </a>
            <a href="{{ route('place-suggestions.create') }}" class="{{ request()->routeIs('place-suggestions.*') ? 'active' : '' }}">
                <i class="fa fa-lightbulb-o"></i> Suggest a Place
            </a>

            <div class="mk-mobile-divider"></div>

            @guest
                <div class="mk-mobile-auth">
                    <a href="{{ route('login') }}"
                        style="flex:1;text-align:center;padding:10px;border:1.5px solid #D1D5DB;border-radius:8px;font-weight:600;color:#374151;text-decoration:none;">Log
                        In</a>
                    <a href="{{ route('register') }}"
                        style="flex:1;text-align:center;padding:10px;background:#10B981;border-radius:8px;font-weight:700;color:#fff;text-decoration:none;">Sign
                        Up</a>
                </div>
            @else
                <a href="{{ route('profile.index') }}">
                    <i class="fa fa-user"></i> My Profile
                </a>
                @if (!auth()->user()->isAdmin())
                    <a href="{{ route('favorites.index') }}">
                        <i class="fa fa-heart"></i> Favorites
                    </a>
                @endif
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fa fa-dashboard"></i> Admin Panel
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="color:#DC2626;">
                        <i class="fa fa-sign-out" style="color:#DC2626;"></i> Log Out
                    </button>
                </form>
            @endguest

        </div>
    </div>
</nav>
<script src="./assets/js/navbar.js"></script>
