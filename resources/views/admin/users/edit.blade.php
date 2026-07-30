@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
    <section class="card" aria-label="Edit User">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">Edit User</h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Users
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="admin-form-grid">
                    <div>
                        <label for="name" class="form-label">Name <span aria-hidden="true">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                            class="form-control @error('name') is-invalid @enderror"
                            aria-required="true">
                        @error('name')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label">Email <span aria-hidden="true">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="form-control @error('email') is-invalid @enderror"
                            aria-required="true">
                        @error('email')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="form-label">Username <span aria-hidden="true">*</span></label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                            class="form-control @error('username') is-invalid @enderror"
                            aria-required="true">
                        @error('username')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            autocomplete="new-password">
                        <div class="admin-help-text">
                            Leave empty to keep current password
                        </div>
                        @error('password')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control"
                            autocomplete="new-password">
                    </div>

                    <div>
                        <label for="role" class="form-label">Role <span aria-hidden="true">*</span></label>
                        <select id="role" name="role" required
                            class="form-select @error('role') is-invalid @enderror"
                            aria-required="true">
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                            <option value="owner" {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>Owner</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
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
                            <span>Active</span>
                        </label>
                    </div>

                    <div class="admin-full-span admin-form-actions">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection
