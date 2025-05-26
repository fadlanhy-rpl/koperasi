<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pembelians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelians')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('restrict'); // Agar barang tidak bisa dihapus jika ada transaksi
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2); // Harga jual barang saat transaksi
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pembelians');
    }
};