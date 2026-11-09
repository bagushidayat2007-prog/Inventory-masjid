
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_pengeluaran', function (Blueprint $table) {
            $table->id('id_pengajuan_pengeluaran');

            $table->unsignedInteger('id_pengguna');
            $table->date('tanggal');
            $table->decimal('jumlah', 15, 2);
            $table->string('tujuan', 150)->nullable();
            $table->string('keterangan', 255)->nullable();

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
        Schema::dropIfExists('pengajuan_pengeluaran');
    }
};
