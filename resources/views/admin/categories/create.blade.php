@extends('layouts.admin')

@section('title', __('admin.crud.add', ['item' => __('admin.dashboard.categories')]))
@section('page-title', __('admin.crud.add', ['item' => __('admin.dashboard.categories')]))

@section('content')
    <div class="card">
        <div class="bg-white rounded p-4 shadow-sm">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                <h6 class="mb-0">{{ __('admin.crud.add', ['item' => __('admin.dashboard.categories')]) }}</h6>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i>{{ __('admin.crud.back') }}
                </a>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.dashboard.name') }} *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.crud.slug') }}</label>
                            <input type="text" name="slug" value="{{ old('slug') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <p class="text-sm text-gray-500 mt-1">{{ __('admin.crud.slug_help') }}</p>
                            @error('slug')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.crud.parent_category') }}</label>
                            <select name="parent_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">{{ __('admin.crud.none_top_level') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.crud.icon_class') }}</label>
                            <input type="text" name="icon_name" value="{{ old('icon_name') }}"
                                placeholder="fa-cutlery"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('icon_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.crud.color') }}</label>
                            <input type="text" name="color_code" value="{{ old('color_code', '#10B981') }}"
                                placeholder="#10B981"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('color_code')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.crud.keywords') }}</label>
                            <input type="text" name="keywords" value="{{ old('keywords') }}"
                                placeholder="restaurants, food, dining"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('keywords')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.crud.schema_type') }}</label>
                            <input type="text" name="schema_type" value="{{ old('schema_type', 'LocalBusiness') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('schema_type')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.crud.sort_order') }}</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                                max="65535"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('sort_order')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex flex-wrap gap-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="has_menu" value="1"
                                    {{ old('has_menu') ? 'checked' : '' }}
                                    class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="ml-2 text-sm text-gray-700">{{ __('admin.crud.has_menu') }}</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="has_booking" value="1"
                                    {{ old('has_booking') ? 'checked' : '' }}
                                    class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="ml-2 text-sm text-gray-700">{{ __('admin.crud.has_booking') }}</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="has_delivery" value="1"
                                    {{ old('has_delivery') ? 'checked' : '' }}
                                    class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="ml-2 text-sm text-gray-700">{{ __('admin.crud.has_delivery') }}</span>
                            </label>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="ml-2 text-sm text-gray-700">{{ __('admin.dashboard.active') }}</span>
                            </label>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-2">
                            <a href="{{ route('admin.categories.index') }}"
                                class="btn btn-outline-secondary">{{ __('admin.crud.cancel') }}</a>
                            <button type="submit"
                                class="btn btn-primary">{{ __('admin.crud.create') }}</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
@endsection
