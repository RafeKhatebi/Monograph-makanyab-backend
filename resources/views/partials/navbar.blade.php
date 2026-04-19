<nav class="navbar navbar-default makanyab-header">
    <div class="container">
        <!-- BRAND: Left -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">
                <span class="brand-text">Makanyab</span>
            </a>
        </div>

        <div class="collapse navbar-collapse" id="navigation">
            <!-- MENU: Center -->
            <ul class="nav navbar-nav navbar-center">
                <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a></li>
                <li class="{{ request()->routeIs('categories.*') ? 'active' : '' }}"><a
                        href="{{ route('categories.index') }}">Categories</a></li>
                <li class="{{ request()->routeIs('places.*') ? 'active' : '' }}"><a
                        href="{{ route('places.index') }}">Places</a></li>
                      <li class=""><a
                        href="">Search</a></li>  
                        <li class=""><a
                        href="">Posts</a></li>  
                <li class="{{ request()->routeIs('about') ? 'active' : '' }}"><a href="{{ route('about') }}">About</a>
                </li>
                <li class="{{ request()->routeIs('contact') ? 'active' : '' }}"><a href="{{ route('contact') }}">Contact</a>
                </li>
                @auth
                    @if (!auth()->user()->isAdmin())
                        <li><a href="{{ route('favorites.index') }}" title="Favorites"><i class="fa fa-heart-o"></i></a>
                        </li>
                    @endif
                @endauth
            </ul>

            <!-- AUTH: Right -->
            <ul class="nav navbar-nav navbar-right auth-section">
                @guest
                    <li><a href="{{ route('login') }}" class="login-link">Login</a></li>
                    <li class="signup-btn-wrapper">
                        <a href="{{ route('register') }}" class="btn btn-mizban-primary">Sign Up</a>
                    </li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle user-name" data-toggle="dropdown">
                            {{ auth()->user()->name }} <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('profile.index') }}"><i class="fa fa-user"></i> Profile</a></li>
                            @if (auth()->user()->isAdmin())
                                <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a>
                                </li>
                            @endif
                            <li class="divider"></li>
                            <li>
                                <a href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">@csrf</form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
