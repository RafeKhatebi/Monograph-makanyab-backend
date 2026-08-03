<footer class="mk-footer">
    <div class="container">
        <div class="row">

            {{-- Col 1: Brand + Contact --}}
            <div class="col-md-4 col-sm-6 mk-footer-column">
                <a href="{{ route('home') }}" class="mk-footer-logo">
                    <div class="mk-footer-logo-icon">M</div>
                    <span class="mk-footer-logo-text">Makan<span>yab</span></span>
                </a>
                <p>Your local discovery platform for the best restaurants, cafes, shops, hotels and services in
                    Afghanistan.</p>
                <ul class="mk-footer-contact">
                    <li>
                        <i class="fa fa-map-marker"></i>
                        <span>Herat, Afghanistan</span>
                    </li>
                    <li>
                        <i class="fa fa-phone"></i>
                        <a href="tel:+93712121211" class="mk-footer-contact-link">+93 712 121
                            211</a>
                    </li>
                    <li>
                        <i class="fa fa-envelope"></i>
                        <a href="mailto:info@makanyab.com" class="mk-footer-contact-link">info@makanyab.com</a>
                    </li>
                </ul>
                <div class="mk-footer-social" aria-label="Social links">
                    <a href="{{ route('contact') }}" aria-label="Contact Makanyab about Facebook"><i class="fa fa-facebook"></i></a>
                    <a href="{{ route('contact') }}" aria-label="Contact Makanyab about Instagram"><i class="fa fa-instagram"></i></a>
                    <a href="{{ route('contact') }}" aria-label="Contact Makanyab about Twitter"><i class="fa fa-twitter"></i></a>
                    <a href="{{ route('contact') }}" aria-label="Contact Makanyab about LinkedIn"><i class="fa fa-linkedin"></i></a>
                </div>
            </div>

            {{-- Col 2: Quick Links --}}
            <div class="col-md-2 col-sm-6 mk-footer-column">
                <h5>Explore</h5>
                <ul class="mk-footer-links">
                    <li><a href="{{ route('home') }}"><i class="fa fa-angle-right"></i> Home</a></li>
                    <li><a href="{{ route('places.index') }}"><i class="fa fa-angle-right"></i>Places</a></li>
                    <li><a href="{{ route('categories.index') }}"><i class="fa fa-angle-right"></i> Categories</a></li>
                    <li><a href="{{ route('search.index') }}"><i class="fa fa-angle-right"></i> Search</a></li>
                    <li><a href="{{ route('posts.index') }}"><i class="fa fa-angle-right"></i> Blog</a></li>
                    <li><a href="{{ route('about') }}"><i class="fa fa-angle-right"></i> About</a></li>
                    <li><a href="{{ route('contact') }}"><i class="fa fa-angle-right"></i> Contact</a></li>
                </ul>
            </div>

            {{-- Col 3: Top Categories --}}
            <div class="col-md-2 col-sm-6 mk-footer-column">
                <h5>Categories</h5>
                <ul class="mk-footer-links">
                    <li><a href="{{ route('places.index', ['category' => 'restaurants']) }}"><i
                                class="fa fa-angle-right"></i> Restaurants</a></li>
                    <li><a href="{{ route('places.index', ['category' => 'cafes']) }}"><i class="fa fa-angle-right"></i>
                            Cafes</a></li>
                    <li><a href="{{ route('places.index', ['category' => 'shopping']) }}"><i
                                class="fa fa-angle-right"></i> Shopping</a></li>
                    <li><a href="{{ route('places.index', ['category' => 'hotels']) }}"><i
                                class="fa fa-angle-right"></i> Hotels</a></li>
                    <li><a href="{{ route('services.index') }}"><i class="fa fa-angle-right"></i> Services</a></li>
                    <li><a href="{{ route('categories.index') }}"><i class="fa fa-angle-right"></i> All Categories</a>
                    </li>
                </ul>
            </div>

            {{-- Col 4: Newsletter --}}
            <div class="col-md-4 col-sm-6 mk-footer-column">
                <h5>Stay Updated</h5>
                <p class="mk-footer-update-text">Contact us to get the latest places, deals, and updates.</p>
                <a class="mk-footer-update-link" href="{{ route('contact') }}">
                    Contact Makanyab
                </a>
                <div class="mk-footer-business">
                    <p class="mk-footer-business-title">Own a business?</p>
                    <p class="mk-footer-business-text">List your place and reach thousands of
                        customers.</p>
                    <a href="{{ route('register') }}" class="mk-footer-business-link">
                        Get Listed Free
                    </a>
                </div>
            </div>

        </div>

        {{-- Bottom Bar --}}
        <div class="mk-footer-bottom">
            <p>© {{ date('Y') }} Makanyab. All rights reserved.</p>
            <div class="mk-footer-bottom-links">
                <a href="{{ route('privacy') }}">Privacy Policy</a>
                <a href="{{ route('terms') }}">Terms of Service</a>
                <a href="{{ route('contact') }}">Support</a>
            </div>
        </div>
    </div>
</footer>
