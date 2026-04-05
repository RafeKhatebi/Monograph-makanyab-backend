@extends('layouts.app')

@section('title', $place->name)

@section('content')

<div class="page-head">
    <div class="container">
        <div class="row">
            <div class="page-head-content">
                <h1 class="page-title">{{ $place->name }}</h1>
            </div>
        </div>
    </div>
</div>

<div class="content-area single-property" style="background-color: #FCFCFC;">
    <div class="container">
        <div class="clearfix padding-top-40">

            {{-- Main Content --}}
            <div class="col-md-8 single-property-content">

                {{-- Gallery Slider --}}
                <div class="row">
                    <div class="light-slide-item">
                        <div class="clearfix">
                            <div class="favorite-and-print">
                                @auth
                                <form method="POST" action="{{ route('favorites.toggle') }}" id="favorite-form">
                                    @csrf
                                    <input type="hidden" name="place_id" value="{{ $place->id }}">
                                    <button type="submit" class="add-to-fav" id="favorite-btn" title="Add to Favorites" style="background:none;border:none;cursor:pointer;">
                                        <i class="fa fa-star-o"></i>
                                    </button>
                                </form>
                            @endauth
                                <a class="printer-icon" href="javascript:window.print()">
                                    <i class="fa fa-print"></i>
                                </a>
                            </div>

                            @if($place->media->count())
                                <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
                                    @foreach($place->media as $media)
                                        <li data-thumb="{{ asset('storage/' . $media->file_path) }}">
                                            <img src="{{ asset('storage/' . $media->file_path) }}" alt="{{ $place->name }}">
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <img src="{{ asset('assets/img/demo/property-1.jpg') }}" class="img-responsive" alt="{{ $place->name }}">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="single-property-wrapper">

                    {{-- Header --}}
                    <div class="single-property-header">
                        <h1 class="property-title pull-left">{{ $place->name }}</h1>
                        <span class="property-price pull-right">
                            @include('components.rating-stars', ['rating' => $place->avg_rating ?? 0])
                            <small>({{ $place->reviews->count() }} reviews)</small>
                        </span>
                    </div>

                    {{-- Meta --}}
                    <div class="property-meta entry-meta clearfix">
                        <div class="col-xs-3 col-sm-3 col-md-3 p-b-15">
                            <span class="property-info-icon"><i class="pe-7s-map-marker" style="font-size:30px; color:#FFA500;"></i></span>
                            <span class="property-info-entry">
                                <span class="property-info-label">City</span>
                                <span class="property-info-value">{{ $place->city }}</span>
                            </span>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3 p-b-15">
                            <span class="property-info-icon"><i class="pe-7s-tag" style="font-size:30px; color:#FFA500;"></i></span>
                            <span class="property-info-entry">
                                <span class="property-info-label">Category</span>
                                <span class="property-info-value">{{ $place->category->name ?? '—' }}</span>
                            </span>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3 p-b-15">
                            <span class="property-info-icon"><i class="pe-7s-clock" style="font-size:30px; color:#FFA500;"></i></span>
                            <span class="property-info-entry">
                                <span class="property-info-label">Status</span>
                                <span class="property-info-value">
                                    @include('components.status-badge', ['status' => $place->status])
                                </span>
                            </span>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3 p-b-15">
                            <span class="property-info-icon"><i class="pe-7s-cash" style="font-size:30px; color:#FFA500;"></i></span>
                            <span class="property-info-entry">
                                <span class="property-info-label">Price Level</span>
                                <span class="property-info-value">{{ ucfirst($place->price_level) }}</span>
                            </span>
                        </div>
                    </div>

                    {{-- Tabs --}}
                    <ul class="nav nav-tabs" style="margin-top:20px;">
                        <li class="active"><a data-toggle="tab" href="#overview">Overview</a></li>
                        <li><a data-toggle="tab" href="#reviews-tab">Reviews</a></li>
                        <li><a data-toggle="tab" href="#hours-tab">Opening Hours</a></li>
                        <li><a data-toggle="tab" href="#contact-tab">Contact</a></li>
                    </ul>

                    <div class="tab-content" style="padding-top:20px;">

                        {{-- Overview Tab --}}
                        <div id="overview" class="tab-pane fade in active">
                            <div class="section">
                                <h4 class="s-property-title">Description</h4>
                                <div class="s-property-content">
                                    <p>{{ $place->description ?? 'No description available.' }}</p>
                                </div>
                            </div>
                            <div class="section additional-details">
                                <h4 class="s-property-title">Details</h4>
                                <ul class="additional-details-list clearfix">
                                    <li>
                                        <span class="col-xs-4 add-d-title">Address</span>
                                        <span class="col-xs-8 add-d-entry">{{ $place->address }}, {{ $place->district }}, {{ $place->city }}</span>
                                    </li>
                                    @if($place->postal_code)
                                        <li>
                                            <span class="col-xs-4 add-d-title">Postal Code</span>
                                            <span class="col-xs-8 add-d-entry">{{ $place->postal_code }}</span>
                                        </li>
                                    @endif
                                    @if($place->tagline)
                                        <li>
                                            <span class="col-xs-4 add-d-title">Tagline</span>
                                            <span class="col-xs-8 add-d-entry">{{ $place->tagline }}</span>
                                        </li>
                                    @endif
                                    <li>
                                        <span class="col-xs-4 add-d-title">Verified</span>
                                        <span class="col-xs-8 add-d-entry">
                                            {!! $place->is_verified ? '<i class="fa fa-check-circle" style="color:green;"></i> Yes' : '<i class="fa fa-times-circle" style="color:gray;"></i> No' !!}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- Reviews Tab --}}
                        <div id="reviews-tab" class="tab-pane fade">
                            <h4 class="s-property-title">
                                Reviews
                                <small class="text-muted">({{ $place->reviews->count() }})</small>
                            </h4>

                            @forelse($place->reviews as $review)
                                @include('components.review-card', ['review' => $review])
                            @empty
                                <p class="text-muted">No reviews yet. Be the first!</p>
                            @endforelse

                            @auth
                                <div class="section" style="margin-top:30px;">
                                    <h4 class="s-property-title">Write a Review</h4>
                                    <form action="{{ route('places.reviews.store', $place) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Rating</label>
                                            <select name="rating" class="form-control" style="width:auto;">
                                                @for($i = 5; $i >= 1; $i--)
                                                    <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Comment</label>
                                            <textarea name="comment" class="form-control" rows="4" placeholder="Share your experience..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Review</button>
                                    </form>
                                </div>
                            @else
                                <p><a href="{{ route('login') }}">Login</a> to write a review.</p>
                            @endauth
                        </div>

                        {{-- Opening Hours Tab --}}
                        <div id="hours-tab" class="tab-pane fade">
                            <h4 class="s-property-title">Opening Hours</h4>
                            @php $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']; @endphp
                            <table class="table table-striped">
                                <tbody>
                                    @foreach($place->openingHours->sortBy('day_of_week') as $hour)
                                        <tr>
                                            <td><strong>{{ $days[$hour->day_of_week] ?? '—' }}</strong></td>
                                            <td>
                                                @if($hour->is_closed)
                                                    <span class="label label-danger">Closed</span>
                                                @else
                                                    {{ $hour->open_time }} – {{ $hour->close_time }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Contact Tab --}}
                        <div id="contact-tab" class="tab-pane fade">
                            <h4 class="s-property-title">Contact</h4>
                            <ul class="additional-details-list clearfix">
                                <li>
                                    <span class="col-xs-4 add-d-title"><i class="fa fa-phone"></i> Phone</span>
                                    <span class="col-xs-8 add-d-entry">
                                        <a href="tel:{{ $place->phone_1 }}">{{ $place->phone_1 }}</a>
                                    </span>
                                </li>
                                @if($place->phone_2)
                                    <li>
                                        <span class="col-xs-4 add-d-title"><i class="fa fa-phone"></i> Phone 2</span>
                                        <span class="col-xs-8 add-d-entry"><a href="tel:{{ $place->phone_2 }}">{{ $place->phone_2 }}</a></span>
                                    </li>
                                @endif
                                @if($place->whatsapp)
                                    <li>
                                        <span class="col-xs-4 add-d-title"><i class="fa fa-whatsapp"></i> WhatsApp</span>
                                        <span class="col-xs-8 add-d-entry">
                                            <a href="https://wa.me/{{ $place->whatsapp }}" target="_blank">{{ $place->whatsapp }}</a>
                                        </span>
                                    </li>
                                @endif
                                @if($place->website)
                                    <li>
                                        <span class="col-xs-4 add-d-title"><i class="fa fa-globe"></i> Website</span>
                                        <span class="col-xs-8 add-d-entry">
                                            <a href="{{ $place->website }}" target="_blank">{{ $place->website }}</a>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-md-4 p0">
                <aside class="sidebar sidebar-property blog-asside-right">

                    {{-- Owner Info --}}
                    <div class="dealer-widget">
                        <div class="dealer-content">
                            <div class="inner-wrapper">
                                <div class="clear">
                                    <div class="col-xs-4 col-sm-4 dealer-face">
                                        <img src="{{ asset('assets/img/client-face1.png') }}" class="img-circle" alt="Owner">
                                    </div>
                                    <div class="col-xs-8 col-sm-8">
                                        <h3 class="dealer-name">
                                            <a href="#">{{ $place->user->name ?? 'Owner' }}</a>
                                            <span>Place Owner</span>
                                        </h3>
                                    </div>
                                </div>
                                <div class="clear">
                                    <ul class="dealer-contacts">
                                        <li><i class="pe-7s-map-marker strong"></i> {{ $place->city }}, {{ $place->country }}</li>
                                        <li><i class="pe-7s-call strong"></i> {{ $place->phone_1 }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Similar Places --}}
                    @if(isset($similarPlaces) && $similarPlaces->count())
                        <div class="panel panel-default sidebar-menu similar-property-wdg wow fadeInRight animated">
                            <div class="panel-heading">
                                <h3 class="panel-title">Similar Places</h3>
                            </div>
                            <div class="panel-body recent-property-widget">
                                <ul>
                                    @foreach($similarPlaces as $similar)
                                        <li>
                                            <div class="col-md-3 col-sm-3 col-xs-3 blg-thumb p0">
                                                <a href="{{ route('places.show', $similar) }}">
                                                    <img src="{{ asset('assets/img/demo/small-property-1.jpg') }}" alt="{{ $similar->name }}">
                                                </a>
                                            </div>
                                            <div class="col-md-8 col-sm-8 col-xs-8 blg-entry">
                                                <h6><a href="{{ route('places.show', $similar) }}">{{ $similar->name }}</a></h6>
                                                <span class="property-price">{{ $similar->city }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                </aside>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        @if($place->media->count())
        $('#image-gallery').lightSlider({
            gallery: true,
            item: 1,
            thumbItem: 4,
            slideMargin: 0,
            speed: 500,
            auto: true,
            loop: true,
            onSliderLoad: function () {
                $('#image-gallery').removeClass('cS-hidden');
            }
        });
        @endif
    });
</script>
@endpush
