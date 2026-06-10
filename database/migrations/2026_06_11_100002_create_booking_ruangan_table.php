<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_ruangan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ruangan_id')->constrained('ruangan')->cascadeOnDelete();

            // Tipe menentukan prioritas alokasi: kelas (1) > dosen (2) > mahasiswa (3)
            $table->enum('tipe', ['kelas', 'dosen', 'mahasiswa']);
            $table->unsignedTinyInteger('prioritas'); // 1=kelas, 2=dosen, 3=mahasiswa

            $table->string('mata_kuliah')->nullable(); // diisi bila tipe = kelas
            $table->text('keperluan');

            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedInteger('jumlah_kursi');

            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'dibatalkan'])->default('pending');
            $table->text('catatan')->nullable();   // alasan tolak / catatan sistem
            $table->timestamp('diproses_at')->nullable();

            $table->timestamps();

            $table->index(['ruangan_id', 'tanggal', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_ruangan');
    }
};
