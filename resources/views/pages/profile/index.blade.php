@extends('layouts.app')
@section('title', __('profile.title'))
@section('content')
    @php
        $role = auth()->user()->role;
        $roleLabel = __('profile.roles.' . $role);
        $roleLabel = $roleLabel === 'profile.roles.' . $role ? ucfirst($role) : $roleLabel;
    @endphp

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
                    <p class="mk-hero__text profile-header-meta">
                        <span>{{ $roleLabel }}</span>
                        <span aria-hidden="true">·</span>
                        <span dir="ltr">{{ auth()->user()->email }}</span>
                    </p>
                    <div class="profile-header-actions">
                        <span class="profile-email-status is-verified">
                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                            {{ __('common.verified') }}
                        </span>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="mk-button mk-button--secondary mk-button--sm">
                                <i class="fa fa-dashboard" aria-hidden="true"></i>
                                {{ __('admin.panel') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mk-page-section mk-page-section--compact">
        <div class="container">
            <div class="row profile-layout-row">

                {{-- Sidebar --}}
                <div class="col-md-3 mk-stack-sm profile-sidebar-col">
                    <div class="mk-card profile-tab-shell">
                        <div id="profile-tabs" class="profile-tabs" role="tablist" aria-label="{{ __('profile.title') }}">
                            <a id="profile-tab-submissions" href="#tab-submissions" data-toggle="tab" class="profile-tab-link active-tab" role="tab" aria-controls="tab-submissions" aria-selected="true">
                                <i class="fa fa-inbox" aria-hidden="true"></i>
                                <span>{{ __('profile.submissions') }}</span>
                            </a>
                            <a id="profile-tab-favorites" href="#tab-favorites" data-toggle="tab" class="profile-tab-link" role="tab" aria-controls="tab-favorites" aria-selected="false">
                                <i class="fa fa-heart" aria-hidden="true"></i>
                                <span>{{ __('profile.favorites') }}</span>
                            </a>
                            <a id="profile-tab-reviews" href="#tab-reviews" data-toggle="tab" class="profile-tab-link" role="tab" aria-controls="tab-reviews" aria-selected="false">
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <span>{{ __('profile.reviews') }}</span>
                            </a>
                            <a id="profile-tab-settings" href="#tab-settings" data-toggle="tab" class="profile-tab-link" role="tab" aria-controls="tab-settings" aria-selected="false">
                                <i class="fa fa-cog" aria-hidden="true"></i>
                                <span>{{ __('profile.settings') }}</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="col-md-9">
                    <div class="tab-content">
                        {{-- Submissions --}}
                        <div id="tab-submissions" class="tab-pane fade in active" role="tabpanel" aria-labelledby="profile-tab-submissions">
                            <div class="mk-card profile-panel">
                                <h3 class="mk-heading mk-heading--md">{{ __('profile.submissions') }}</h3>
                                @forelse($submissions ?? [] as $submission)
                                    <div class="profile-submission-item">
                                        <div>
                                            <div class="profile-submission-meta">
                                                <span>{{ $submission['type'] }}</span>
                                                @if ($submission['category'])
                                                    <span>{{ $submission['category'] }}</span>
                                                @endif
                                            </div>
                                            <h4>{{ $submission['title'] }}</h4>
                                            <p>{{ \App\Support\LocalizedDate::date($submission['date']) }}</p>
                                        </div>
                                        <div class="profile-submission-actions">
                                            <span class="profile-status-pill">{{ $submission['status'] }}</span>
                                            <a href="{{ $submission['preview_url'] }}" class="mk-button mk-button--secondary mk-button--sm">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                <span>{{ __('suggestions.preview_card') }}</span>
                                            </a>
                                            @if ($submission['can_edit'] ?? false)
                                                <a href="{{ $submission['edit_url'] }}" class="mk-button mk-button--secondary mk-button--sm">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                    <span>{{ __('profile.edit_submission') }}</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center profile-empty">
                                        <div class="mk-empty-icon"><i class="fa fa-inbox" aria-hidden="true"></i></div>
                                        <p class="mk-text mk-text--muted">{{ __('profile.empty_submissions') }}</p>
                                        <a href="{{ route('add.create') }}" class="mk-button mk-button--primary mk-button--md">{{ __('profile.add_submission') }}</a>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Favorites --}}
                        <div id="tab-favorites" class="tab-pane fade" role="tabpanel" aria-labelledby="profile-tab-favorites">
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
                                @if (($favoritePosts ?? collect())->isNotEmpty())
                                    <h4 class="mk-heading mk-heading--sm">{{ __('profile.saved_posts') }}</h4>
                                    <div class="profile-post-list">
                                        @foreach ($favoritePosts as $post)
                                            <a href="{{ route('posts.show', $post->slug) }}" class="profile-post-link">
                                                <strong>{{ $post->title }}</strong>
                                                <span>{{ \App\Support\LocalizedDate::date($post->published_at ?? $post->created_at) }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                                @if (($favorites ?? collect())->isNotEmpty() || $favoriteServices->isNotEmpty() || ($favoritePosts ?? collect())->isNotEmpty())
                                    <a href="{{ route('favorites.index') }}" class="mk-button mk-button--secondary mk-button--md">
                                        {{ __('profile.view_all_favorites') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Reviews --}}
                        <div id="tab-reviews" class="tab-pane fade" role="tabpanel" aria-labelledby="profile-tab-reviews">
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
                        <div id="tab-settings" class="tab-pane fade" role="tabpanel" aria-labelledby="profile-tab-settings">
                            <div class="mk-card profile-panel">
                                <h3 class="mk-heading mk-heading--md">{{ __('profile.account_settings') }}
                                </h3>
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
                                                <label for="profile-name" class="mk-label">{{ __('profile.first_name') }} <span aria-hidden="true">*</span></label>
                                                <input id="profile-name" type="text" name="name" autocomplete="given-name" required
                                                    value="{{ old('name', auth()->user()->name) }}" class="form-control @error('name') is-invalid @enderror">
                                                <x-input-error :messages="$errors->get('name')" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="profile-form-group">
                                                <label for="profile-lastname" class="mk-label">{{ __('profile.last_name') }}</label>
                                                <input id="profile-lastname" type="text" name="lastname" autocomplete="family-name"
                                                    value="{{ old('lastname', auth()->user()->lastname) }}"
                                                    class="form-control @error('lastname') is-invalid @enderror">
                                                <x-input-error :messages="$errors->get('lastname')" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="profile-form-group">
                                        <label for="profile-email" class="mk-label">{{ __('profile.email') }} <span aria-hidden="true">*</span></label>
                                        <input id="profile-email" type="email" name="email" autocomplete="email" required
                                            value="{{ old('email', auth()->user()->email) }}" class="form-control @error('email') is-invalid @enderror">
                                        <x-input-error :messages="$errors->get('email')" />
                                    </div>
                                    <div class="profile-form-group">
                                        <label for="profile-username" class="mk-label">{{ __('profile.username') }} <span aria-hidden="true">*</span></label>
                                        <input id="profile-username" type="text" name="username" autocomplete="username" required
                                            value="{{ old('username', auth()->user()->username) }}" class="form-control @error('username') is-invalid @enderror">
                                        <x-input-error :messages="$errors->get('username')" />
                                    </div>
                                    <div class="profile-form-group">
                                        <label for="profile-phone" class="mk-label">{{ __('profile.phone') }}</label>
                                        <input id="profile-phone" type="tel" name="phone" autocomplete="tel"
                                            value="{{ old('phone', auth()->user()->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                                        <x-input-error :messages="$errors->get('phone')" />
                                    </div>
                                    <div class="profile-form-group">
                                        <label for="profile-bio" class="mk-label">{{ __('profile.bio') }}</label>
                                        <textarea id="profile-bio" name="bio" class="form-control @error('bio') is-invalid @enderror" rows="3">{{ old('bio', auth()->user()->bio) }}</textarea>
                                        <x-input-error :messages="$errors->get('bio')" />
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
                                        <label for="profile-current-password" class="mk-label">{{ __('profile.current_password') }}</label>
                                        <input id="profile-current-password" type="password" name="current_password" autocomplete="current-password" class="form-control @error('current_password') is-invalid @enderror">
                                        <x-input-error :messages="$errors->get('current_password')" />
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="profile-form-group">
                                                <label for="profile-password" class="mk-label">{{ __('profile.new_password') }}</label>
                                                <input id="profile-password" type="password" name="password" autocomplete="new-password" class="form-control @error('password') is-invalid @enderror">
                                                <x-input-error :messages="$errors->get('password')" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="profile-form-group">
                                                <label for="profile-password-confirmation" class="mk-label">{{ __('profile.confirm_password') }}</label>
                                                <input id="profile-password-confirmation" type="password" name="password_confirmation" autocomplete="new-password" class="form-control">
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

@push('scripts')
    <script>
        (function () {
            const links = document.querySelectorAll('.profile-tab-link[data-toggle="tab"]');
            const activate = function (activeLink) {
                links.forEach(function (link) {
                    const isActive = link === activeLink;
                    link.classList.toggle('active-tab', isActive);
                    link.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });
            };

            links.forEach(function (link) {
                link.addEventListener('click', function () {
                    activate(link);
                });
            });

            if (window.location.hash) {
                const hashLink = document.querySelector('.profile-tab-link[href="' + window.location.hash + '"]');
                if (hashLink && window.jQuery) {
                    window.jQuery(hashLink).tab('show');
                    activate(hashLink);
                }
            }
        })();
    </script>
@endpush
