@extends('layouts.app')

@section('title', __('suggestions.service.title'))

@section('content')
    @include('pages.shared.suggestion-page', [
        'title' => __('suggestions.service.title'),
        'description' => __('suggestions.service.description'),
        'switchText' => __('suggestions.service.switch_text'),
        'switchRoute' => 'place-suggestions.create',
        'switchLinkText' => __('suggestions.service.switch_link'),
        'formTitle' => __('suggestions.service.form_title'),
        'formAction' => route('service-suggestions.store'),
        'categoryField' => 'service_category_id',
        'categoryLabel' => __('suggestions.category'),
        'categories' => $categories->pluck('name', 'id')->toArray(),
        'submitText' => __('suggestions.submit_suggestion'),
    ])
@endsection
