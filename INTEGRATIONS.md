# Payment and E-Invoice Integration Notes

Last updated: 2026-07-02

This project currently supports manual payment and a provider-ready gateway payload flow. Real production integration still needs the final Taiwan provider decision and credentials.

## Payment Flow Status

Implemented:
- Manual bank transfer instructions.
- Proof of payment upload.
- Admin payment verification/rejection.
- Payment logs.
- Payment success and failed pages.
- Gateway payload generation.
- Gateway callback route.
- Credit card and ATM gateway method mapping.
- Optional `PAYMENT_GATEWAY_ENDPOINT` for configured provider POST handoff.
- Payment provider adapter structure:
  - `app/Services/Payments/PaymentGatewayProviderInterface.php`
  - `app/Services/Payments/ManualPaymentProvider.php`
  - `app/Services/Payments/EcpayPaymentProvider.php`
  - `app/Services/Payments/NewebPayPaymentProvider.php`
  - `app/Services/Payments/PaymentGatewayManager.php`

Still required before production online payment:
- Choose one provider: ECPay or NewebPay.
- Confirm official production/sandbox endpoint.
- Confirm exact signature algorithm and parameter encoding.
- Confirm callback success/failure response format.
- Confirm ATM virtual account response fields and expiry behavior.
- Complete NewebPay adapter if NewebPay is selected.
- Re-check the ECPay signature implementation against the official SDK/docs before production.
- Add provider IP allowlist or provider verification if available.
- Run real sandbox transaction tests.

## Expected Payment Provider Fields

These are stored through admin payment settings or server `.env`, depending on the final provider setup:

- Provider: `manual`, `ecpay`, or `newebpay`.
- Mode: `sandbox` or `production`.
- Merchant ID.
- Hash Key.
- Hash IV.
- Callback URL.
- Return URL.
- Success URL.
- Failed URL.
- Payment deadline days.

## E-Invoice / Fapiao Status

Implemented:
- Service fee is separated from exam fee.
- Receipt request form supports buyer, email, phone, company, GUI/tax ID, and receipt type.
- Admin can list/filter/export receipts.
- Admin can manually mark receipt/fapiao as issued.
- Manual sandbox auto issue adapter exists for development only.
- E-invoice provider adapter structure:
  - `app/Services/EInvoices/EInvoiceProviderInterface.php`
  - `app/Services/EInvoices/ManualEInvoiceProvider.php`
  - `app/Services/EInvoices/EcpayEInvoiceProvider.php`
  - `app/Services/EInvoices/NewebPayEInvoiceProvider.php`
  - `app/Services/EInvoices/EInvoiceProviderManager.php`

Still required before production auto fapiao:
- Choose Taiwan e-invoice provider.
- Confirm issue/cancel/void/resend APIs.
- Confirm whether late fee is taxable.
- Confirm company GUI validation requirements.
- Implement provider-specific API client.
- Replace placeholder ECPay/NewebPay adapter logic with real API issue/cancel/resend calls.
- Store provider credentials only encrypted or in server `.env`.
- Run sandbox issue and cancel tests.

## Current Recommendation

For the first client update:
- Keep payment manual unless provider credentials are ready.
- Keep fapiao manual unless e-invoice provider is confirmed.
- Show online card/ATM as "gateway setup required" until real sandbox tests pass.
