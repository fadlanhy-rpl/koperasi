<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin Koperasi',
            'email' => 'admin@koperasi.test',
            'password' => Hash::make('password'), // Ganti dengan password yang aman
            'nomor_anggota' => 'ADM001',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Pengurus Users
        User::create([
            'name' => 'Pengurus Satu',
            'email' => 'pengurus1@koperasi.test',
            'password' => Hash::make('password'),
            'nomor_anggota' => 'PNG001',
            'role' => 'pengurus',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Pengurus Dua',
            'email' => 'pengurus2@koperasi.test',
            'password' => Hash::make('password'),
            'nomor_anggota' => 'PNG002',
            'role' => 'pengurus',
            'email_verified_at' => now(),
        ]);

        // Anggota/User Biasa
        User::factory()->count(10)->create(); // Menggunakan factory untuk data dummy anggota

        // Atau buat anggota secara manual jika tidak menggunakan factory
        /*
        User::create([
            'name' => 'Anggota Budi',
            'email' => 'budi@anggota.test',
            'password' => Hash::make('password'),
            'nomor_anggota' => 'AGT001',
            'role' => 'anggota',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Anggota Ani',
            'email' => 'ani@anggota.test',
            'password' => Hash::make('password'),
            'nomor_anggota' => 'AGT002',
            'role' => 'anggota',
            'email_verified_at' => now(),
        ]);
        */
    }
}