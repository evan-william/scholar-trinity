<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePaymentSettingsRequest;
use App\Http\Requests\VerifyManualPaymentRequest;
use App\Models\PaymentSetting;
use App\Models\RegistrationPayment;
use App\Services\PaymentFlowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Services\SecurityAuditService;

class PaymentAdminController extends Controller
{
    public function index(Request $request): View
    {
        $payments = RegistrationPayment::query()
            ->with(['registration.contact'])
            ->when($request->query('search'), function ($query, string $search): void {
                $query->where('payment_reference', 'like', "%{$search}%")
                    ->orWhereHas('registration', fn ($registration) => $registration->where('registration_number', 'like', "%{$search}%")->orWhere('student_full_name', 'like', "%{$search}%"));
            })
            ->when($request->query('payment_status'), fn ($query, string $status) => $query->where('payment_status', $status))
            ->when($request->query('payment_method'), fn ($query, string $method) => $query->where('payment_method', $method))
            ->when($request->query('period'), fn ($query, string $period) => $query->whereHas('registration', fn ($registration) => $registration->where('registration_period', $period)))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function show(RegistrationPayment $registrationPayment): View
    {
        return view('admin.payments.show', [
            'payment' => $registrationPayment->load(['registration.contact', 'registration.exams', 'logs', 'verifier']),
        ]);
    }

    public function verify(
        VerifyManualPaymentRequest $request,
        RegistrationPayment $registrationPayment,
        PaymentFlowService $service
    ): RedirectResponse {
        if ($request->validated('action') === 'verify') {
            $service->verifyManual($registrationPayment, $request->user()->id, $request->validated('note'), $request->ip());
            return redirect()->route('admin.payments.show', $registrationPayment)->with('status', 'Payment verified.');
        }

        $service->rejectManual($registrationPayment, $request->user()->id, $request->validated('rejected_reason'), $request->ip());

        return redirect()->route('admin.payments.show', $registrationPayment)->with('status', 'Payment rejected.');
    }

    public function proofPreview(RegistrationPayment $registrationPayment): StreamedResponse
    {
        abort_unless($registrationPayment->proof_file_path && Storage::disk('local')->exists($registrationPayment->proof_file_path), 404);
        app(SecurityAuditService::class)->log('documents', 'payment_proof_viewed', 'Payment proof viewed.', $registrationPayment);

        $fileName = $this->safeFileName($registrationPayment->proof_original_name ?: 'payment-proof');

        return Storage::disk('local')->response($registrationPayment->proof_file_path, $fileName, [
            'Content-Type' => $registrationPayment->proof_mime_type ?: 'application/octet-stream',
        ]);
    }

    public function proofDownload(RegistrationPayment $registrationPayment): StreamedResponse
    {
        abort_unless($registrationPayment->proof_file_path && Storage::disk('local')->exists($registrationPayment->proof_file_path), 404);
        app(SecurityAuditService::class)->log('documents', 'payment_proof_downloaded', 'Payment proof downloaded.', $registrationPayment);

        return Storage::disk('local')->download($registrationPayment->proof_file_path, $this->safeFileName($registrationPayment->proof_original_name ?: 'payment-proof'));
    }

    public function settings(): View
    {
        return view('admin.payments.settings', [
            'setting' => PaymentSetting::query()->where('is_active', true)->latest()->first() ?? new PaymentSetting(['provider' => 'manual', 'mode' => 'sandbox', 'payment_deadline_days' => 7, 'is_active' => true]),
        ]);
    }

    public function updateSettings(UpdatePaymentSettingsRequest $request): RedirectResponse
    {
        $setting = PaymentSetting::query()->where('is_active', true)->latest()->first() ?? new PaymentSetting(['created_by' => $request->user()->id]);
        $data = collect($request->validated())->except(['hash_key', 'hash_iv'])->all();
        $setting->fill($data + ['updated_by' => $request->user()->id, 'is_active' => (bool) $request->boolean('is_active', true)]);
        $setting->setHashKey($request->validated('hash_key'));
        $setting->setHashIv($request->validated('hash_iv'));
        $setting->save();

        return redirect()->route('admin.payments.settings')->with('status', 'Payment settings saved.');
    }

    private function safeFileName(string $name): string
    {
        $name = basename(str_replace(["\r", "\n", '"', '\\'], '', $name));
        $name = preg_replace('/[^A-Za-z0-9._ -]/', '_', $name) ?: 'download';

        return trim($name) !== '' ? $name : 'download';
    }
}
