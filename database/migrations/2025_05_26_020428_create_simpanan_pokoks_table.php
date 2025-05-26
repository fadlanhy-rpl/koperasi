<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simpanan_pokoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Anggota
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal_bayar');
            $table->foreignId('pengurus_id')->nullable()->constrained('users')->onDelete('set null'); // Pengurus yg mencatat
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'tanggal_bayar']); // Biasanya simpanan pokok sekali atau per periode tertentu
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simpanan_pokoks');
    }
};
