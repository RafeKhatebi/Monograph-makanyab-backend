@extends('layouts.admin')

@section('title', __('admin.crud.edit').' '. __('admin.navigation.posts'))
@section('page-title', __('admin.crud.edit').' '. __('admin.navigation.posts'))

@section('content')
    @php
        $backSection = match (true) {
            $post->is_published => 'approved',
            $post->submission_status?->value === 'under_review' => 'pending',
            $post->submission_status?->value === 'changes_requested' => 'changes_requested',
            $post->submission_status?->value === 'rejected' => 'rejected',
            default => 'index',
        };
    @endphp
    <section class="card" aria-label="{{ __('admin.crud.edit').' '. __('admin.navigation.posts') }}">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ $post->title }}</h2>
            <a href="{{ route('admin.posts.'.$backSection) }}"
                class="btn btn-outline-secondary btn-sm">{{ __('admin.crud.back') }}</a>
        </div>
        <div class="card-body">
            <div class="admin-status-row">
                <span class="badge {{ $post->is_published ? 'badge-success' : ($post->submission_status?->value === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                    {{ $post->is_published ? __('admin.dashboard.published') : ($post->submission_status?->label() ?? __('admin.dashboard.draft')) }}
                </span>
            </div>
            <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('admin.posts.form')

                <button type="submit" class="btn btn-primary">{{ __('admin.crud.update') }}</button>
            </form>

            @if ($post->submission_status?->value === 'under_review')
                <div class="admin-action-panel admin-mt-1">
                    <h3 class="admin-section-title">{{ __('admin.suggestions.review_actions') }}</h3>
                    <form action="{{ route('admin.posts.approve', $post) }}" method="POST" class="admin-form-block">
                        @csrf
                        <button type="submit" class="btn btn-success admin-btn-block">{{ __('admin.suggestions.approve_publish') }}</button>
                    </form>
                    <form action="{{ route('admin.posts.request-changes', $post) }}" method="POST" class="admin-form-block">
                        @csrf
                        <label for="changes_note" class="form-label">{{ __('admin.suggestions.changes_note') }}</label>
                        <textarea id="changes_note" name="admin_note" rows="3" maxlength="2000" required class="form-control"></textarea>
                        <button type="submit" class="btn btn-outline-primary admin-btn-block admin-mt-1">{{ __('admin.suggestions.request_changes') }}</button>
                    </form>
                    <form action="{{ route('admin.posts.reject', $post) }}" method="POST">
                        @csrf
                        <label for="reject_note" class="form-label">{{ __('admin.suggestions.rejection_note') }}</label>
                        <textarea id="reject_note" name="admin_note" rows="3" maxlength="2000" class="form-control"></textarea>
                        <button type="submit" class="btn btn-outline-danger admin-btn-block admin-mt-1">{{ __('admin.suggestions.reject') }}</button>
                    </form>
                </div>
            @endif
        </div>
    </section>
@endsection
