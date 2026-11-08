<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_log', function (Blueprint $table) {
            $table->increments('id_log');
            $table->unsignedInteger('id_pengguna')->nullable();
            $table->string('aktivitas', 255);
            $table->timestamp('waktu')->useCurrent();
            $table->string('ip_address', 45)->nullable();

            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_log');
    }
};
