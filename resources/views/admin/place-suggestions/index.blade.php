@extends('layouts.admin')

@section('title', 'Place Suggestions')
@section('page-title', 'Place Suggestions')

@section('content')
    <section class="card" aria-label="Place Suggestions">
        <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
            <h2 style="margin: 0; font-weight: 600; font-size: var(--font-size-base);">Pending Suggestions</h2>
            <form method="GET" action="{{ route('admin.place-suggestions.index') }}" role="search" aria-label="Filter suggestions" style="display: flex; gap: 8px; flex-wrap: wrap;">
                <label for="search" class="sr-only">Search suggestions</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search suggestions..."
                    class="form-control" style="min-width: 220px;">
                <label for="status" class="sr-only">Filter by status</label>
                <select id="status" name="status" class="form-select" style="width: auto;">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
        <div class="card-body">
            <div style="overflow-x: auto;">
                <table class="table" aria-label="Place Suggestions list">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">City</th>
                            <th scope="col">Category</th>
                            <th scope="col">Submitted By</th>
                            <th scope="col">Status</th>
                            <th scope="col" style="text-align: right;">Actions</th>
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
                                    <small style="color: var(--color-gray-500);">{{ $suggestion->submitted_by_email ?? $suggestion->user->email ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $suggestion->suggestion_status === 'approved' ? 'badge-success' : ($suggestion->suggestion_status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                        {{ ucfirst($suggestion->suggestion_status->value) }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('admin.place-suggestions.show', $suggestion) }}" class="btn btn-sm btn-primary"
                                        aria-label="View suggestion: {{ $suggestion->name }}">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 48px; color: var(--color-gray-400);">
                                    No suggestions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($suggestions->hasPages())
                <nav style="padding-top: 24px; display: flex; justify-content: center;" aria-label="Suggestions pagination">
                    {{ $suggestions->appends(request()->query())->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
