<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->increments('id_keluar');
            $table->unsignedInteger('id_pengguna')->nullable();
            $table->date('tanggal');
            $table->string('tujuan', 255)->nullable();
            $table->string('keterangan', 255)->nullable();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_keluar');
    }
};
