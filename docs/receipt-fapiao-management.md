# Receipt / Fapiao Management

## Fee Separation

Receipt/fapiao amount is calculated server-side from payment snapshots. The default rule is:

```text
Receipt Amount = Service Fee Total
Non-receipt Amount = Exam Fee Total + Late Registration Fee Total
```

The e-invoice setting `late_fee_taxable` can include late fees in the receipt amount. Exam fees are never included unless a future explicit setting is added.

## User Flow

After payment, users can submit receipt information at:

```text
/receipts/{paymentUuid}/create
```

Supported receipt types:

- Personal receipt
- Company receipt
- Donation receipt, reserved for future use
- No receipt required

Company receipt requires company name and Taiwan GUI / Tax ID validation.

## Admin Flow

Admin routes:

- `/admin/receipts`
- `/admin/receipts/export`
- `/admin/receipts/settings`
- `/admin/receipts/{receiptUuid}`

Admins can edit buyer information, mark receipts as issued, set receipt number, resend receipt email, void/cancel/fail records, export CSV, and run a sandbox auto e-invoice transaction.

## E-Invoice Readiness

`e_invoice_settings` stores provider credentials encrypted. `e_invoice_transactions` stores issue requests, responses, provider invoice numbers, random codes, failures, and retry context.

The current auto issue action is sandbox/mock only. It creates a transaction record and logs provider failures without calling an external provider.

## Audit

Every receipt action writes to `receipt_logs`, including request creation, admin edits, issue, email send, status changes, and e-invoice failures.
