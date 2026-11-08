<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keuangan', function (Blueprint $table) {
            $table->increments('id_keuangan');
            $table->unsignedInteger('id_pengguna')->nullable();
            $table->date('tanggal');
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->decimal('jumlah', 15, 2);
            $table->string('sumber', 150)->nullable();
            $table->string('tujuan', 150)->nullable();
            $table->string('keterangan', 255)->nullable();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};
