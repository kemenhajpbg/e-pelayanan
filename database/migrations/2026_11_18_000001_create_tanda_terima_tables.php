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
        Schema::create('tanda_terima', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_tanda_terima')->unique();
            $table->string('nama_penyetor');
            $table->string('no_hp');
            $table->text('keterangan')->nullable();
            $table->longText('tanda_tangan'); // Base64 data URI PNG
            $table->foreignId('petugas_id')->constrained('users');
            $table->string('status')->default('Diterima'); // Diterima, Diproses, Selesai, Dikembalikan, Dibatalkan
            $table->text('alasan_status')->nullable();
            $table->dateTime('submitted_at'); // Waktu resmi server Asia/Jakarta
            $table->timestamps();

            $table->index('submitted_at');
            $table->index('status');
            $table->index('nama_penyetor');
            $table->index('no_hp');
        });

        Schema::create('tanda_terima_berkas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanda_terima_id')->constrained('tanda_terima')->cascadeOnDelete();
            $table->string('jenis_berkas');
            $table->timestamps();

            $table->index('jenis_berkas');
        });

        Schema::create('tanda_terima_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tanda_terima_id')->constrained('tanda_terima')->cascadeOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('users');
            $table->string('status');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanda_terima_status_history');
        Schema::dropIfExists('tanda_terima_berkas');
        Schema::dropIfExists('tanda_terima');
    }
};
