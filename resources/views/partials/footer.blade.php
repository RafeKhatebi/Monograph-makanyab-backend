<div class="footer-area">
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 wow fadeInRight animated">
                    <div class="single-footer">
                        <h4>About Makanyab</h4>
                        <div class="footer-title-line"></div>
                        <p>Discover the best places around you — restaurants, cafes, shops, and more. Your local guide
                            to everything nearby.</p>
                        <ul class="footer-adress">
                            <li><i class="pe-7s-map-marker strong"></i> Your City, Country</li>
                            <li><i class="pe-7s-mail strong"></i> info@makanyab.com</li>
                            <li><i class="pe-7s-call strong"></i> +1 234 567 7890</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 wow fadeInRight animated">
                    <div class="single-footer">
                        <h4>Quick Links</h4>
                        <div class="footer-title-line"></div>
                        <ul class="footer-menu">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('places.index') }}">Browse Places</a></li>
                            <li><a href="{{ route('categories.index') }}">Categories</a></li>
                            @auth
                                <li><a href="{{ route('favorites.index') }}">My Favorites</a></li>
                                <li><a href="{{ route('profile.index') }}">My Profile</a></li>
                            @else
                                <li><a href="{{ route('login') }}">Login</a></li>
                                <li><a href="{{ route('register') }}">Register</a></li>
                            @endauth
                        </ul>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 wow fadeInRight animated">
                    <div class="single-footer">
                        <h4>Popular Categories</h4>
                        <div class="footer-title-line"></div>
                        <ul class="footer-menu">
                            <li><a href="{{ route('places.index', ['category' => 'restaurants']) }}">Restaurants</a>
                            </li>
                            <li><a href="{{ route('places.index', ['category' => 'cafes']) }}">Cafes</a></li>
                            <li><a href="{{ route('places.index', ['category' => 'shopping']) }}">Shopping</a></li>
                            <li><a href="{{ route('places.index', ['category' => 'hotels']) }}">Hotels</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 wow fadeInRight animated">
                    <div class="single-footer news-letter">
                        <h4>Stay in Touch</h4>
                        <div class="footer-title-line"></div>
                        <p>Subscribe to get updates on new places and features.</p>
                        <form>
                            <div class="input-group">
                                <input class="form-control" type="email" placeholder="Your email...">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary subscribe" type="button">
                                        <i class="pe-7s-paper-plane pe-2x"></i>
                                    </button>
                                </span>
                            </div>
                        </form>
                        <div class="social pull-right">
                            <ul>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-copy text-center">
        <div class="container">
            <div class="row">
                <div class="pull-left">
                    <span>&copy; {{ date('Y') }} Makanyab. All rights reserved.</span>
                </div>
                <div class="bottom-menu pull-right">
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('places.index') }}">Places</a></li>
                        <li><a href="{{ route('categories.index') }}">Categories</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
