@extends('layouts.admin')

@section('title', 'Add User')
@section('page-title', 'Add User')

@section('content')
    <section class="card" aria-label="Add New User">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">Add New User</h2>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Users
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST" novalidate>
                @csrf

                <div class="admin-form-grid">
                    <div>
                        <label for="name" class="form-label">Name <span aria-hidden="true">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="form-control @error('name') is-invalid @enderror"
                            aria-required="true">
                        @error('name')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label">Email <span aria-hidden="true">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="form-control @error('email') is-invalid @enderror"
                            aria-required="true">
                        @error('email')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="form-label">Username <span aria-hidden="true">*</span></label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required
                            class="form-control @error('username') is-invalid @enderror"
                            aria-required="true">
                        @error('username')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label">Password <span aria-hidden="true">*</span></label>
                        <input type="password" id="password" name="password" required
                            class="form-control @error('password') is-invalid @enderror"
                            aria-required="true"
                            autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label">Confirm Password <span aria-hidden="true">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="form-control"
                            autocomplete="new-password">
                    </div>

                    <div>
                        <label for="role" class="form-label">Role <span aria-hidden="true">*</span></label>
                        <select id="role" name="role" required
                            class="form-select @error('role') is-invalid @enderror"
                            aria-required="true">
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-full-span">
                        <label class="admin-check-row">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="form-check-input">
                            <span>Active</span>
                        </label>
                    </div>

                    <div class="admin-full-span admin-form-actions">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create User</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection
