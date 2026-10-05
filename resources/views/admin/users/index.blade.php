@extends('layouts.admin')

@section('title', __('admin.users.title'))
@section('page-title', __('admin.users.title'))

@section('content')
    <section class="card" aria-label="{{ __('admin.users.title') }}">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ __('admin.users.all') }} ({{ $users->total() }})</h2>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus" aria-hidden="true"></i> {{ __('admin.users.add') }}
            </a>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" role="search" aria-label="{{ __('admin.users.filter_aria') }}" class="admin-filter-form">
                <div class="admin-filter-field">
                    <label for="search" class="sr-only">{{ __('admin.crud.search', ['item' => __('admin.dashboard.total_users')]) }}</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.crud.search', ['item' => __('admin.dashboard.total_users')]) }}"
                        class="form-control">
                </div>
                <div>
                    <label for="role" class="sr-only">{{ __('admin.users.role_filter') }}</label>
                    <select id="role" name="role" class="form-select admin-filter-select">
                        <option value="">{{ __('admin.dashboard.role') }}</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>{{ __('admin.dashboard.user') }}</option>
                        <option value="owner" {{ request('role') === 'owner' ? 'selected' : '' }}>{{ __('admin.dashboard.owner') }}</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>{{ __('admin.dashboard.admin') }}</option>
                    </select>
                </div>
                <div>
                    <label for="is_active" class="sr-only">{{ __('admin.users.status_filter') }}</label>
                    <select id="is_active" name="is_active" class="form-select admin-filter-select admin-filter-select--sm">
                        <option value="">{{ __('admin.crud.all_status') }}</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>{{ __('admin.users.active') }}</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>{{ __('admin.users.inactive') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter" aria-hidden="true"></i> {{ __('admin.crud.filter') }}
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">{{ __('admin.crud.clear') }}</a>
            </form>

            <div class="admin-table-wrap">
                <table class="table" aria-label="{{ __('admin.users.list_aria') }}">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('admin.dashboard.name') }}</th>
                            <th scope="col">{{ __('admin.dashboard.email') }}</th>
                            <th scope="col">{{ __('admin.dashboard.role') }}</th>
                            <th scope="col">{{ __('admin.dashboard.reviews') }}</th>
                            <th scope="col">{{ __('navigation.favorites') }}</th>
                            <th scope="col">{{ __('admin.dashboard.status') }}</th>
                            <th scope="col">{{ __('admin.users.joined') }}</th>
                            <th scope="col">{{ __('admin.users.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge badge-primary">{{ __('admin.dashboard.'.$user->role) }}</span></td>
                                <td>{{ $user->reviews_count }}</td>
                                <td>{{ $user->favorites_count }}</td>
                                <td>
                                    <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $user->is_active ? __('admin.dashboard.active') : __('admin.dashboard.inactive') }}
                                    </span>
                                </td>
                                <td>{{ \App\Support\LocalizedDate::date($user->created_at) }}</td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="{{ route('admin.users.show', $user) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            aria-label="{{ __('admin.users.view_name', ['name' => $user->name]) }}">{{ __('admin.users.view') }}</a>
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="btn btn-sm btn-outline-success"
                                            aria-label="{{ __('admin.users.edit_name', ['name' => $user->name]) }}">{{ __('admin.users.edit_action') }}</a>
                                        @if ($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                data-confirm-delete="{{ $user->name }}"
                                                class="admin-action-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    aria-label="{{ __('admin.users.delete_name', ['name' => $user->name]) }}">{{ __('admin.users.delete') }}</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="admin-empty">
                                    {{ __('admin.users.empty') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <nav class="admin-pagination" aria-label="{{ __('admin.users.pagination') }}">
                    {{ $users->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
