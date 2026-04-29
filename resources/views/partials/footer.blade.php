<footer class="mk-footer">
    <div class="container">
        <div class="row">

            {{-- Col 1: Brand + Contact --}}
            <div class="col-md-4 col-sm-6" style="margin-bottom:32px;">
                <a href="{{ route('home') }}" class="mk-footer-logo">
                    <div class="mk-footer-logo-icon">M</div>
                    <span class="mk-footer-logo-text">Makan<span>yab</span></span>
                </a>
                <p>Your local discovery platform for the best restaurants, cafes, shops, hotels and services in
                    Afghanistan.</p>
                <ul class="mk-footer-contact" style="margin-top:20px;">
                    <li>
                        <i class="fa fa-map-marker"></i>
                        <span>Herat, Afghanistan</span>
                    </li>
                    <li>
                        <i class="fa fa-phone"></i>
                        <a href="tel:+93728958411" style="color:#9CA3AF;text-decoration:none;transition:color .2s;"
                            onmouseover="this.style.color='#10B981'" onmouseout="this.style.color='#9CA3AF'">+93 728 958
                            411</a>
                    </li>
                    <li>
                        <i class="fa fa-envelope"></i>
                        <a href="mailto:info@makanyab.com"
                            style="color:#9CA3AF;text-decoration:none;transition:color .2s;"
                            onmouseover="this.style.color='#10B981'"
                            onmouseout="this.style.color='#9CA3AF'">info@makanyab.com</a>
                    </li>
                </ul>
                <div class="mk-footer-social">
                    <a href="#" title="Facebook"><i class="fa fa-facebook"></i></a>
                    <a href="#" title="Instagram"><i class="fa fa-instagram"></i></a>
                    <a href="#" title="Twitter"><i class="fa fa-twitter"></i></a>
                    <a href="#" title="LinkedIn"><i class="fa fa-linkedin"></i></a>
                </div>
            </div>

            {{-- Col 2: Quick Links --}}
            <div class="col-md-2 col-sm-6" style="margin-bottom:32px;">
                <h5>Explore</h5>
                <ul class="mk-footer-links">
                    <li><a href="{{ route('home') }}"><i class="fa fa-angle-right"></i> Home</a></li>
                    <li><a href="{{ route('places.index') }}"><i class="fa fa-angle-right"></i> Browse Places</a></li>
                    <li><a href="{{ route('categories.index') }}"><i class="fa fa-angle-right"></i> Categories</a></li>
                    <li><a href="{{ route('search.index') }}"><i class="fa fa-angle-right"></i> Search</a></li>
                    <li><a href="{{ route('posts.index') }}"><i class="fa fa-angle-right"></i> Blog</a></li>
                    <li><a href="{{ route('about') }}"><i class="fa fa-angle-right"></i> About Us</a></li>
                    <li><a href="{{ route('contact') }}"><i class="fa fa-angle-right"></i> Contact</a></li>
                </ul>
            </div>

            {{-- Col 3: Top Categories --}}
            <div class="col-md-2 col-sm-6" style="margin-bottom:32px;">
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
                    <li><a href="{{ route('places.index', ['category' => 'services']) }}"><i
                                class="fa fa-angle-right"></i> Services</a></li>
                    <li><a href="{{ route('categories.index') }}"><i class="fa fa-angle-right"></i> All Categories</a>
                    </li>
                </ul>
            </div>

            {{-- Col 4: Newsletter --}}
            <div class="col-md-4 col-sm-6" style="margin-bottom:32px;">
                <h5>Stay Updated</h5>
                <p style="margin-bottom:16px;">Subscribe to get the latest places, deals and updates delivered to your
                    inbox.</p>
                <div class="mk-footer-newsletter">
                    <form onsubmit="return false;">
                        <input type="email" placeholder="Your email address">
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
                <div style="margin-top:24px;padding:16px;background:#1F2937;border-radius:12px;">
                    <p style="margin:0 0 10px;font-size:13px;color:#D1D5DB;font-weight:600;">Own a business?</p>
                    <p style="margin:0 0 12px;font-size:13px;color:#9CA3AF;">List your place and reach thousands of
                        customers.</p>
                    <a href="{{ route('register') }}"
                        style="display:inline-block;background:#10B981;color:#fff;padding:8px 18px;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;">
                        Get Listed Free
                    </a>
                </div>
            </div>

        </div>

        {{-- Bottom Bar --}}
        <div class="mk-footer-bottom">
            <p>© {{ date('Y') }} Makanyab. All rights reserved.</p>
            <div class="mk-footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="{{ route('contact') }}">Support</a>
            </div>
        </div>
    </div>
</footer>
