<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::connection()->getPdo();
            $superRole = Role::firstOrCreate(['name' => 'superadmin']);
            $adminRole = Role::firstOrCreate(['name' => 'admin']);
            User::updateOrCreate(
                ['email' => 'superadmin@example.com'],
                [
                    'name' => 'Super Admin',
                    'password' => Hash::make('password'),
                    'role_id' => $superRole->id,
                ]
            );

            // Create or update Admin
            User::updateOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Admin',
                    'password' => Hash::make('password'),
                    'role_id' => $adminRole->id,
                ]
            );
        } catch (\Exception $e) {
            Log::warning('AdminUsersSeeder skipped: database unavailable - ' . $e->getMessage());
            return;
        }
    }
}
