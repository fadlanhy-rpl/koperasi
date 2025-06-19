<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('nomor_anggota')->unique()->nullable(); // Nomor unik untuk anggota
            $table->enum('role', ['admin', 'pengurus', 'anggota'])->default('anggota');
            $table->rememberToken();
            $table->timestamps();
            $table->date('date_of_birth')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};