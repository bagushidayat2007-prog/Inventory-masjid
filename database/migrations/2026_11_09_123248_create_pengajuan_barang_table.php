
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_barang', function (Blueprint $table) {
            $table->id('id_pengajuan_barang');

            $table->unsignedInteger('id_pengguna');
            $table->unsignedInteger('id_kategori')->nullable();
            $table->unsignedInteger('id_supplier')->nullable();

            $table->string('nama_barang', 100);
            $table->string('satuan', 30);
            $table->integer('stok')->default(0);
            $table->enum('kondisi', [
                'baik',
                'rusak_ringan',
                'rusak_berat'
            ])->default('baik');

            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak'
            ])->default('menunggu');

            $table->unsignedInteger('disetujui_oleh')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamp('diproses_pada')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_barang');
    }
};
