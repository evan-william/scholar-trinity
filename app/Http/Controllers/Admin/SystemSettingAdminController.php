<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemSettingAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.system-settings.index', [
            'settings' => SystemSetting::query()->orderBy('group')->orderBy('key')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'group' => ['required', 'string', 'max:80'],
            'key' => ['required', 'string', 'max:120', 'regex:/^[a-z0-9_.-]+$/'],
            'value' => ['nullable', 'string'],
            'type' => ['required', 'in:string,boolean,integer,text,json'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_public' => ['nullable', 'boolean'],
        ]);

        SystemSetting::query()->updateOrCreate(
            ['key' => $data['key']],
            $data + ['is_public' => $request->boolean('is_public'), 'updated_by' => $request->user()->id]
        );

        return redirect()->route('admin.system-settings.index')->with('status', 'System setting saved.');
    }

    public function destroy(SystemSetting $systemSetting): RedirectResponse
    {
        $systemSetting->delete();

        return redirect()->route('admin.system-settings.index')->with('status', 'System setting removed.');
    }
}
