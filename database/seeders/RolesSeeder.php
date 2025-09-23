<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $admin = Role::findOrCreate('Admin', 'web');
        $default = Role::findOrCreate('Default', 'web');

        $user = User::query()->where('email', 'test@example.com')->first();
        if ($user) {
            if (! $user->hasRole($admin)) {
                $user->assignRole($admin);
            }
        }
    }
}


