@extends('layouts.app')

@section('title', '500 Server Error')

@section('content')
    <div class="container text-center mb-5">
        <h1 class="display-1">500</h1>
        <p class="lead">Something went wrong on our side. Please try again later.</p>
        <a href="{{ url('/') }}" class="btn btn-primary p-3 mb-6">Go to Home</a>
    </div>
@endsection
