@extends('layouts.admin')

@section('title', __('admin.crud.add', ['item' => __('admin.dashboard.service_categories')]))
@section('page-title', __('admin.crud.add', ['item' => __('admin.dashboard.service_categories')]))

@section('content')
    <section class="card admin-category-form" aria-label="{{ __('admin.crud.add', ['item' => __('admin.dashboard.service_categories')]) }}">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ __('admin.crud.add', ['item' => __('admin.dashboard.service_categories')]) }}</h2>
            <a href="{{ route('admin.service-categories.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin.crud.back') }}
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.service-categories.store') }}" method="POST" novalidate>
                @csrf

                <div class="admin-form-grid">
                    <div>
                        <label for="name" class="form-label">{{ __('admin.dashboard.name') }} <span aria-hidden="true">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="form-control @error('name') is-invalid @enderror" aria-required="true">
                        @error('name')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="form-label">{{ __('admin.crud.slug') }}</label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                            class="form-control @error('slug') is-invalid @enderror">
                        <div class="form-text">{{ __('admin.crud.slug_help') }}</div>
                        @error('slug')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-full-span">
                        <label for="description" class="form-label">{{ __('admin.crud.description') }}</label>
                        <textarea id="description" name="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-full-span">
                        <label for="parent_id" class="form-label">{{ __('admin.crud.parent_category') }}</label>
                        <select id="parent_id" name="parent_id"
                            class="form-select @error('parent_id') is-invalid @enderror">
                            <option value="">{{ __('admin.crud.none_top_level') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('parent_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="keywords" class="form-label">{{ __('admin.crud.keywords') }}</label>
                        <input type="text" id="keywords" name="keywords" value="{{ old('keywords') }}"
                            placeholder="plumber, electrician"
                            class="form-control @error('keywords') is-invalid @enderror">
                        @error('keywords')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="schema_type" class="form-label">{{ __('admin.crud.schema_type') }}</label>
                        <input type="text" id="schema_type" name="schema_type"
                            value="{{ old('schema_type', 'Service') }}"
                            class="form-control @error('schema_type') is-invalid @enderror">
                        @error('schema_type')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-full-span admin-category-options">
                        <label class="admin-check-row">
                            <input type="checkbox" name="has_menu" value="1" @checked(old('has_menu'))
                                class="form-check-input">
                            <span>{{ __('admin.crud.has_menu') }}</span>
                        </label>
                        <label class="admin-check-row">
                            <input type="checkbox" name="has_booking" value="1" @checked(old('has_booking'))
                                class="form-check-input">
                            <span>{{ __('admin.crud.has_booking') }}</span>
                        </label>
                        <label class="admin-check-row">
                            <input type="checkbox" name="has_delivery" value="1" @checked(old('has_delivery'))
                                class="form-check-input">
                            <span>{{ __('admin.crud.has_delivery') }}</span>
                        </label>
                        <label class="admin-check-row">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))
                                class="form-check-input">
                            <span>{{ __('admin.dashboard.active') }}</span>
                        </label>
                    </div>

                    <div class="admin-full-span admin-form-actions">
                        <a href="{{ route('admin.service-categories.index') }}" class="btn btn-outline-secondary">{{ __('admin.crud.cancel') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('admin.crud.create') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
