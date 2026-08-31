<footer class="mk-footer">
    <div class="container">
        <div class="row">

            {{-- Col 1: Brand + Contact --}}
            <div class="col-md-4 col-sm-6 mk-footer-column">
                <a href="{{ route('home') }}" class="mk-footer-logo">
                    <img class="mk-footer-logo-img" src="{{ asset('assets/img/branding/makanyab-logo-monochrome.svg') }}" alt="Makanyab">
                </a>
                <p>{{ __('footer.description') }}</p>
                <ul class="mk-footer-contact">
                    <li>
                        <i class="fa fa-map-marker"></i>
                        <span>{{ __('footer.location') }}</span>
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
                <div class="mk-footer-social" aria-label="{{ __('footer.social_links') }}">
                    <a href="{{ route('contact') }}" aria-label="{{ __('contact.facebook') }}"><i class="fa fa-facebook"></i></a>
                    <a href="{{ route('contact') }}" aria-label="{{ __('contact.instagram') }}"><i class="fa fa-instagram"></i></a>
                    <a href="{{ route('contact') }}" aria-label="{{ __('contact.twitter') }}"><i class="fa fa-twitter"></i></a>
                    <a href="{{ route('contact') }}" aria-label="{{ __('footer.linkedin') }}"><i class="fa fa-linkedin"></i></a>
                </div>
            </div>

            {{-- Col 2: Quick Links --}}
            <div class="col-md-2 col-sm-6 mk-footer-column">
                <h5>{{ __('navigation.explore') }}</h5>
                <ul class="mk-footer-links">
                    <li><a href="{{ route('home') }}"><i class="fa fa-angle-right"></i> {{ __('navigation.home') }}</a></li>
                    <li><a href="{{ route('places.index') }}"><i class="fa fa-angle-right"></i> {{ __('navigation.places') }}</a></li>
                    <li><a href="{{ route('categories.index') }}"><i class="fa fa-angle-right"></i> {{ __('navigation.categories') }}</a></li>
                    <li><a href="{{ route('search.index') }}"><i class="fa fa-angle-right"></i> {{ __('navigation.search') }}</a></li>
                    <li><a href="{{ route('posts.index') }}"><i class="fa fa-angle-right"></i> {{ __('navigation.blog') }}</a></li>
                    <li><a href="{{ route('about') }}"><i class="fa fa-angle-right"></i> {{ __('navigation.about') }}</a></li>
                    <li><a href="{{ route('contact') }}"><i class="fa fa-angle-right"></i> {{ __('navigation.contact') }}</a></li>
                </ul>
            </div>

            {{-- Col 3: Top Categories --}}
            <div class="col-md-2 col-sm-6 mk-footer-column">
                <h5>{{ __('navigation.categories') }}</h5>
                <ul class="mk-footer-links">
                    <li><a href="{{ route('places.index', ['category' => 'restaurants']) }}"><i
                                class="fa fa-angle-right"></i> {{ __('footer.categories.restaurants') }}</a></li>
                    <li><a href="{{ route('places.index', ['category' => 'cafes']) }}"><i class="fa fa-angle-right"></i>
                            {{ __('footer.categories.cafes') }}</a></li>
                    <li><a href="{{ route('places.index', ['category' => 'shopping']) }}"><i
                                class="fa fa-angle-right"></i> {{ __('footer.categories.shopping') }}</a></li>
                    <li><a href="{{ route('places.index', ['category' => 'hotels']) }}"><i
                                class="fa fa-angle-right"></i> {{ __('footer.categories.hotels') }}</a></li>
                    <li><a href="{{ route('services.index') }}"><i class="fa fa-angle-right"></i> {{ __('navigation.services') }}</a></li>
                    <li><a href="{{ route('categories.index') }}"><i class="fa fa-angle-right"></i> {{ __('footer.all_categories') }}</a>
                    </li>
                </ul>
            </div>

            {{-- Col 4: Newsletter --}}
            <div class="col-md-4 col-sm-6 mk-footer-column">
                <h5>{{ __('navigation.stay_updated') }}</h5>
                <p class="mk-footer-update-text">{{ __('footer.updates') }}</p>
                <a class="mk-footer-update-link" href="{{ route('contact') }}">
                    {{ __('footer.contact_makanyab') }}
                </a>
                <div class="mk-footer-business">
                    <p class="mk-footer-business-title">{{ __('footer.own_business') }}</p>
                    <p class="mk-footer-business-text">{{ __('footer.list_business') }}</p>
                    <a href="{{ route('add.create') }}" class="mk-footer-business-link">
                        {{ __('footer.get_listed') }}
                    </a>
                </div>
            </div>

        </div>

        {{-- Bottom Bar --}}
        <div class="mk-footer-bottom">
            <p>© {{ \App\Support\LocalizedDate::year(now()) }} Makanyab. {{ __('footer.rights') }}</p>
            <div class="mk-footer-bottom-links">
                <a href="{{ route('privacy') }}">{{ __('footer.privacy') }}</a>
                <a href="{{ route('terms') }}">{{ __('footer.terms') }}</a>
                <a href="{{ route('guides.share') }}">{{ __('footer.how_to_share') }}</a>
                <a href="{{ route('guides.send-places') }}">{{ __('footer.how_to_send_places') }}</a>
                <a href="{{ route('guides.send-services') }}">{{ __('footer.how_to_send_services') }}</a>
                <a href="{{ route('guides.send-posts') }}">{{ __('footer.how_to_send_posts') }}</a>
                <a href="{{ route('contact') }}">{{ __('navigation.support') }}</a>
            </div>
        </div>
    </div>
</footer>
