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
        Schema::create('daily_work_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('petugas')->nullable();
            $table->date('tanggal')->index();
            $table->string('waktu_mulai', 10)->nullable();
            $table->string('waktu_selesai', 10)->nullable();
            $table->text('kegiatan');
            $table->string('kategori')->default('Pelayanan Front Office')->index();
            $table->string('output_hasil')->nullable();
            $table->integer('volume')->default(1);
            $table->string('satuan', 50)->default('Kegiatan');
            $table->enum('status', ['selesai', 'proses', 'tertunda'])->default('selesai');
            $table->text('keterangan')->nullable();
            $table->string('file_dokumentasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_work_reports');
    }
};
