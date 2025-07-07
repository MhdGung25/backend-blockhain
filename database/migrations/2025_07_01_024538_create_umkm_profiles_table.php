<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('umkm_profiles', function (Blueprint $table) {
             $table->id();
    $table->unsignedBigInteger('user_id');
    $table->string('nama_usaha');
    $table->string('jenis_usaha');
    $table->text('deskripsi');
    $table->string('alamat', 500);
    $table->string('no_telepon', 20);
    $table->string('email');
    $table->year('tahun_berdiri');
   $table->integer('jumlah_karyawan')->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkm_profiles');
    }
};
