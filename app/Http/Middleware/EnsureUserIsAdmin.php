<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Services\SecurityAuditService;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isAdmin()) {
            Log::warning('Unauthorized admin access attempt.', [
                'user_id' => $request->user()?->id,
                'path' => $request->path(),
                'ip' => $request->ip(),
            ]);
            app(SecurityAuditService::class)->log('auth', 'unauthorized_access', 'Unauthorized admin access attempt.', null, [], [], ['path' => $request->path()], 'failed', $request);

            abort(403);
        }

        return $next($request);
    }
}
