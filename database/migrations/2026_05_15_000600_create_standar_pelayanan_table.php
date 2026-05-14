<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('standar_pelayanan', function (Blueprint $table) {
            $table->id();

            $table->string('nama');
            $table->string('email');
            $table->string('nomor_hp');

            $table->string('judul');
            $table->text('pesan');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standar_pelayanan');
    }
};