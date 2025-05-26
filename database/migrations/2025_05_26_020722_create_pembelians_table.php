<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembelian')->unique(); // Misal: INV/YYYYMMDD/XXXX
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Anggota/Pembeli
            $table->foreignId('kasir_id')->nullable()->constrained('users')->onDelete('set null'); // Pengurus/Admin yg melayani
            $table->dateTime('tanggal_pembelian');
            $table->decimal('total_harga', 15, 2);
            $table->decimal('total_bayar', 15, 2)->default(0);
            $table->decimal('kembalian', 15, 2)->default(0);
            $table->enum('status_pembayaran', ['lunas', 'belum_lunas', 'cicilan'])->default('lunas');
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'saldo_sukarela', 'hutang'])->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};