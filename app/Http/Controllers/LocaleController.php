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
        ])['locale'];

        $request->session()->put('locale', $locale);

        return redirect()->to($this->safeRedirect($request));
    }

    private function safeRedirect(Request $request): string
    {
        $previous = url()->previous();
        $appUrl = rtrim(config('app.url'), '/');

        return str_starts_with($previous, $appUrl) ? $previous : route('home');
    }
}