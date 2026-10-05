@php
    $section = in_array($status, ['pending', 'approved', 'rejected'], true) ? $status : 'all';
    $pageTitle = __('admin.moderation.'.$section.'_places');
@endphp
@extends('layouts.admin')

@section('title', $pageTitle)
@section('page-title', $pageTitle)

@section('content')
    @include('admin.partials.suggestions-index', ['type' => 'places', 'suggestionType' => 'place-suggestions'])
@endsection
