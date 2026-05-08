@extends('layouts.admin')

@section('title', 'Service Category Details')
@section('page-title', 'Service Category Details')

@section('content')
    <div class="bg-light rounded h-100 p-4">
        <div class="bg-white rounded p-4 shadow-sm">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                <h6 class="mb-0">{{ $serviceCategory->name }}</h6>
                <div class="d-flex gap-2">
                        <a href="{{ route('admin.service-categories.edit', $serviceCategory) }}"
                            class="text-emerald-600 hover:text-emerald-700">Edit</a>
                        <a href="{{ route('admin.service-categories.index') }}"
                            class="text-gray-600 hover:text-gray-700">Back</a>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <span
                            class="px-3 py-1 {{ $serviceCategory->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }} text-sm rounded-full">
                            {{ $serviceCategory->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Slug</h3>
                            <p class="text-gray-900">{{ $serviceCategory->slug }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Parent Category</h3>
                            <p class="text-gray-900">{{ $serviceCategory->parent?->name ?? 'None (Top Level)' }}</p>
                        </div>
                    </div>

                    @if ($serviceCategory->description)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Description</h3>
                            <p class="text-gray-900">{{ $serviceCategory->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Icon Name</h3>
                            <p class="text-gray-900">{{ $serviceCategory->icon_name ?: '-' }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Color</h3>
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded border border-gray-200 inline-block"
                                    style="background-color: {{ $serviceCategory->color_code ?: '#ffffff' }};"></span>
                                <span class="text-gray-900">{{ $serviceCategory->color_code ?: '-' }}</span>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Sort Order</h3>
                            <p class="text-gray-900">{{ $serviceCategory->sort_order ?? 0 }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Has Menu</h3>
                            <p class="text-gray-900">{{ $serviceCategory->has_menu ? 'Yes' : 'No' }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Has Booking</h3>
                            <p class="text-gray-900">{{ $serviceCategory->has_booking ? 'Yes' : 'No' }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Has Delivery</h3>
                            <p class="text-gray-900">{{ $serviceCategory->has_delivery ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Total Services</h3>
                            <p class="text-gray-900">{{ $serviceCategory->services_count }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Subcategories</h3>
                            <p class="text-gray-900">{{ $serviceCategory->children_count }}</p>
                        </div>
                    </div>

                    @if ($serviceCategory->keywords)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Keywords</h3>
                            <p class="text-gray-900">{{ $serviceCategory->keywords }}</p>
                        </div>
                    @endif

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Schema Type</h3>
                        <p class="text-gray-900">{{ $serviceCategory->schema_type ?: '-' }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Created</h3>
                            <p class="text-gray-900">{{ $serviceCategory->created_at?->format('M d, Y H:i') }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Last Updated</h3>
                            <p class="text-gray-900">{{ $serviceCategory->updated_at?->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection
