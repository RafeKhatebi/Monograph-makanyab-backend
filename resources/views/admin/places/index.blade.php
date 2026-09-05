@extends('layouts.admin')

@section('title', __('admin.crud.manage', ['item' => __('admin.dashboard.places')]))
@section('page-title', __('admin.dashboard.places'))

@section('content')
    @if (($pendingSuggestions ?? collect())->isNotEmpty())
        <section class="card admin-mb-4" aria-label="{{ __('admin.suggestions.pending_place') }}">
            <div class="card-header admin-card-header">
                <h2 class="admin-card-title">{{ __('admin.suggestions.pending_place') }}</h2>
            </div>
            <div class="card-body">
                <div class="admin-table-wrap">
                    <table class="table" aria-label="{{ __('admin.suggestions.pending_place') }}">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('admin.suggestions.name') }}</th>
                                <th scope="col">{{ __('admin.suggestions.city') }}</th>
                                <th scope="col">{{ __('admin.suggestions.category') }}</th>
                                <th scope="col">{{ __('admin.suggestions.submitted_by') }}</th>
                                <th scope="col" class="admin-table-actions">{{ __('admin.suggestions.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingSuggestions as $suggestion)
                                <tr>
                                    <td>{{ $suggestion->name }}</td>
                                    <td>{{ $suggestion->city }}</td>
                                    <td>{{ $suggestion->category->name ?? '-' }}</td>
                                    <td>{{ $suggestion->submitted_by_name ?? ($suggestion->user->name ?? __('admin.suggestions.guest')) }}</td>
                                    <td class="admin-table-actions">
                                        <div class="admin-actions admin-actions--end">
                                            <a href="{{ route('admin.place-suggestions.show', $suggestion) }}" class="btn btn-sm btn-outline-primary">{{ __('common.actions.view') }}</a>
                                            <form action="{{ route('admin.place-suggestions.approve', $suggestion) }}" method="POST" class="admin-action-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">{{ __('admin.suggestions.approve') }}</button>
                                            </form>
                                            <form action="{{ route('admin.place-suggestions.reject', $suggestion) }}" method="POST" class="admin-action-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('admin.suggestions.reject') }}</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endif

    <section class="card" aria-label="{{ __('admin.crud.manage', ['item' => __('admin.dashboard.places')]) }}">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ __('admin.crud.all', ['item' => __('admin.dashboard.places')]) }} ({{ $places->total() }})</h2>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.places.index') }}" role="search" aria-label="{{ __('admin.crud.search', ['item' => __('admin.dashboard.places')]) }}" class="admin-filter-form">
                <div class="admin-filter-field">
                    <label for="search" class="sr-only">{{ __('admin.crud.search', ['item' => __('admin.dashboard.places')]) }}</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.crud.search', ['item' => __('admin.dashboard.places')]) }}"
                        class="form-control">
                </div>
                <div>
                    <label for="category" class="sr-only">{{ __('admin.dashboard.category') }}</label>
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
                    <label for="is_verified" class="sr-only">{{ __('admin.crud.all_verification') }}</label>
                    <select id="is_verified" name="is_verified" class="form-select admin-filter-select">
                        <option value="">{{ __('admin.crud.all_verification') }}</option>
                        <option value="1" {{ request('is_verified') === '1' ? 'selected' : '' }}>{{ __('admin.crud.verified') }}</option>
                        <option value="0" {{ request('is_verified') === '0' ? 'selected' : '' }}>{{ __('admin.crud.not_verified') }}</option>
                    </select>
                </div>
                <div>
                    <label for="is_active" class="sr-only">{{ __('admin.dashboard.status') }}</label>
                    <select id="is_active" name="is_active" class="form-select admin-filter-select admin-filter-select--sm">
                        <option value="">{{ __('admin.crud.all_status') }}</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>{{ __('admin.dashboard.active') }}</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>{{ __('admin.dashboard.inactive') }}</option>
                    </select>
                </div>
                <div>
                    <label for="trashed" class="sr-only">{{ __('admin.crud.deleted') }}</label>
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
                <table class="table" aria-label="{{ __('admin.crud.all', ['item' => __('admin.dashboard.places')]) }}">
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
                                        <span class="badge badge-success admin-mt-1">{{ __('admin.crud.verified') }}</span>
                                    @endif
                                    @if ($place->trashed())
                                        <span class="badge badge-danger admin-mt-1">{{ __('admin.crud.deleted') }}</span>
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
                                        {{ $place->is_active ? __('admin.dashboard.active') : __('admin.dashboard.inactive') }}
                                    </span>
                                </td>
                                <td class="admin-table-actions">
                                    <div class="admin-actions admin-actions--end">
                                        @if ($place->trashed())
                                            <form action="{{ route('admin.places.restore', $place->slug) }}" method="POST"
                                                class="admin-action-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    aria-label="{{ __('admin.crud.restore') }} {{ $place->name }}">
                                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.places.show', $place) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                aria-label="{{ __('admin.crud.view') }} {{ $place->name }}">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                            <a href="{{ route('admin.places.edit', $place) }}"
                                                class="btn btn-sm btn-outline-success"
                                                aria-label="{{ __('admin.crud.edit') }} {{ $place->name }}">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                            </a>
                                            <form action="{{ route('admin.places.destroy', $place) }}" method="POST"
                                                onsubmit="return confirm('{{ __('admin.crud.confirm_delete', ['item' => __('admin.dashboard.places')]) }}');"
                                                class="admin-action-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    aria-label="{{ __('admin.crud.delete') }} {{ $place->name }}">
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
                                    {{ __('admin.crud.no_found', ['item' => __('admin.dashboard.places')]) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($places->hasPages())
                <nav class="admin-pagination" aria-label="{{ __('admin.dashboard.places') }}">
                    {{ $places->links() }}
                </nav>
            @endif
        </div>
    </section>

@endsection
