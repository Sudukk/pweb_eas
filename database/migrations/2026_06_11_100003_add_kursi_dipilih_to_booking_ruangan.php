<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_ruangan', function (Blueprint $table) {
            $table->json('kursi_dipilih')->nullable()->after('jumlah_kursi');
        });
    }

    public function down(): void
    {
        Schema::table('booking_ruangan', function (Blueprint $table) {
            $table->dropColumn('kursi_dipilih');
        });
    }
};
