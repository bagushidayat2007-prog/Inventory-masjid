<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_barang_masuk', function (Blueprint $table) {
            $table->increments('id_detail');
            $table->unsignedInteger('id_masuk');
            $table->unsignedInteger('id_barang');
            $table->integer('jumlah');

            $table->foreign('id_masuk')->references('id_masuk')->on('barang_masuk')->cascadeOnDelete();
            $table->foreign('id_barang')->references('id_barang')->on('barang')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_barang_masuk');
    }
};
