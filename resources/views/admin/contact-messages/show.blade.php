@extends('layouts.admin')

@section('title', __('admin.contact_messages.single'))
@section('page-title', __('admin.contact_messages.single'))

@section('content')
    <section class="card" aria-label="{{ __('admin.contact_messages.details') }}">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ $message->subject }}</h2>
            <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> {{ __('admin.contact_messages.back') }}
            </a>
        </div>

        <div class="card-body">
            <dl class="admin-detail-grid">
                <dt>{{ __('admin.contact_messages.name') }}</dt>
                <dd>{{ $message->name }}</dd>

                <dt>{{ __('admin.contact_messages.email') }}</dt>
                <dd><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></dd>

                <dt>{{ __('admin.contact_messages.telephone') }}</dt>
                <dd><a href="tel:{{ $message->telephone }}">{{ $message->telephone }}</a></dd>

                <dt>{{ __('admin.contact_messages.submitted_by') }}</dt>
                <dd>{{ $message->user ? $message->user->name.' ('.$message->user->email.')' : __('admin.contact_messages.guest') }}</dd>

                <dt>{{ __('admin.contact_messages.received') }}</dt>
                <dd>{{ $message->created_at->format('M d, Y H:i') }}</dd>

                <dt>{{ __('admin.contact_messages.status') }}</dt>
                <dd>
                    @if ($message->archived_at)
                        <span class="badge badge-secondary">{{ __('admin.contact_messages.archived') }}</span>
                    @elseif ($message->read_at)
                        <span class="badge badge-success">{{ __('admin.contact_messages.read') }}</span>
                    @else
                        <span class="badge badge-warning">{{ __('admin.contact_messages.unread') }}</span>
                    @endif
                </dd>
            </dl>

            <div class="admin-message-body">
                <h3 class="admin-card-title">{{ __('admin.contact_messages.message') }}</h3>
                <p>{{ $message->message }}</p>
            </div>

            <div class="admin-actions">
                <form action="{{ route('admin.contact-messages.mark-unread', $message) }}" method="POST" class="admin-action-form">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">{{ __('common.actions.mark_unread') }}</button>
                </form>

                @if ($message->archived_at)
                    <form action="{{ route('admin.contact-messages.restore', $message) }}" method="POST" class="admin-action-form">
                        @csrf
                        <button type="submit" class="btn btn-outline-success">{{ __('admin.crud.restore') }}</button>
                    </form>
                @else
                    <form action="{{ route('admin.contact-messages.archive', $message) }}" method="POST" class="admin-action-form">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">{{ __('common.actions.archive') }}</button>
                    </form>
                @endif

                <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST"
                    onsubmit="return confirm(@js(__('admin.contact_messages.delete_confirm')));"
                    class="admin-action-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">{{ __('admin.crud.delete') }}</button>
                </form>
            </div>
        </div>
    </section>
@endsection
