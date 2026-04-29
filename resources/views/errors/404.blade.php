@extends('layouts.app')
@section('title', '404 Not Found')
@section('content')
    <div class="container text-center mb-5">
        <h1 class="display-1">404</h1>
        <p class="lead">Sorry, the page you are looking for could not be found.</p>
        <a href="{{ url('/') }}" class="btn btn-primary p-3 mb-6">Go to Home</a>
    </div>
@endsection
