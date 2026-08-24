<nav class="mk-nav" aria-label="{{ __('navigation.aria_primary') }}">
    <div class="container">
        <div class="mk-inner">

            <a href="{{ route('home') }}" class="mk-logo">
                <span class="mk-logo-icon">
                    <img src="{{ asset('assets/img/map-logo.svg') }}" alt="" aria-hidden="true">
                </span>
                <!-- <span class="mk-logo-text">Makan<span>yab</span></span> -->
            </a>

            <ul class="mk-links">

                <li>
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                        <i></i> {{ __('navigation.home') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('search.index') }}"
                        class="{{ request()->routeIs('search.index') ? 'active' : '' }}">
                        <i></i> {{ __('navigation.search') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('place-suggestions.create') }}"
                        class="{{ request()->routeIs('place-suggestions.*') ? 'active' : '' }}">
                        <i></i> {{ __('navigation.suggest') }}
                    </a>
                </li>
                <li
                    class="mk-dd-item {{ request()->routeIs('places.*') || request()->routeIs('services.*') || request()->routeIs('categories.*') || request()->routeIs('service-categories.*') ? 'open-default' : '' }}">
                    <button type="button"
                        class="mk-nav-dropdown-trigger {{ request()->routeIs('places.*') || request()->routeIs('services.*') || request()->routeIs('categories.*') || request()->routeIs('service-categories.*') ? 'active' : '' }}"
                        aria-expanded="false" aria-haspopup="true" aria-controls="mk-discover-menu">
                        <i></i> {{ __('navigation.discover') }} <i class="fa fa-chevron-down mk-caret"></i>
                    </button>
                    <div class="mk-dd" id="mk-discover-menu" aria-hidden="true">
                        <a href="{{ route('places.index') }}">
                            <i class="fa fa-map-marker"></i> {{ __('navigation.places') }}
                        </a>
                        <a href="{{ route('categories.index') }}">
                            <i class="fa fa-th-large"></i> {{ __('navigation.place_categories') }}
                        </a>
                        <div class="mk-dd-divider"></div>
                        <a href="{{ route('services.index') }}">
                            <i class="fa fa-briefcase"></i> {{ __('navigation.services') }}
                        </a>
                        <a href="{{ route('service-categories.index') }}">
                            <i class="fa fa-list-alt"></i> {{ __('navigation.service_categories') }}
                        </a>
                    </div>
                </li>


                <li>
                    <a href="{{ route('posts.index') }}" class="{{ request()->routeIs('posts.*') ? 'active' : '' }}">
                        <i></i> {{ __('navigation.blog') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                        <i></i> {{ __('navigation.contact') }}
                    </a>
                </li>

            </ul>

            <div class="mk-auth">
                @include('partials.language-switcher')
                @guest
                    <a href="{{ route('login') }}" class="mk-btn-login">{{ __('navigation.login') }}</a>
                    <a href="{{ route('register') }}" class="mk-btn-signup">{{ __('navigation.register') }}</a>
                @else
                    @if (!auth()->user()->isAdmin())
                        <a href="{{ route('favorites.index') }}"
                            class="mk-nav-favorite"
                            title="{{ __('navigation.favorites') }}">
                            <i class="fa fa-heart-o"></i>
                        </a>
                    @endif
                    <div class="mk-user-menu" id="mk-user-menu">
                        <button type="button" class="mk-user-trigger" id="mk-user-trigger" aria-expanded="false"
                            aria-haspopup="true" aria-controls="mk-user-dropdown">
                            <div class="mk-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            <span>{{ explode(' ', auth()->user()->name)[0] }}</span>
                            <i class="fa fa-chevron-down mk-caret"></i>
                        </button>
                        <div class="mk-user-dd" id="mk-user-dropdown" aria-hidden="true">
                            <a href="{{ route('profile.index') }}">
                                <i class="fa fa-user"></i> {{ __('navigation.profile') }}
                            </a>
                            @if (!auth()->user()->isAdmin())
                                <a href="{{ route('favorites.index') }}">
                                    <i class="fa fa-heart"></i> {{ __('navigation.favorites') }}
                                </a>
                            @endif
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}">
                                    <i class="fa fa-dashboard"></i> {{ __('navigation.admin') }}
                                </a>
                            @endif
                            <div class="mk-divider"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="mk-danger">
                                    <i class="fa fa-sign-out"></i> {{ __('navigation.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            <button class="mk-hamburger" id="mk-hamburger" aria-label="{{ __('navigation.open_menu') }}" aria-expanded="false"
                aria-controls="mk-mobile">
                <span></span><span></span><span></span>
            </button>

        </div>
    </div>

    <div class="mk-mobile" id="mk-mobile" aria-hidden="true">
        <div class="container">

            <div class="mk-mobile-search">
                <form action="{{ route('search.index') }}" method="GET">
                    <label for="mk-mobile-search-input" class="sr-only">{{ __('navigation.search_placeholder') }}</label>
                    <input id="mk-mobile-search-input" type="search" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('navigation.search_placeholder') }}">
                    <button type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="mk-mobile-divider"></div>

            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fa fa-home"></i> {{ __('navigation.home') }}
            </a>

            <button class="mk-mobile-group-btn" id="mob-discover-btn" aria-expanded="false" aria-controls="mob-discover">
                <span class="mk-mobile-label">
                    <i class="fa fa-compass"></i> {{ __('navigation.discover') }}
                </span>
                <i class="fa fa-chevron-down mk-caret"></i>
            </button>
            <div class="mk-mobile-sub" id="mob-discover" aria-hidden="true">
                <a href="{{ route('places.index') }}" class="{{ request()->routeIs('places.*') ? 'active' : '' }}">
                    <i class="fa fa-map-marker"></i> {{ __('navigation.places') }}
                </a>
                <a href="{{ route('categories.index') }}"
                    class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="fa fa-th-large"></i> {{ __('navigation.place_categories') }}
                </a>
                <a href="{{ route('services.index') }}"
                    class="{{ request()->routeIs('services.*') ? 'active' : '' }}">
                    <i class="fa fa-briefcase"></i> {{ __('navigation.services') }}
                </a>
                <a href="{{ route('service-categories.index') }}"
                    class="{{ request()->routeIs('service-categories.*') ? 'active' : '' }}">
                    <i class="fa fa-list-alt"></i> {{ __('navigation.service_categories') }}
                </a>
            </div>


            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">
                <i></i> {{ __('navigation.about') }}
            </a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                <i class="fa fa-envelope"></i> {{ __('navigation.contact') }}
            </a>
            <a href="{{ route('posts.index') }}" class="{{ request()->routeIs('posts.*') ? 'active' : '' }}">
                <i class="fa fa-newspaper-o"></i> {{ __('navigation.blog') }}
            </a>
            <a href="{{ route('place-suggestions.create') }}" class="{{ request()->routeIs('place-suggestions.*') ? 'active' : '' }}">
                <i class="fa fa-lightbulb-o"></i> {{ __('navigation.suggest_place') }}
            </a>
            <a href="{{ route('service-suggestions.create') }}" class="{{ request()->routeIs('service-suggestions.*') ? 'active' : '' }}">
                <i class="fa fa-concierge-bell"></i> {{ __('navigation.suggest_service') }}
            </a>

            <div class="mk-mobile-divider"></div>

            <div class="mk-mobile-language">
                @include('partials.language-switcher')
            </div>

            @guest
                <div class="mk-mobile-auth">
                    <a href="{{ route('login') }}" class="mk-mobile-auth-link mk-mobile-auth-link--login">{{ __('navigation.login') }}</a>
                    <a href="{{ route('register') }}" class="mk-mobile-auth-link mk-mobile-auth-link--signup">{{ __('navigation.register') }}</a>
                </div>
            @else
                <a href="{{ route('profile.index') }}">
                    <i class="fa fa-user"></i> {{ __('navigation.profile') }}
                </a>
                @if (!auth()->user()->isAdmin())
                    <a href="{{ route('favorites.index') }}">
                        <i class="fa fa-heart"></i> {{ __('navigation.favorites') }}
                    </a>
                @endif
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fa fa-dashboard"></i> {{ __('navigation.admin') }}
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="mk-mobile-logout">
                        <i class="fa fa-sign-out"></i> {{ __('navigation.logout') }}
                    </button>
                </form>
            @endguest

        </div>
    </div>
</nav>
