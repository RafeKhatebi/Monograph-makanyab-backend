@extends('layouts.admin')

@section('title', __('admin.suggestions.place_title'))
@section('page-title', __('admin.suggestions.place_title'))

@section('content')
    <section class="card" aria-label="{{ __('admin.suggestions.place_title') }}">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ __('admin.suggestions.pending_place') }}</h2>
            <form method="GET" action="{{ route('admin.place-suggestions.index') }}" role="search" aria-label="{{ __('admin.suggestions.filter_aria') }}" class="admin-filter-form admin-filter-form--compact">
                <label for="search" class="sr-only">{{ __('admin.suggestions.search_label') }}</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.suggestions.search_placeholder') }}"
                    class="form-control admin-filter-select">
                <label for="status" class="sr-only">{{ __('admin.suggestions.status_label') }}</label>
                <select id="status" name="status" class="form-select admin-filter-select admin-filter-select--sm">
                    <option value="">{{ __('admin.suggestions.all_statuses') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('suggestions.status.pending') }}</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('suggestions.status.approved') }}</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('suggestions.status.rejected') }}</option>
                </select>
                <button type="submit" class="btn btn-primary">{{ __('common.actions.filter') }}</button>
            </form>
        </div>
        <div class="card-body">
            <div class="admin-table-wrap">
                <table class="table" aria-label="{{ __('admin.suggestions.list_aria', ['type' => __('admin.suggestions.place_title')]) }}">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('admin.suggestions.name') }}</th>
                            <th scope="col">{{ __('admin.suggestions.city') }}</th>
                            <th scope="col">{{ __('admin.suggestions.category') }}</th>
                            <th scope="col">{{ __('admin.suggestions.submitted_by') }}</th>
                            <th scope="col">{{ __('admin.suggestions.status') }}</th>
                            <th scope="col" class="admin-table-actions">{{ __('admin.suggestions.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suggestions as $suggestion)
                            <tr>
                                <td>{{ $suggestion->name }}</td>
                                <td>{{ $suggestion->city }}</td>
                                <td>{{ $suggestion->category->name ?? '—' }}</td>
                                <td>
                                    {{ $suggestion->submitted_by_name ?? ($suggestion->user->name ?? __('admin.suggestions.guest')) }}
                                    <br>
                                    <small class="admin-inline-muted">{{ $suggestion->submitted_by_email ?? $suggestion->user->email ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $suggestion->suggestion_status?->value === 'approved' ? 'badge-success' : ($suggestion->suggestion_status?->value === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                        {{ $suggestion->suggestion_status->label() }}
                                    </span>
                                </td>
                                <td class="admin-table-actions">
                                    <a href="{{ route('admin.place-suggestions.show', $suggestion) }}" class="btn btn-sm btn-primary"
                                        aria-label="{{ __('admin.suggestions.view_aria', ['name' => $suggestion->name]) }}">{{ __('common.actions.view') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="admin-empty">
                                    {{ __('admin.suggestions.empty') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($suggestions->hasPages())
                <nav class="admin-pagination" aria-label="{{ __('admin.suggestions.pagination') }}">
                    {{ $suggestions->appends(request()->query())->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
