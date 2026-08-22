@extends('layouts.app')
@section('title', __('errors.404.title'))
@section('content')
    <div class="container text-center mb-5">
        <h1 class="display-1">404</h1>
        <p class="lead">{{ __('errors.404.description') }}</p>
        <a href="{{ url('/') }}" class="btn btn-primary p-3 mb-6">{{ __('errors.404.action') }}</a>
    </div>
@endsection
