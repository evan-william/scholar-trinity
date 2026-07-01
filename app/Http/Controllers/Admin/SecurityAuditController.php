<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityAuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SecurityAuditController extends Controller
{
    public function index(Request $request): View
    {
        $logs = SecurityAuditLog::query()
            ->with('auditable')
            ->when($request->query('module'), fn ($query, string $module) => $query->where('module', $module))
            ->when($request->query('event_type'), fn ($query, string $event) => $query->where('event_type', $event))
            ->when($request->query('user_id'), fn ($query, string $userId) => $query->where('user_id', $userId))
            ->when($request->query('ip_address'), fn ($query, string $ip) => $query->where('ip_address', 'like', "%{$ip}%"))
            ->when($request->query('status'), fn ($query, string $status) => $query->where('status', $status))
            ->when($request->query('date_from'), fn ($query, string $date) => $query->whereDate('created_at', '>=', $date))
            ->when($request->query('date_to'), fn ($query, string $date) => $query->whereDate('created_at', '<=', $date))
            ->latest('created_at')
            ->paginate(30)
            ->withQueryString();

        return view('admin.security.audit-index', compact('logs'));
    }

    public function show(SecurityAuditLog $securityAuditLog): View
    {
        return view('admin.security.audit-show', ['log' => $securityAuditLog]);
    }
}
