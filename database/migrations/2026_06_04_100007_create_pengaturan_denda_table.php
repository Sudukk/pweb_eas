<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_denda', function (Blueprint $table) {
            $table->id();
            $table->decimal('tarif_per_hari', 10, 2)->default(5000);
            $table->decimal('denda_kerusakan_ringan', 10, 2)->default(50000);
            $table->decimal('denda_kerusakan_berat', 10, 2)->default(200000);
            $table->decimal('denda_kehilangan', 10, 2)->default(500000);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_denda');
    }
};
