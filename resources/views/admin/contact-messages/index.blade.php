@extends('layouts.admin')

@section('title', __('admin.contact_messages.title'))
@section('page-title', __('admin.contact_messages.title'))

@section('content')
    <section class="card" aria-label="{{ __('admin.contact_messages.management') }}">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ __('admin.contact_messages.count', ['count' => $messages->total()]) }}</h2>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.contact-messages.index') }}" role="search" aria-label="{{ __('admin.contact_messages.filter_aria') }}" class="admin-filter-form">
                <div class="admin-filter-field">
                    <label for="search" class="sr-only">{{ __('admin.contact_messages.search_label') }}</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.contact_messages.search_placeholder') }}"
                        class="form-control">
                </div>
                <div>
                    <label for="status" class="sr-only">{{ __('admin.contact_messages.status_label') }}</label>
                    <select id="status" name="status" class="form-select admin-filter-select">
                        <option value="">{{ __('admin.contact_messages.inbox') }}</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>{{ __('admin.contact_messages.unread') }}</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>{{ __('admin.contact_messages.read') }}</option>
                        <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>{{ __('admin.contact_messages.archived') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter" aria-hidden="true"></i> {{ __('admin.crud.filter') }}
                </button>
                <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-secondary">{{ __('admin.crud.clear') }}</a>
            </form>

            <div class="admin-table-wrap">
                <table class="table" aria-label="{{ __('admin.contact_messages.list_aria') }}">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('admin.contact_messages.from') }}</th>
                            <th scope="col">{{ __('admin.contact_messages.subject') }}</th>
                            <th scope="col">{{ __('admin.contact_messages.status') }}</th>
                            <th scope="col">{{ __('admin.contact_messages.received') }}</th>
                            <th scope="col">{{ __('admin.contact_messages.actions') }}</th>
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
                                        <span class="badge badge-secondary">{{ __('admin.contact_messages.archived') }}</span>
                                    @elseif ($message->read_at)
                                        <span class="badge badge-success">{{ __('admin.contact_messages.read') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ __('admin.contact_messages.unread') }}</span>
                                    @endif
                                </td>
                                <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="{{ route('admin.contact-messages.show', $message) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            aria-label="{{ __('admin.contact_messages.view_from', ['name' => Str::limit($message->name, 30)]) }}">{{ __('admin.crud.view') }}</a>
                                        @if ($message->archived_at)
                                            <form action="{{ route('admin.contact-messages.restore', $message) }}" method="POST" class="admin-action-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">{{ __('admin.crud.restore') }}</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.contact-messages.archive', $message) }}" method="POST" class="admin-action-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-secondary">{{ __('common.actions.archive') }}</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="admin-empty">
                                    {{ __('admin.contact_messages.empty') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($messages->hasPages())
                <nav class="admin-pagination" aria-label="{{ __('admin.contact_messages.pagination') }}">
                    {{ $messages->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
