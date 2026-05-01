@extends('layouts.app')

@section('title', 'Suggest a Place')

@section('content')
    @include('pages.shared.suggestion-page', [
        'title' => 'Suggest a Place',
        'description' => 'Know a place that should be on Makanyab? Submit it and our admin team will review it before it goes live.',
        'switchText' => 'Want to suggest a service instead?',
        'switchRoute' => 'service-suggestions.create',
        'switchLinkText' => 'Submit a service suggestion',
        'formTitle' => 'Submit a new place',
        'formAction' => route('place-suggestions.store'),
        'categoryField' => 'place_category_id',
        'categoryLabel' => 'Category',
        'categories' => $categories->pluck('name', 'id')->toArray(),
        'submitText' => 'Submit Suggestion',
    ])
@endsection
