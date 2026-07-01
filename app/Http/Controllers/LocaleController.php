<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ApplyLocale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LocaleController extends Controller
{
    public function __invoke(string $locale, Request $request): RedirectResponse
    {
        abort_unless(in_array($locale, ApplyLocale::SUPPORTED, true), 404);

        $request->session()->put('locale', $locale);
        Cookie::queue(cookie('locale', $locale, 60 * 24 * 365, null, null, false, false, false, 'lax'));

        return redirect()->to($request->query('redirect', url()->previous() ?: route('landing')));
    }
}
