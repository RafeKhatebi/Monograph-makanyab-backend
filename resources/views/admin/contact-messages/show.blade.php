@extends('layouts.admin')

@section('title', 'Contact Message')
@section('page-title', 'Contact Message')

@section('content')
    <section class="card" aria-label="Contact Message Details">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ $message->subject }}</h2>
            <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
            </a>
        </div>

        <div class="card-body">
            <dl class="admin-detail-grid">
                <dt>Name</dt>
                <dd>{{ $message->name }}</dd>

                <dt>Email</dt>
                <dd><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></dd>

                <dt>Telephone</dt>
                <dd><a href="tel:{{ $message->telephone }}">{{ $message->telephone }}</a></dd>

                <dt>Submitted By</dt>
                <dd>{{ $message->user ? $message->user->name.' ('.$message->user->email.')' : 'Guest' }}</dd>

                <dt>Received</dt>
                <dd>{{ $message->created_at->format('M d, Y H:i') }}</dd>

                <dt>Status</dt>
                <dd>
                    @if ($message->archived_at)
                        <span class="badge badge-secondary">Archived</span>
                    @elseif ($message->read_at)
                        <span class="badge badge-success">Read</span>
                    @else
                        <span class="badge badge-warning">Unread</span>
                    @endif
                </dd>
            </dl>

            <div class="admin-message-body">
                <h3 class="admin-card-title">Message</h3>
                <p>{{ $message->message }}</p>
            </div>

            <div class="admin-actions">
                <form action="{{ route('admin.contact-messages.mark-unread', $message) }}" method="POST" class="admin-action-form">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">Mark Unread</button>
                </form>

                @if ($message->archived_at)
                    <form action="{{ route('admin.contact-messages.restore', $message) }}" method="POST" class="admin-action-form">
                        @csrf
                        <button type="submit" class="btn btn-outline-success">Restore</button>
                    </form>
                @else
                    <form action="{{ route('admin.contact-messages.archive', $message) }}" method="POST" class="admin-action-form">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">Archive</button>
                    </form>
                @endif

                <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST"
                    onsubmit="return confirm('Delete this contact message?');"
                    class="admin-action-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">Delete</button>
                </form>
            </div>
        </div>
    </section>
@endsection
