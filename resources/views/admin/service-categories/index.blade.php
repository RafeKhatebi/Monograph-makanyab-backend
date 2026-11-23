@extends('layouts.admin')

@section('title', __('admin.crud.manage', ['item' => __('admin.dashboard.service_categories')]))
@section('page-title', __('admin.dashboard.service_categories'))

@section('content')
    <section class="card" aria-label="{{ __('admin.crud.manage', ['item' => __('admin.dashboard.service_categories')]) }}">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ __('admin.crud.all', ['item' => __('admin.dashboard.service_categories')]) }} ({{ $categories->total() }})</h2>
            <a href="{{ route('admin.service-categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus" aria-hidden="true"></i> {{ __('admin.crud.add', ['item' => __('admin.dashboard.service_categories')]) }}
            </a>
        </div>

        <div class="card-body">
            <div class="admin-table-wrap">
                <table class="table admin-service-categories-table" aria-label="{{ __('admin.dashboard.service_categories') }} {{ __('admin.crud.list') }}">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('admin.dashboard.name') }}</th>
                            <th scope="col">{{ __('admin.crud.slug') }}</th>
                            <th scope="col">{{ __('admin.crud.children') }}</th>
                            <th scope="col">{{ __('admin.dashboard.services') }}</th>
                            <th scope="col">{{ __('admin.dashboard.status') }}</th>
                            <th scope="col">{{ __('admin.crud.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->children_count }}</td>
                                <td>{{ $category->services_count }}</td>
                                <td>
                                    <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $category->is_active ? __('admin.dashboard.active') : __('admin.dashboard.inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="{{ route('admin.service-categories.show', $category) }}"
                                            class="btn btn-sm btn-outline-primary admin-icon-button"
                                            title="{{ __('admin.crud.view') }}"
                                            aria-label="{{ __('admin.crud.view') }} {{ $category->name }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        <a href="{{ route('admin.service-categories.edit', $category) }}"
                                            class="btn btn-sm btn-outline-success admin-icon-button"
                                            title="{{ __('admin.crud.edit') }}"
                                            aria-label="{{ __('admin.crud.edit') }} {{ $category->name }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                        <form action="{{ route('admin.service-categories.destroy', $category) }}" method="POST"
                                            data-confirm-delete="{{ $category->name }}"
                                            class="admin-action-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger admin-icon-button"
                                                title="{{ __('admin.crud.delete') }}"
                                                aria-label="{{ __('admin.crud.delete') }} {{ $category->name }}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="admin-empty">
                                    {{ __('admin.crud.no_found', ['item' => __('admin.dashboard.service_categories')]) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($categories->hasPages())
                <nav class="admin-pagination" aria-label="{{ __('admin.dashboard.service_categories') }} {{ __('admin.crud.pagination') }}">
                    {{ $categories->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
