@extends('layouts.app')

@section('title', '403 Forbidden')

@section('content')
    <div class="container text-center mb-5">
        <h1 class="display-1">403</h1>
        <p class="lead">You do not have permission to access this page.</p>
        <a href="{{ url('/') }}" class="btn btn-primary p-3 mb-6">Go to Home</a>
    </div>
@endsection
