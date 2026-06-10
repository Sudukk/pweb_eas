<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pinjam')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->text('keperluan');
            $table->string('dokumen_pendukung')->nullable();
            $table->enum('status', [
                'menunggu',
                'disetujui_dosen',
                'disetujui_admin',
                'ditolak',
                'dipinjam',
                'dikembalikan',
                'selesai',
            ])->default('menunggu');
            $table->text('catatan_penolakan')->nullable();
            $table->foreignId('reviewed_by_dosen')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reviewed_by_admin')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('dosen_reviewed_at')->nullable();
            $table->timestamp('admin_reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
