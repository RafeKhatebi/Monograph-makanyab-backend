@props(['context' => 'signin'])

@php
    $action = $context === 'register' ? 'Sign up' : 'Sign in';
@endphp

<div class="mk-social-auth" data-social-auth>
    <a href="{{ route('social.redirect', 'google') }}" class="mk-social-auth__button mk-social-auth__button--google"
        data-loading-text="Redirecting to Google..." aria-label="{{ $action }} with Google">
        <i class="fa fa-google" aria-hidden="true"></i>
        <span>{{ $action }} with Google</span>
    </a>
    <a href="{{ route('social.redirect', 'facebook') }}" class="mk-social-auth__button mk-social-auth__button--facebook"
        data-loading-text="Redirecting to Facebook..." aria-label="{{ $action }} with Facebook">
        <i class="fa fa-facebook" aria-hidden="true"></i>
        <span>{{ $action }} with Facebook</span>
    </a>
</div>

<div class="mk-auth-divider" role="separator">
    <span>or continue with email</span>
</div>

@once
    @push('scripts')
        <script>
            document.querySelectorAll('[data-social-auth] a').forEach(function (link) {
                link.addEventListener('click', function () {
                    link.classList.add('is-loading');
                    link.setAttribute('aria-disabled', 'true');
                    link.querySelector('span').textContent = link.dataset.loadingText;
                });
            });
        </script>
    @endpush
@endonce
