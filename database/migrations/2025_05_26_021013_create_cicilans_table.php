<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cicilans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelians')->onDelete('cascade');
            // $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Anggota yg mencicil (redundant, bisa dari pembelian->user_id)
            $table->decimal('jumlah_bayar', 15, 2);
            $table->date('tanggal_bayar');
            $table->foreignId('pengurus_id')->nullable()->constrained('users')->onDelete('set null'); // Pengurus yg mencatat
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cicilans');
    }
};