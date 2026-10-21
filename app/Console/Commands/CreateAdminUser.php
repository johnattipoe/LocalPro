<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'localpro:make-admin';
    protected $description = 'Create the first LocalPro administrator account';

    public function handle(): int
    {
        $name = trim((string) $this->ask('Administrator name'));
        $email = strtolower(trim((string) $this->ask('Administrator email')));
        if ($name === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Enter a name and a valid email address.');
            return self::FAILURE;
        }
        $existingUser = User::where('email', $email)->first();
        if ($existingUser && ! $this->confirm('That email already belongs to an account. Promote it to an active administrator and set a new password?', false)) {
            $this->line('No account changes were made.');
            return self::SUCCESS;
        }

        $password = (string) $this->secret('Password (at least 12 characters)');
        $confirmation = (string) $this->secret('Confirm password');
        if (strlen($password) < 12 || $password !== $confirmation) {
            $this->error('Passwords must match and be at least 12 characters long.');
            return self::FAILURE;
        }

        if ($existingUser) {
            $existingUser->update([
                'name' => $name,
                'role' => 'admin',
                'is_active' => true,
                'password' => Hash::make($password),
            ]);
            $this->info('Existing account promoted to administrator with a new password. You can now sign in at /login.');
        } else {
            User::create([
                'name' => $name,
                'email' => $email,
                'role' => 'admin',
                'city' => 'Ho',
                'is_active' => true,
                'password' => Hash::make($password),
            ]);
            $this->info('Administrator account created. You can now sign in at /login.');
        }

        return self::SUCCESS;
    }
}
