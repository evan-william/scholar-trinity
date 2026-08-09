<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePaymentSettingsRequest;
use App\Http\Requests\VerifyManualPaymentRequest;
use App\Models\PaymentSetting;
use App\Models\RegistrationPayment;
use App\Models\RegistrationPricingTier;
use App\Services\PaymentFlowService;
use App\Services\RegistrationPricingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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

    public function remind(Request $request, RegistrationPayment $registrationPayment, PaymentFlowService $service): RedirectResponse
    {
        $service->sendReminder($registrationPayment, $request->user()->id, $request->ip());

        return redirect()->route('admin.payments.show', $registrationPayment)->with('status', 'Payment reminder sent.');
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

    public function settings(RegistrationPricingService $pricing): View
    {
        return view('admin.payments.settings', [
            'setting' => PaymentSetting::query()->where('is_active', true)->latest()->first() ?? new PaymentSetting([
                'provider' => 'manual',
                'mode' => 'sandbox',
                'bank_name' => '臺灣銀行松山分行',
                'bank_code' => '004',
                'account_name' => '力可科技股份有限公司',
                'account_number' => '064001061782',
                'manual_instruction' => 'Please include your AP registration reference number in the transfer note and send the transfer receipt by email or Line for manual verification.',
                'payment_deadline_days' => 7,
                'is_active' => true,
            ]),
            'pricingTiers' => $pricing->tiers(),
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

    public function updatePricing(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tiers' => ['required', 'array', 'min:1', 'max:20'],
            'tiers.*.exam_count' => ['required', 'integer', 'min:1', 'max:20', 'distinct'],
            'tiers.*.reference_usd_per_exam' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'tiers.*.combined_fee_per_exam' => ['required', 'integer', 'min:0', 'max:999999'],
            'tiers.*.exam_fee_per_exam' => ['required', 'integer', 'min:0', 'max:999999'],
            'tiers.*.currency' => ['required', 'string', 'max:8'],
            'tiers.*.is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($data): void {
            foreach ($data['tiers'] as $row) {
                $combinedFee = (int) $row['combined_fee_per_exam'];
                $examFee = (int) $row['exam_fee_per_exam'];

                if ($examFee > $combinedFee) {
                    throw ValidationException::withMessages([
                        'tiers' => 'The TPCA exam fee cannot exceed the unified fee.',
                    ]);
                }

                RegistrationPricingTier::query()->updateOrCreate(
                    ['exam_count' => (int) $row['exam_count']],
                    [
                        'reference_usd_per_exam' => $row['reference_usd_per_exam'] ?? null,
                        'combined_fee_per_exam' => $combinedFee,
                        'exam_fee_per_exam' => $examFee,
                        'service_fee_per_exam' => $combinedFee - $examFee,
                        'currency' => $row['currency'],
                        'is_active' => filter_var($row['is_active'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    ]
                );
            }
        });

        return redirect()->route('admin.payments.settings')->with('status', 'Unified registration pricing saved.');
    }

    private function safeFileName(string $name): string
    {
        $name = basename(str_replace(["\r", "\n", '"', '\\'], '', $name));
        $name = preg_replace('/[^A-Za-z0-9._ -]/', '_', $name) ?: 'download';

        return trim($name) !== '' ? $name : 'download';
    }
}
