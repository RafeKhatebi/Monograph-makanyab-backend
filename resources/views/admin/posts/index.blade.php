@extends('layouts.admin')

@section('title', __('admin.moderation.'.$section.'_posts'))
@section('page-title', __('admin.moderation.'.$section.'_posts'))

@section('content')
    <section class="card" aria-label="{{ __('admin.moderation.'.$section.'_posts') }}">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ __('admin.moderation.'.$section.'_posts') }} ({{ $posts->total() }})</h2>
        </div>

        <div class="card-body">
            @include('admin.partials.moderation-tabs', ['type' => 'posts', 'activeSection' => $section])

            <form method="GET" action="{{ url()->current() }}" role="search" aria-label="{{ __('admin.crud.search', ['item' => __('admin.navigation.posts')]) }}" class="admin-filter-form">
                <div class="admin-filter-field">
                    <label for="search" class="sr-only">{{ __('admin.crud.search', ['item' => __('admin.navigation.posts')]) }}</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.crud.search', ['item' => __('admin.navigation.posts')]) }}"
                        class="form-control">
                </div>
                @if ($section === 'all')
                    <div>
                        <label for="is_published" class="sr-only">{{ __('admin.dashboard.status') }}</label>
                        <select id="is_published" name="is_published" class="form-select admin-filter-select">
                            <option value="">{{ __('admin.crud.all_status') }}</option>
                            <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>{{ __('admin.dashboard.published') }}</option>
                            <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>{{ __('admin.dashboard.draft') }}</option>
                        </select>
                    </div>
                @endif
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter" aria-hidden="true"></i> {{ __('admin.crud.filter') }}
                </button>
                <a href="{{ route('admin.posts.'.($section === 'all' ? 'index' : $section)) }}" class="btn btn-outline-secondary">{{ __('admin.crud.clear') }}</a>
            </form>

            <div class="admin-table-wrap">
                <table class="table" aria-label="{{ __('admin.moderation.'.$section.'_posts') }}">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('suggestions.title') }}</th>
                            <th scope="col">{{ __('content.posts.by') }}</th>
                            <th scope="col">{{ __('admin.dashboard.status') }}</th>
                            <th scope="col">{{ __('content.posts.published') }}</th>
                            <th scope="col">{{ __('admin.crud.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>
                                    {{ Str::limit($post->title, 50) }}
                                    @if (in_array($post->submission_status?->value, ['rejected', 'changes_requested'], true))
                                        <small class="admin-inline-muted">{{ __('admin.moderation.review_note') }}: {{ $post->admin_note ?: __('admin.moderation.no_note') }}</small>
                                    @endif
                                </td>
                                <td>{{ $post->user->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $post->is_published ? 'badge-success' : ($post->submission_status?->value === 'rejected' ? 'badge-danger' : ($post->submission_status?->value === 'under_review' ? 'badge-warning' : 'badge-secondary')) }}">
                                        {{ $post->is_published ? __('admin.dashboard.published') : ($post->submission_status?->label() ?? __('admin.dashboard.draft')) }}
                                    </span>
                                </td>
                                <td>{{ $post->published_at ? \App\Support\LocalizedDate::date($post->published_at) : '-' }}</td>
                                <td>
                                    <div class="admin-actions">
                                        @if ($post->submission_status?->value === 'under_review')
                                            <form action="{{ route('admin.posts.approve', $post) }}" method="POST" class="admin-action-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary">{{ __('admin.suggestions.approve') }}</button>
                                            </form>
                                            <form action="{{ route('admin.posts.reject', $post) }}" method="POST" class="admin-action-form">
                                                @csrf
                                                <label for="reject-note-{{ $post->id }}" class="sr-only">{{ __('admin.moderation.review_note') }}</label>
                                                <input id="reject-note-{{ $post->id }}" type="text" name="admin_note" maxlength="2000"
                                                    placeholder="{{ __('admin.moderation.review_note') }}" class="form-control admin-review-note">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('admin.suggestions.reject') }}</button>
                                            </form>
                                            <form action="{{ route('admin.posts.request-changes', $post) }}" method="POST" class="admin-action-form">
                                                @csrf
                                                <label for="changes-note-{{ $post->id }}" class="sr-only">{{ __('admin.suggestions.changes_note') }}</label>
                                                <input id="changes-note-{{ $post->id }}" type="text" name="admin_note" maxlength="2000" required
                                                    placeholder="{{ __('admin.suggestions.changes_note') }}" class="form-control admin-review-note">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">{{ __('admin.suggestions.request_changes') }}</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.posts.edit', $post) }}"
                                            class="btn btn-sm btn-outline-success"
                                            aria-label="{{ __('admin.crud.edit') }} {{ Str::limit($post->title, 30) }}">{{ __('admin.crud.edit') }}</a>
                                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                            data-confirm-delete="{{ $post->title }}"
                                            class="admin-action-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                aria-label="{{ __('admin.crud.delete') }} {{ Str::limit($post->title, 30) }}">{{ __('admin.crud.delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="admin-empty">
                                    {{ __('admin.crud.no_found', ['item' => __('admin.navigation.posts')]) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($posts->hasPages())
                <nav class="admin-pagination" aria-label="{{ __('admin.moderation.'.$section.'_posts') }}">
                    {{ $posts->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
