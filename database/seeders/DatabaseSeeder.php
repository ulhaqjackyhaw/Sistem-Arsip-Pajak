<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Vendors (wadah per NPWP) ---
        $vendors = [
            ['npwp' => '0123456789012345', 'name' => 'PT Contoh Satu', 'email' => 'finance@contoh1.test'],
            ['npwp' => '9876543210987654', 'name' => 'PT Contoh Dua',  'email' => 'finance@contoh2.test'],
        ];

        foreach ($vendors as $v) {
            Vendor::firstOrCreate(
                ['npwp' => $v['npwp']],
                ['name' => $v['name'], 'email' => $v['email'] ?? null]
            );
        }

        // --- Admin ---
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Admin',
                'role'     => 'admin',
                'password' => Hash::make('Password123!'),
            ]
        );

        // --- Petugas (officer) ---
        User::updateOrCreate(
            ['email' => 'petugas@example.com'],
            [
                'name'     => 'Petugas Pajak',
                'role'     => 'officer',
                'password' => Hash::make('Password123!'),
            ]
        );

        // --- Vendor user #1 (login: NPWP + password) ---
        $v1 = Vendor::where('npwp', '0123456789012345')->first();
        User::updateOrCreate(
            ['email' => 'vendor@example.com'],
            [
                'name'      => 'Vendor A',
                'role'      => 'vendor',
                'npwp'      => '0123456789012345',
                'vendor_id' => $v1?->id,
                'password'  => Hash::make('Password123!'),
            ]
        );

        // --- Vendor user #2 (opsional, untuk uji multi vendor) ---
        $v2 = Vendor::where('npwp', '9876543210987654')->first();
        User::updateOrCreate(
            ['email' => 'vendor2@example.com'],
            [
                'name'      => 'Vendor B',
                'role'      => 'vendor',
                'npwp'      => '9876543210987654',
                'vendor_id' => $v2?->id,
                'password'  => Hash::make('Password123!'),
            ]
        );
    }
}
