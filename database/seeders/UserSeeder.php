<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // admin acc
        User::create([
            'name' => 'Admin',
            'phone' => '1234567890',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // user acc
        User::create([
            'name' => 'User',
            'phone' => '0987654321',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // user acc 2
        User::create([
            'name' => 'Biman Shah',
            'phone' => '0112345678',
            'email' => 'aiman@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // user acc 3
        User::create([
            'name' => 'Nur Aina',
            'phone' => '0198765555',
            'email' => 'aina@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // user acc 4
        User::create([
            'name' => 'Daniel Lim',
            'phone' => '0102233445',
            'email' => 'daniel@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
