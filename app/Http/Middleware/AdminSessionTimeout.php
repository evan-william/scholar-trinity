<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Services\SecurityAuditService;

class AdminSessionTimeout
{
    public function handle(Request $request, Closure $next): Response
    {
        $timeoutMinutes = (int) config('admin.session_timeout_minutes', 30);
        $lastActivity = $request->session()->get('admin_last_activity_at');

        if ($request->user()?->isAdmin() && $lastActivity && $lastActivity->diffInMinutes(now()) >= $timeoutMinutes) {
            Log::info('Admin session timed out.', ['user_id' => $request->user()->id]);
            app(SecurityAuditService::class)->log('auth', 'session_timeout', 'Admin session timed out.', $request->user(), [], [], [], 'failed', $request);
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->withErrors([
                'email' => __('admin_auth.session_expired'),
            ]);
        }

        if ($request->user()?->isAdmin()) {
            $request->session()->put('admin_last_activity_at', now());
        }

        return $next($request);
    }
}
