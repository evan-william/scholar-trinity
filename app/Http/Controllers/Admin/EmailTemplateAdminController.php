<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplateSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailTemplateAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.email-templates.index', [
            'templates' => EmailTemplateSetting::query()->orderBy('template_key')->orderBy('locale')->get(),
            'templateKeys' => [
                'student_registration_confirmation',
                'payment_instruction',
                'payment_reminder',
                'payment_confirmation',
                'passport_reupload_requested',
                'registration_completed',
                'receipt_request_received',
                'receipt_issued',
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'template_key' => ['required', 'string', 'max:120'],
            'locale' => ['required', 'string', 'max:8'],
            'subject' => ['required', 'string', 'max:255'],
            'body_html' => ['required', 'string'],
            'body_text' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        EmailTemplateSetting::query()->updateOrCreate(
            ['template_key' => $data['template_key'], 'locale' => $data['locale']],
            $data + ['is_active' => $request->boolean('is_active'), 'updated_by' => $request->user()->id, 'created_by' => $request->user()->id]
        );

        return redirect()->route('admin.email-templates.index')->with('status', 'Email template saved.');
    }

    public function destroy(EmailTemplateSetting $emailTemplate): RedirectResponse
    {
        $emailTemplate->delete();

        return redirect()->route('admin.email-templates.index')->with('status', 'Email template removed.');
    }
}
