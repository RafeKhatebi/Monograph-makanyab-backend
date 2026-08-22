@extends('layouts.app')

@section('title', '419 Page Expired')

@section('content')
    <div class="container text-center mb-5">
        <h1 class="display-1">419</h1>
        <p class="lead">Your session has expired. Please refresh the page and try again.</p>
        <a href="{{ url()->previous() ?: url('/') }}" class="btn btn-primary p-3 mb-6">Try Again</a>
    </div>
@endsection
