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
        Schema::create('pengajuans', function (Blueprint $table) {
           $table->id();
        $table->unsignedBigInteger('user_id');
       $table->string('bank')->nullable();
        $table->string('status');
       $table->string('logo_url')->nullable();
       $table->integer('tenor')->default(12);
        $table->integer('jumlah');
         $table->string('alasan');
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
