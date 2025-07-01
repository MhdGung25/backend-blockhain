<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tujuans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('umkm_id');
            $table->string('tujuan');
            $table->timestamps();

            // Relasi ke tabel usaha (jika ada)
            $table->foreign('umkm_id')->references('id')->on('usahas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tujuans');
    }
};
