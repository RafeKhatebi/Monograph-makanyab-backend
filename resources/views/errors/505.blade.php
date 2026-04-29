@extends('layouts.app')
@section('title', '505 Server Error!')
@section('content')
    <div class="container text-center mb-5">
        <h1 class="display-1">505</h1>
        <p class="lead">Sorry, there is an error with server...</p>
        <a href="{{ url('/') }}" class="btn btn-primary p-3 mb-6">Go to Home</a>
    </div>
@endsection
