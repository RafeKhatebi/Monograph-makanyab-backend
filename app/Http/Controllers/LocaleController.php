<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $locale = $request->validate([
            'locale' => ['required', 'string', 'in:en,fa,ps'],
            'redirect_to' => ['nullable', 'string', 'max:2048'],
        ])['locale'];

        $request->session()->put('locale', $locale);

        return redirect()->to($this->safeRedirect($request, $locale));
    }

    private function safeRedirect(Request $request, string $locale): string
    {
        $target = $request->input('redirect_to') ?: url()->previous();

        if (! is_string($target) || trim($target) === '') {
            return route('home');
        }

        $target = $this->sameSiteUrl($request, $target);

        if ($target === null) {
            return route('home');
        }

        return $this->withLocalePath($target, $locale);
    }

    private function sameSiteUrl(Request $request, string $target): ?string
    {
        if (str_starts_with($target, '/') && ! str_starts_with($target, '//')) {
            return url($target);
        }

        $parts = parse_url($target);

        if (! is_array($parts) || empty($parts['host']) || $parts['host'] !== $request->getHost()) {
            return null;
        }

        return $target;
    }

    private function withLocalePath(string $url, string $locale): string
    {
        $parts = parse_url($url);

        if (! is_array($parts)) {
            return $url;
        }

        $supported = array_keys(config('locales', ['en' => []]));
        $path = $parts['path'] ?? '/';
        $segments = explode('/', ltrim($path, '/'));

        if (isset($segments[0]) && in_array($segments[0], $supported, true)) {
            $segments[0] = $locale;
            $parts['path'] = '/'.implode('/', $segments);
        }

        return $this->buildUrl($parts);
    }

    private function buildUrl(array $parts): string
    {
        $url = '';

        if (isset($parts['scheme'])) {
            $url .= $parts['scheme'].'://';
        }

        if (isset($parts['user'])) {
            $url .= $parts['user'];

            if (isset($parts['pass'])) {
                $url .= ':'.$parts['pass'];
            }

            $url .= '@';
        }

        if (isset($parts['host'])) {
            $url .= $parts['host'];
        }

        if (isset($parts['port'])) {
            $url .= ':'.$parts['port'];
        }

        $url .= $parts['path'] ?? '/';

        if (isset($parts['query']) && $parts['query'] !== '') {
            $url .= '?'.$parts['query'];
        }

        if (isset($parts['fragment']) && $parts['fragment'] !== '') {
            $url .= '#'.$parts['fragment'];
        }

        return $url;
    }
}
