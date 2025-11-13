<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder admin
        $this->call([
            AdminUserSeeder::class,
        ]);

        // Contoh user tambahan
        // User::create([
        //     'full_name'     => 'Test User', // gunakan 'full_name' jika kolom Anda bernama itu
        //     'email'    => 'test@example.com',
        //     'password' => Hash::make('password'), // hapus jika kolom password tidak wajib
        // ]);
    }
}
