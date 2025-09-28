<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ProductionUserSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Create roles if they don't exist
        $admin = Role::findOrCreate('Admin', 'web');
        $default = Role::findOrCreate('Default', 'web');

        // Create or update admin user
        $user = User::updateOrCreate(
            ['email' => 'admin@projectopia.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('admin123!'), // Change this in production!
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role
        if (!$user->hasRole($admin)) {
            $user->assignRole($admin);
        }

        // Create a default team for the admin user
        $team = Team::updateOrCreate(
            ['name' => 'Default Team'],
            ['owner_id' => $user->id]
        );

        $this->command->info('Production user and team created successfully!');
        $this->command->info('Email: admin@projectopia.com');
        $this->command->info('Password: admin123! (CHANGE THIS IN PRODUCTION!)');
        $this->command->info('Team: Default Team');
    }
}
