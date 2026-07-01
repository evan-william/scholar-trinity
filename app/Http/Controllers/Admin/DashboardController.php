<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApExamSubject;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, AdminDashboardService $service): View
    {
        $filters = $request->only(['period', 'date_from', 'date_to', 'status', 'subject_id']);
        Log::info('Admin dashboard viewed.', ['user_id' => $request->user()->id, 'filters' => array_filter($filters)]);

        return view('admin.dashboard', $service->stats($filters) + [
            'subjects' => ApExamSubject::query()->orderBy('name')->get(),
        ]);
    }
}
