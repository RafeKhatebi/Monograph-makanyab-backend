@extends('layouts.app')

@section('title', 'Suggest a Service')

@section('content')
    @include('pages.shared.suggestion-page', [
        'title' => 'Suggest a Service',
        'description' =>
            'Know a service that should be listed on Makanyab? Submit it and our admin team will review it before it goes live.',
        'switchText' => 'If you meant to suggest a place instead,',
        'switchRoute' => 'place-suggestions.create',
        'switchLinkText' => 'click here',
        'formTitle' => 'Submit a new service',
        'formAction' => route('service-suggestions.store'),
        'categoryField' => 'service_category_id',
        'categoryLabel' => 'Category',
        'categories' => $categories->pluck('name', 'id')->toArray(),
        'submitText' => 'Submit Suggestion',
    ])
@endsection
