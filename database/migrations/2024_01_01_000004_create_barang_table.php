<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->increments('id_barang');
            $table->unsignedInteger('id_kategori')->nullable();
            $table->unsignedInteger('id_supplier')->nullable();
            $table->string('nama_barang', 100);
            $table->string('satuan', 30);
            $table->integer('stok')->default(0);
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->nullOnDelete();
            $table->foreign('id_supplier')->references('id_supplier')->on('supplier')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
