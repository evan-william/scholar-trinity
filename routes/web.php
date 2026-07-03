<?php

use App\Http\Controllers\Admin\LandingPageAdminController;
use App\Http\Controllers\Admin\AnnualReportController;
use App\Http\Controllers\Admin\ApExamSubjectAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExamSeasonAdminController;
use App\Http\Controllers\Admin\PassportManagementController;
use App\Http\Controllers\Admin\PaymentAdminController;
use App\Http\Controllers\Admin\ReceiptAdminController;
use App\Http\Controllers\Admin\RegistrationExportController;
use App\Http\Controllers\Admin\SecurityAuditController;
use App\Http\Controllers\Admin\StudentRegistrationAdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\StudentRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', LocaleController::class)->name('locale.switch');
Route::get('/', LandingPageController::class)->name('landing');
Route::redirect('/register', '/student-registration')->name('registrations.create');
Route::post('/registrations', fn () => redirect('/student-registration'))->name('registrations.store');
Route::redirect('/registrations/{registration}', '/student-registration')->name('registrations.show');

Route::get('/student-registration', [StudentRegistrationController::class, 'create'])->name('student-registrations.create');
Route::post('/student-registration/passport-draft', [StudentRegistrationController::class, 'storePassportDraft'])->middleware('throttle:12,1')->name('student-registrations.passport-draft');
Route::post('/student-registration', [StudentRegistrationController::class, 'store'])->middleware('throttle:8,1')->name('student-registrations.store');
Route::get('/student-registration/{registrationNumber}', [StudentRegistrationController::class, 'show'])->name('student-registrations.show');
Route::get('/payments/{registrationNumber}', [PaymentController::class, 'show'])->name('payments.show');
Route::post('/payments/{registrationPayment}/proof', [PaymentController::class, 'uploadProof'])->middleware('throttle:8,1')->name('payments.proof.upload');
Route::get('/payments/{registrationPayment}/gateway', [PaymentController::class, 'gatewayStart'])->middleware('throttle:8,1')->name('payments.gateway.start');
Route::post('/payments/gateway/callback', [PaymentController::class, 'gatewayCallback'])->middleware('throttle:60,1')->name('payments.gateway.callback');
Route::get('/payments/{registrationPayment}/success', [PaymentController::class, 'success'])->name('payments.success');
Route::get('/payments/{registrationPayment}/failed', [PaymentController::class, 'failed'])->name('payments.failed');
Route::get('/receipts/{registrationPayment}/create', [ReceiptController::class, 'create'])->name('receipts.create');
Route::post('/receipts/{registrationPayment}', [ReceiptController::class, 'store'])->middleware('throttle:8,1')->name('receipts.store');
Route::get('/receipt-requests/{receiptRequest}', [ReceiptController::class, 'show'])->name('receipts.show');
Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');

Route::middleware('guest')->group(function (): void {
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1')->name('admin.login.store');
    Route::get('/admin/forgot-password', [AdminAuthController::class, 'showForgotPassword'])->name('admin.password.request');
    Route::post('/admin/forgot-password', [AdminAuthController::class, 'sendResetLink'])->middleware('throttle:3,1')->name('admin.password.email');
    Route::get('/admin/reset-password/{token}', [AdminAuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/admin/reset-password', [AdminAuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('admin.password.update');
});

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('auth')->name('admin.logout');

Route::middleware(['auth', 'admin', 'admin.timeout'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/reports/annual', [AnnualReportController::class, 'index'])->name('reports.annual');
    Route::get('/reports/annual/export', [AnnualReportController::class, 'export'])->name('reports.annual.export');

    Route::post('/exam-seasons/{examSeason}/activate', [ExamSeasonAdminController::class, 'activate'])->middleware('throttle:20,1')->name('exam-seasons.activate');
    Route::post('/exam-seasons/{examSeason}/archive', [ExamSeasonAdminController::class, 'archive'])->middleware('throttle:20,1')->name('exam-seasons.archive');
    Route::post('/exam-seasons/{examSeason}/duplicate', [ExamSeasonAdminController::class, 'duplicate'])->middleware('throttle:20,1')->name('exam-seasons.duplicate');
    Route::resource('exam-seasons', ExamSeasonAdminController::class)
        ->names('exam-seasons')
        ->except(['show']);

    Route::prefix('landing')->name('landing.')->group(function (): void {
        Route::get('/', [LandingPageAdminController::class, 'edit'])->name('edit');
        Route::put('/', [LandingPageAdminController::class, 'update'])->middleware('throttle:30,1')->name('update');
    });

    Route::prefix('student-registrations')->name('student-registrations.')->group(function (): void {
        Route::get('/', [StudentRegistrationAdminController::class, 'index'])->name('index');
        Route::get('/export', [StudentRegistrationAdminController::class, 'export'])->name('export');
        Route::get('/{studentRegistration}', [StudentRegistrationAdminController::class, 'show'])->name('show');
        Route::get('/{studentRegistration}/edit', [StudentRegistrationAdminController::class, 'edit'])->name('edit');
        Route::put('/{studentRegistration}', [StudentRegistrationAdminController::class, 'update'])->middleware('throttle:30,1')->name('update');
        Route::patch('/{studentRegistration}/manage', [StudentRegistrationAdminController::class, 'updateManaged'])->middleware('throttle:30,1')->name('manage-update');
        Route::post('/{studentRegistration}/verify', [StudentRegistrationAdminController::class, 'verify'])->middleware('throttle:30,1')->name('verify');
        Route::post('/{studentRegistration}/notes', [StudentRegistrationAdminController::class, 'addNote'])->middleware('throttle:30,1')->name('notes.store');
        Route::get('/{studentRegistration}/passport/preview', [PassportManagementController::class, 'preview'])->name('passport.preview');
        Route::get('/{studentRegistration}/passport/download', [PassportManagementController::class, 'download'])->name('passport.download');
        Route::post('/{studentRegistration}/passport/replace', [PassportManagementController::class, 'replace'])->middleware('throttle:20,1')->name('passport.replace');
        Route::post('/{studentRegistration}/passport/status', [PassportManagementController::class, 'status'])->middleware('throttle:30,1')->name('passport.status');
        Route::post('/{studentRegistration}/passport/reupload', [PassportManagementController::class, 'reupload'])->middleware('throttle:20,1')->name('passport.reupload');
        Route::delete('/{studentRegistration}', [StudentRegistrationAdminController::class, 'destroy'])->name('destroy');
        Route::get('/{studentRegistration}/print', [StudentRegistrationAdminController::class, 'print'])->name('print');
    });

    Route::get('/exports', [RegistrationExportController::class, 'index'])->name('exports.index');
    Route::post('/exports', [RegistrationExportController::class, 'store'])->middleware('throttle:20,1')->name('exports.store');
    Route::get('/exports/{registrationExportLog}/download', [RegistrationExportController::class, 'download'])->name('exports.download');

    Route::prefix('payments')->name('payments.')->group(function (): void {
        Route::get('/', [PaymentAdminController::class, 'index'])->name('index');
        Route::get('/settings', [PaymentAdminController::class, 'settings'])->name('settings');
        Route::put('/settings', [PaymentAdminController::class, 'updateSettings'])->middleware('throttle:20,1')->name('settings.update');
        Route::get('/{registrationPayment}', [PaymentAdminController::class, 'show'])->name('show');
        Route::post('/{registrationPayment}/verify', [PaymentAdminController::class, 'verify'])->middleware('throttle:30,1')->name('verify');
        Route::get('/{registrationPayment}/proof/preview', [PaymentAdminController::class, 'proofPreview'])->name('proof.preview');
        Route::get('/{registrationPayment}/proof/download', [PaymentAdminController::class, 'proofDownload'])->name('proof.download');
    });

    Route::prefix('receipts')->name('receipts.')->group(function (): void {
        Route::get('/', [ReceiptAdminController::class, 'index'])->name('index');
        Route::get('/export', [ReceiptAdminController::class, 'export'])->name('export');
        Route::get('/settings', [ReceiptAdminController::class, 'settings'])->name('settings');
        Route::put('/settings', [ReceiptAdminController::class, 'updateSettings'])->middleware('throttle:20,1')->name('settings.update');
        Route::get('/{receiptRequest}', [ReceiptAdminController::class, 'show'])->name('show');
        Route::patch('/{receiptRequest}', [ReceiptAdminController::class, 'update'])->middleware('throttle:30,1')->name('update');
        Route::post('/{receiptRequest}/issue', [ReceiptAdminController::class, 'issue'])->middleware('throttle:30,1')->name('issue');
        Route::post('/{receiptRequest}/status', [ReceiptAdminController::class, 'status'])->middleware('throttle:30,1')->name('status');
        Route::post('/{receiptRequest}/send', [ReceiptAdminController::class, 'send'])->middleware('throttle:30,1')->name('send');
        Route::post('/{receiptRequest}/auto-issue', [ReceiptAdminController::class, 'autoIssue'])->middleware('throttle:20,1')->name('auto-issue');
    });

    Route::prefix('security/audit')->name('security.audit.')->group(function (): void {
        Route::get('/', [SecurityAuditController::class, 'index'])->name('index');
        Route::get('/{securityAuditLog}', [SecurityAuditController::class, 'show'])->name('show');
    });

    Route::resource('ap-exam-subjects', ApExamSubjectAdminController::class)
        ->names('ap-exam-subjects')
        ->except(['show']);
});
