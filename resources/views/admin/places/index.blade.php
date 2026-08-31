@extends('layouts.admin')

@section('title', __('admin.crud.manage', ['item' => __('admin.dashboard.places')]))
@section('page-title', __('admin.dashboard.places'))

@section('content')
    <section class="card" aria-label="{{ __('admin.crud.manage', ['item' => __('admin.dashboard.places')]) }}">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ __('admin.crud.all', ['item' => __('admin.dashboard.places')]) }} ({{ $places->total() }})</h2>
            <a href="{{ route('admin.places.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus" aria-hidden="true"></i> {{ __('admin.crud.add', ['item' => __('admin.dashboard.places')]) }}
            </a>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.places.index') }}" role="search" aria-label="Filter places" class="admin-filter-form">
                <div class="admin-filter-field">
                    <label for="search" class="sr-only">{{ __('admin.crud.search', ['item' => __('admin.dashboard.places')]) }}</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.crud.search', ['item' => __('admin.dashboard.places')]) }}"
                        class="form-control">
                </div>
                <div>
                    <label for="category" class="sr-only">Filter by category</label>
                    <select id="category" name="category" class="form-select admin-filter-select">
                        <option value="">{{ __('admin.crud.all_categories') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="is_verified" class="sr-only">Filter by verification status</label>
                    <select id="is_verified" name="is_verified" class="form-select admin-filter-select">
                        <option value="">{{ __('admin.crud.all_verification') }}</option>
                        <option value="1" {{ request('is_verified') === '1' ? 'selected' : '' }}>{{ __('admin.crud.verified') }}</option>
                        <option value="0" {{ request('is_verified') === '0' ? 'selected' : '' }}>{{ __('admin.crud.not_verified') }}</option>
                    </select>
                </div>
                <div>
                    <label for="is_active" class="sr-only">Filter by status</label>
                    <select id="is_active" name="is_active" class="form-select admin-filter-select admin-filter-select--sm">
                        <option value="">{{ __('admin.crud.all_status') }}</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>{{ __('admin.dashboard.active') }}</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>{{ __('admin.dashboard.inactive') }}</option>
                    </select>
                </div>
                <div>
                    <label for="trashed" class="sr-only">Filter deleted places</label>
                    <select id="trashed" name="trashed" class="form-select admin-filter-select admin-filter-select--sm">
                        <option value="">{{ __('admin.crud.current') }}</option>
                        <option value="with" {{ request('trashed') === 'with' ? 'selected' : '' }}>{{ __('admin.crud.with_deleted') }}</option>
                        <option value="only" {{ request('trashed') === 'only' ? 'selected' : '' }}>{{ __('admin.crud.deleted_only') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter" aria-hidden="true"></i> {{ __('admin.crud.filter') }}
                </button>
                <a href="{{ route('admin.places.index') }}" class="btn btn-outline-secondary">
                    {{ __('admin.crud.clear') }}
                </a>
            </form>

            <div class="admin-table-wrap">
                <table class="table" aria-label="Places list">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('admin.dashboard.name') }}</th>
                            <th scope="col">{{ __('admin.dashboard.category') }}</th>
                            <th scope="col">{{ __('admin.crud.address') }}</th>
                            <th scope="col">{{ __('admin.dashboard.reviews') }}</th>
                            <th scope="col">{{ __('admin.dashboard.rating') }}</th>
                            <th scope="col">{{ __('admin.dashboard.status') }}</th>
                            <th scope="col" class="admin-table-actions">{{ __('admin.crud.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($places as $place)
                            <tr>
                                <td>
                                    <div class="admin-table-cell-heading">
                                        {{ $place->name }}
                                    </div>
                                    @if ($place->is_verified)
                                        <span class="badge badge-success admin-mt-1">Verified</span>
                                    @endif
                                    @if ($place->trashed())
                                        <span class="badge badge-danger admin-mt-1">Deleted</span>
                                    @endif
                                </td>
                                <td>{{ $place->category->name ?? '-' }}</td>
                                <td class="admin-table-muted">{{ Str::limit($place->address, 30) }}</td>
                                <td>{{ $place->reviews_count }}</td>
                                <td>
                                    <span class="admin-rating">
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        {{ number_format($place->reviews_avg_rating ?? 0, 1) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $place->is_active ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $place->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="admin-table-actions">
                                    <div class="admin-actions admin-actions--end">
                                        @if ($place->trashed())
                                            <form action="{{ route('admin.places.restore', $place->slug) }}" method="POST"
                                                class="admin-action-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    aria-label="Restore {{ $place->name }}">
                                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.places.show', $place) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                aria-label="View {{ $place->name }}">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                            <a href="{{ route('admin.places.edit', $place) }}"
                                                class="btn btn-sm btn-outline-success"
                                                aria-label="Edit {{ $place->name }}">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                            </a>
                                            <form action="{{ route('admin.places.destroy', $place) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this place?');"
                                                class="admin-action-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    aria-label="Delete {{ $place->name }}">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="admin-empty">
                                    <i class="fa fa-map-marker-alt admin-empty-icon" aria-hidden="true"></i>
                                    No places found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($places->hasPages())
                <nav class="admin-pagination" aria-label="Places pagination">
                    {{ $places->links() }}
                </nav>
            @endif
        </div>
    </section>

@endsection
