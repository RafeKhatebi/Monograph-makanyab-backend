@extends('layouts.admin')

@section('title', __('admin.crud.manage', ['item' => __('admin.navigation.posts')]))
@section('page-title', __('admin.navigation.posts'))

@section('content')
    <section class="card" aria-label="{{ __('admin.crud.manage', ['item' => __('admin.navigation.posts')]) }}">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ __('admin.crud.all', ['item' => __('admin.navigation.posts')]) }} ({{ $posts->total() }})</h2>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.posts.index') }}" role="search" aria-label="{{ __('admin.crud.search', ['item' => __('admin.navigation.posts')]) }}" class="admin-filter-form">
                <div class="admin-filter-field">
                    <label for="search" class="sr-only">{{ __('admin.crud.search', ['item' => __('admin.navigation.posts')]) }}</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.crud.search', ['item' => __('admin.navigation.posts')]) }}"
                        class="form-control">
                </div>
                <div>
                    <label for="is_published" class="sr-only">{{ __('admin.dashboard.status') }}</label>
                    <select id="is_published" name="is_published" class="form-select admin-filter-select">
                        <option value="">{{ __('admin.crud.all_status') }}</option>
                        <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>{{ __('admin.dashboard.published') }}</option>
                        <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>{{ __('admin.dashboard.draft') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter" aria-hidden="true"></i> {{ __('admin.crud.filter') }}
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">{{ __('admin.crud.clear') }}</a>
            </form>

            <div class="admin-table-wrap">
                <table class="table" aria-label="{{ __('admin.crud.all', ['item' => __('admin.navigation.posts')]) }}">
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
                                <td>{{ Str::limit($post->title, 50) }}</td>
                                <td>{{ $post->user->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $post->is_published ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $post->is_published ? __('admin.dashboard.published') : __('admin.dashboard.draft') }}
                                    </span>
                                </td>
                                <td>{{ $post->published_at ? \App\Support\LocalizedDate::date($post->published_at) : '-' }}</td>
                                <td>
                                    <div class="admin-actions">
                                        @unless($post->is_published)
                                            <form action="{{ route('admin.posts.approve', $post) }}" method="POST" class="admin-action-form">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary">{{ __('admin.suggestions.approve') }}</button>
                                            </form>
                                        @endunless
                                        <a href="{{ route('admin.posts.edit', $post) }}"
                                            class="btn btn-sm btn-outline-success"
                                            aria-label="{{ __('admin.crud.edit') }} {{ Str::limit($post->title, 30) }}">{{ __('admin.crud.edit') }}</a>
                                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                            onsubmit="return confirm('{{ __('admin.crud.confirm_delete', ['item' => __('admin.navigation.posts')]) }}');"
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
                <nav class="admin-pagination" aria-label="{{ __('admin.navigation.posts') }}">
                    {{ $posts->links() }}
                </nav>
            @endif
        </div>
    </section>
@endsection
