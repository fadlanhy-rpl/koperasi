<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitUsaha;

class UnitUsahaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitUsahaData = [
            ['nama_unit_usaha' => 'Seragam Sekolah Anak', 'deskripsi' => 'Menyediakan berbagai jenis seragam sekolah untuk anak.'],
            ['nama_unit_usaha' => 'Alat Tulis Kantor (ATK)', 'deskripsi' => 'Menyediakan alat tulis dan kebutuhan kantor.'],
            ['nama_unit_usaha' => 'Kantin Koperasi', 'deskripsi' => 'Menyediakan makanan dan minuman ringan.'],
            ['nama_unit_usaha' => 'Barang Pokok', 'deskripsi' => 'Menyediakan kebutuhan pokok anggota.'],
        ];

        foreach ($unitUsahaData as $data) {
            UnitUsaha::create($data);
        }
    }
}