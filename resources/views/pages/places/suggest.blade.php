@extends('layouts.app')

@section('title', __('suggestions.place.title'))

@section('content')
    @include('pages.shared.suggestion-page', [
        'title' => __('suggestions.place.title'),
        'description' => __('suggestions.place.description'),
        'switchText' => __('suggestions.place.switch_text'),
        'switchRoute' => 'service-suggestions.create',
        'switchLinkText' => __('suggestions.place.switch_link'),
        'formTitle' => __('suggestions.place.form_title'),
        'formAction' => route('place-suggestions.store'),
        'categoryField' => 'place_category_id',
        'categoryLabel' => __('suggestions.category'),
        'categories' => $categories->pluck('name', 'id')->toArray(),
        'submitText' => __('suggestions.submit_suggestion'),
    ])
@endsection
