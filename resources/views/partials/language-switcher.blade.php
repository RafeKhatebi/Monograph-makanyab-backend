@php($localeSelectId = 'locale-select-' . uniqid())

<form action="{{ route('locale.update') }}" method="POST" class="mk-language-switcher" aria-label="{{ __('common.language') }}">
    @csrf
    <label for="{{ $localeSelectId }}" class="sr-only">{{ __('common.language') }}</label>
    <select id="{{ $localeSelectId }}" name="locale" onchange="this.form.submit()">
        @foreach (config('locales') as $code => $locale)
            <option value="{{ $code }}" @selected(app()->getLocale() === $code)>
                {{ __('common.languages.' . $code) }}
            </option>
        @endforeach
    </select>
</form>
