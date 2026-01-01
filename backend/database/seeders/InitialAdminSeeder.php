<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class InitialAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ticketflow.app'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
            ]
        );
    }
}
