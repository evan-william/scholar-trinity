<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminNotificationController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.notifications.index', [
            'notifications' => AdminNotification::query()
                ->with(['registration', 'payment', 'receipt'])
                ->when($request->query('status') === 'unread', fn ($query) => $query->whereNull('read_at'))
                ->when($request->query('type'), fn ($query, string $type) => $query->where('type', $type))
                ->latest()
                ->paginate(30)
                ->withQueryString(),
            'unreadCount' => AdminNotification::query()->whereNull('read_at')->count(),
        ]);
    }

    public function markRead(AdminNotification $adminNotification): RedirectResponse
    {
        $adminNotification->update(['read_at' => now()]);

        return back()->with('status', 'Notification marked as read.');
    }

    public function markAllRead(): RedirectResponse
    {
        AdminNotification::query()->whereNull('read_at')->update(['read_at' => now()]);

        return back()->with('status', 'All notifications marked as read.');
    }
}
