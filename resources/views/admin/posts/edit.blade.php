@extends('layouts.admin')

@section('title', __('admin.crud.edit').' '. __('admin.navigation.posts'))
@section('page-title', __('admin.crud.edit').' '. __('admin.navigation.posts'))

@section('content')
    <section class="card" aria-label="{{ __('admin.crud.edit').' '. __('admin.navigation.posts') }}">
        <div class="card-header admin-card-header">
            <h2 class="admin-card-title">{{ $post->title }}</h2>
            <a href="{{ route('admin.posts.'.($post->submission_status?->value === 'under_review' ? 'pending' : ($post->submission_status?->value === 'rejected' ? 'rejected' : ($post->is_published ? 'approved' : 'index')))) }}"
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
        </div>
    </section>
@endsection
