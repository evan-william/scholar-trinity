<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadPaymentProofRequest;
use App\Models\RegistrationPayment;
use App\Models\StudentRegistration;
use App\Services\PaymentFlowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function show(string $registrationNumber, PaymentFlowService $service): View
    {
        $registration = StudentRegistration::query()
            ->with(['contact', 'exams', 'latestPayment'])
            ->where('registration_number', $registrationNumber)
            ->firstOrFail();
        $payment = $service->ensurePayment($registration);

        return view('payments.show', [
            'registration' => $registration->fresh(['contact', 'exams']),
            'payment' => $payment->fresh(),
            'setting' => $service->activeSetting(),
        ]);
    }

    public function uploadProof(
        UploadPaymentProofRequest $request,
        RegistrationPayment $registrationPayment,
        PaymentFlowService $service
    ): RedirectResponse {
        $service->uploadProof($registrationPayment, $request->file('proof'), $request->ip());

        return redirect()->route('payments.show', $registrationPayment->registration->registration_number)
            ->with('status', __('payment.proof_uploaded'));
    }

    public function gatewayStart(RegistrationPayment $registrationPayment, PaymentFlowService $service): View
    {
        return view('payments.gateway-start', [
            'payment' => $registrationPayment->load('registration'),
            'payload' => $service->gatewayPayload($registrationPayment->load('registration')),
        ]);
    }

    public function gatewayCallback(Request $request, PaymentFlowService $service): string
    {
        $service->handleGatewayCallback($request->all(), $request->ip());

        return '1|OK';
    }

    public function success(RegistrationPayment $registrationPayment): View
    {
        return view('payments.success', ['payment' => $registrationPayment->load(['registration.contact', 'registration.exams'])]);
    }

    public function failed(RegistrationPayment $registrationPayment): View
    {
        return view('payments.failed', ['payment' => $registrationPayment->load('registration')]);
    }
}
