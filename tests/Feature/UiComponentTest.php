<?php

use Illuminate\Support\Facades\Blade;

test('ui button variants render stable classes', function () {
    $html = Blade::render('<x-ui.button href="/search" variant="secondary" size="lg" block>Search</x-ui.button>');

    expect($html)
        ->toContain('href="/search"')
        ->toContain('mk-ui-button--secondary')
        ->toContain('mk-ui-button--lg')
        ->toContain('mk-ui-button--block');
});

test('ui form controls render validation state', function () {
    $html = Blade::render(
        '<x-ui.form-group for="email" label="Email" :messages="[\'Email is required.\']" required>'.
        '<x-ui.text-input id="email" name="email" invalid />'.
        '</x-ui.form-group>'
    );

    expect($html)
        ->toContain('mk-ui-form-group')
        ->toContain('mk-ui-required')
        ->toContain('aria-invalid="true"')
        ->toContain('Email is required.');
});

test('legacy auth components use the ui system', function () {
    $html = Blade::render('<x-primary-button class="w-full">Save</x-primary-button>');

    expect($html)
        ->toContain('mk-ui-button')
        ->toContain('mk-ui-button--primary')
        ->toContain('w-full');
});

test('ui empty state and badge render accessible primitives', function () {
    $html = Blade::render('<x-ui.empty-state title="No results" message="Try a different search." /> <x-ui.badge variant="success">Verified</x-ui.badge>');

    expect($html)
        ->toContain('mk-ui-empty')
        ->toContain('No results')
        ->toContain('mk-ui-badge--success');
});
