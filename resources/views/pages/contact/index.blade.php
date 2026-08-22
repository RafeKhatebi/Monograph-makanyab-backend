@extends('layouts.app')
@section('title', __('contact.title'))
@section('content')

    <div class="mk-hero">
        <div class="container text-center">
            <h1 class="mk-hero__title">{{ __('contact.title') }}</h1>
            <p class="mk-hero__text mk-hero__text--center">
                {{ __('contact.intro') }}
            </p>
        </div>
    </div>

    <div class="mk-page-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mk-stack-md">
                    <div class="mk-card mk-card--feature">
                        <h3 class="mk-heading mk-heading--md">{{ __('contact.get_in_touch') }}</h3>
                        <p class="mk-text mk-text--muted mk-text--compact mk-stack-sm">
                            {{ __('contact.help') }}
                        </p>

                        <div class="mk-icon-item">
                            <div class="mk-icon-box"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                            <div>
                                <p class="mk-meta-label">{{ __('contact.office') }}</p>
                                <p class="mk-meta-value">Herat, Afghanistan</p>
                            </div>
                        </div>

                        <div class="mk-icon-item">
                            <div class="mk-icon-box"><i class="fa fa-phone" aria-hidden="true"></i></div>
                            <div>
                                <p class="mk-meta-label">{{ __('places.phone') }}</p>
                                <a href="tel:+93728958411">+93 728 958 411</a>
                            </div>
                        </div>

                        <div class="mk-icon-item">
                            <div class="mk-icon-box"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                            <div>
                                <p class="mk-meta-label">{{ __('auth.ui.email') }}</p>
                                <a href="mailto:info@makanyab.com">info@makanyab.com</a>
                            </div>
                        </div>

                        <div class="mk-icon-item">
                            <div class="mk-icon-box"><i class="fa fa-clock-o" aria-hidden="true"></i></div>
                            <div>
                                <p class="mk-meta-label">{{ __('contact.working_hours') }}</p>
                                <p class="mk-meta-value">Sat - Thu, 9:00 AM - 6:00 PM</p>
                            </div>
                        </div>

                        <div class="mk-social">
                            <p class="mk-meta-label mk-stack-sm">{{ __('contact.follow') }}</p>
                            <div class="mk-social__links">
                                <a href="#" class="mk-social__link" aria-label="Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                <a href="#" class="mk-social__link" aria-label="Instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                                <a href="#" class="mk-social__link" aria-label="Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 mk-stack-md">
                    <div class="mk-card">
                        <h3 class="mk-heading mk-heading--md mk-stack-sm">{{ __('contact.send_message') }}</h3>

                        @if (session('success'))
                            <div class="mk-alert mk-alert--success">
                                <i class="fa fa-check-circle" aria-hidden="true"></i> {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mk-form-group">
                                        <label class="mk-label">{{ __('contact.full_name') }} *</label>
                                        <input type="text" name="name" value="{{ old('name') }}" required class="mk-input">
                                        @error('name')
                                            <small class="mk-error">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mk-form-group">
                                        <label class="mk-label">{{ __('places.phone') }} *</label>
                                        <input type="text" name="telephone" value="{{ old('telephone') }}" required class="mk-input">
                                        @error('telephone')
                                            <small class="mk-error">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mk-form-group">
                                <label class="mk-label">{{ __('auth.ui.email') }} *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="mk-input">
                                @error('email')
                                    <small class="mk-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mk-form-group">
                                <label class="mk-label">{{ __('contact.subject') }}</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" class="mk-input">
                            </div>

                            <div class="mk-form-group">
                                <label class="mk-label">{{ __('contact.message') }} *</label>
                                <textarea name="message" rows="5" required class="mk-textarea">{{ old('message') }}</textarea>
                                @error('message')
                                    <small class="mk-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="mk-button mk-button--primary mk-button--lg">
                                <i class="fa fa-paper-plane" aria-hidden="true"></i> {{ __('contact.send') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mk-map-frame">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31119.506003890427!2d62.21074765598502!3d34.33735505799986!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x38dcb80c8ebfb51f%3A0x9618cd21c33e93b0!2sHerat%2C%20Afghanistan!5e0!3m2!1sen!2sus!4v0000000000000000"
                            allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
