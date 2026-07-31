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
    <a href="{{ route('social.redirect', 'facebook') }}"
        class="mk-social-auth__button mk-social-auth__button--facebook" data-loading-text="Redirecting to Facebook..."
        aria-label="{{ $action }} with Facebook">
        <i class="fa fa-facebook" aria-hidden="true"></i>
        <span>{{ $action }} with Facebook</span>
    </a>
</div>

<div class="mk-auth-divider" role="separator">
    <span>or continue with email</span>
</div>

@once
    @push('styles')
        <style>
            .mk-social-auth {
                display: grid;
                gap: 10px;
                margin-bottom: 20px;
            }

            .mk-social-auth__button {
                align-items: center;
                border: 1px solid #D1D5DB;
                border-radius: 8px;
                color: #111827;
                display: flex;
                font-size: 14px;
                font-weight: 700;
                gap: 10px;
                height: 46px;
                justify-content: center;
                text-decoration: none;
                transition: border-color .2s ease, background .2s ease, color .2s ease, opacity .2s ease;
                width: 100%;
            }

            .mk-social-auth__button:focus,
            .mk-social-auth__button:hover {
                border-color: #10B981;
                color: #065F46;
                outline: 2px solid rgba(16, 185, 129, .18);
                outline-offset: 2px;
                text-decoration: none;
            }

            .mk-social-auth__button--facebook i {
                color: #1877F2;
            }

            .mk-social-auth__button--google i {
                color: #DB4437;
            }

            .mk-social-auth__button.is-loading {
                opacity: .68;
                pointer-events: none;
            }

            .mk-auth-divider {
                align-items: center;
                color: #6B7280;
                display: flex;
                font-size: 13px;
                gap: 12px;
                margin: 20px 0;
                text-align: center;
            }

            .mk-auth-divider::before,
            .mk-auth-divider::after {
                background: #E5E7EB;
                content: "";
                flex: 1;
                height: 1px;
            }
        </style>
    @endpush

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
