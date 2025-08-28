<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- 5 Admin (admin1..admin5) ---
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => "admin{$i}@example.com"],
                [
                    'name'     => "Admin {$i}",
                    'role'     => 'admin',
                    'password' => Hash::make('Password123!'),
                ]
            );
        }

        // --- 10 Petugas (petugas1..petugas10) ---
        for ($i = 1; $i <= 10; $i++) {
            User::updateOrCreate(
                ['email' => "petugas{$i}@example.com"],
                [
                    'name'     => "Petugas {$i}",
                    'role'     => 'officer', // sesuaikan kalau enum/kolom berbeda
                    'password' => Hash::make('Password123!'),
                ]
            );
        }
    }
}
