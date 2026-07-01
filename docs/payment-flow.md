# AP Exam Payment Flow

## Scope

Phase 3 adds a provider-ready payment layer without changing existing registration behavior. Registrations keep their original fee snapshots, while payment records hold the operational payment status, proof uploads, gateway references, and audit logs.

## Fee Snapshot

At registration time the system stores:

- `exam_fee_total`
- `service_fee_total`
- `late_fee_total`
- `grand_total`
- `currency`
- `fee_snapshot_at`

Future AP subject fee changes do not alter existing registrations or their `registration_payments` rows.

## Manual Bank Transfer

Students open `/payments/{registrationNumber}` after registration. The page shows bank transfer instructions, exact amount, payment reference, deadline, and proof upload.

Proof files:

- Accepted: PDF, JPG, JPEG, PNG
- Max size: 10MB
- Stored on the private `local` disk under `payment-proofs`
- Admin-only preview/download

Admin verification is handled at `/admin/payments/{payment}`. Admins can verify or reject manual payment. Both actions create `payment_logs` rows and send a payment update email to the student and parent.

## Taiwan Gateway Readiness

The gateway adapter is intentionally provider-ready rather than hard-coded to one production account. It supports ECPay/NewebPay-style payloads with:

- Provider setting (`manual`, `ecpay`, `newebpay`)
- Sandbox/production mode
- Merchant ID
- Encrypted Hash Key and Hash IV
- Callback and return URLs
- Signature verification
- Amount validation
- Duplicate callback protection

Callback endpoint:

```text
POST /payments/gateway/callback
```

The callback is excluded from CSRF because it is called by an external provider. Security is enforced through signature verification, amount validation, order lookup, and idempotent status updates.

## Admin Routes

- `/admin/payments`
- `/admin/payments/settings`
- `/admin/payments/{payment}`
- `/admin/payments/{payment}/proof/preview`
- `/admin/payments/{payment}/proof/download`

## Logs

All payment status-changing events are written to `payment_logs`, including:

- `payment_created`
- `manual_proof_uploaded`
- `manual_payment_verified`
- `manual_payment_rejected`
- `gateway_request_created`
- `gateway_callback_received`
- `gateway_payment_paid`

## Tests

Coverage includes fee snapshot preservation, manual payment proof upload and validation, admin authorization, manual verify/reject, encrypted settings, gateway signature validation, duplicate callback handling, amount tampering rejection, and payment confirmation email.
