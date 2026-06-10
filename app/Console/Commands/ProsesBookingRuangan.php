<?php

namespace App\Console\Commands;

use App\Models\BookingRuangan;
use App\Models\PengaturanBooking;
use App\Services\AlokasiKursiService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ProsesBookingRuangan extends Command
{
    protected $signature = 'booking:proses {--tanggal= : Proses hanya tanggal ini (YYYY-MM-DD); default: semua tanggal pending}';

    protected $description = 'Alokasi otomatis booking kursi ruangan berdasarkan prioritas (kelas > dosen > mahasiswa).';

    public function handle(AlokasiKursiService $alokasi): int
    {
        if ($this->option('tanggal')) {
            $tanggalList = [Carbon::parse($this->option('tanggal'))->toDateString()];
        } else {
            $tanggalList = BookingRuangan::where('status', 'pending')
                ->distinct()
                ->orderBy('tanggal')
                ->pluck('tanggal')
                ->map(fn($t) => Carbon::parse($t)->toDateString())
                ->all();
        }

        if (empty($tanggalList)) {
            $this->info('Tidak ada booking pending yang perlu diproses.');
            return self::SUCCESS;
        }

        $total = ['disetujui' => 0, 'ditolak' => 0];

        foreach ($tanggalList as $tanggal) {
            $this->info("Memproses tanggal {$tanggal} ...");
            $hasil = $alokasi->prosesTanggal($tanggal);
            $this->line("  → Disetujui: {$hasil['disetujui']}, Ditolak: {$hasil['ditolak']}");
            $total['disetujui'] += $hasil['disetujui'];
            $total['ditolak']   += $hasil['ditolak'];
        }

        $this->info("Selesai. Total disetujui: {$total['disetujui']}, ditolak: {$total['ditolak']}.");

        // Catat tanggal terakhir alokasi dijalankan agar scheduler tidak menjalankan ulang hari ini
        if (!$this->option('tanggal')) {
            PengaturanBooking::query()->update(['last_ran_date' => today()->toDateString()]);
        }

        return self::SUCCESS;
    }
}
