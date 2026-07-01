<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReceiptRequest;
use App\Models\RegistrationPayment;
use App\Services\ReceiptService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReceiptController extends Controller
{
    public function create(RegistrationPayment $registrationPayment, ReceiptService $service): View
    {
        return view('receipts.create', [
            'payment' => $registrationPayment->load(['registration.contact', 'receiptRequest']),
            'receipt' => $registrationPayment->receiptRequest,
            'taxableAmount' => $service->taxableAmount($registrationPayment),
            'nonReceiptAmount' => $service->nonReceiptAmount($registrationPayment),
        ]);
    }

    public function store(
        StoreReceiptRequest $request,
        RegistrationPayment $registrationPayment,
        ReceiptService $service
    ): RedirectResponse {
        $receipt = $service->saveRequest($registrationPayment, $request->validated(), $request->ip());

        return redirect()->route('receipts.show', $receipt)->with('status', __('receipt.saved'));
    }

    public function show(\App\Models\ReceiptRequest $receiptRequest): View
    {
        return view('receipts.show', [
            'receipt' => $receiptRequest->load(['registration', 'payment']),
        ]);
    }
}
