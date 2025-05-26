<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SimpananPokok;
use App\Models\SimpananSukarela;
use Carbon\Carbon;

class SimpananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anggotaUsers = User::where('role', 'anggota')->take(5)->get(); // Ambil 5 anggota
        $pengurusUser = User::where('role', 'pengurus')->first(); // Untuk pengurus_id

        if ($anggotaUsers->isNotEmpty() && $pengurusUser) {
            foreach ($anggotaUsers as $anggota) {
                // Simpanan Pokok
                SimpananPokok::create([
                    'user_id' => $anggota->id,
                    'jumlah' => 100000, // Contoh jumlah simpanan pokok
                    'tanggal_bayar' => Carbon::now()->subMonths(rand(1,6))->startOfMonth(),
                    'pengurus_id' => $pengurusUser->id,
                    'keterangan' => 'Simpanan pokok awal keanggotaan',
                ]);

                // Beberapa Simpanan Sukarela
                $saldoSukarela = 0;
                for ($i = 0; $i < rand(2, 5); $i++) {
                    $jumlahSetor = rand(20, 100) * 1000;
                    SimpananSukarela::create([
                        'user_id' => $anggota->id,
                        'tipe_transaksi' => 'setor',
                        'jumlah' => $jumlahSetor,
                        'saldo_sebelum' => $saldoSukarela,
                        'saldo_sesudah' => $saldoSukarela + $jumlahSetor,
                        'tanggal_transaksi' => Carbon::now()->subDays(rand(1, 90)),
                        'pengurus_id' => $pengurusUser->id,
                        'keterangan' => 'Setoran sukarela',
                    ]);
                    $saldoSukarela += $jumlahSetor;
                }

                // Opsional: contoh penarikan sukarela
                if ($saldoSukarela > 50000 && rand(0,1)) {
                    $jumlahTarik = rand(10, floor($saldoSukarela / 1000 / 2)) * 1000; // Tarik maksimal setengah saldo
                     SimpananSukarela::create([
                        'user_id' => $anggota->id,
                        'tipe_transaksi' => 'tarik',
                        'jumlah' => $jumlahTarik,
                        'saldo_sebelum' => $saldoSukarela,
                        'saldo_sesudah' => $saldoSukarela - $jumlahTarik,
                        'tanggal_transaksi' => Carbon::now()->subDays(rand(1, 30)),
                        'pengurus_id' => $pengurusUser->id,
                        'keterangan' => 'Penarikan sukarela',
                    ]);
                    $saldoSukarela -= $jumlahTarik;
                }
            }
        } else {
            $this->command->info('Tidak ada user anggota atau pengurus yang ditemukan untuk SimpananSeeder.');
        }
    }
}