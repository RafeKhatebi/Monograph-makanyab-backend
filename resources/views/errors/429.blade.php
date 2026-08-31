@extends('layouts.app')

@section('title', __('errors.429.title'))

@section('content')
    <div class="container text-center mb-5">
        <h1 class="display-1">429</h1>
        <p class="lead">{{ __('errors.429.description') }}</p>
        <a href="{{ url('/') }}" class="btn btn-primary p-3 mb-6">{{ __('errors.429.action') }}</a>
    </div>
@endsection
