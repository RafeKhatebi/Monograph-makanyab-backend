@extends('layouts.app')

@section('title', 'Home')

@section('content')

    {{-- Hero Slider --}}
    <div class="slider-area">
        <div class="slider">
            <div id="bg-slider" class="owl-carousel owl-theme">
                <div class="item"><img src="{{ asset('assets/img/slide1/slider-image-1.jpg') }}" alt="Discover Places"></div>
                <div class="item"><img src="{{ asset('assets/img/slide1/slider-image-2.jpg') }}" alt="Find Nearby"></div>
                <div class="item"><img src="{{ asset('assets/img/slide1/slider-image-4.jpg') }}" alt="Explore More"></div>
            </div>
        </div>
        <div class="slider-content">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12">
                    <h2>Discover the Best Places Near You</h2>
                    <p>Find restaurants, cafes, shops, hotels and more — all in one place.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Featured Places --}}
    <div class="content-area home-area-1 recent-property" style="background-color: #FCFCFC; padding-bottom: 55px;">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1 col-sm-12 text-center page-title">
                    <h2>Featured Places</h2>
                    <p>Handpicked top-rated places loved by our community.</p>
                </div>
            </div>
            <div class="row">
                <div class="proerty-th">
                    @forelse($featuredPlaces ?? [] as $place)
                        @include('components.place-card', ['place' => $place])
                    @empty
                        {{-- Placeholder cards using demo images --}}
                        @for ($i = 1; $i <= 7; $i++)
                            <div class="col-sm-6 col-md-3 p0">
                                <div class="box-two proerty-item">
                                    <div class="item-thumb">
                                        <a href="#">
                                            <img src="{{ asset('assets/img/demo/property-' . (($i % 6) + 1) . '.jpg') }}"
                                                alt="Place">
                                        </a>
                                    </div>
                                    <div class="item-entry overflow">
                                        <h5><a href="#">Sample Place {{ $i }}</a></h5>
                                        <div class="dot-hr"></div>
                                        <span class="pull-left"><i class="fa fa-map-marker"></i> City</span>
                                        <span class="proerty-price pull-right">
                                            <i class="fa fa-star"></i> 4.5
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    @endforelse

                    <div class="col-sm-6 col-md-3 p0">
                        <div class="box-tree more-proerty text-center">
                            <div class="item-tree-icon"><i class="fa fa-th"></i></div>
                            <div class="more-entry overflow">
                                <h5><a href="{{ route('places.index') }}">See More Places</a></h5>
                                <h5 class="tree-sub-ttl">Browse all listings</h5>
                                <a href="{{ route('places.index') }}" class="btn border-btn more-black">All Places</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Categories --}}
    <div class="content-area home-area-2 property-listing" style="background-color: #ffffff; padding: 55px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1 col-sm-12 text-center page-title">
                    <h2>Browse by Category</h2>
                    <p>Explore popular categories and the number of active places in each.</p>
                </div>
            </div>
            <div class="row">
                @forelse($categories ?? [] as $category)
                    <div class="col-sm-6 col-md-4">
                        <div class="box-two category-item text-center p0">
                            <div class="item-thumb">
                                <div class="category-icon" style="font-size: 36px; margin: 20px 0; color: {{ $category->color_code ?? '#333' }};">
                                    <i class="fa {{ $category->icon_name ?? 'fa-folder' }}"></i>
                                </div>
                            </div>
                            <div class="item-entry overflow">
                                <h5><a href="{{ route('categories.index') }}">{{ $category->name }}</a></h5>
                                <div class="dot-hr"></div>
                                <span class="pull-left"><i class="fa fa-building-o"></i> {{ $category->places_count }} places</span>
                                <span class="pull-right"><a href="{{ route('categories.index') }}" class="btn border-btn more-black">View</a></span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-sm-12 text-center">
                        <p>No categories available yet. Please check back soon.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="boy-sale-area">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-10 col-sm-offset-1 col-md-offset-0 col-xs-12">
                    <div class="asks-first">
                        <div class="asks-first-circle"><span class="fa fa-search"></span></div>
                        <div class="asks-first-info">
                            <h2>Looking for a Place?</h2>
                            <p>Search thousands of places by category, location, and rating.</p>
                        </div>
                        <div class="asks-first-arrow">
                            <a href="{{ route('places.index') }}"><span class="fa fa-angle-right"></span></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-10 col-sm-offset-1 col-xs-12 col-md-offset-0">
                    <div class="asks-first">
                        <div class="asks-first-circle"><span class="fa fa-plus"></span></div>
                        <div class="asks-first-info">
                            <h2>Own a Business?</h2>
                            <p>List your place and reach thousands of potential customers.</p>
                        </div>
                        <div class="asks-first-arrow">
                            <a href="{{ route('register') }}"><span class="fa fa-angle-right"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#bg-slider').owlCarousel({
                items: 1,
                autoPlay: true,
                navigation: false,
                pagination: false,
                singleItem: true,
            });
        });
    </script>
@endpush
