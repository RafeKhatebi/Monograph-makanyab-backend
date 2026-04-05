@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

    <div class="page-head">
        <div class="container">
            <div class="row">
                <div class="page-head-content">
                    <h1 class="page-title">My Profile</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="properties-area recent-property" style="background-color: #FFF; padding-bottom: 55px;">
        <div class="container">
            <div class="row padding-top-40">

                {{-- Profile Sidebar --}}
                <div class="col-md-3">
                    <div class="dealer-widget">
                        <div class="dealer-content">
                            <div class="inner-wrapper text-center">
                                <img src="{{ auth()->user()->profile_picture
                                    ? asset('storage/' . auth()->user()->profile_picture)
                                    : asset('assets/img/client-face1.png') }}"
                                    class="img-circle" style="width:100px; height:100px; object-fit:cover;" alt="Profile">
                                <h3 style="margin-top:15px;">{{ auth()->user()->name }}</h3>
                                <p class="text-muted">{{ ucfirst(auth()->user()->role) }}</p>
                                <p class="text-muted"><i class="fa fa-envelope"></i> {{ auth()->user()->email }}</p>
                                @if (auth()->user()->phone)
                                    <p class="text-muted"><i class="fa fa-phone"></i> {{ auth()->user()->phone }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default sidebar-menu" style="margin-top:20px;">
                        <div class="panel-heading">
                            <h3 class="panel-title">Navigation</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="footer-menu">
                                <li><a href="#tab-favorites" data-toggle="tab"><i class="fa fa-heart"></i> My Favorites</a>
                                </li>
                                <li><a href="#tab-reviews" data-toggle="tab"><i class="fa fa-star"></i> My Reviews</a></li>
                                <li><a href="#tab-settings" data-toggle="tab"><i class="fa fa-cog"></i> Settings</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Profile Content --}}
                <div class="col-md-9">
                    <div class="tab-content">

                        {{-- Favorites --}}
                        <div id="tab-favorites" class="tab-pane fade in active">
                            <h3>My Favorites</h3>
                            <div class="proerty-th">
                                @forelse($favorites ?? [] as $place)
                                    @include('components.place-card', ['place' => $place])
                                @empty
                                    <p class="text-muted">You haven't saved any places yet.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Reviews --}}
                        <div id="tab-reviews" class="tab-pane fade">
                            <h3>My Reviews</h3>
                            @forelse($reviews ?? [] as $review)
                                @include('components.review-card', [
                                    'review' => $review,
                                    'showPlace' => true,
                                ])
                            @empty
                                <p class="text-muted">You haven't written any reviews yet.</p>
                            @endforelse
                        </div>

                        {{-- Settings --}}
                        <div id="tab-settings" class="tab-pane fade">
                            <h3>Account Settings</h3>
                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name', auth()->user()->name) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input type="text" name="lastname" class="form-control"
                                                value="{{ old('lastname', auth()->user()->lastname) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', auth()->user()->email) }}">
                                </div>

                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ old('phone', auth()->user()->phone) }}">
                                </div>

                                <div class="form-group">
                                    <label>Bio</label>
                                    <textarea name="bio" class="form-control" rows="3">{{ old('bio', auth()->user()->bio) }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
