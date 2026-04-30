@extends('layouts.admin')

@section('title', 'Place Suggestions')
@section('page-title', 'Place Suggestions')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading" style="display:flex;justify-content:space-between;align-items:center;">
            <h3 class="panel-title">Pending Suggestions</h3>
            <form method="GET" action="{{ route('admin.place-suggestions.index') }}" style="display:flex;gap:8px;flex-wrap:wrap;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search suggestions..."
                    style="padding:10px 12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;min-width:220px;">
                <select name="status" style="padding:10px 12px;border:1px solid #D1D5DB;border-radius:10px;outline:none;">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>City</th>
                            <th>Category</th>
                            <th>Submitted By</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
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
                                    <small class="text-muted">{{ $suggestion->submitted_by_email ?? $suggestion->user->email ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $suggestion->suggestion_status === 'approved' ? 'badge-success' : ($suggestion->suggestion_status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                        {{ ucfirst($suggestion->suggestion_status) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.place-suggestions.show', $suggestion) }}" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No suggestions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="text-center" style="margin-top:20px;">
                {{ $suggestions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
