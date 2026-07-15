<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;
use App\Services\SecurityAuditService;

class AdminAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'login' => ['nullable', 'string', 'max:255', 'required_without:email'],
            'email' => ['nullable', 'email', 'required_without:login'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $remember = $request->boolean('remember');
        $identifier = trim((string) ($validated['login'] ?? $validated['email']));
        $errorKey = array_key_exists('login', $validated) ? 'login' : 'email';
        $email = Str::lower($identifier) === Str::lower((string) config('admin.login_username', 'admin'))
            ? (string) config('admin.login_email', 'admin@trinityscholar.local')
            : Str::lower($identifier);
        $credentials = ['email' => $email, 'password' => $validated['password']];

        if (! Auth::attempt($credentials, $remember) || ! Auth::user()->isAdmin()) {
            $userId = Auth::id();
            Auth::logout();
            Log::warning('Failed admin login.', ['identifier' => $identifier, 'user_id' => $userId, 'ip' => $request->ip()]);
            app(SecurityAuditService::class)->log('auth', 'admin_login_failed', 'Failed admin login.', null, [], [], ['identifier' => $identifier], 'failed', $request, $userId);

            return back()->withErrors([$errorKey => __('admin_auth.failed')])->onlyInput($errorKey);
        }

        $request->session()->regenerate();
        $request->session()->put('admin_last_activity_at', now());
        Log::info('Successful admin login.', ['user_id' => Auth::id(), 'ip' => $request->ip()]);
        app(SecurityAuditService::class)->log('auth', 'admin_login_success', 'Successful admin login.', Auth::user(), [], [], [], 'success', $request);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Log::info('Admin logout.', ['user_id' => Auth::id()]);
        app(SecurityAuditService::class)->log('auth', 'admin_logout', 'Admin logout.', $request->user(), [], [], [], 'success', $request);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function showForgotPassword(): View
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        Log::info('Admin password reset requested.', ['email' => $request->input('email')]);
        app(SecurityAuditService::class)->log('auth', 'password_reset_requested', 'Password reset requested.', null, [], [], ['email' => $request->input('email')], 'success', $request);
        Password::sendResetLink($request->only('email'));

        return back()->with('status', __('admin_auth.reset_link_sent'));
    }

    public function showResetPassword(Request $request, string $token): View
    {
        return view('admin.auth.reset-password', ['token' => $token, 'email' => $request->query('email')]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(12)->mixedCase()->numbers()->symbols()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                    'password_changed_at' => now(),
                ])->save();
                event(new PasswordReset($user));
                Log::info('Admin password reset completed.', ['user_id' => $user->id]);
                app(SecurityAuditService::class)->log('auth', 'password_reset_completed', 'Password reset completed.', $user, [], [], [], 'success', request(), $user->id);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('status', __('admin_auth.password_reset'))
            : back()->withErrors(['email' => __('admin_auth.reset_failed')]);
    }
}
