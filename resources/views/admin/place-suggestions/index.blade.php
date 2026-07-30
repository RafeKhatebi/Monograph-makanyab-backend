@extends('layouts.admin')

@section('title', 'Place Suggestions')
@section('page-title', 'Place Suggestions')

@section('content')
    <section class="card" aria-label="Place Suggestions">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">Pending Suggestions</h2>
            <form method="GET" action="{{ route('admin.place-suggestions.index') }}" role="search" aria-label="Filter suggestions" class="admin-filter-form admin-filter-form--compact">
                <label for="search" class="sr-only">Search suggestions</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search suggestions..."
                    class="form-control admin-filter-select">
                <label for="status" class="sr-only">Filter by status</label>
                <select id="status" name="status" class="form-select admin-filter-select admin-filter-select--sm">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
        <div class="card-body">
            <div class="admin-table-wrap">
                <table class="table" aria-label="Place Suggestions list">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">City</th>
                            <th scope="col">Category</th>
                            <th scope="col">Submitted By</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="admin-table-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suggestions as $suggestion)
                            <tr>
                                <td>{{ $suggestion->name }}</td>
                                <td>{{ $suggestion->city }}</td>
                                <td>{{ $suggestion->category->name ?? '—' }}</td>
                                <td>
                                    {{ $suggestion->submitted_by_name ?? ($suggestion->user->name ?? 'Guest') }}
                                    <br>
                                    <small class="admin-inline-muted">{{ $suggestion->submitted_by_email ?? $suggestion->user->email ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $suggestion->suggestion_status === 'approved' ? 'badge-success' : ($suggestion->suggestion_status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                        {{ ucfirst($suggestion->suggestion_status->value) }}
                                    </span>
                                </td>
                                <td class="admin-table-actions">
                                    <a href="{{ route('admin.place-suggestions.show', $suggestion) }}" class="btn btn-sm btn-primary"
                                        aria-label="View suggestion: {{ $suggestion->name }}">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="admin-empty">
                                    No suggestions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($suggestions->hasPages())
                <nav class="admin-pagination" aria-label="Suggestions pagination">
                    {{ $suggestions->appends(request()->query())->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
