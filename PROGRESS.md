# Trinity Scholar Progress Tracker

Last updated: 2026-07-02, Asia/Bangkok

This file is the working source of truth for project status. Every implementation pass must update:
- `Current Progress` for what changed.
- `TODO` for remaining work.
- `Bugs / Re-Audit Findings` when a new issue is found.
- Remove items from `TODO` only after the code is actually implemented and re-checked.

Do not store server passwords, DB passwords, API keys, or payment provider credentials in this file or anywhere committed to Git.

## Project Direction

- Stack decision: Laravel + Vue.
- Current frontend state: Laravel Blade is still the main UI. Vue is now wired as a progressive frontend path, but pages still need migration/redesign.
- Production direction: one Laravel app should serve the site and built Vue assets. Use `npm run build` for production assets; do not run a separate Node frontend server in production unless the deployment plan changes.
- Server info from team: domain `trinity.sophistec.global`, app port `3014`.
- Database direction: MySQL/MariaDB for server deploy. DB name, username, and password still need to be filled in server `.env`. Do not commit credentials.

## Template Candidates

Recommended split:
- Frontend/compro: education or institutional HTML template, then convert into Laravel Blade/Vue components.
- Backend/admin: admin dashboard template with Laravel support, then wire existing admin routes/services into the layout.

Shortlist:
- Admin/backend recommended: Skote - HTML & Laravel Admin Dashboard Template  
  Link: https://themeforest.net/item/skote-html-laravel-admin-dashboard-template/25548061  
  Why: Laravel admin, Bootstrap, Vite, responsive, multilingual support. Better fit if we want simple/clean admin quickly.

- Admin/backend alternative: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template  
  Link: https://themeforest.net/item/vuexy-vuejs-html-laravel-admin-dashboard-template/23328599  
  Why: Laravel + Vue support, polished dashboard components, larger UI kit. Heavier but good if we need many admin screens.

- Admin/backend premium alternative: Metronic - Vue/Laravel Admin Dashboard Template  
  Link: https://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469  
  Why: very mature admin system, supports Vue and Laravel. More expensive/heavy; use only if we want a long-term dashboard base.

- Frontend/compro option: Educavo - Education HTML Template  
  Link: https://themeforest.net/search/education%20html%20template  
  Why: education/institution style, good for landing, program overview, FAQ, timeline.

- Frontend/compro option: LearnUp / Edubin / Kingster from the same ThemeForest education search  
  Link: https://themeforest.net/search/education%20html%20template  
  Why: suitable education landing pages; choose after previewing visual fit.

- Free fallback sources from Ko Daiva:
  - https://www.free-css.com/
  - https://www.themezy.com/free-website-templates/242-ink-tattoo-free-responsive-website-template

Template decision still pending. For tomorrow's update, prioritize a clean compro landing and the registration form flow over perfect backend theme integration.

## Current Progress

2026-07-02
- Confirmed direction from team chat: Laravel + Vue.
- Vue setup added in codebase: `vue`, `@vitejs/plugin-vue`, Vite Vue plugin, and progressive Vue mount helper.
- Registration data persistence improved:
  - Student legal name fields are saved.
  - Parent first/last name and mailing fields are saved.
  - Accommodation fields are saved.
  - Practice exam count and total are saved.
- Registration validation improved:
  - Removed fake defaults for DOB, nationality, passport number, relationship, and emergency relationship.
  - Added visible fields to registration form for DOB, nationality, passport number, passport expiry, relationship, and emergency relationship.
- Practice exam fee now calculated server-side from selected practice exams using `registration.practice_exam_fee`.
- Admin exam replacement now updates old/new subject quota counts and recalculates unpaid payment totals.
- Payment method handling improved for gateway payload: credit card maps to `Credit`, ATM maps to `ATM`.
- Admin detail/edit/print now shows the newer registration fields: legal names, DOB, nationality, passport expiry, parent mailing fields, emergency relationship, accommodations, and practice exams.
- CSV/XLSX export now includes legal names, DOB, nationality, passport expiry, parent/mailing/emergency fields, accommodations, practice exams, payment fields, and template-specific TPCA/school subsets.
- Student confirmation and pre-submit review now show more complete submitted data.
- Deployment prep added:
  - `.env.example` now has production-oriented placeholders for app port, MySQL DB, file size, registration fee, payment gateway, and e-invoice values.
  - `DEPLOYMENT.md` documents server setup without secrets.
  - `INTEGRATIONS.md` documents payment and e-invoice provider checklist.
- Payment gateway skeleton now supports `PAYMENT_GATEWAY_ENDPOINT`; when configured it renders a POST handoff form, otherwise it stays in sandbox payload preview mode.
- Landing CTA cleanup:
  - Legacy `/register` now redirects to the new `/student-registration` flow.
  - Legacy `/registrations` POST and `/registrations/{registration}` GET now redirect to the new flow instead of invoking the old controller.
  - Landing CTA links now point to the new student registration route.
- Admin dashboard data polish:
  - Added operations queue metrics for pending documents, waiting verification, payment pending, receipt pending, and quota watch.
  - Annual report now includes accommodation request count, practice exam count, and practice exam fee revenue.
- Email completion:
  - Added `RegistrationCompletedMail` with HTML/text templates.
  - Admin verification now marks a paid+verified registration as `completed`, writes audit logs, and sends the completion email.
- Security polish:
  - Passport and payment proof preview/download filenames are sanitized before response headers are generated.
- Integration structure:
  - Added payment gateway provider interface and adapters for manual, ECPay, and NewebPay placeholder.
  - Added e-invoice/fapiao provider interface and adapters for manual sandbox, ECPay placeholder, and NewebPay placeholder.
- Deployment docs:
  - Added `.env.production.example`, `SERVER_CHECKLIST.md`, and `DEPLOY_COMMANDS.md`.
- Tests prepared:
  - Updated tests for new registration field persistence, server-side practice fee calculation, export columns, legacy route redirect, and registration completion email.
- Non-UI backend hardening:
  - Admin registration list/export now supports document status, verification status, receipt status, accommodation requested, and accommodation status filters.
  - Annual report can now be exported as CSV.
  - Added `security:backup-storage` artisan command for private storage manifest/optional zip backup logging.
  - Added provider manager tests for payment and e-invoice adapters.
  - Added storage backup and annual report export tests.
- Re-audit status: easy backend correctness fixes above are done; production payment/e-invoice/template redesign remain.

## TODO

### Immediate Update Needed For Tomorrow

- Build/update compro landing page:
  - Program overview.
  - AP exam registration explanation.
  - Registration timeline.
  - Fee explanation.
  - Required documents.
  - FAQ.
  - Contact info.
  - Register Now CTA.
  - Use selected template style; if template is not purchased yet, use temporary clean education layout.
  - DONE: temporary landing content/module already exists and CTA now points to `/student-registration`.

- Polish registration form:
  - Keep 5-step/6-step flow working.
  - Clean visual design to match chosen template.
  - Make required fields obvious.
  - Confirm mobile layout.
  - DONE: pre-submit review now includes the newly required fields and accommodations summary.

- Decide template:
  - Pick admin template: Skote vs Vuexy vs Metronic.
  - Pick compro template: Envato education HTML template or free fallback.
  - Record final choice here.

### Phase 1 - MVP Registration Platform

- Landing / Information Page:
  - `PARTIAL`: content exists in backend/landing module but needs template-quality redesign.
  - DONE: CTA no longer points to legacy `/register`.
  - TODO: implement final homepage/compro layout.
  - TODO: verify bilingual content.

- Student Registration Form:
  - `PARTIAL`: form and backend exist.
  - DONE: important hidden/missing data persistence fixed.
  - DONE: review step now includes DOB, nationality, passport number, relationship, emergency contact, and accommodations.
  - TODO: visual polish with template.
  - DONE: new submitted fields appear in admin detail/edit/print and exports.

- Exam Preference Selection:
  - `PARTIAL`: available subjects, multiple selection, fee display, late fee, quota/status exist.
  - DONE: practice fee no longer trusted from frontend hidden total.
  - DONE: practice selections are readable through `RegistrationExamSelection`, admin detail/print, confirmation, and export.

- Passport Upload:
  - `MOSTLY DONE`: upload, validation, private storage, admin access, replacement exist.
  - DONE: `.env.example` now aligns `SECURITY_FILE_MAX_KB=10240` with the 10MB registration form limit.

- Submission Confirmation:
  - `PARTIAL`: confirmation page and email exist.
  - DONE: confirmation page now includes more complete student/guardian/exam/payment summary.
  - DONE: registration completed email is implemented for paid + verified registrations.
  - TODO: final visual polish and bilingual email rendering QA.

### Phase 2 - Admin Management System

- Admin Login:
  - `MOSTLY DONE`: login, forgot password, session timeout, admin guard exist.
  - TODO: apply admin template layout.

- Registration Dashboard:
  - `PARTIAL`: metrics exist.
  - DONE: operations queue metrics added for document, verification, payment, receipt, and quota follow-up.
  - DONE: dashboard counts uploaded/pending-review passports instead of a hardcoded placeholder.
  - TODO: redesign dashboard using selected admin template.

- Registration Management:
  - `PARTIAL`: list/search/filter/detail/edit/verify/notes exist.
  - DONE: exam replacement quota recalculation fixed.
  - DONE: new registration fields appear on admin show/edit/print pages.

- Passport Management:
  - `MOSTLY DONE`: preview, download, replace, valid/invalid, reupload request exist.
  - DONE: passport download/preview filename headers are sanitized.
  - TODO: confirm private storage behavior on server.

- Export Data:
  - `MOSTLY DONE`: CSV/XLSX export exists.
  - DONE: new registration fields and practice/accommodation data are included in exports.
  - DONE: export filters now include document status, verification status, receipt status, and accommodations.
  - TODO: verify XLSX works on server with PHP Zip extension.

### Phase 3 - Payment Flow

- Payment Setup:
  - `PARTIAL`: fee separation and totals exist.
  - DONE: server-side practice fee calculation.
  - TODO: admin fee update UX and recalculation rules.

- Taiwan Payment Gateway:
  - `NOT PRODUCTION READY`: gateway payload/callback skeleton exists.
  - DONE: provider adapter structure added for manual, ECPay, and NewebPay.
  - DONE: ATM method can now map to gateway `ATM`.
  - DONE: gateway page can POST to a configured `PAYMENT_GATEWAY_ENDPOINT`; otherwise it stays in sandbox preview.
  - TODO: choose provider: ECPay or NewebPay.
  - TODO: configure and verify real checkout endpoint.
  - TODO: implement provider-specific signature algorithm exactly.
  - TODO: webhook security/IP/provider validation.
  - TODO: success and failed handling with real provider response.

- Manual Payment:
  - `MOSTLY DONE`: bank instruction, proof upload, admin verify/reject exist.
  - TODO: polish UI and email text.

- Payment Confirmation:
  - `PARTIAL`: success page, email, admin record, transaction ID fields exist.
  - DONE: failed page route/view exists.
  - TODO: real gateway failure states after provider sandbox testing.

### Phase 4 - Receipt / Fapiao Management

- Fee Separation:
  - `MOSTLY DONE`: receipt only applies to service fee by default.
  - TODO: confirm late fee taxable rule with client.

- Receipt Information Form:
  - `MOSTLY DONE`: buyer/company/GUI/type fields exist.
  - TODO: visual polish.

- Admin Receipt Management:
  - `PARTIAL`: list/filter/export/issue/manual receipt number exist.
  - TODO: template redesign.

- Auto Fapiao Integration:
  - `NOT PRODUCTION READY`: current auto issue is sandbox simulation.
  - DONE: provider adapter structure added for manual sandbox, ECPay placeholder, and NewebPay placeholder.
  - TODO: choose Taiwan e-invoice provider.
  - TODO: implement real issue/cancel/resend API.

### Phase 5 - Multi-language & UX

- Language System:
  - `PARTIAL`: English and Traditional Chinese files exist.
  - TODO: remove hardcoded text from Blade pages.
  - TODO: make language switch consistent across landing/register/admin where required.

- Form UX:
  - `PARTIAL`: mobile, progress, validation, confirmation exist.
  - TODO: polish layout with selected template.
  - TODO: browser QA on desktop/mobile.

- Email Templates:
  - `PARTIAL`: registration/payment/missing document templates exist.
  - DONE: registration completed email.
  - TODO: bilingual QA pass.

### Phase 6 - Security & Data Protection

- Secure Hosting:
  - `PENDING SERVER`: HTTPS/SSL, firewall, deploy user, environment variables.
  - DONE: `.env.example`, `.env.production.example`, `DEPLOYMENT.md`, `SERVER_CHECKLIST.md`, and `DEPLOY_COMMANDS.md` now document required production variables and operations without secrets.
  - TODO: configure real `.env` on server only.
  - TODO: confirm SSL for `trinity.sophistec.global`.

- Database Security:
  - `PENDING SERVER`: MySQL/MariaDB direction is documented; DB credentials not provided yet.
  - TODO: define DB name/user/password on server.
  - DONE: backup checklist documented in `SERVER_CHECKLIST.md`.
  - DONE: `security:backup-storage` creates a private storage manifest or optional zip and logs it.
  - TODO: configure actual backup job and restricted DB access on server.

- File Security:
  - `PARTIAL`: private file storage and validation exist.
  - TODO: optional file encryption decision.
  - TODO: confirm storage symlink is not exposing passport files.

- Audit Log:
  - `PARTIAL`: login/edit/payment/passport/receipt audit logs exist.
  - TODO: re-audit coverage after admin template integration.

### Phase 7 - Annual / Future Use

- Registration Period Management:
  - `PARTIAL`: main/late periods exist through exam seasons.
  - TODO: admin UX polish.

- Exam Season Management:
  - `MOSTLY DONE`: create, active, duplicate, archive exist.
  - TODO: verify old data separation in reports/exports.

- Exam Management:
  - `MOSTLY DONE`: add/edit/disable/quota/date/fees exist.
  - TODO: template polish and validation QA.

- Reporting:
  - `PARTIAL`: annual, revenue, subject, payment, receipt summaries exist.
  - DONE: annual report now includes practice exam and accommodation breakdowns.
  - DONE: annual report CSV export endpoint added.
  - TODO: better charts after admin template is selected.

## Bugs / Re-Audit Findings

- PHP and Composer are not available in the current Codex environment, so tests have not been run here.
- `resources/views/student-registration/create.blade.php` still contains a lot of inline CSS/JS and should be replaced or refactored after template choice.
- Gateway page now supports configured endpoint handoff, but real provider signature and sandbox verification are still pending.
- Receipt auto issue is still not production-ready; manual sandbox and provider placeholder adapters exist, but real issue/cancel/resend APIs are pending.
- Language coverage is incomplete because many view strings are hardcoded.
- Server credentials were shared in chat but must stay out of Git.

## Verification Log

2026-07-02
- Static check: `git diff --check` passed for this implementation pass.
- Static check: no merge conflict markers found in this implementation pass.
- Static check: no `.agent` or `.agents` folder found in repo root.
- Static check: `git diff --check` passed in previous implementation pass.
- Static check: `package.json` parsed successfully in previous implementation pass.
- Blocked: `php -v` failed because PHP is not in PATH.
- Blocked: `composer --version` failed because Composer is not in PATH.

## Suggested Next Work Order

1. Choose template today.
2. Build compro landing page first for tomorrow update.
3. Polish registration form visual flow.
4. Apply admin template shell to dashboard/list/detail pages.
5. Apply selected admin template shell to dashboard/list/detail pages.
6. Server deploy execution: fill `.env`, DB credentials, `npm run build`, Laravel migrate.
7. Payment provider decision and real integration.
