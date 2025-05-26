<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\UnitUsaha;
use App\Models\HistoriStok; // Jika ingin mencatat stok awal sebagai histori

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitSeragam = UnitUsaha::where('nama_unit_usaha', 'Seragam Sekolah Anak')->first();
        $unitATK = UnitUsaha::where('nama_unit_usaha', 'Alat Tulis Kantor (ATK)')->first();
        $unitKantin = UnitUsaha::where('nama_unit_usaha', 'Kantin Koperasi')->first();
        $adminUser = \App\Models\User::where('role', 'admin')->first(); // Untuk user_id di histori stok

        if ($unitSeragam) {
            $barangSeragam = [
                ['kode_barang' => 'SRG-SD-001', 'nama_barang' => 'Seragam SD Merah Putih (Set L)', 'harga_beli' => 75000, 'harga_jual' => 90000, 'stok' => 50, 'satuan' => 'set'],
                ['kode_barang' => 'SRG-SMP-001', 'nama_barang' => 'Seragam SMP Biru Putih (Set M)', 'harga_beli' => 85000, 'harga_jual' => 100000, 'stok' => 40, 'satuan' => 'set'],
                ['kode_barang' => 'SRG-PRAM-001', 'nama_barang' => 'Seragam Pramuka Penggalang (Set)', 'harga_beli' => 90000, 'harga_jual' => 110000, 'stok' => 30, 'satuan' => 'set'],
            ];
            foreach ($barangSeragam as $item) {
                $barang = $unitSeragam->barangs()->create($item);
                // Catat histori stok awal
                if ($adminUser) {
                    HistoriStok::create([
                        'barang_id' => $barang->id,
                        'user_id' => $adminUser->id,
                        'tipe' => 'masuk',
                        'jumlah' => $item['stok'],
                        'stok_sebelum' => 0,
                        'stok_sesudah' => $item['stok'],
                        'keterangan' => 'Stok awal',
                    ]);
                }
            }
        }

        if ($unitATK) {
            $barangATK = [
                ['kode_barang' => 'ATK-PUL-001', 'nama_barang' => 'Pulpen Pilot G2 Hitam', 'harga_beli' => 8000, 'harga_jual' => 10000, 'stok' => 100, 'satuan' => 'pcs'],
                ['kode_barang' => 'ATK-BUK-001', 'nama_barang' => 'Buku Tulis Sidu 38 Lembar', 'harga_beli' => 2500, 'harga_jual' => 3500, 'stok' => 200, 'satuan' => 'pcs'],
                ['kode_barang' => 'ATK-PEN-001', 'nama_barang' => 'Pensil Faber Castell 2B', 'harga_beli' => 3000, 'harga_jual' => 4000, 'stok' => 150, 'satuan' => 'pcs'],
            ];
            foreach ($barangATK as $item) {
                $barang = $unitATK->barangs()->create($item);
                if ($adminUser) {
                     HistoriStok::create([
                        'barang_id' => $barang->id,
                        'user_id' => $adminUser->id,
                        'tipe' => 'masuk',
                        'jumlah' => $item['stok'],
                        'stok_sebelum' => 0,
                        'stok_sesudah' => $item['stok'],
                        'keterangan' => 'Stok awal',
                    ]);
                }
            }
        }

        if ($unitKantin) {
            $barangKantin = [
                ['kode_barang' => 'KTN-MIN-001', 'nama_barang' => 'Air Mineral Botol 600ml', 'harga_beli' => 2000, 'harga_jual' => 3000, 'stok' => 70, 'satuan' => 'botol'],
                ['kode_barang' => 'KTN-SNK-001', 'nama_barang' => 'Roti Coklat Sari Roti', 'harga_beli' => 4000, 'harga_jual' => 5000, 'stok' => 50, 'satuan' => 'pcs'],
            ];
            foreach ($barangKantin as $item) {
                $barang = $unitKantin->barangs()->create($item);
                 if ($adminUser) {
                     HistoriStok::create([
                        'barang_id' => $barang->id,
                        'user_id' => $adminUser->id,
                        'tipe' => 'masuk',
                        'jumlah' => $item['stok'],
                        'stok_sebelum' => 0,
                        'stok_sesudah' => $item['stok'],
                        'keterangan' => 'Stok awal',
                    ]);
                }
            }
        }
    }
}