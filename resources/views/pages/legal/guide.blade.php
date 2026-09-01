@extends('layouts.app')

@php
    $page = __('content.guides.' . $guide);
@endphp

@section('title', $page['title'])

@section('content')
    <section class="mk-hero">
        <div class="container">
            <h1 class="mk-hero__title">{{ $page['title'] }}</h1>
            <p class="mk-hero__text">{{ $page['intro'] }}</p>
        </div>
    </section>

    <section class="mk-page-section">
        <div class="container">
            <div class="static-content static-content--guide">
                @foreach ($page['sections'] as $section)
                    <section class="static-content__section">
                        <h2 class="mk-heading mk-heading--md">{{ $section['title'] }}</h2>
                        <p class="mk-text">{{ $section['body'] }}</p>
                    </section>
                @endforeach

                <div class="static-content__actions">
                    <x-ui.button :href="route('contact')" variant="secondary">
                        {{ __('navigation.contact') }}
                    </x-ui.button>
                </div>
            </div>
        </div>
    </section>
@endsection
