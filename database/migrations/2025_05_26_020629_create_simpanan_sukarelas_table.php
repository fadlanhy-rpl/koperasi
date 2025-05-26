<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simpanan_sukarelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Anggota
            $table->enum('tipe_transaksi', ['setor', 'tarik']);
            $table->decimal('jumlah', 15, 2);
            $table->decimal('saldo_sebelum', 15, 2)->default(0);
            $table->decimal('saldo_sesudah', 15, 2)->default(0);
            $table->date('tanggal_transaksi');
            $table->foreignId('pengurus_id')->nullable()->constrained('users')->onDelete('set null'); // Pengurus yg mencatat
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simpanan_sukarelas');
    }
};