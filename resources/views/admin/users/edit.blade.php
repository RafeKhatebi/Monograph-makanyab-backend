@extends('layouts.admin')

@section('title', __('admin.users.edit'))
@section('page-title', __('admin.users.edit'))

@section('content')
    <section class="card" aria-label="{{ __('admin.users.edit') }}">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ __('admin.users.edit') }}</h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin.users.back') }}
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="admin-form-grid">
                    <div>
                        <label for="name" class="form-label">{{ __('admin.users.name') }} <span aria-hidden="true">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                            class="form-control @error('name') is-invalid @enderror"
                            aria-required="true">
                        @error('name')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label">{{ __('admin.users.email') }} <span aria-hidden="true">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="form-control @error('email') is-invalid @enderror"
                            aria-required="true">
                        @error('email')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="form-label">{{ __('admin.users.username') }} <span aria-hidden="true">*</span></label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                            class="form-control @error('username') is-invalid @enderror"
                            aria-required="true">
                        @error('username')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label">{{ __('admin.users.password') }}</label>
                        <div class="admin-password-field">
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                autocomplete="new-password">
                            <button type="button" class="admin-password-toggle" data-admin-password-toggle
                                aria-controls="password" aria-pressed="false"
                                aria-label="{{ __('auth.layout.show_password') }}"
                                data-show-label="{{ __('auth.layout.show_password') }}"
                                data-hide-label="{{ __('auth.layout.hide_password') }}">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="admin-help-text">
                            {{ __('admin.users.password_help') }}
                        </div>
                        @error('password')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label">{{ __('admin.users.password_confirmation') }}</label>
                        <div class="admin-password-field">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" autocomplete="new-password">
                            <button type="button" class="admin-password-toggle" data-admin-password-toggle
                                aria-controls="password_confirmation" aria-pressed="false"
                                aria-label="{{ __('auth.layout.show_password') }}"
                                data-show-label="{{ __('auth.layout.show_password') }}"
                                data-hide-label="{{ __('auth.layout.hide_password') }}">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="role" class="form-label">{{ __('admin.users.role') }} <span aria-hidden="true">*</span></label>
                        <select id="role" name="role" required
                            class="form-select @error('role') is-invalid @enderror"
                            aria-required="true">
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>{{ __('admin.dashboard.user') }}</option>
                            <option value="owner" {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>{{ __('admin.dashboard.owner') }}</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>{{ __('admin.dashboard.admin') }}</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-full-span">
                        <label class="admin-check-row">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                class="form-check-input">
                            <span>{{ __('admin.users.active') }}</span>
                        </label>
                    </div>

                    <div class="admin-full-span admin-form-actions">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">{{ __('admin.users.cancel') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('admin.users.update') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection
