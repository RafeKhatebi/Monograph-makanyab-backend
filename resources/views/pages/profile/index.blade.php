@extends('layouts.app')
@section('title', 'My Profile')
@section('content')

    {{-- Header --}}
    <div class="mk-hero">
        <div class="container">
            <div class="profile-header">
                @if (auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="profile-avatar" alt="Profile">
                @else
                    <span class="profile-avatar-placeholder" aria-label="Profile">
                        {{ Str::upper(Str::substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </span>
                @endif
                <div>
                    <h1 class="mk-hero__title">{{ auth()->user()->name }}</h1>
                    <p class="mk-hero__text">{{ ucfirst(auth()->user()->role) }} ·
                        {{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mk-page-section mk-page-section--compact">
        <div class="container">
            <div class="row">

                {{-- Sidebar --}}
                <div class="col-md-3 mk-stack-sm">
                    <div class="mk-card profile-tab-shell">
                        <div id="profile-tabs" class="profile-tabs">
                            <a href="#tab-favorites" data-toggle="tab" class="profile-tab-link active-tab">
                                <i class="fa fa-heart" aria-hidden="true"></i> My Favorites
                            </a>
                            <a href="#tab-reviews" data-toggle="tab" class="profile-tab-link">
                                <i class="fa fa-star" aria-hidden="true"></i> My Reviews
                            </a>
                            <a href="#tab-settings" data-toggle="tab" class="profile-tab-link">
                                <i class="fa fa-cog" aria-hidden="true"></i> Settings
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="col-md-9">
                    <div class="tab-content">

                        {{-- Favorites --}}
                        <div id="tab-favorites" class="tab-pane fade in active">
                            <div class="mk-card profile-panel">
                                <h3 class="mk-heading mk-heading--md">My Favorites</h3>
                                <div class="row">
                                    @forelse($favorites ?? [] as $place)
                                        @include('components.place-card', ['place' => $place])
                                    @empty
                                        <div class="col-md-12 text-center profile-empty">
                                            <div class="mk-empty-icon"><i class="fa fa-heart" aria-hidden="true"></i></div>
                                            <p class="mk-text mk-text--muted">You haven't saved any places yet.</p>
                                            <a href="{{ route('places.index') }}" class="mk-button mk-button--primary mk-button--md">Explore Places</a>
                                        </div>
                                    @endforelse
                                </div>
                                @if ($favoriteServices->isNotEmpty())
                                    <h4 class="mk-heading mk-heading--sm">Saved services</h4>
                                    <div class="row">
                                        @foreach ($favoriteServices as $service)
                                            <div class="col-sm-6 col-md-4 mk-stack-sm">
                                                <x-service-card :service="$service" />
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Reviews --}}
                        <div id="tab-reviews" class="tab-pane fade">
                            <div class="mk-card profile-panel">
                                <h3 class="mk-heading mk-heading--md">My Reviews</h3>
                                @forelse($reviews ?? [] as $review)
                                    @include('components.review-card', [
                                        'review' => $review,
                                        'showPlace' => true,
                                    ])
                                @empty
                                    <div class="text-center profile-empty">
                                        <div class="mk-empty-icon"><i class="fa fa-star" aria-hidden="true"></i></div>
                                        <p class="mk-text mk-text--muted">You haven't written any reviews yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Settings --}}
                        <div id="tab-settings" class="tab-pane fade">
                            <div class="mk-card profile-panel">
                                <h3 class="mk-heading mk-heading--md">Account Settings
                                </h3>
                                @if (session('success'))
                                    <div class="mk-alert mk-alert--success">
                                        {{ session('success') }}</div>
                                @endif
                                @if (session('status'))
                                    <div class="mk-alert mk-alert--success">
                                        {{ session('status') }}</div>
                                @endif
                                @if ($errors->has('social'))
                                    <div class="mk-alert flash-error">
                                        {{ $errors->first('social') }}</div>
                                @endif
                                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div style="margin-bottom:16px;">
                                                <label
                                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">First
                                                    Name</label>
                                                <input type="text" name="name"
                                                    value="{{ old('name', auth()->user()->name) }}" class="form-control"
                                                    style="height:44px;border-radius:8px;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div style="margin-bottom:16px;">
                                                <label
                                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Last
                                                    Name</label>
                                                <input type="text" name="lastname"
                                                    value="{{ old('lastname', auth()->user()->lastname) }}"
                                                    class="form-control" style="height:44px;border-radius:8px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div style="margin-bottom:16px;">
                                        <label
                                            style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Email</label>
                                        <input type="email" name="email"
                                            value="{{ old('email', auth()->user()->email) }}" class="form-control"
                                            style="height:44px;border-radius:8px;">
                                    </div>
                                    <div style="margin-bottom:16px;">
                                        <label
                                            style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Username</label>
                                        <input type="text" name="username"
                                            value="{{ old('username', auth()->user()->username) }}" class="form-control"
                                            style="height:44px;border-radius:8px;">
                                    </div>
                                    <div style="margin-bottom:16px;">
                                        <label
                                            style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Phone</label>
                                        <input type="text" name="phone"
                                            value="{{ old('phone', auth()->user()->phone) }}" class="form-control"
                                            style="height:44px;border-radius:8px;">
                                    </div>
                                    <div style="margin-bottom:20px;">
                                        <label
                                            style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Bio</label>
                                        <textarea name="bio" class="form-control" rows="3" style="border-radius:8px;">{{ old('bio', auth()->user()->bio) }}</textarea>
                                    </div>
                                    <div style="margin-bottom:20px;">
                                        <label for="profile_picture"
                                            style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Profile picture</label>
                                        <input id="profile_picture" type="file" name="profile_picture"
                                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                            class="form-control">
                                        <small style="color:#6B7280;">JPG, PNG, or WebP; maximum 2MB.</small>
                                        @error('profile_picture')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <hr style="margin:24px 0;">
                                    <h4 style="font-size:16px;font-weight:700;color:#111827;margin:0 0 16px;">Change Password
                                    </h4>
                                    <div style="margin-bottom:16px;">
                                        <label
                                            style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Current
                                            Password</label>
                                        <input type="password" name="current_password" class="form-control"
                                            style="height:44px;border-radius:8px;">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div style="margin-bottom:16px;">
                                                <label
                                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">New
                                                    Password</label>
                                                <input type="password" name="password" class="form-control"
                                                    style="height:44px;border-radius:8px;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div style="margin-bottom:16px;">
                                                <label
                                                    style="font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;display:block;">Confirm
                                                    New Password</label>
                                                <input type="password" name="password_confirmation" class="form-control"
                                                    style="height:44px;border-radius:8px;">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit"
                                        style="background:#10B981;color:#fff;border:none;padding:12px 28px;border-radius:10px;font-weight:700;font-size:14px;cursor:pointer;">
                                        Save Changes
                                    </button>
                                </form>
                                <hr style="margin:24px 0;">
                                <h4 style="font-size:16px;font-weight:700;color:#111827;margin:0 0 16px;">Connected Accounts</h4>
                                <div style="display:grid;gap:10px;max-width:360px;">
                                    @foreach (['google' => 'Google', 'facebook' => 'Facebook'] as $provider => $label)
                                        @php
                                            $linked = auth()->user()->socialAccounts()->where('provider', $provider)->exists();
                                        @endphp
                                        <a href="{{ route('social.connect.redirect', $provider) }}"
                                            style="display:flex;align-items:center;justify-content:space-between;border:1px solid #D1D5DB;border-radius:8px;padding:11px 14px;color:#111827;text-decoration:none;font-weight:700;">
                                            <span><i class="fa fa-{{ $provider }}" aria-hidden="true"></i> {{ $label }}</span>
                                            <span style="font-size:12px;color:{{ $linked ? '#065F46' : '#6B7280' }};">{{ $linked ? 'Linked' : 'Connect' }}</span>
                                        </a>
                                        @if ($linked)
                                            <form method="POST" action="{{ route('social.disconnect', $provider) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="border:0;background:transparent;color:#B91C1C;font-size:12px;padding:0 14px;">Disconnect {{ $label }}</button>
                                            </form>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
