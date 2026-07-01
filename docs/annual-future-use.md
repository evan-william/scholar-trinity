# Annual / Future Use

The AP Exam Registration Platform now supports yearly reuse through `exam_seasons`.

## Season Management

- Admin URL: `/admin/exam-seasons`
- Each season controls the main registration window, late registration window, timezone, currency, default service fee, default late fee, status, active state, public message, close reason, and archive metadata.
- Only one season should be active at a time. Activating a season automatically deactivates the others.
- Archiving a season marks it closed and inactive while preserving registrations, passport metadata, payment records, receipts, and audit logs.

## Registration Period Rules

- Public subject selection uses the active season when one exists.
- A subject linked to a season is selectable only during that season's main or late registration window.
- Late fees are automatically applied when the season's current period is `late`.
- Legacy subjects without a season continue to use their own subject-level registration dates for backward compatibility.
- New registrations store `exam_season_id` and `registration_period_type` so reports can separate years and main/late periods.

## Duplicate Previous Year

The duplicate action copies:

- Exam subjects
- Subject category, fee, quota, date, time, timezone, location, and status
- Registration windows shifted by one year by default

It does not copy:

- Student registrations
- Passport files
- Payments
- Receipts
- Audit logs

## Reports

Admin URL: `/admin/reports/annual`

The report dashboard includes:

- Registration totals by selected season
- Main vs late registration counts
- Revenue summary split by exam fee, service fee, late fee, paid, pending, refunded, and receipt-eligible amount
- Subject quota, registered count, remaining seats, paid count, and fee total
- Payment status summary
- Receipt status summary
- Registration trend by submitted date

## Security and Audit

Season create, update, duplicate, archive, active-season changes, and report views are logged through `SecurityAuditService`.
Historical seasons should be archived rather than deleted.
