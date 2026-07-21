# Trinity Scholar Progress Tracker

Last updated: 2026-07-21, Asia/Bangkok

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
- AP announcement image remains in `public/images/ap-late-registration-2026.jpeg` as source/reference material only; current landing and registration pages use extracted text content instead of displaying the poster image.
- Backend/admin source reviewed: `template-source/backend/filament-4.x.zip` is Filament framework/package source, not a drop-in admin HTML theme. Do not copy it into the app raw. Install/evaluate Filament later through Composer after PHP/Composer are available and admin redesign is approved.
- Backend/admin current template pass: custom Blade `admin-shell` is being used as the temporary admin dashboard template until a final AdminLTE/Filament/Envato decision is approved.
- Raw downloaded templates are ignored through `template-source/` in `.gitignore`.

## Current Progress

2026-07-21
- Admin login recovery:
  - Confirmed the deployed `admin` / bootstrap-password failure can occur when the server seeder was never run or an older admin row already exists, because the normal seeder intentionally does not overwrite existing passwords.
  - Added `php artisan admin:bootstrap` to explicitly create or reset the configured admin account while retaining hashed password storage and the normal Laravel login/audit flow.
  - Added feature coverage for initial admin creation and deliberate password reset through the command.
  - Added the recovery command to `SERVER_UPLOAD_GUIDE.md`.

2026-07-15
- Registration layout repair:
  - Forced the shared public/form footer into a stable three-column desktop grid so Important Notice stays to the right of contact and registration links.
  - Added two-column tablet and single-column mobile footer fallbacks.
  - Moved the step-one sticky Important Notice below the full public header offset and replaced the generic blue side stripe with a restrained information marker.
  - Bumped the shared public UI cache version to `20260715-1`.
- Admin entry and bootstrap login:
  - Added `/admin` as the canonical entry URL; guests are redirected to `/admin/login` and authenticated admins to `/admin/dashboard`.
  - Admin login now accepts either a username alias or an email while preserving Laravel authentication, rate limiting, session regeneration, admin authorization, and security audit logging.
  - Added a one-time bootstrap admin account through `DatabaseSeeder`; subsequent seeding does not overwrite an existing admin password.
  - Added targeted feature coverage for `/admin` routing and username-alias login.
  - The bootstrap credential is temporary and must be rotated immediately after first server login.

2026-07-14
- Landing image differentiation:
  - Kept the supplied HD registration-support image for the Student Registration process section.
  - Replaced the duplicated Document Checklist image with the template's distinct 1920x820 student study/document scene (`bg1.jpg`).
  - Bumped the public UI cache version to `20260714-6` for deployment consistency.
- Registration-step contrast hotfix:
  - Fixed white-on-white process numbers caused by a cached/missing external CSS custom property during deployment.
  - Added a hardcoded Trinity-blue critical fallback, explicit white text fill, and higher-specificity landing selector so steps 1-4 remain visible even while server CSS caches refresh.
  - Bumped the public UI cache version to `20260714-5`.
- Website-wide typography standardization:
  - Selected `Playfair Display` for editorial/institutional headings and `Open Sans` for body copy, navigation, buttons, forms, tables, and dashboard controls.
  - Applied the pairing to the public shell, landing page, no-login student form, admin dashboard shell, admin authentication, legacy registration pages, and printable registration detail.
  - Removed active-route `Muli`, `Roboto Slab`, Segoe UI, and generic system-font overrides that could make different pages render inconsistently.
  - Added Google Fonts preconnects and bumped the shared public UI cache version to `20260714-4`.
  - Left the unused Laravel `welcome.blade.php` and email-client layout on their compatibility fonts by design; neither controls the active website UI.
- Final public UI repair pass from screenshot feedback:
  - Replaced oversized section-title blocks with compact Trinity-blue line markers and forced navigation active/hover states to remain blue.
  - Made landing registration step numbers solid blue with high-contrast white numerals.
  - Replaced the low-quality preparation image with the supplied HD `artie112-ai-generated-9030608.jpg` asset.
  - Kept language and `Start Form` actions aligned horizontally on desktop and compact mobile widths.
  - Changed the registration-form footer to use the same full white Trinity Scholar mark as its approved header.
  - Moved the first-step Important Notice beside the student-information card on desktop, with responsive stacking on tablet/mobile.
  - Bumped public UI asset cache version to `20260714-3` for ZIP/server deployments.
- Trinity blue branding and deployment-cache repair:
  - Added a versioned `public-ui.css` URL plus a small critical inline brand fallback so cached Edification CSS can no longer restore orange top bars, buttons, active lines, or section accents after ZIP deployment.
  - Forced the desktop language selector and `Start Form` action to remain side-by-side, with a compact horizontal mobile layout.
  - Cropped the newly supplied transparent Trinity Scholar logo to its visible pixel bounds and added it as `public/images/trinity-scholar-logo-clean.png`.
  - Applied the clean logo to the landing/public header and every public/form footer while intentionally preserving the already-approved registration-form header logo.
- Registration-flow visual repair:
  - Replaced the low-resolution process photo with the supplied HD `artie112-ai-generated-9030608.jpg` asset.
  - Reworked the weak 2x2 process panel into a clearer vertical step sequence with consistent blue numbering, spacing, and responsive stacking.
- Template-led public UI overhaul:
  - Re-read the local Edification Home 1, Home 2, and Home 3 templates and reused their image-led about, course, event, and editorial composition patterns instead of introducing a separate generic design language.
  - Added one shared `public/theme/trinity/css/public-ui.css` layer for public typography, header, logo, navigation, buttons, footer, responsive behavior, selection color, and reduced-motion handling.
  - Removed the large white logo container; the supplied Trinity Scholar mark now renders as a clean white mark directly on the dark template header and footer.
  - Rebuilt the landing body with a compact icon facts row, split overview image, three template image cards, photo-backed late-registration notice, event-style timeline, image-led registration flow, image-led fee explanation, and visual document checklist.
  - Fixed the unreadable late-registration heading by explicitly maintaining white heading contrast over the template background image.
- Registration form UI overhaul:
  - Kept all existing fields, routes, validation, draft upload, calculations, review behavior, and submission JavaScript unchanged.
  - Reworked the form presentation with a restrained system/serif institutional font stack, neutral dividers, flatter section panels, quieter inputs, simplified progress steps, and an image-led late-registration summary.
  - Removed the visible blue gradient edge, large glow effects, glass cards, and repeated blue-outline treatment that made the form feel disconnected from the Edification landing page.
  - Replaced the text-only passport upload marker with the template Font Awesome upload icon.
- Visual-content and pricing safeguards:
  - Continued to show fee categories and explanations while keeping every public amount as localized `Coming Soon` / `即將公布`.
  - Preserved the native expandable FAQ accordion and all required registration/document content.
- Verification:
  - `git diff --check`, merge-marker scan, section-balance scan, form JavaScript ID scan, asset-existence scan, and public fee-amount scan passed.
  - Direct Vite production build passed with `node node_modules\vite\bin\vite.js build`.
  - Browser QA was intentionally not run because the user explicitly requested no browser use.
- Backup UI restoration:
  - Restored the shared public shell, landing page, and student registration form from `Backup/scholar-trinity-20260714-131756` after reverting the latest premium visual redesign.
  - Removed the additional `public/theme/trinity/css/premium.css` override so spacing, typography, animation, and component styling return to the backed-up Edification/Trinity implementation.
  - Preserved the existing form validation, calculation, review, and submission logic.
- Public fee visibility:
  - Kept the complete landing and registration fee sections, labels, explanations, subject counts, and internal calculations.
  - Replaced only public-facing fee amounts with localized `Coming Soon` / `即將公布` text until pricing is approved.
- FAQ interaction:
  - Kept the landing FAQ as an accessible native expandable/collapsible accordion, with the first item open by default and reduced-motion handling.
- Verification:
  - Confirmed the shared public shell matches the backup byte-for-byte and the removed premium override is no longer present.
  - `git diff --check`, merge-marker scan, form JavaScript ID scan, and public fee-amount scan passed.
  - Direct Vite production build passed with `node node_modules\vite\bin\vite.js build`.
  - Browser QA was intentionally not run for this pass, following the user instruction not to open a browser.

2026-07-11
- Premium UI and motion pass:
  - Used the local `ui-design` and `ui-animation` skill guidance for this pass: preserve the Edification/Trinity visual system, improve hierarchy and spacing, use transform/opacity motion, avoid `transition: all`, and provide reduced-motion fallbacks.
  - Upgraded the public landing visual layer with a more cinematic Trinity-blue hero overlay, softened typography rhythm, glass quick-facts strip, stronger card depth, refined section titles, hover states, and timeline/process/FAQ/document card polish.
  - Added CSS-only reveal motion for landing sections/cards with IntersectionObserver, plus reduced-motion handling so animation does not run for users who disable motion.
  - Upgraded the student registration form surface with a richer header band, glass progress wrapper, blue intro accent, softer card/input focus states, premium exam/payment option hovers, and step-enter animation on Next/Back without changing validation or submission logic.
- Public UI repair pass:
  - Repaired the broken `Registration Flow` section so the dark band wraps the full section, the title/subtitle stay inside the section, and the step cards no longer overflow or get cut off on the left/right edges.
  - Reworked the registration-flow step cards with fixed card sizing, centered Bootstrap columns, cleaner spacing, and Trinity-blue step pills.
  - Changed public text selection highlight from the Edification orange default to a readable Trinity-blue highlight.
  - Reduced public header nav/language control font weight so the header reads closer to the Edification template instead of looking overly bold.
- Registration form typography pass:
  - Added final form CSS overrides to use the Edification font system more consistently: `Muli` for body/form copy and `Roboto Slab` only for headings.
  - Softened label/input/button weights, restored rounded primary buttons, and tightened the form intro/card styling so the form looks closer to the public homepage theme.
- Deployment config safety:
  - Changed `.env.production.example` temporary defaults from empty MySQL/database cache settings to SQLite + file cache/session + sync queue so a no-credential deploy does not immediately hit a Laravel 500.
  - Added no-DB read fallbacks for the public landing payload and student registration form subject list, so compro/form preview pages can still render while the real database is not configured yet.

2026-07-10
- Trinity Scholar branding pass:
  - Generated and added `public/images/trinity-scholar-logo.png` and `public/images/trinity-scholar-favicon.png` from the provided Trinity Scholar logo reference.
  - Replaced Edification logo usage in the public header/footer and student registration header/footer with the Trinity Scholar logo asset.
  - Switched public header, CTA, section accents, menu hover/active accents, and form accent colors from Edification orange to Trinity blue.
- Footer/copyright update:
  - Changed the footer copyright format to match the Sophistec reference: `Copyright © 2026 Trinity Scholar. All Rights Reserved. Designed By Sophistec Dev House. Powered by Sophistec Global.`
  - Added clickable footer links to `https://devhouse.sophistec.global/` and `https://sophistec.global/`.
- Public UX polish:
  - Added smooth scrolling for same-page hash navigation such as `#contact`.
  - Localized the public header nav labels and the form step/header controls so English and Traditional Chinese no longer show together in those primary UI areas.
  - Added form-side locale handling for next/back/submit labels, selected-exam count, upload status, review fallback text, and section/field labels that provide Traditional Chinese spans.
- Trinity blue UI cleanup and bilingual pass:
  - Fixed the oversized blue blocks beside Edification `section-title-style2` headings by restoring them as small line accents.
  - Overrode remaining Edification orange hover, button, carousel, contact-strip, and footer accent colors to Trinity blue.
  - Tightened landing section spacing and added consistent card polish for program, process, fee, document, FAQ, and contact areas.
  - Converted the landing fallback content, public footer, registration intro, upload notice, practice exam copy, accommodation notes, preparation survey, acknowledgement, and confirmation copy to render as either English or Traditional Chinese instead of mixed bilingual text.
  - Localized registration review totals, date fallback, dynamically added accommodation row placeholders, and footer links for the selected UI language.

2026-07-07
- Landing spacing and late notice polish:
  - Replaced the `take-toure-area` late-registration section because its built-in white pseudo block created a large empty gap before the timeline.
  - Added a compact dark late-notice section with two white notice cards and three quick status bars: deadline, form/payment requirement, and admin review.
  - Reduced the timeline top padding and added scroll-margin offsets so fixed/transparent headers do not crowd section titles when navigating by anchors.
- Home 2 public template switch:
  - Replaced the shared public header with the Edification Home 2 structure: orange `header-top`, transparent/dark overlay `header-bottom`, left logo, centered nav, and right-side language switcher plus `Start Form` CTA.
  - Rebuilt the landing hero using Home 2 `slider-area` / `slider_item` markup with the original template background images and Trinity Scholar AP registration copy.
  - Replaced the student registration header with the same Home 2 public header structure and added a template-image top band so the transparent header works on the form page without covering the progress stepper.
  - Removed the middle-logo/header-two layout from the form page so landing and registration no longer use different public header systems.
- Public template bugfix pass:
  - Fixed the Edification slider next/previous controls by pointing the original `angle-left.png` and `angle-right.png` paths to the Laravel public theme asset path.
  - Locked the public header `Start Form` CTA to the template pill radius so the registration form page's local `.btn` rules no longer make it square.
- Public header and landing layout correction:
  - Added the real Laravel language switcher to the shared public landing header, backed by the existing `/locale/{locale}` route, session, and cookie flow.
  - Changed the shared public header to a single consistent nav set: `Home`, `Program`, `Timeline`, middle logo, `Fees`, `FAQ`, `Contact`, plus language switcher and `Start Form`.
  - Fixed the home header right-side overlap by replacing the stacked `Form Info` / `Start Form` buttons with language selector + one primary `Start Form` CTA.
  - Matched the student registration header to the same public header structure so the form page no longer has a different top navigation from the home page.
  - Adjusted the registration page fixed-header spacing by removing the header wrap/height mismatch that could cover the first form intro line.
  - Reduced the gap between fee and document sections, changed the fee total card into the same row as the fee cards, and rebuilt FAQ as a two-column card grid.
- Public landing content fallback fix:
  - Added Blade-level fallback content for fee explanation, required documents, and FAQ so the live landing page no longer renders empty sections when the server database has not been seeded yet.
  - Fee section now shows AP Exam Fee, Trinity Service Fee, Late Registration Fee, and Base Total instead of only a single Base Total card.
  - Required Documents now shows passport, student information, parent information, AP exam selection, payment proof, and accommodation documents.
  - FAQ now shows AP overview, eligibility, late deadline, completion rule, change request, and accommodation guidance.
  - Earlier header CTA test used `Form Info` and `Start Form`; this has since been superseded by the Home 2 header with language switcher plus one `Start Form` CTA.
  - Removed the extra manual Google Fonts link from the public shell so fonts load through Edification's original `default-css.css` import order.
- Hero font/render correction:
  - Confirmed the Edification font stack uses `Muli`, `Quicksand`, and `Roboto Slab`; the public shell now lets the original template CSS load those fonts instead of adding a separate manual font link.
  - Reworked the landing hero copy to match the template rhythm more closely: short uppercase eyebrow, long orange first headline line, long white second headline line, and concise supporting text.
- Header template correction:
  - Earlier Home 1/Home 2 alignment attempts used the middle-logo `header-two` layout; this is now superseded by the Home 2 transparent header.
- Hero template correction:
  - Earlier Home 2-style hero tests used `hero-content`; this is now superseded by the Home 2 `slider-area` hero.
- Edification copy-paste correction pass:
  - Removed the large custom CSS block from the shared public shell so the public header, hero, cards, footer, fonts, and spacing come from the original Edification CSS files.
  - Changed the landing page to use Edification/Bootstrap cards and sections directly; removed the temporary `ts-*` custom classes and the landing-only style block.
  - Fixed the registration page visual break where form-level `.row`, `.card`, `.btn`, and body styles were overriding the copied Edification header/footer. Header/footer rows now reset to the original template layout, and the form progress starts below the fixed template header.
  - Kept the existing registration form fields, autosave, validation, upload draft, payment review, and JavaScript flow intact.
- HTTPS asset URL fix:
  - Added `ASSET_URL` support to Laravel config and forced HTTPS URL generation when `APP_URL` is HTTPS.
  - Reason: on CloudPanel/nginx proxy, the app may see upstream requests as HTTP and generate HTTP asset links, which can make browsers block the original Edification CSS on the HTTPS page.
  - No custom replacement CSS was added; public pages must continue to rely on the original Edification template CSS/JS.
- Edification template alignment pass:
  - Earlier shell used the original `header-two` navigation and middle logo; this has since been replaced by the Home 2 transparent header structure.
  - Converted the landing page to render inside the shared Edification shell instead of using a separate custom head/body/header layout.
  - Reworked the landing sections to follow the approved education-template structure while keeping Word/PDF-required content: program overview, AP registration explanation, late-registration announcement, timeline, process, fees, required documents, FAQ, contact, and Register Now CTA.
  - Updated the student registration page head/header/footer to use the same Edification CSS, header navigation, logo treatment, content width, and footer as the landing page while preserving the existing multi-step form logic.
  - Kept the AP announcement as extracted web copy only; no raw poster image is displayed in the landing or registration page.
- Client content correction pass:
  - Removed the pasted AP announcement image from the landing hero and student registration intro.
  - Converted the announcement into proper web content: late registration deadline, Taipei test-center support, extra late-fee warning, seat-full warning, full-subject notice, completion rule, and admin-confirmation rule.
  - Preserved Ricky's latest registration form changes: numeric phone/postal input handling, no-type input styling, review table styling, and validation behavior.
  - Removed the empty ignored `.agents` folder from the repo root.
- Latest requirement re-check:
  - Re-read `Reference/Trinity Scholar - Features.pdf`; it still maps to the 7-page checklist already tracked in this file.
  - Re-read the Word feature breakdown; landing information page requires program overview, AP registration explanation, timeline, fees, required documents, FAQ, contact, and Register Now CTA.
  - Confirmed the poster content belongs under Landing Website > Announcement Banner / Program Information and Student Registration intro guidance, not as a raw image block.
- Deployment support:
  - Added `SERVER_UPLOAD_GUIDE.md` explaining GitHub-pull deployment, zip-upload fallback, package reinstall, DB credential creation, `.env` handling, port `3014`, and smoke tests.
  - Linked the new upload guide from `DEPLOYMENT.md` and `DEPLOY_COMMANDS.md`.

2026-07-06
- New PDF checklist audit:
  - Read `Reference/Trinity Scholar - Features.pdf` with `pdfplumber`; extracted all 7 pages successfully.
  - PDF contains a broader feature checklist than the earlier MVP notes: Phase 1 Core Registration, Phase 2 Administration Portal, Phase 3 Optional Advanced Features, and Phase 4 Future Online Practice Exam Platform.
  - Re-audited the current Laravel/Vue codebase against every PDF module and sub-module.
  - Added explicit TODO coverage for new missing areas: AP preparation interest survey, tutoring CRM, bulk passport ZIP download, admin practice exam schedule management, payment reminders, admin notifications, email template management UI, general system configuration UI, and online practice exam platform.
  - Confirmed current repo was clean before this audit pass.
- PDF checklist implementation pass:
  - Added AP preparation/tutoring interest fields to student registrations, student form, validation, persistence, admin detail, registration list filter, and exports.
  - Added admin-managed practice exam options with schedule, location, fee, currency, active status, and student-form integration.
  - Practice exam fee is now calculated server-side from selected active practice exam options; legacy fallback is only used when no active practice exam master data exists.
  - Added admin passport ZIP download using current registration filters and private local passport files.
  - Added payment reminder email, admin send-reminder action, payment log/audit entry, notification entry, and `payments:send-reminders` artisan command.
  - Added admin notification module with notification table, service, list page, read/all-read actions, sidebar entry, and event hooks for registration, payment, passport, and receipt events.
  - Added email template management UI backed by the existing `EmailTemplateSetting` table.
  - Added general system settings table/model/admin UI for non-payment/non-e-invoice app preferences.
  - Fixed the practice exam admin page to avoid invalid table/form nesting.
  - Added passport ZIP cleanup when selected registrations have no readable files.
  - Added feature test coverage for AP prep persistence, active practice exam option fee calculation, invalid practice exam rejection, and admin passport ZIP download.

### PDF Checklist Audit - 2026-07-06

Source: `Reference/Trinity Scholar - Features.pdf`

Legend:
- `DONE`: implemented in code at a usable MVP level.
- `PARTIAL`: represented in code, but incomplete, unverified, or missing an expected sub-flow.
- `NOT DONE`: no meaningful implementation found.
- `FUTURE`: explicitly future scope in the PDF.

### Phase 1 - Core Registration Platform

| Module | Sub Module | Status | Evidence / Notes |
| --- | --- | --- | --- |
| Landing Website | Landing Page | DONE | `LandingPageController`, `resources/views/landing/index.blade.php`, landing CMS tables/seeders. |
| Landing Website | Program Information | PARTIAL | Program/AP registration copy exists, but final official client content still pending. |
| Landing Website | Registration Timeline | DONE | Landing timeline model/seeder/view exists. |
| Landing Website | FAQ | DONE | Landing FAQ model/seeder/view exists. |
| Landing Website | Contact Information | DONE | Landing contact model/seeder/view exists. |
| Landing Website | Announcement Banner | DONE | Landing page uses extracted announcement text, deadline, full-subject status, and completion rules. |
| Student Registration | Student Information | DONE | `student_registrations` fields, form, request validation, service persistence. |
| Student Registration | Parent / Guardian Information | DONE | `RegistrationContact`, form fields, persistence, admin display/export. |
| Student Registration | Address Information | DONE | Mailing address/city/district/postal code stored in contact record. |
| Student Registration | Emergency Contact | DONE | Emergency contact fields stored and shown/exported. |
| Student Registration | Registration Validation | DONE | `StoreStudentRegistrationRequest` validates required registration fields and passport requirement. Runtime tests still blocked here by missing PHP. |
| Document Management | Passport Upload | DONE | Student form + draft upload + final storage. |
| Document Management | File Validation | DONE | MIME/size validation and `FileSecurityService`. |
| Document Management | File Preview | PARTIAL | Admin preview exists. Student-side pre-submit visual preview is not fully implemented beyond selected/draft file handling. |
| Document Management | Secure Storage | DONE | Private Laravel local disk, authenticated admin preview/download. |
| Exam Registration | Official AP Exam | DONE | AP subject model, selection, persistence, admin management. |
| Exam Registration | Practice Exam | DONE | Student optional practice exams, server-side total, persistence, export/report. |
| Exam Registration | Subject Availability | DONE | Subject status/open/full/closed handling exists. |
| Exam Registration | Seat Availability | DONE | Quota/registered count/remaining seats displayed and enforced. |
| Exam Registration | Fee Calculation | DONE | Server-side totals for exam/service/late/practice fees. |
| AP Preparation Interest | Preparation Survey | DONE | Preparation/tutoring interest fields are stored on `student_registrations` and collected in the student form. |
| AP Preparation Interest | Group Class Interest | DONE | Group class interest checkbox persists and exports. |
| AP Preparation Interest | Private Tutoring | DONE | Private tutoring interest checkbox persists and exports. |
| AP Preparation Interest | Preferred Schedule | DONE | Preferred tutoring schedule field persists and exports. |
| AP Preparation Interest | Preferred Language | DONE | Preferred tutoring language field persists and exports. |
| Registration Confirmation | Registration Summary | DONE | Review step + confirmation page. |
| Registration Confirmation | Confirmation Checkbox | DONE | Agreement checkboxes and agreement records exist. |
| Registration Confirmation | Registration Lock | PARTIAL | Public flow has no edit route after submit, but there is no explicit lock flag/policy beyond admin-only edits. |
| Registration Confirmation | Registration Number | DONE | Unique registration number generated. |
| Registration Confirmation | Confirmation Page | DONE | `student-registration/show.blade.php`. |
| Basic Payment | Payment Summary | DONE | Payment instruction/status pages show totals and references. |
| Basic Payment | Manual Bank Transfer | DONE | Payment settings/instructions/proof upload/manual verify flow. |
| Basic Payment | Upload Payment Slip | DONE | `payments.proof.upload`, private storage, admin preview/download. |
| Basic Payment | Payment Status | DONE | Registration and payment status fields/workflows exist. |
| Email Notification | Registration Email | DONE | `StudentRegistrationConfirmation`. |
| Email Notification | Payment Reminder | DONE | `PaymentReminderMail`, admin remind action, and `payments:send-reminders` command exist. |
| Email Notification | Missing Document Email | DONE | Passport re-upload request email exists. |

### Phase 2 - Administration Portal

| Module | Sub Module | Status | Evidence / Notes |
| --- | --- | --- | --- |
| Dashboard | Dashboard Overview | DONE | `AdminDashboardService`, admin dashboard view. |
| Dashboard | Registration Statistics | DONE | Metrics, filters, chart data. |
| Dashboard | Revenue Summary | DONE | Revenue/payment metrics and annual report. |
| Dashboard | Practice Exam Statistics | DONE | Practice exam counts/revenue in dashboard/report services. |
| Registration Management | Registration List | DONE | Admin registration index. |
| Registration Management | Search & Filter | DONE | Repository filters include search/status/payment/document/subject/season/date/accommodation. |
| Registration Management | Registration Detail | DONE | Admin detail page. |
| Registration Management | Edit Registration | DONE | Admin edit/manage update exists. |
| Registration Management | Internal Notes | DONE | Admin notes model/request/action. |
| Registration Management | Registration Status | DONE | Verification/status workflow exists. |
| Exam Management | Subject Management | DONE | AP subject CRUD exists. |
| Exam Management | Practice Exam Management | DONE | `PracticeExamOption` CRUD manages optional practice exam schedule, location, fee, and active status. |
| Exam Management | Seat Quota | DONE | Quota and registered count are tracked. |
| Exam Management | Subject Pricing | DONE | Exam/service/late fees editable per subject. |
| Payment Management | Payment Verification | DONE | Manual verify/reject flow exists. |
| Payment Management | Payment History | DONE | Payment logs/records exist. |
| Payment Management | Payment Filter | DONE | Payment admin search/status/method filters exist. |
| Passport Management | Passport Preview | DONE | Admin preview route/service. |
| Passport Management | Download Passport | DONE | Admin download route/service. |
| Passport Management | Download ZIP | DONE | Admin registration list has filtered private passport ZIP download with audit logging. |
| Passport Management | Document Verification | DONE | Valid/invalid/reupload status flow exists. |
| Export & Reports | Excel Export | DONE | XLS Blade export route exists. |
| Export & Reports | CSV Export | DONE | CSV export service/routes exist. |
| Export & Reports | Export by Subject | DONE | Subject filters exist. |
| Export & Reports | Export by Payment | DONE | Payment status filters exist. |
| Export & Reports | Annual Report | DONE | Annual report service/view/export exists. |
| Tutoring CRM | Lead Dashboard | NOT DONE | No CRM lead dashboard found. |
| Tutoring CRM | Lead Assignment | NOT DONE | No counselor assignment model/flow found. |
| Tutoring CRM | Lead Status | NOT DONE | No tutoring lead status workflow found. |
| Tutoring CRM | CRM Report | NOT DONE | No lead conversion report found. |

### Phase 3 - Advanced Features / Optional Add-ons

| Module | Sub Module | Status | Evidence / Notes |
| --- | --- | --- | --- |
| Payment Gateway | ECPay / NewebPay Integration | PARTIAL | Adapter skeleton exists. ECPay has basic payload/signature path; NewebPay throws `LogicException`. Not production-ready. |
| Payment Gateway | Payment Callback | PARTIAL | Callback route/service exists, but provider-specific validation/security is not complete. |
| Payment Gateway | Payment Notification | PARTIAL | Payment confirmation email exists, but real-time provider notification handling is not production-verified. |
| Receipt / Fapiao | Receipt Information | DONE | Public receipt/fapiao form and request persistence. |
| Receipt / Fapiao | Receipt Management | DONE | Admin list/detail/settings/issue/status/send. |
| Receipt / Fapiao | Receipt Export | DONE | Receipt CSV export exists. |
| Receipt / Fapiao | E-Invoice Integration | PARTIAL | Provider interfaces/placeholders exist; real Taiwan e-invoice API not implemented. |
| System Enhancement | Admin Notification | DONE | Admin notification table/service/page exists, with hooks for important registration/payment/passport/receipt events. |
| System Enhancement | Email Template Management | PARTIAL | Admin CRUD UI exists for template overrides; current mailables still render Blade views until dynamic rendering is QA-tested. |
| System Enhancement | System Configuration | DONE | General `system_settings` table/model/admin UI exists. |
| System Enhancement | Activity Log | DONE | Security audit logs and registration audit logs exist. |

### Phase 4 - Online Practice Exam Platform

| Module | Status | Notes |
| --- | --- | --- |
| Question Bank | FUTURE / NOT DONE | No online exam platform implementation found. |
| Multiple Choice Engine | FUTURE / NOT DONE | No online exam engine found. |
| Exam Timer | FUTURE / NOT DONE | No exam timer found. |
| Auto Scoring | FUTURE / NOT DONE | No scoring engine found. |
| Result Dashboard | FUTURE / NOT DONE | No result dashboard found. |
| Analytics | FUTURE / NOT DONE | No online practice analytics found. |
| Certificate Generation | FUTURE / NOT DONE | No certificate generation found. |
| Student Exam History | FUTURE / NOT DONE | No student online exam history found. |

### Earlier Progress

2026-07-04
- Teammate update review:
  - Reviewed `ricky.md` and the files changed by Ricky.
  - Preserved his registration behavior work: validation returns to the relevant step, old form data is restored, browser draft autosave exists, passport draft upload exists, and inline toast messages replace blocking alerts.
- Client/boss feedback pass for Monday demo:
  - Removed the repo-local `.agents` folder from the project directory.
  - Polished the no-login student registration form without changing Ricky's flow logic.
  - Updated registration form inputs/selects/textareas to use softer filled fields, rounded corners, clearer hover/focus/invalid states, and card spacing closer to the client-highlighted textbox style.
  - Cleaned the registration header placeholder branding from generic AP/FORM pills to a Trinity Scholar/AP Registration placeholder that can later be replaced by real logos.
  - Superseded on 2026-07-07: the provided AP announcement is no longer displayed as a poster image in the UI; its content is now extracted into normal web copy.
  - Simplified the landing brand mark to `TS` so the top logo area is less placeholder-heavy until official logo assets arrive.
- Word requirement re-audit:
  - Re-read `Reference/Trinity Scholar - Features.docx` and matched the current code against all phases/modules.
  - Fixed a critical registration gap: passport upload is now required server-side through either an uploaded file or a saved passport draft token.
  - Added a service-level guard so an expired/fake passport draft token cannot create a registration without a passport file.
  - Added test coverage for rejecting registration submissions without passport upload/draft.
  - Limited the demo admin seeder account to local/testing environments so accidental production `db:seed` does not create `test@example.com`.
  - Reconfirmed the largest remaining production gaps are real payment provider integration, real e-invoice/fapiao integration, MySQL production backup automation, full bilingual hardcoded text cleanup, final admin template coverage, and browser/test QA.
- Admin template and bilingual pass:
  - Converted remaining admin management pages to the shared `admin-shell`: registration detail/edit, landing content editor, AP subject index/form, exam season index/form, security audit index/detail.
  - Left print and auth screens standalone intentionally because print needs a clean printable document layout and auth screens are not dashboard management pages.
  - Added shared English and Traditional Chinese admin language files at `lang/en/admin.php` and `lang/zh_TW/admin.php`.
  - Updated admin shell navigation/top actions to use bilingual labels and show the language switcher across admin pages.
  - Replaced major headings, actions, and table labels in the newly converted management pages with `__('admin.*')` keys.
- Deep static admin re-audit:
  - Fixed AP subject active/inactive select handling so an old submitted value of `0` no longer incorrectly selects `Yes`.
  - Fixed exam season active/inactive select handling to use explicit string comparisons.
  - Moved long Blade title expressions in AP subject and exam season forms into local variables for safer template parsing.
  - Improved admin sidebar active states so create/edit/detail routes highlight the correct parent section.

2026-07-03
- Frontend template integration:
  - Integrated the Edification education template assets into `public/theme/edification/`.
  - Added the supplied 2026 AP late registration announcement image to `public/images/ap-late-registration-2026.jpeg` as source/reference material.
  - Rebuilt the public landing/compro page with an education-style hero, quick facts, AP late registration announcement copy, overview cards, process, timeline, fees, required documents, FAQ, contact, privacy, and registration CTA.
  - Kept backend-managed landing content (`hero`, `overview`, `process`, `fees`, `documents`, `faqs`, `contact`, `privacy`) rendering inside the new layout.
  - Added a context intro card above the no-login student registration form.
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

### PDF Checklist Delta - Added 2026-07-06

These items come directly from `Reference/Trinity Scholar - Features.pdf` and were not fully covered by the earlier MVP work.

- AP Preparation Interest:
  - DONE: add registration fields/migration/model support for preparation interest survey.
  - DONE: collect group class interest.
  - DONE: collect private tutoring interest.
  - DONE: collect preferred tutoring schedule.
  - DONE: collect preferred tutoring language.
  - DONE: show/export these fields in admin registration detail, list filter, and CSV/XLSX exports.

- Student-side document preview:
  - TODO: add clearer student-side uploaded passport preview or draft preview before final submit.
  - DONE: admin passport preview exists.

- Practice Exam Management:
  - DONE: create admin CRUD for practice exam schedule/subjects/pricing.
  - DONE: added dedicated `practice_exam_options` table while keeping selected rows in `registration_exam_selections`.

- Passport bulk download:
  - DONE: add admin bulk ZIP download for passport files with audit logging.
  - DONE: ZIP is created from private local disk and skips missing unreadable files.

- Payment reminder:
  - DONE: add scheduled/manual payment reminder email for unpaid or waiting-verification registrations.
  - DONE: initial payment instruction email exists.

- Tutoring CRM:
  - TODO: build lead dashboard for students interested in AP preparation/tutoring.
  - TODO: add counselor assignment.
  - TODO: add lead status/follow-up workflow.
  - TODO: add CRM conversion report.

- System enhancement:
  - DONE: add admin notification mechanism for important events such as new registration, payment proof upload, invalid passport, and receipt request.
  - DONE: build admin email template management UI using existing `EmailTemplateSetting` model/table.
  - DONE: add general system configuration module for app-level preferences that are not payment/e-invoice/landing settings.
  - TODO: wire saved email template overrides into actual mailable rendering after copy QA.

- Online Practice Exam Platform:
  - FUTURE TODO: question bank.
  - FUTURE TODO: multiple choice engine.
  - FUTURE TODO: exam timer.
  - FUTURE TODO: auto scoring.
  - FUTURE TODO: result dashboard.
  - FUTURE TODO: analytics.
  - FUTURE TODO: certificate generation.
  - FUTURE TODO: student exam history.

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
  - DONE: public landing page now uses extracted AP announcement content and education-style visual layout.
  - DONE: temporary landing content/module already exists and CTA now points to `/student-registration`.
  - TODO: final client content review once Trinity sends official copy beyond the announcement/site reference.
  - TODO: replace/expand visual assets if the team approves a different premium template.

- Polish registration form:
  - Keep 5-step/6-step flow working.
  - Clean visual design to match chosen template.
  - Make required fields obvious.
  - Confirm mobile layout.
  - DONE: added no-login AP registration intro with deadline/payment-completion reminders.
  - DONE: pre-submit review now includes the newly required fields and accommodations summary.
  - DONE: textbox/input style now has the softer filled look requested in the client screenshot.
  - DONE: Ricky's validation-step return, autosave, passport draft upload, and toast improvements were reviewed and preserved.
  - DONE: replaced temporary branding with the supplied Trinity Scholar logo assets; the form header keeps its approved treatment and all footers use the cleaned transparent mark.
  - TODO: browser QA on desktop/mobile after PHP server can run locally or on staging.

- Decide template:
  - DONE: current frontend/compro pass uses local Edification template assets.
  - TODO: pick final admin template path: keep Blade/admin shell, install Filament by Composer, or choose another Laravel admin template.
  - TODO: decide whether Edification is final or only temporary before buying Envato assets.

### Phase 1 - MVP Registration Platform

- Landing / Information Page:
  - `PARTIAL`: Edification-styled page exists and still supports backend-managed content.
  - DONE: CTA no longer points to legacy `/register`.
  - DONE: current homepage/compro layout implemented with template assets and extracted announcement content.
  - TODO: final content approval and image replacement if client provides more assets.
  - TODO: verify bilingual content.

- Student Registration Form:
  - `PARTIAL`: form and backend exist.
  - DONE: important hidden/missing data persistence fixed.
  - DONE: review step now includes DOB, nationality, passport number, relationship, emergency contact, and accommodations.
  - DONE: top-of-form intro now uses extracted announcement content and no-login registration guidance.
  - TODO: deeper visual refactor after final frontend/admin template decision.
  - DONE: new submitted fields appear in admin detail/edit/print and exports.

- Exam Preference Selection:
  - `PARTIAL`: available subjects, multiple selection, fee display, late fee, quota/status exist.
  - DONE: practice fee no longer trusted from frontend hidden total.
  - DONE: practice selections are readable through `RegistrationExamSelection`, admin detail/print, confirmation, and export.

- Passport Upload:
  - `MOSTLY DONE`: upload, validation, private storage, admin access, replacement exist.
  - DONE: `.env.example` now aligns `SECURITY_FILE_MAX_KB=10240` with the 10MB registration form limit.
  - DONE: server-side validation now requires either passport upload or a saved passport draft token.
  - DONE: service layer rejects missing/expired passport draft tokens before creating a registration.

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
  - DONE: registration detail/edit pages now use the backend/admin shell.

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
  - TODO: complete NewebPay checkout/callback adapter if NewebPay is chosen.
  - TODO: verify ECPay CheckMacValue/signature against official sandbox examples before enabling production.
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
  - TODO: replace placeholder e-invoice providers with verified provider API calls before using auto issue.

### Phase 5 - Multi-language & UX

- Language System:
  - `PARTIAL`: English and Traditional Chinese files exist.
  - DONE: admin shell navigation/top actions and newly converted admin management pages use shared bilingual keys.
  - DONE: admin language key audit confirms all used `admin.*` translation keys exist in both English and Traditional Chinese files.
  - DONE: language switcher is now visible on landing, student registration, and admin headers; it uses the Laravel locale route/session/cookie flow.
  - TODO: remove hardcoded text from Blade pages.

- Form UX:
  - `PARTIAL`: mobile, progress, validation, confirmation exist.
  - DONE: post-submit confirmation/payment/receipt pages now share a consistent responsive shell.
  - DONE: landing and student registration now share the same Edification header/footer/template CSS instead of visually separate custom layouts.
  - DONE: registration form visual style updated with softer inputs, clearer focus/invalid states, improved header, and text-based announcement treatment.
  - DONE: Ricky's draft autosave/passport draft/validation-step-return improvements are present.
  - TODO: browser QA the Edification-aligned landing/register pages on staging after deploy.
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
  - DONE: production-capable bootstrap admin seeding is available and does not overwrite an existing account password on later seed runs.
  - TODO: rotate the temporary bootstrap admin credential immediately after first successful server login.
  - TODO: implement or document the actual production MySQL/MariaDB backup command/job; built-in `security:backup-database` only supports local SQLite.
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
- Admin dashboard/login requires an initialized database and migrations; the public no-database fallback does not make authenticated admin features database-free.
- `resources/views/student-registration/create.blade.php` still contains a lot of inline CSS/JS and should be replaced or refactored after template choice.
- Backend template zip is Filament source/package code, not a safe raw drop-in template. It should be installed through Composer when the environment supports it.
- Word re-audit 2026-07-04: core MVP registration/admin/manual-payment features are mostly represented, but the app is not production-complete until real payment gateway, real e-invoice/fapiao, and server QA are done.
- Gateway page now supports configured endpoint handoff, but real provider signature and sandbox verification are still pending.
- NewebPay payment adapter still throws `LogicException`; only ECPay skeleton has a payload/signature path.
- Receipt auto issue is still not production-ready; manual sandbox and provider placeholder adapters exist, but real issue/cancel/resend APIs are pending.
- Built-in database backup command only supports local SQLite; production MySQL/MariaDB needs a server backup job.
- Language coverage is incomplete because many view strings are hardcoded.
- Deep admin static audit 2026-07-04 found and fixed a boolean select bug in AP subject/exam season active status fields.
- PDF checklist implementation 2026-07-06 resolved the no-credential PDF gaps for AP preparation interest fields, passport ZIP download, practice exam schedule management, payment reminders, admin notifications, email template management UI, and system configuration UI.
- Remaining PDF gaps that need product/client scope or credentials: tutoring CRM, real ECPay/NewebPay production integration, real Taiwan e-invoice/fapiao API, MySQL production backup job, and future online practice exam platform.
- Server credentials were shared in chat but must stay out of Git.

## Verification Log

2026-07-21
- Confirmed Laravel 12 automatically discovers command classes under `app/Console/Commands` through `ApplicationBuilder::withCommands()`.
- `git diff --check` passed and no merge-conflict markers were found after adding admin recovery.
- Direct Vite production build passed with `node node_modules\vite\bin\vite.js build`.
- Added PHPUnit coverage for command-based admin creation and password reset, but it could not be executed locally because PHP/Composer remain unavailable in PATH.
- Browser QA was not used.

2026-07-15
- `git diff --check` passed and no merge-conflict markers were found after the footer, sticky notice, and admin-entry changes.
- Direct Vite production build passed with `node node_modules\vite\bin\vite.js build`.
- Static checks confirmed the `/admin` route, username alias configuration, bootstrap credential configuration, login translation key, sticky offset, and three-column footer override are present.
- Regenerated `scholar-trinity-deploy.zip` from current tracked source plus the latest Vite build; verified required app/admin/UI files are present and local `.env`, dependencies, logs, and private uploads are excluded.
- PHP and Composer remain unavailable in the local PATH, so the added Laravel feature test could not be executed locally.
- Browser QA was intentionally skipped because the user explicitly requested no browser use.

2026-07-14
- Blue-brand/cache-busting, clean-logo, and registration-flow repair passed `git diff --check`, merge-marker scan, required-asset scan, and the registration JavaScript ID-reference scan (`51` referenced IDs, `0` missing).
- Direct Vite production build passed with `node node_modules\vite\bin\vite.js build`.
- Regenerated `scholar-trinity-deploy.zip` and verified it contains the clean logo, versioned public UI stylesheet source, updated shared shell, landing/form views, and Vite build manifest.
- Browser QA intentionally remains skipped because the user explicitly requested no browser use.

2026-07-11
- Skill-guided UI pass: read/used local `ui-design` and `ui-animation` instructions for the premium public UI/motion polish.
- Static check: `git diff --check` passed after the premium landing/form motion pass.
- Motion check: grep found no `transition: all` and no layout-property transition for `width`, `height`, `top`, or `left` in the touched public UI Blade files.
- Build check: direct Vite production build passed with `node node_modules\vite\bin\vite.js build`.
- Deploy package: regenerated `scholar-trinity-deploy.zip` from the updated source and built public assets.
- Blocked: PHP and Composer are still not available in PATH, so Laravel/PHPUnit tests and PHP lint were not run in this local environment.

2026-07-07
- Static check: shared public shell no longer contains custom inline CSS; it loads the original Edification CSS/JS stack and exposes optional named slots only.
- Static check: landing page no longer contains `@push('styles')`, `ts-*`, or `trinity-meta` template override classes.
- Static check: registration page now resets copied Edification header/footer rows after the form stylesheet so the form grid CSS no longer breaks the template header/footer.
- Static check: `git diff --check` passed after Edification shared-layout pass.
- Static check: no merge conflict markers found in changed public Blade views.
- Static check: landing and student registration views do not reference `public/images/ap-late-registration-2026.jpeg`.
- Build check: direct Vite build passed with bundled Node using `node node_modules/vite/bin/vite.js build`.
- Server deploy finding from manual check: `bootstrap.min.css` and `styles.css` return `200 text/css`; added HTTPS asset URL fix to avoid mixed-content stylesheet links behind proxy.
- Static check: re-read `Reference/Trinity Scholar - Features.pdf`; extracted all 7 pages successfully with `pdfplumber`.
- Static check: re-read `Reference/Trinity Scholar - Features.docx`; confirmed landing/content/form/admin/payment/security requirements are still the same feature breakdown.
- Static check: `resources/views/landing/index.blade.php` no longer references the supplied AP announcement image, poster card, or poster caption.
- Static check: `resources/views/student-registration/create.blade.php` no longer references the supplied AP announcement image or intro poster class.
- Static check: no `.agent` or `.agents` folder remains in the repo root.
- Build check: direct Vite build passed with bundled Node using `node node_modules/vite/bin/vite.js build`.
- Blocked: `npm run build` fails in this Windows path because the npm `.cmd` wrapper resolves Vite as `D:\vite\bin\vite.js`; direct Vite build works.
- Blocked: PHP and Composer are still not available in PATH, so Laravel/PHPUnit tests were not run here.

2026-07-06
- PDF audit: extracted all 7 pages from `Reference/Trinity Scholar - Features.pdf` with `pdfplumber`.
- Static audit: mapped every PDF checklist module/sub-module against routes, controllers, services, models, migrations, views, tests, docs, config, and language files.
- Static audit: confirmed AP Preparation Interest and Tutoring CRM features are not meaningfully implemented yet.
- Static audit: confirmed bulk passport ZIP download is not implemented yet.
- Static audit: confirmed payment/e-invoice provider work remains placeholder/partial, not production-ready.
- Static audit: repo was clean before this audit pass.
- Static check: `git diff --check` passed after the PDF implementation pass.
- Static check: no merge conflict markers found after the PDF implementation pass.
- Static check: no `.agent` or `.agents` folder found in repo root after the PDF implementation pass.
- Static check: confirmed new admin routes for notifications, email templates, system settings, practice exams, payment reminders, and passport ZIP are present.
- Static check: confirmed practice exam update forms no longer use invalid table/form nesting.
- Static check: added targeted PHPUnit coverage for the new no-credential PDF features, but it is not executed here yet.
- Static check: confirmed PHP and Composer are still not available in PATH.
- Blocked: PHP/Laravel tests and browser QA still were not run because PHP/Composer are not available in this Codex environment.

2026-07-04
- Static check: `git diff --check` passed after boss-feedback visual pass.
- Static check: no merge conflict markers found.
- Static check: no `.agent` or `.agents` folder found in repo after cleanup.
- Static check: changed files are limited to `PROGRESS.md`, `resources/views/landing/index.blade.php`, and `resources/views/student-registration/create.blade.php`.
- Static review: Ricky's validation/autosave/passport draft/toast registration changes were left intact.
- Not run: PHP/Laravel browser QA or automated tests in this pass.
- Static/Word audit: re-read `Trinity Scholar - Features.docx` and mapped the current app against each module.
- Static check: `git diff --check` passed after passport-required and seeder-safety fixes.
- Static check: no merge conflict markers found after the re-audit fixes.
- Static check: passport-required validation keys exist in English and Traditional Chinese language files.
- Blocked: `php -v` failed because PHP is not in PATH, so Laravel tests still have not been run here.
- Static check: `git diff --check` passed after admin-shell/bilingual pass.
- Static check: no merge conflict markers found after admin-shell/bilingual pass.
- Static check: all admin management pages now use `x-admin-shell`; only auth screens and print layout remain standalone by design.
- Static check: admin management raw `<!DOCTYPE>` scan is clean when excluding auth and print views.
- Static check: `git diff --check` passed after deep admin re-audit fixes.
- Static check: no merge conflict markers found after deep admin re-audit fixes.
- Static check: old unsafe active-select boolean patterns are no longer present in admin views.
- Static check: all 59 admin route calls found in admin views/components match the route names defined in `routes/web.php`.
- Static check: all used `__('admin.*')` keys exist in both `lang/en/admin.php` and `lang/zh_TW/admin.php`.

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

1. For Monday/demo scope: keep compro + no-login registration + admin can view data stable; run PHP/browser QA on a real PHP environment.
2. Get client/team approval on current Edification compro pass or replace with the approved premium frontend template.
3. Choose final admin template path: keep current Blade admin shell, install Filament by Composer, or use a Laravel admin template.
4. Server deploy execution: fill `.env`, DB credentials, `npm run build`, Laravel migrate.
5. Payment provider decision and real ECPay/NewebPay integration.
6. Fapiao provider decision and real Taiwan e-invoice integration.
7. Build tutoring CRM only if AP preparation interest is confirmed as in-scope for the first paid milestone.
8. Keep Phase 4 online practice exam platform as future scope unless the client explicitly moves it into this project.
