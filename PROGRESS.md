# Trinity Scholar Progress Tracker

Last updated: 2026-07-04, Asia/Bangkok

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

Current local template pass:
- Frontend/compro selected for now: local Edification education template from `template-source/frontend/edification-main.zip`.
- Frontend assets now live in `public/theme/edification/` with the original MIT license retained.
- AP announcement image now lives in `public/images/ap-late-registration-2026.jpeg`.
- Backend/admin source reviewed: `template-source/backend/filament-4.x.zip` is Filament framework/package source, not a drop-in admin HTML theme. Do not copy it into the app raw. Install/evaluate Filament later through Composer after PHP/Composer are available and admin redesign is approved.
- Backend/admin current template pass: custom Blade `admin-shell` is being used as the temporary admin dashboard template until a final AdminLTE/Filament/Envato decision is approved.
- Raw downloaded templates are ignored through `template-source/` in `.gitignore`.

## Current Progress

2026-07-04
- Teammate update review:
  - Reviewed `ricky.md` and the files changed by Ricky.
  - Preserved his registration behavior work: validation returns to the relevant step, old form data is restored, browser draft autosave exists, passport draft upload exists, and inline toast messages replace blocking alerts.
- Client/boss feedback pass for Monday demo:
  - Removed the repo-local `.agents` folder from the project directory.
  - Polished the no-login student registration form without changing Ricky's flow logic.
  - Updated registration form inputs/selects/textareas to use softer filled fields, rounded corners, clearer hover/focus/invalid states, and card spacing closer to the client-highlighted textbox style.
  - Cleaned the registration header placeholder branding from generic AP/FORM pills to a Trinity Scholar/AP Registration placeholder that can later be replaced by real logos.
  - Improved the registration intro poster treatment so the provided AP announcement looks more like an intended poster, not a plain white placeholder.
  - Updated the landing hero poster panel from a white card to a dark/glass poster frame and changed the caption to `Official announcement poster`.
  - Simplified the landing brand mark to `TS` so the top logo area is less placeholder-heavy until official logo assets arrive.

2026-07-03
- Frontend template integration:
  - Integrated the Edification education template assets into `public/theme/edification/`.
  - Added the supplied 2026 AP late registration announcement poster to `public/images/ap-late-registration-2026.jpeg`.
  - Rebuilt the public landing/compro page with an education-style hero, poster panel, quick facts, AP late registration announcement copy, overview cards, process, timeline, fees, required documents, FAQ, contact, privacy, and registration CTA.
  - Kept backend-managed landing content (`hero`, `overview`, `process`, `fees`, `documents`, `faqs`, `contact`, `privacy`) rendering inside the new layout.
  - Added a poster/context intro card above the no-login student registration form.
- Template source hygiene:
  - Added `template-source/` to `.gitignore` so raw zip/template downloads are not accidentally committed.
  - Preserved the Edification MIT license in the copied public asset folder.
- Backend template review:
  - Reviewed the provided backend zip and deferred Filament installation because it needs a Composer/PHP dependency path, not raw file copy.
- Public registration/payment flow polish:
  - Added shared `public-flow-shell` Blade component for student-facing confirmation, payment, gateway, and receipt pages.
  - Reworked submitted registration confirmation page to match the AP registration flow and show reference number, student/parent details, exam selections, fee summary, and next steps.
  - Reworked payment instruction page with payment reference, deadline, amount breakdown, bank transfer instructions, proof upload, and gateway fallback.
  - Reworked gateway start page so configured providers submit to the endpoint and unconfigured providers clearly show sandbox payload preview.
  - Reworked payment success/failed pages with clear next steps and manual fallback.
  - Reworked receipt/fapiao create/status pages to emphasize service-fee-only receipt handling and buyer/company fields.
  - Cleaned admin dashboard unlimited quota display from symbol-only output to `Unlimited`.
- Admin surface polish:
  - Added shared `admin-shell` Blade component with sidebar navigation, top actions, responsive layout, and admin route shortcuts.
  - Reworked admin dashboard to show Word-aligned metrics, filters, operations queue, quick actions, daily registration chart, payment status breakdown, and subject quota/fee summary.
  - Reworked admin login page into a branded secure operations login surface while keeping existing auth routes and validation untouched.
- Backend/admin shell expansion:
  - Applied `admin-shell` to registration management index, including full filters and export controls.
  - Applied `admin-shell` to payment list/detail/settings pages, including proof review, manual verification, gateway fields, and manual bank transfer settings.
  - Applied `admin-shell` to receipt/fapiao list/detail/settings pages, including service-fee-only receipt amount, manual issue actions, sandbox auto issue, and e-invoice provider fields.
  - Applied `admin-shell` to export history and annual report pages.

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
  - DONE: Edification template assets are integrated for the current compro pass.
  - DONE: public landing page now uses the supplied AP announcement poster and education-style visual layout.
  - DONE: temporary landing content/module already exists and CTA now points to `/student-registration`.
  - TODO: final client content review once Trinity sends official copy beyond the poster/site reference.
  - TODO: replace/expand visual assets if the team approves a different premium template.

- Polish registration form:
  - Keep 5-step/6-step flow working.
  - Clean visual design to match chosen template.
  - Make required fields obvious.
  - Confirm mobile layout.
  - DONE: added no-login AP registration intro with poster/deadline/payment-completion reminders.
  - DONE: pre-submit review now includes the newly required fields and accommodations summary.
  - DONE: textbox/input style now has the softer filled look requested in the client screenshot.
  - DONE: Ricky's validation-step return, autosave, passport draft upload, and toast improvements were reviewed and preserved.
  - TODO: replace temporary TS/AP Registration text branding with official Trinity Scholar/logo assets.
  - TODO: browser QA on desktop/mobile after PHP server can run locally or on staging.

- Decide template:
  - DONE: current frontend/compro pass uses local Edification template assets.
  - TODO: pick final admin template path: keep Blade/admin shell, install Filament by Composer, or choose another Laravel admin template.
  - TODO: decide whether Edification is final or only temporary before buying Envato assets.

### Phase 1 - MVP Registration Platform

- Landing / Information Page:
  - `PARTIAL`: Edification-styled page exists and still supports backend-managed content.
  - DONE: CTA no longer points to legacy `/register`.
  - DONE: current homepage/compro layout implemented with template assets and poster content.
  - TODO: final content approval and image replacement if client provides more assets.
  - TODO: verify bilingual content.

- Student Registration Form:
  - `PARTIAL`: form and backend exist.
  - DONE: important hidden/missing data persistence fixed.
  - DONE: review step now includes DOB, nationality, passport number, relationship, emergency contact, and accommodations.
  - DONE: top-of-form intro now uses the supplied announcement poster and no-login registration guidance.
  - TODO: deeper visual refactor after final frontend/admin template decision.
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
  - DONE: confirmation page now uses the student-facing public shell and clearer next-step payment CTA.
  - DONE: registration completed email is implemented for paid + verified registrations.
  - TODO: bilingual email rendering QA.

### Phase 2 - Admin Management System

- Admin Login:
  - `MOSTLY DONE`: login, forgot password, session timeout, admin guard exist.
  - DONE: login page has branded admin UI polish.
  - TODO: apply the final selected admin template across forgot/reset pages too.

- Registration Dashboard:
  - `PARTIAL`: metrics exist.
  - DONE: operations queue metrics added for document, verification, payment, receipt, and quota follow-up.
  - DONE: dashboard counts uploaded/pending-review passports instead of a hardcoded placeholder.
  - DONE: dashboard now uses a reusable admin shell with sidebar, filters, metrics, operations queue, quick actions, chart, and subject summary.
  - TODO: apply final selected admin template styling to every admin management page.

- Registration Management:
  - `PARTIAL`: list/search/filter/detail/edit/verify/notes exist.
  - DONE: exam replacement quota recalculation fixed.
  - DONE: new registration fields appear on admin show/edit/print pages.
  - DONE: registration management index now uses the backend/admin shell with full filters and export controls.
  - TODO: apply admin shell to registration detail/edit pages.

- Passport Management:
  - `MOSTLY DONE`: preview, download, replace, valid/invalid, reupload request exist.
  - DONE: passport download/preview filename headers are sanitized.
  - TODO: confirm private storage behavior on server.

- Export Data:
  - `MOSTLY DONE`: CSV/XLSX export exists.
  - DONE: new registration fields and practice/accommodation data are included in exports.
  - DONE: export filters now include document status, verification status, receipt status, and accommodations.
  - DONE: export history page now uses the backend/admin shell.
  - TODO: verify XLSX works on server with PHP Zip extension.

### Phase 3 - Payment Flow

- Payment Setup:
  - `PARTIAL`: fee separation and totals exist.
  - DONE: server-side practice fee calculation.
  - DONE: admin payment settings page now uses the backend/admin shell.
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
  - DONE: public payment instruction UI now shows amount breakdown, bank transfer details, proof upload, and gateway fallback clearly.
  - DONE: admin payment list/detail pages now use the backend/admin shell and expose proof review/manual verification clearly.
  - TODO: polish email text after final bilingual copy review.

- Payment Confirmation:
  - `PARTIAL`: success page, email, admin record, transaction ID fields exist.
  - DONE: failed page route/view exists.
  - DONE: public success/failed pages now show clearer status, reference, next steps, and fallback actions.
  - TODO: real gateway failure states after provider sandbox testing.

### Phase 4 - Receipt / Fapiao Management

- Fee Separation:
  - `MOSTLY DONE`: receipt only applies to service fee by default.
  - TODO: confirm late fee taxable rule with client.

- Receipt Information Form:
  - `MOSTLY DONE`: buyer/company/GUI/type fields exist.
  - DONE: public receipt/fapiao form now explains service-fee-only receipt rules and amount breakdown.

- Admin Receipt Management:
  - `PARTIAL`: list/filter/export/issue/manual receipt number exist.
  - DONE: receipt list/detail/settings now use the backend/admin shell.
  - TODO: final provider-specific e-invoice UX after provider is chosen.

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
  - DONE: post-submit confirmation/payment/receipt pages now share a consistent responsive shell.
  - DONE: registration form visual style updated with softer inputs, clearer focus/invalid states, improved header, and poster treatment.
  - DONE: Ricky's draft autosave/passport draft/validation-step-return improvements are present.
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
  - TODO: re-audit coverage after full admin template integration across all admin pages.

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
  - DONE: annual report page now uses the backend/admin shell with registration/revenue cards and subject/payment/receipt tables.
  - TODO: richer charts after final admin template is selected.

## Bugs / Re-Audit Findings

- PHP and Composer are not available in the current Codex environment, so tests have not been run here.
- `resources/views/student-registration/create.blade.php` still contains a lot of inline CSS/JS and should be replaced or refactored after template choice.
- Backend template zip is Filament source/package code, not a safe raw drop-in template. It should be installed through Composer when the environment supports it.
- Gateway page now supports configured endpoint handoff, but real provider signature and sandbox verification are still pending.
- Receipt auto issue is still not production-ready; manual sandbox and provider placeholder adapters exist, but real issue/cancel/resend APIs are pending.
- Language coverage is incomplete because many view strings are hardcoded.
- Server credentials were shared in chat but must stay out of Git.

## Verification Log

2026-07-04
- Static check: `git diff --check` passed after boss-feedback visual pass.
- Static check: no merge conflict markers found.
- Static check: no `.agent` or `.agents` folder found in repo after cleanup.
- Static check: changed files are limited to `PROGRESS.md`, `resources/views/landing/index.blade.php`, and `resources/views/student-registration/create.blade.php`.
- Static review: Ricky's validation/autosave/passport draft/toast registration changes were left intact.
- Not run: PHP/Laravel browser QA or automated tests in this pass.

2026-07-03
- Static/template review: local Edification frontend zip inspected and usable assets copied.
- Static/template review: local Filament backend zip inspected and deferred because it is package/framework source.
- Static check: `git diff --check` passed.
- Static check: no merge conflict markers found.
- Static check: no `.agent` or `.agents` folder found in repo root.
- Static check: `template-source/` is ignored and raw template zip files are not staged by default.
- Static check: `git diff --check` passed after public confirmation/payment/receipt page polish.
- Static check: no obvious mojibake markers found in newly touched public flow views.
- Static check: `git diff --check` passed after admin dashboard/login shell polish.
- Static check: no obvious mojibake markers found in newly touched admin shell/dashboard/login views.
- Static check: `git diff --check` passed after applying admin shell to registration/payment/receipt/export/report pages.
- Static check: no obvious mojibake markers found in newly touched admin management views.
- Blocked: `php -v` failed because PHP is not in PATH.
- Blocked: `composer --version` failed because Composer is not in PATH.

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
2. Get client/team approval on current Edification compro pass or replace with the approved premium frontend template.
3. Apply final admin template shell to dashboard/list/detail pages.
4. Server deploy execution: fill `.env`, DB credentials, `npm run build`, Laravel migrate.
5. Payment provider decision and real integration.
