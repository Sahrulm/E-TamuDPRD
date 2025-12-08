<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Gunakan updateOrCreate agar idempotent (aman dijalankan berulang)
        User::updateOrCreate(
            ['email' => 'resepsionis@dprdgorontalo.com'],
            [
                'full_name'     => 'resepsionis',
                'role'     => 'resepsionis',                   // pastikan kolom 'role' ada
                'password' => Hash::make('password123'),    // password di-hash
            ],
        );
        User::updateOrCreate(
            ['email' => 'admin@dprdgorontalo.com'],
            [
                'full_name'     => 'admin',
                'role'     => 'admin',                   // pastikan kolom 'role' ada
                'password' => Hash::make('password123'),    // password di-hash
            ],
        );
        User::updateOrCreate(
            ['email' => 'host@dprdgorontalo.com'],
            [
                'full_name'     => 'host',
                'role'     => 'host',                   // pastikan kolom 'role' ada
                'password' => Hash::make('password123'),    // password di-hash
            ],
        );
    }
}
