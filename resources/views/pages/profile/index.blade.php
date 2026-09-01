@extends('layouts.app')
@section('title', __('profile.title'))
@section('content')

    {{-- Header --}}
    <div class="mk-hero">
        <div class="container">
            <div class="profile-header">
                @if (auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="profile-avatar" alt="{{ __('profile.title') }}">
                @else
                    <span class="profile-avatar-placeholder" aria-label="{{ __('profile.title') }}">
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
                                <i class="fa fa-heart" aria-hidden="true"></i> {{ __('profile.favorites') }}
                            </a>
                            <a href="#tab-reviews" data-toggle="tab" class="profile-tab-link">
                                <i class="fa fa-star" aria-hidden="true"></i> {{ __('profile.reviews') }}
                            </a>
                            <a href="#tab-settings" data-toggle="tab" class="profile-tab-link">
                                <i class="fa fa-cog" aria-hidden="true"></i> {{ __('profile.settings') }}
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
                                <h3 class="mk-heading mk-heading--md">{{ __('profile.favorites') }}</h3>
                                <div class="row">
                                    @forelse($favorites ?? [] as $place)
                                        @include('components.place-card', ['place' => $place])
                                    @empty
                                        <div class="col-md-12 text-center profile-empty">
                                            <div class="mk-empty-icon"><i class="fa fa-heart" aria-hidden="true"></i></div>
                                            <p class="mk-text mk-text--muted">{{ __('profile.empty_places') }}</p>
                                            <a href="{{ route('places.index') }}" class="mk-button mk-button--primary mk-button--md">{{ __('profile.explore_places') }}</a>
                                        </div>
                                    @endforelse
                                </div>
                                @if ($favoriteServices->isNotEmpty())
                                    <h4 class="mk-heading mk-heading--sm">{{ __('profile.saved_services') }}</h4>
                                    <div class="row">
                                        @foreach ($favoriteServices as $service)
                                            <x-service-card :service="$service" />
                                        @endforeach
                                    </div>
                                @endif
                                @if (($favorites ?? collect())->isNotEmpty() || $favoriteServices->isNotEmpty())
                                    <a href="{{ route('favorites.index') }}" class="mk-button mk-button--secondary mk-button--md">
                                        {{ __('profile.view_all_favorites') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Reviews --}}
                        <div id="tab-reviews" class="tab-pane fade">
                            <div class="mk-card profile-panel">
                                <h3 class="mk-heading mk-heading--md">{{ __('profile.reviews') }}</h3>
                                @forelse($reviews ?? [] as $review)
                                    @include('components.review-card', [
                                        'review' => $review,
                                        'showPlace' => true,
                                    ])
                                @empty
                                    <div class="text-center profile-empty">
                                        <div class="mk-empty-icon"><i class="fa fa-star" aria-hidden="true"></i></div>
                                        <p class="mk-text mk-text--muted">{{ __('profile.empty_reviews') }}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Settings --}}
                        <div id="tab-settings" class="tab-pane fade">
                            <div class="mk-card profile-panel">
                                <h3 class="mk-heading mk-heading--md">{{ __('profile.account_settings') }}
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
                                            <div class="profile-form-group">
                                                <label class="mk-label">{{ __('profile.first_name') }}</label>
                                                <input type="text" name="name"
                                                    value="{{ old('name', auth()->user()->name) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="profile-form-group">
                                                <label class="mk-label">{{ __('profile.last_name') }}</label>
                                                <input type="text" name="lastname"
                                                    value="{{ old('lastname', auth()->user()->lastname) }}"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="profile-form-group">
                                        <label class="mk-label">{{ __('profile.email') }}</label>
                                        <input type="email" name="email"
                                            value="{{ old('email', auth()->user()->email) }}" class="form-control">
                                    </div>
                                    <div class="profile-form-group">
                                        <label class="mk-label">{{ __('profile.username') }}</label>
                                        <input type="text" name="username"
                                            value="{{ old('username', auth()->user()->username) }}" class="form-control">
                                    </div>
                                    <div class="profile-form-group">
                                        <label class="mk-label">{{ __('profile.phone') }}</label>
                                        <input type="text" name="phone"
                                            value="{{ old('phone', auth()->user()->phone) }}" class="form-control">
                                    </div>
                                    <div class="profile-form-group">
                                        <label class="mk-label">{{ __('profile.bio') }}</label>
                                        <textarea name="bio" class="form-control" rows="3">{{ old('bio', auth()->user()->bio) }}</textarea>
                                    </div>
                                    <div class="profile-form-group">
                                        <label for="profile_picture" class="mk-label">{{ __('profile.picture') }}</label>
                                        <input id="profile_picture" type="file" name="profile_picture"
                                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                            class="form-control">
                                        <small class="profile-help">{{ __('profile.picture_help') }}</small>
                                        @error('profile_picture')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <hr class="profile-divider">
                                    <h4 class="profile-section-title">{{ __('profile.change_password') }}
                                    </h4>
                                    <div class="profile-form-group">
                                        <label class="mk-label">{{ __('profile.current_password') }}</label>
                                        <input type="password" name="current_password" class="form-control">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="profile-form-group">
                                                <label class="mk-label">{{ __('profile.new_password') }}</label>
                                                <input type="password" name="password" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="profile-form-group">
                                                <label class="mk-label">{{ __('profile.confirm_password') }}</label>
                                                <input type="password" name="password_confirmation" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="mk-btn mk-btn-primary">
                                        {{ __('profile.save_changes') }}
                                    </button>
                                </form>
                                <hr class="profile-divider">
                                <h4 class="profile-section-title">{{ __('profile.connected_accounts') }}</h4>
                                <div class="profile-social-grid">
                                    @foreach (['google' => 'Google', 'facebook' => 'Facebook'] as $provider => $label)
                                        @php
                                            $linked = auth()->user()->socialAccounts()->where('provider', $provider)->exists();
                                        @endphp
                                        <a href="{{ route('social.connect.redirect', $provider) }}" class="profile-social-link">
                                            <span><i class="fa fa-{{ $provider }}" aria-hidden="true"></i> {{ $label }}</span>
                                            <span class="profile-social-status {{ $linked ? 'is-linked' : '' }}">{{ $linked ? __('profile.linked') : __('profile.connect') }}</span>
                                        </a>
                                        @if ($linked)
                                            <form method="POST" action="{{ route('social.disconnect', $provider) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="profile-social-disconnect">{{ __('profile.disconnect') }} {{ $label }}</button>
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
