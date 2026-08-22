<?php

test('english is the default locale and uses ltr direction', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('lang="en" dir="ltr"', false);
});

test('users can switch locale and preserve the current route', function () {
    $this->from('/search')
        ->post(route('locale.update'), ['locale' => 'fa'])
        ->assertRedirect('/search');

    $this->get('/search')
        ->assertOk()
        ->assertSee('lang="fa" dir="rtl"', false)
        ->assertSee('جستجو');

    $this->get('/about')
        ->assertOk()
        ->assertSee('lang="fa" dir="rtl"', false);
});

test('pashto uses rtl direction and remains available after switching', function () {
    $this->post(route('locale.update'), ['locale' => 'ps'])
        ->assertRedirect(route('home'));

    $this->get('/')
        ->assertOk()
        ->assertSee('lang="ps" dir="rtl"', false)
        ->assertSee('ژبه');
});

test('unsupported locales are rejected', function () {
    $this->from('/')
        ->post(route('locale.update'), ['locale' => '<script>'])
        ->assertSessionHasErrors('locale')
        ->assertRedirect('/');

    expect(session('locale'))->toBeNull();
});

test('all supported locales expose the same translation keys', function () {
    $flatten = function (array $lines, string $prefix = '') use (&$flatten): array {
        $keys = [];

        foreach ($lines as $key => $value) {
            $path = $prefix === '' ? (string) $key : $prefix.'.'.$key;

            if (is_array($value)) {
                $keys = array_merge($keys, $flatten($value, $path));
            } else {
                $keys[] = $path;
            }
        }

        return $keys;
    };

    $localeKeys = [];

    foreach (['en', 'fa', 'ps'] as $locale) {
        $keys = [];

        foreach (glob(lang_path($locale.'/*.php')) as $file) {
            $lines = require $file;

            foreach ($flatten($lines, pathinfo($file, PATHINFO_FILENAME)) as $key) {
                $keys[] = $key;
            }
        }

        sort($keys);
        $localeKeys[$locale] = $keys;
    }

    expect($localeKeys['fa'])->toBe($localeKeys['en'])
        ->and($localeKeys['ps'])->toBe($localeKeys['en']);
});

test('localized javascript bridge is rendered for media controls', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('window.AppTranslations', false)
        ->assertSee('mediaCover', false);
});
