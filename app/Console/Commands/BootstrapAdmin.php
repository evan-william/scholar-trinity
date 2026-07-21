<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class BootstrapAdmin extends Command
{
    protected $signature = 'admin:bootstrap
        {--username= : Admin username alias}
        {--email= : Admin account email}
        {--password= : Admin password}';

    protected $description = 'Create or reset the configured Trinity Scholar administrator account';

    public function handle(): int
    {
        $username = trim((string) ($this->option('username') ?: config('admin.login_username', 'admin')));
        $email = strtolower(trim((string) ($this->option('email') ?: config('admin.login_email', 'admin@trinityscholar.local'))));
        $password = (string) ($this->option('password') ?: config('admin.bootstrap_password', ''));

        if ($username === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->error('Provide a valid username and email address.');

            return self::FAILURE;
        }

        if (strlen($password) < 8) {
            $this->error('The bootstrap password must contain at least 8 characters.');

            return self::FAILURE;
        }

        $admin = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $username,
                'password' => Hash::make($password),
                'is_admin' => true,
                'email_verified_at' => now(),
                'password_changed_at' => now(),
            ]
        );

        $this->info("Admin account {$admin->email} is ready. Sign in with username {$username} or the account email.");

        return self::SUCCESS;
    }
}
