<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@library.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->assignRole('admin');

        $employee = User::updateOrCreate(
            ['email' => 'employee@library.com'],
            [
                'name' => 'Library Employee',
                'password' => Hash::make('password123'),
            ]
        );
        $employee->assignRole('employee');
    }
}