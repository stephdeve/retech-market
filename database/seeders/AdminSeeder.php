<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'stevendevadmin@retechmarket.com'],
            [
                'name' => 'stevendevadmin',
                'email' => 'stevendevadmin@retechmarket.com',
                'password' => Hash::make('stevendevadmin123'),
                'email_verified_at' => now(),
                'currency' => 'XOF',
            ]
        );

        // Ensure admin role exists and assign it
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        $this->command->info('✅ Admin user created: stevendevadmin@retechmarket.com');
        $this->command->info('🔑 Password: stevendevadmin123');
    }
}
