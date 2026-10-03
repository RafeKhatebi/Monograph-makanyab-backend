@props([
    'name' => 'content',
    'value' => '',
    'label' => null,
    'required' => false,
    'placeholder' => '',
])

@php($editorId = $attributes->get('id', $name))

<div class="markdown-editor" data-markdown-editor data-preview-url="{{ route('add.markdown-preview') }}"
    data-preview-empty="{{ __('suggestions.markdown.preview_empty') }}"
    data-preview-error="{{ __('suggestions.markdown.preview_error') }}">
    @if ($label)
        <label for="{{ $editorId }}">{{ $label }} @if ($required)<span aria-hidden="true">*</span>@endif</label>
    @endif

    <div class="markdown-editor__toolbar" role="toolbar" aria-label="{{ __('suggestions.markdown.toolbar') }}">
        <button type="button" data-markdown-action="heading" title="{{ __('suggestions.markdown.heading') }}" aria-label="{{ __('suggestions.markdown.heading') }}"><strong>H2</strong></button>
        <button type="button" data-markdown-action="bold" title="{{ __('suggestions.markdown.bold') }}" aria-label="{{ __('suggestions.markdown.bold') }}"><strong>B</strong></button>
        <button type="button" data-markdown-action="italic" title="{{ __('suggestions.markdown.italic') }}" aria-label="{{ __('suggestions.markdown.italic') }}"><em>I</em></button>
        <button type="button" data-markdown-action="quote" title="{{ __('suggestions.markdown.quote') }}" aria-label="{{ __('suggestions.markdown.quote') }}">❝</button>
        <button type="button" data-markdown-action="list" title="{{ __('suggestions.markdown.list') }}" aria-label="{{ __('suggestions.markdown.list') }}">• {{ __('suggestions.markdown.list_short') }}</button>
        <button type="button" data-markdown-action="link" title="{{ __('suggestions.markdown.link') }}" aria-label="{{ __('suggestions.markdown.link') }}"><i class="fa fa-link" aria-hidden="true"></i></button>
        <button type="button" data-markdown-action="code" title="{{ __('suggestions.markdown.code') }}" aria-label="{{ __('suggestions.markdown.code') }}">&lt;/&gt;</button>
        <button type="button" data-markdown-action="table" title="{{ __('suggestions.markdown.table') }}" aria-label="{{ __('suggestions.markdown.table') }}"><i class="fa fa-table" aria-hidden="true"></i></button>
        <button type="button" data-markdown-action="image" title="{{ __('suggestions.markdown.image') }}" aria-label="{{ __('suggestions.markdown.image') }}"><i class="fa fa-image" aria-hidden="true"></i></button>
    </div>

    <div class="markdown-editor__tabs" role="tablist" aria-label="{{ __('suggestions.markdown.mode') }}">
        <button type="button" role="tab" aria-selected="true" data-markdown-tab="write">{{ __('suggestions.markdown.write') }}</button>
        <button type="button" role="tab" aria-selected="false" data-markdown-tab="preview">{{ __('suggestions.markdown.preview') }}</button>
    </div>

    <textarea id="{{ $editorId }}" name="{{ $name }}" rows="12" maxlength="20000"
        placeholder="{{ $placeholder }}" dir="auto" @required($required)
        {{ $attributes->except('id') }} data-markdown-input>{{ $value }}</textarea>
    <div class="markdown-editor__preview detail-copy detail-copy--article" dir="auto" role="tabpanel"
        aria-live="polite" hidden data-markdown-preview></div>
    <p class="markdown-editor__help">{{ __('suggestions.markdown.help') }}</p>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>

@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/markdown-editor.css') }}">
    @endpush
    @push('scripts')
        <script src="{{ asset('assets/js/markdown-editor.js') }}"></script>
    @endpush
@endonce
