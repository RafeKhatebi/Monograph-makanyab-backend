@extends('layouts.admin')

@section('title', __('admin.dashboard.categories'))
@section('page-title', __('admin.dashboard.categories'))

@section('content')
    <div class="card">
        <div class="bg-white rounded p-4 shadow-sm">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                <h6 class="mb-0">{{ $category->name }}</h6>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.categories.edit', $category) }}"
                        class="text-emerald-600 hover:text-emerald-700">{{ __('admin.crud.edit') }}</a>
                    <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:text-gray-700">← {{ __('admin.crud.back') }}</a>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <span
                        class="px-3 py-1 {{ $category->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }} text-sm rounded-full">
                        {{ $category->is_active ? __('admin.dashboard.active') : __('admin.dashboard.inactive') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('admin.crud.slug') }}</h3>
                        <p class="text-gray-900">{{ $category->slug }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('admin.crud.parent_category') }}</h3>
                        <p class="text-gray-900">{{ $category->parent ? $category->parent->name : __('admin.crud.none_top_level') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('admin.crud.icon_class') }}</h3>
                        <p class="text-gray-900">{{ $category->icon_name ?: __('admin.crud.not_set') }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('admin.crud.color') }}</h3>
                        <p class="text-gray-900">{{ $category->color_code }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('admin.crud.sort_order') }}</h3>
                        <p class="text-gray-900">{{ $category->sort_order }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('admin.crud.keywords') }}</h3>
                    <p class="text-gray-900">{{ $category->keywords ?: __('admin.crud.not_set') }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('admin.crud.schema_type') }}</h3>
                    <p class="text-gray-900">{{ $category->schema_type ?: __('admin.crud.not_set') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('admin.dashboard.total_places') }}</h3>
                        <p class="text-gray-900">{{ $category->places_count }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('admin.crud.subcategories') }}</h3>
                        <p class="text-gray-900">{{ $category->children_count }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('admin.crud.has_menu') }}</h3>
                        <p class="text-gray-900">{{ $category->has_menu ? __('admin.crud.enabled') : __('admin.crud.disabled') }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('admin.crud.has_booking') }}</h3>
                        <p class="text-gray-900">{{ $category->has_booking ? __('admin.crud.enabled') : __('admin.crud.disabled') }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('admin.crud.has_delivery') }}</h3>
                        <p class="text-gray-900">{{ $category->has_delivery ? __('admin.crud.enabled') : __('admin.crud.disabled') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('admin.crud.created') }}</h3>
                        <p class="text-gray-900">{{ $category->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">{{ __('admin.crud.last_updated') }}</h3>
                        <p class="text-gray-900">{{ $category->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
