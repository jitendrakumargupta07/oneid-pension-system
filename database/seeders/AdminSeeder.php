<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@oneid.gov.in'],
            [
                'name'     => 'System Administrator',
                'email'    => 'admin@oneid.gov.in',
                'password' => Hash::make('Admin@1234'),
                'role'     => 'admin',
                'is_active'=> true,
            ]
        );

        $this->command->info('✅ Admin user created: admin@oneid.gov.in / Admin@1234');
    }
}
