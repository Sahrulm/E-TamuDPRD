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
                'password' => Hash::make('resepsionis123'),    // password di-hash
            ],
            
        );
    }
}
