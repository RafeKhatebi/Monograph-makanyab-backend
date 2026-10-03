<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ config('locales.'.app()->getLocale().'.direction', 'ltr') }}"
    data-show-password="{{ __('auth.layout.show_password') }}" data-hide-password="{{ __('auth.layout.hide_password') }}">

     <head>
         <meta charset="utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1">
         <meta name="csrf-token" content="{{ csrf_token() }}">
         <title>@yield('title', 'Makanyab')</title>
         <link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/branding/makanyab-app-icon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Naskh+Arabic:wght@400;500;600;700&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap"
            rel="stylesheet">

        <!-- Garo Estate Theme CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/normalize.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/fonts/icon-7-stroke/css/pe-icon-7-stroke.css') }}">
        <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/variables.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/makanyab.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/ui-system.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/frontend-components.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/frontend-pages.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/rtl.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/responsive-overrides.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">

        @stack('styles')
    </head>

    <body class="auth-page">
        <a class="mk-skip-link" href="#auth-main">{{ __('common.skip_to_content') }}</a>
        <div class="auth-layout">
            <div class="auth-layout__illustration">
                <img src="{{ asset('assets/img/branding/makanyab-auth-discovery-illustration.png') }}" alt="" class="auth-illustration__img">
                <div class="auth-brand-message">
                    <span class="auth-brand-message__eyebrow">{{ __('auth.layout.eyebrow') }}</span>
                    <h2>{{ __('auth.layout.brand_title') }}</h2>
                    <p>{{ __('auth.layout.brand_description') }}</p>
                </div>
            </div>
            <div class="auth-layout__form">
                <div class="auth-layout__topbar">
                    <a href="{{ route('home') }}" class="auth-home-link">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        {{ __('auth.layout.back_home') }}
                    </a>
                    @include('partials.language-switcher')
                </div>
                <main id="auth-main">@yield('content')</main>
            </div>
        </div>

        <script src="{{ asset('assets/js/jquery-1.10.2.min.js') }}"></script>
        <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/auth.js') }}"></script>

        @stack('scripts')
    </body>

</html>
