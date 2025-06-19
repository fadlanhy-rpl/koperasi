<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Tambahkan setelah kolom deskripsi atau kolom lain yang sesuai
            $table->string('gambar_path', 2048)->nullable()->after('deskripsi'); 
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn('gambar_path');
        });
    }
};