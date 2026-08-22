@extends('layouts.app')

@section('title', '429 Too Many Requests')

@section('content')
    <div class="container text-center mb-5">
        <h1 class="display-1">429</h1>
        <p class="lead">Too many requests were sent in a short time. Please wait and try again.</p>
        <a href="{{ url('/') }}" class="btn btn-primary p-3 mb-6">Go to Home</a>
    </div>
@endsection
