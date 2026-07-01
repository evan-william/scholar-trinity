<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class ApplyLocale
{
    public const SUPPORTED = ['en', 'zh-TW'];

    public static function normalize(?string $locale): string
    {
        return match ($locale) {
            'zh_TW', 'zh-tw', 'zh-TW' => 'zh-TW',
            'en' => 'en',
            default => 'en',
        };
    }

    public static function laravelLocale(string $locale): string
    {
        return $locale === 'zh-TW' ? 'zh_TW' : 'en';
    }

    public function handle(Request $request, Closure $next): Response
    {
        $locale = self::normalize($request->session()->get('locale') ?: $request->cookie('locale'));
        App::setLocale(self::laravelLocale($locale));
        $request->session()->put('locale', $locale);

        $response = $next($request);
        $locale = self::normalize($request->session()->get('locale') ?: $locale);
        Cookie::queue(cookie('locale', $locale, 60 * 24 * 365, null, null, false, false, false, 'lax'));

        return $response;
    }
}
