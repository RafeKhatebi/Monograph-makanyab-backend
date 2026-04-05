<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">
                <strong>Makanyab</strong>
            </a>
        </div>

        <div class="collapse navbar-collapse yamm" id="navigation">
            <div class="button navbar-right">
                @guest
                    <a href="{{ route('login') }}" class="navbar-btn nav-button wow bounceInRight login" data-wow-delay="0.4s">Login</a>
                    <a href="{{ route('register') }}" class="navbar-btn nav-button wow fadeInRight" data-wow-delay="0.5s">Register</a>
                @else
                    <a href="{{ route('profile.index') }}" class="navbar-btn nav-button wow bounceInRight login" data-wow-delay="0.4s">
                        {{ auth()->user()->name }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="navbar-btn nav-button wow fadeInRight" data-wow-delay="0.5s">Logout</button>
                    </form>
                @endguest
            </div>

            <ul class="main-nav nav navbar-nav navbar-right">
                <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                </li>
                <li class="{{ request()->routeIs('places.*') ? 'active' : '' }}">
                    <a href="{{ route('places.index') }}" class="{{ request()->routeIs('places.*') ? 'active' : '' }}">Places</a>
                </li>
                <li class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">Categories</a>
                </li>
                @auth
                    @if(auth()->user()->isAdmin())
                        <li class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">
                                <i class="fa fa-dashboard"></i> Admin
                            </a>
                        </li>
                    @endif
                    <li class="{{ request()->routeIs('favorites.*') ? 'active' : '' }}">
                        <a href="{{ route('favorites.index') }}" class="{{ request()->routeIs('favorites.*') ? 'active' : '' }}">
                            <i class="fa fa-heart-o"></i> Favorites
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <a href="{{ route('profile.index') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">Profile</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
