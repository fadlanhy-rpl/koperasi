<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('histori_stoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // User yg melakukan perubahan
            $table->enum('tipe', ['masuk', 'keluar', 'penyesuaian']); // Masuk (pembelian), Keluar (penjualan/rusak), Penyesuaian (stok opname)
            $table->integer('jumlah');
            $table->integer('stok_sebelum');
            $table->integer('stok_sesudah');
            $table->text('keterangan')->nullable(); // Misal: "Pembelian dari Supplier X", "Penjualan No. INV/...", "Barang Rusak"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('histori_stoks');
    }
};