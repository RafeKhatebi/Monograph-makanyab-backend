@php
    $localeSelectId = 'locale-select-' . uniqid();
    $variant = $variant ?? 'select';
    $activeLocale = app()->getLocale();
@endphp

@if ($variant === 'icon')
    <div class="mk-language-menu" data-language-menu>
        <button type="button"
            class="mk-language-trigger"
            aria-label="{{ __('common.language') }}"
            aria-haspopup="true"
            aria-expanded="false"
            aria-controls="{{ $localeSelectId }}-menu"
            data-language-trigger>
            <i class="fa fa-globe" aria-hidden="true"></i>
            <span class="sr-only">{{ __('common.language') }}</span>
        </button>
        <div class="mk-language-popover" id="{{ $localeSelectId }}-menu" role="menu" aria-hidden="true" data-language-popover>
            @foreach (config('locales') as $code => $locale)
                <form action="{{ route('locale.update') }}" method="POST" class="mk-language-option-form">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">
                    <input type="hidden" name="locale" value="{{ $code }}">
                    <button type="submit"
                        class="mk-language-option {{ $activeLocale === $code ? 'is-active' : '' }}"
                        role="menuitemradio"
                        aria-checked="{{ $activeLocale === $code ? 'true' : 'false' }}">
                        <span class="mk-language-option__text">
                            <span class="mk-language-option__name">{{ __('common.languages.' . $code) }}</span>
                            <span class="mk-language-option__code">{{ strtoupper($code) }}</span>
                        </span>
                        @if ($activeLocale === $code)
                            <i class="fa fa-check" aria-hidden="true"></i>
                        @endif
                    </button>
                </form>
            @endforeach
        </div>
    </div>
@else
    <form action="{{ route('locale.update') }}" method="POST" class="mk-language-switcher" aria-label="{{ __('common.language') }}">
        @csrf
        <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">
        <label for="{{ $localeSelectId }}" class="sr-only">{{ __('common.language') }}</label>
        <select id="{{ $localeSelectId }}" name="locale" onchange="this.form.submit()">
            @foreach (config('locales') as $code => $locale)
                <option value="{{ $code }}" @selected($activeLocale === $code)>
                    {{ __('common.languages.' . $code) }}
                </option>
            @endforeach
        </select>
    </form>
@endif
