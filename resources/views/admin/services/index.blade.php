@extends('layouts.admin')

@section('title', __('admin.crud.manage', ['item' => __('admin.dashboard.services')]))
@section('page-title', __('admin.dashboard.services'))

@section('content')
    <section class="card" aria-label="{{ __('admin.crud.manage', ['item' => __('admin.dashboard.services')]) }}">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ __('admin.crud.all', ['item' => __('admin.dashboard.services')]) }} ({{ $services->total() }})</h2>
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus" aria-hidden="true"></i> {{ __('admin.crud.add', ['item' => __('admin.dashboard.services')]) }}
            </a>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.services.index') }}" role="search" aria-label="Filter services" class="admin-filter-form">
                <div class="admin-filter-field">
                    <label for="search" class="sr-only">{{ __('admin.crud.search', ['item' => __('admin.dashboard.services')]) }}</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.crud.search', ['item' => __('admin.dashboard.services')]) }}"
                        class="form-control">
                </div>
                <div>
                    <label for="service_category" class="sr-only">Filter by category</label>
                    <select id="service_category" name="service_category" class="form-select admin-filter-select">
                        <option value="">{{ __('admin.crud.all_categories') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('service_category') == $category->id ? 'selected' : '' }}>
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
                    <label for="trashed" class="sr-only">Filter deleted services</label>
                    <select id="trashed" name="trashed" class="form-select admin-filter-select admin-filter-select--sm">
                        <option value="">{{ __('admin.crud.current') }}</option>
                        <option value="with" {{ request('trashed') === 'with' ? 'selected' : '' }}>{{ __('admin.crud.with_deleted') }}</option>
                        <option value="only" {{ request('trashed') === 'only' ? 'selected' : '' }}>{{ __('admin.crud.deleted_only') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter" aria-hidden="true"></i> {{ __('admin.crud.filter') }}
                </button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">{{ __('admin.crud.clear') }}</a>
            </form>

            <div class="admin-table-wrap">
                <table class="table" aria-label="Services list">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('admin.dashboard.name') }}</th>
                            <th scope="col">{{ __('admin.dashboard.category') }}</th>
                            <th scope="col">{{ __('admin.crud.address') }}</th>
                            <th scope="col">{{ __('admin.dashboard.owner') }}</th>
                            <th scope="col">{{ __('admin.dashboard.status') }}</th>
                            <th scope="col">{{ __('admin.crud.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr>
                                <td>
                                    {{ $service->name }}
                                    @if ($service->is_verified)
                                        <span class="badge badge-success admin-ml-1">{{ __('admin.crud.verified') }}</span>
                                    @endif
                                    @if ($service->trashed())
                                        <span class="badge badge-danger admin-ml-1">{{ __('admin.crud.deleted') }}</span>
                                    @endif
                                </td>
                                <td>{{ $service->category->name ?? '-' }}</td>
                                <td>{{ Str::limit($service->address, 35) }}</td>
                                <td>{{ $service->user->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $service->is_active ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $service->is_active ? __('admin.dashboard.active') : __('admin.dashboard.inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="admin-actions">
                                        @if ($service->trashed())
                                            <form action="{{ route('admin.services.restore', $service->slug) }}" method="POST"
                                                class="admin-action-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    aria-label="{{ __('admin.crud.restore') }} {{ $service->name }}">{{ __('admin.crud.restore') }}</button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.services.show', $service) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                aria-label="{{ __('admin.crud.view') }} {{ $service->name }}">{{ __('admin.crud.view') }}</a>
                                            <a href="{{ route('admin.services.edit', $service) }}"
                                                class="btn btn-sm btn-outline-success"
                                                aria-label="{{ __('admin.crud.edit') }} {{ $service->name }}">{{ __('admin.crud.edit') }}</a>
                                            <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                                onsubmit="return confirm('{{ __('admin.crud.confirm_delete', ['item' => __('admin.dashboard.services')]) }}');"
                                                class="admin-action-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    aria-label="{{ __('admin.crud.delete') }} {{ $service->name }}">{{ __('admin.crud.delete') }}</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="admin-empty">
                                    {{ __('admin.crud.no_found', ['item' => __('admin.dashboard.services')]) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($services->hasPages())
                <nav class="admin-pagination" aria-label="Services pagination">
                    {{ $services->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
