@extends('layouts.admin')

@section('title', __('admin.crud.manage', ['item' => __('admin.dashboard.categories')]))
@section('page-title', __('admin.dashboard.categories'))

@section('content')
    <section class="card" aria-label="{{ __('admin.crud.manage', ['item' => __('admin.dashboard.categories')]) }}">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ __('admin.crud.all', ['item' => __('admin.dashboard.categories')]) }} ({{ $categories->total() }})</h2>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus" aria-hidden="true"></i> {{ __('admin.crud.add', ['item' => __('admin.dashboard.categories')]) }}
            </a>
        </div>

        <div class="card-body">
            <div class="admin-table-wrap">
                <table class="table" aria-label="Categories list">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('admin.dashboard.name') }}</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Parent</th>
                            <th scope="col">Children</th>
                            <th scope="col">{{ __('admin.dashboard.places') }}</th>
                            <th scope="col">{{ __('admin.dashboard.status') }}</th>
                            <th scope="col">{{ __('admin.crud.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->parent->name ?? '-' }}</td>
                                <td>{{ $category->children_count }}</td>
                                <td>{{ $category->places_count }}</td>
                                <td>
                                    <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $category->is_active ? __('admin.dashboard.active') : __('admin.dashboard.inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="{{ route('admin.categories.show', $category) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            aria-label="{{ __('admin.crud.view') }} {{ $category->name }}">{{ __('admin.crud.view') }}</a>
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                            class="btn btn-sm btn-outline-success"
                                            aria-label="{{ __('admin.crud.edit') }} {{ $category->name }}">{{ __('admin.crud.edit') }}</a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                            onsubmit="return confirm('{{ __('admin.crud.confirm_delete', ['item' => __('admin.dashboard.categories')]) }}');"
                                            class="admin-action-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                aria-label="{{ __('admin.crud.delete') }} {{ $category->name }}">{{ __('admin.crud.delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="admin-empty">
                                    {{ __('admin.crud.no_found', ['item' => __('admin.dashboard.categories')]) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($categories->hasPages())
                <nav class="admin-pagination" aria-label="Categories pagination">
                    {{ $categories->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
