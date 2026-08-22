@extends('layouts.admin')

@section('title', 'Contact Messages')
@section('page-title', 'Contact Messages')

@section('content')
    <section class="card" aria-label="Contact Messages Management">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">Contact Messages ({{ $messages->total() }})</h2>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.contact-messages.index') }}" role="search" aria-label="Filter contact messages" class="admin-filter-form">
                <div class="admin-filter-field">
                    <label for="search" class="sr-only">Search messages</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone, or subject..."
                        class="form-control">
                </div>
                <div>
                    <label for="status" class="sr-only">Filter by status</label>
                    <select id="status" name="status" class="form-select admin-filter-select">
                        <option value="">Inbox</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                        <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter" aria-hidden="true"></i> Filter
                </button>
                <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-secondary">Clear</a>
            </form>

            <div class="admin-table-wrap">
                <table class="table" aria-label="Contact messages list">
                    <thead>
                        <tr>
                            <th scope="col">From</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Status</th>
                            <th scope="col">Received</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                            <tr>
                                <td>
                                    <strong>{{ Str::limit($message->name, 35) }}</strong><br>
                                    <span class="text-muted">{{ Str::limit($message->email, 45) }}</span>
                                </td>
                                <td>{{ Str::limit($message->subject, 55) }}</td>
                                <td>
                                    @if ($message->archived_at)
                                        <span class="badge badge-secondary">Archived</span>
                                    @elseif ($message->read_at)
                                        <span class="badge badge-success">Read</span>
                                    @else
                                        <span class="badge badge-warning">Unread</span>
                                    @endif
                                </td>
                                <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="{{ route('admin.contact-messages.show', $message) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            aria-label="View message from {{ Str::limit($message->name, 30) }}">View</a>
                                        @if ($message->archived_at)
                                            <form action="{{ route('admin.contact-messages.restore', $message) }}" method="POST" class="admin-action-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">Restore</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.contact-messages.archive', $message) }}" method="POST" class="admin-action-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-secondary">Archive</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="admin-empty">
                                    No contact messages found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($messages->hasPages())
                <nav class="admin-pagination" aria-label="Contact messages pagination">
                    {{ $messages->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
