<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class GrantSystemAdministrator extends Command
{
    protected $signature = 'app:grant-system-admin {email}';

    protected $description = 'Grant the System Administrator role to an existing user';

    public function handle(): int
    {
        $user = User::query()->where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('No user exists with that email address.');

            return self::FAILURE;
        }

        $role = Role::query()->where('slug', 'system-administrator')->first();

        if (! $role) {
            $this->error('System Administrator role is missing. Run the database seeder first.');

            return self::FAILURE;
        }

        $user->roles()->syncWithoutDetaching([$role->getKey()]);

        $this->info("System Administrator granted to {$user->email}.");

        return self::SUCCESS;
    }
}
