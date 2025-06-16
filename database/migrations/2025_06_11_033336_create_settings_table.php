<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps(); // Opsional, tapi baik untuk melacak kapan setting diubah
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};