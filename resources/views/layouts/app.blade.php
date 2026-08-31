<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ config('locales.'.app()->getLocale().'.direction', 'ltr') }}"
    data-open-menu="{{ __('navigation.open_menu') }}" data-close-menu="{{ __('navigation.close_menu') }}"
    data-show-filters="{{ __('search.show_filters') }}" data-hide-filters="{{ __('search.hide_filters') }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="@yield('meta-description', __('common.meta.default_description'))">
        <title>@yield('title', 'Makanyab') - {{ __('common.meta.title_suffix') }}</title>

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
        <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/owl.transitions.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/lightslider.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">

        <!-- Makanyab Green Theme Override -->
        <link rel="stylesheet" href="{{ asset('assets/css/variables.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/makanyab.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/ui-system.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/frontend-components.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/frontend-pages.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/rtl.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/responsive-overrides.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">

        @stack('styles')
    </head>

    <body>

        @include('partials.navbar')
        @include('partials.flash-message')
        <main>@yield('content')</main>
        @include('partials.footer')

        <!-- Scripts -->
        @php
            $appTranslations = [
                'mediaCover' => __('common.media.cover'),
                'mediaSetCover' => __('common.media.set_cover'),
                'mediaMoveEarlier' => __('common.media.move_earlier'),
                'mediaMoveLater' => __('common.media.move_later'),
                'mediaRemoveFile' => __('common.media.remove_file'),
                'remove' => __('common.actions.remove'),
                'saving' => __('common.media.saving'),
            ];
        @endphp
        <script>
            window.AppTranslations = Object.assign(window.AppTranslations || {}, @json($appTranslations));
        </script>
        <script src="{{ asset('assets/js/jquery-1.10.2.min.js') }}"></script>
        <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('assets/js/wow.js') }}"></script>
        <script src="{{ asset('assets/js/icheck.min.js') }}"></script>
        <script src="{{ asset('assets/js/lightslider.min.js') }}"></script>
        <script src="{{ asset('assets/js/main.js') }}"></script>
        <script src="{{ asset('assets/js/navbar.js') }}"></script>
        <script src="{{ asset('assets/js/frontend-pages.js') }}"></script>

        @stack('scripts')
    </body>

</html>
