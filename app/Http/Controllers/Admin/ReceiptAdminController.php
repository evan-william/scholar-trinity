<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUpdateReceiptRequest;
use App\Http\Requests\IssueReceiptRequest;
use App\Http\Requests\UpdateEInvoiceSettingsRequest;
use App\Http\Requests\UpdateReceiptStatusRequest;
use App\Models\EInvoiceSetting;
use App\Models\ReceiptRequest;
use App\Services\ReceiptService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Services\SecurityAuditService;

class ReceiptAdminController extends Controller
{
    public function index(Request $request): View
    {
        $receipts = $this->query($request)
            ->with(['registration', 'payment'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.receipts.index', compact('receipts'));
    }

    public function show(ReceiptRequest $receiptRequest): View
    {
        return view('admin.receipts.show', [
            'receipt' => $receiptRequest->load(['registration', 'payment', 'logs', 'transactions', 'issuer']),
        ]);
    }

    public function update(
        AdminUpdateReceiptRequest $request,
        ReceiptRequest $receiptRequest,
        ReceiptService $service
    ): RedirectResponse {
        $service->updateByAdmin($receiptRequest, $request->validated(), $request->user()->id, $request->ip());

        return redirect()->route('admin.receipts.show', $receiptRequest)->with('status', 'Receipt information updated.');
    }

    public function issue(
        IssueReceiptRequest $request,
        ReceiptRequest $receiptRequest,
        ReceiptService $service
    ): RedirectResponse {
        $service->markIssued(
            $receiptRequest,
            $request->validated('receipt_number'),
            $request->user()->id,
            $request->ip(),
            $request->validated('notes')
        );

        return redirect()->route('admin.receipts.show', $receiptRequest)->with('status', 'Receipt marked as issued.');
    }

    public function status(
        UpdateReceiptStatusRequest $request,
        ReceiptRequest $receiptRequest,
        ReceiptService $service
    ): RedirectResponse {
        $service->updateStatus($receiptRequest, $request->validated('status'), $request->user()->id, $request->ip(), $request->validated('notes'));

        return redirect()->route('admin.receipts.show', $receiptRequest)->with('status', 'Receipt status updated.');
    }

    public function send(ReceiptRequest $receiptRequest, ReceiptService $service, Request $request): RedirectResponse
    {
        $service->sendEmail($receiptRequest, $request->user()->id, $request->ip());

        return redirect()->route('admin.receipts.show', $receiptRequest)->with('status', 'Receipt email sent.');
    }

    public function autoIssue(ReceiptRequest $receiptRequest, ReceiptService $service): RedirectResponse
    {
        $transaction = $service->simulateAutoIssue($receiptRequest);

        return redirect()->route('admin.receipts.show', $receiptRequest)->with('status', 'Sandbox e-invoice transaction: '.$transaction->provider_status);
    }

    public function export(Request $request)
    {
        $rows = $this->query($request)->with('registration')->get()->map(fn (ReceiptRequest $receipt) => [
            'Registration Reference' => $receipt->registration?->registration_number,
            'Student Name' => $receipt->registration?->student_full_name,
            'Buyer Name' => $receipt->buyer_name,
            'Receipt Type' => $receipt->receipt_type,
            'Company Name' => $receipt->company_name,
            'GUI Tax ID' => $receipt->gui_tax_id,
            'Email' => $receipt->buyer_email,
            'Phone' => $receipt->buyer_phone,
            'Service Fee Amount' => $receipt->service_fee_amount,
            'Receipt Amount' => $receipt->taxable_receipt_amount,
            'Status' => $receipt->status,
            'Receipt Number' => $receipt->receipt_number,
            'Issued At' => optional($receipt->issued_at)->format('Y-m-d H:i'),
        ]);

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, array_keys($rows->first() ?? ['Registration Reference' => null]));
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        app(SecurityAuditService::class)->log('receipt', 'receipt_exported', 'Receipt data exported.', null, [], [], ['rows' => $rows->count()], 'success', $request);

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="receipt-requests.csv"',
        ]);
    }

    public function settings(): View
    {
        return view('admin.receipts.settings', [
            'setting' => EInvoiceSetting::query()->where('is_active', true)->latest()->first()
                ?? new EInvoiceSetting(['provider' => 'manual', 'environment' => 'sandbox', 'is_active' => true]),
        ]);
    }

    public function updateSettings(UpdateEInvoiceSettingsRequest $request): RedirectResponse
    {
        $setting = EInvoiceSetting::query()->where('is_active', true)->latest()->first() ?? new EInvoiceSetting(['created_by' => $request->user()->id]);
        $data = collect($request->validated())->except(['api_key', 'hash_key', 'hash_iv'])->all();
        $setting->fill($data + [
            'updated_by' => $request->user()->id,
            'late_fee_taxable' => $request->boolean('late_fee_taxable'),
            'allow_unpaid_receipts' => $request->boolean('allow_unpaid_receipts'),
            'is_active' => $request->boolean('is_active', true),
        ]);
        $setting->setApiKey($request->validated('api_key'));
        $setting->setHashKey($request->validated('hash_key'));
        $setting->setHashIv($request->validated('hash_iv'));
        $setting->save();

        return redirect()->route('admin.receipts.settings')->with('status', 'E-invoice settings saved.');
    }

    private function query(Request $request)
    {
        return ReceiptRequest::query()
            ->when($request->query('search'), function ($query, string $search): void {
                $query->where('buyer_name', 'like', "%{$search}%")
                    ->orWhere('buyer_email', 'like', "%{$search}%")
                    ->orWhere('receipt_number', 'like', "%{$search}%")
                    ->orWhereHas('registration', fn ($registration) => $registration->where('registration_number', 'like', "%{$search}%")->orWhere('student_full_name', 'like', "%{$search}%"));
            })
            ->when($request->query('status'), fn ($query, string $status) => $query->where('status', $status))
            ->when($request->query('receipt_type'), fn ($query, string $type) => $query->where('receipt_type', $type))
            ->when($request->query('period'), fn ($query, string $period) => $query->whereHas('registration', fn ($registration) => $registration->where('registration_period', $period)))
            ->when($request->query('payment_status'), fn ($query, string $status) => $query->whereHas('payment', fn ($payment) => $payment->where('payment_status', $status)))
            ->when($request->query('date_from'), fn ($query, string $date) => $query->whereDate('created_at', '>=', $date))
            ->when($request->query('date_to'), fn ($query, string $date) => $query->whereDate('created_at', '<=', $date));
    }
}
