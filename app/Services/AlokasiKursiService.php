<?php

namespace App\Services;

use App\Models\BookingRuangan;
use App\Models\Ruangan;
use Illuminate\Support\Facades\DB;

class AlokasiKursiService
{
    /**
     * Puncak kursi terpakai oleh kumpulan booking pada window [$mulai, $selesai).
     */
    public function puncakTerpakai($bookings, string $mulai, string $selesai): int
    {
        $titik = [$mulai];
        foreach ($bookings as $b) {
            if ($b->jam_mulai < $selesai && $b->jam_selesai > $mulai) {
                $titik[] = max($b->jam_mulai, $mulai);
            }
        }

        $puncak = 0;
        foreach (array_unique($titik) as $t) {
            $sum = 0;
            foreach ($bookings as $b) {
                if ($b->jam_mulai <= $t && $b->jam_selesai > $t) {
                    $sum += $b->jumlah_kursi;
                }
            }
            $puncak = max($puncak, $sum);
        }

        return $puncak;
    }

    /**
     * Semua nomor kursi yang sudah terpakai (dari booking disetujui) pada slot ini.
     */
    public function seatsYangTerpakai(Ruangan $ruangan, $tanggal, string $mulai, string $selesai, ?int $excludeId = null): array
    {
        return BookingRuangan::where('ruangan_id', $ruangan->id)
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'disetujui')
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where('jam_mulai', '<', $selesai)
            ->where('jam_selesai', '>', $mulai)
            ->whereNotNull('kursi_dipilih')
            ->pluck('kursi_dipilih')
            ->flatten()
            ->unique()
            ->map(fn($n) => (int) $n)
            ->values()
            ->toArray();
    }

    /**
     * Sisa kursi (count-based) — dipakai untuk approve manual jika booking tidak punya kursi_dipilih.
     */
    public function kursiTersedia(Ruangan $ruangan, $tanggal, string $mulai, string $selesai, ?int $excludeId = null): int
    {
        $disetujui = BookingRuangan::where('ruangan_id', $ruangan->id)
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'disetujui')
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->get();

        $puncak = $this->puncakTerpakai($disetujui, $mulai, $selesai);

        return max(0, $ruangan->kapasitas_kursi - $puncak);
    }

    /**
     * Cek apakah kursi_dipilih booking ini bentrok dengan booking lain yang sudah disetujui.
     * Mengembalikan array nomor kursi yang bentrok (kosong = tidak ada konflik).
     */
    public function konflikKursi(Ruangan $ruangan, $tanggal, string $mulai, string $selesai, array $kursiDipilih, ?int $excludeId = null): array
    {
        $terpakai = $this->seatsYangTerpakai($ruangan, $tanggal, $mulai, $selesai, $excludeId);

        return array_values(array_intersect($kursiDipilih, $terpakai));
    }

    /**
     * Proses seluruh booking pending pada satu tanggal.
     *
     * Urutan: prioritas (kelas=1 > dosen=2 > mahasiswa=3) lalu FIFO.
     * Booking dengan kursi_dipilih: dicek konflik kursi spesifik.
     * Booking tanpa kursi_dipilih (legacy): dicek kapasitas total.
     */
    public function prosesTanggal($tanggal): array
    {
        $hasil = ['disetujui' => 0, 'ditolak' => 0];

        DB::transaction(function () use ($tanggal, &$hasil) {
            $ruanganIds = BookingRuangan::whereDate('tanggal', $tanggal)
                ->where('status', 'pending')
                ->distinct()
                ->pluck('ruangan_id');

            foreach ($ruanganIds as $ruanganId) {
                $ruangan = Ruangan::find($ruanganId);
                if (! $ruangan) continue;

                $pending = BookingRuangan::where('ruangan_id', $ruanganId)
                    ->whereDate('tanggal', $tanggal)
                    ->where('status', 'pending')
                    ->orderBy('prioritas')   // kelas (1) → dosen (2) → mahasiswa (3)
                    ->orderBy('created_at')  // FIFO dalam prioritas sama
                    ->lockForUpdate()
                    ->get();

                foreach ($pending as $booking) {
                    $bisa   = false;
                    $catatan = '';

                    if ($booking->kursi_dipilih && count($booking->kursi_dipilih) > 0) {
                        // ── Cek konflik kursi spesifik ──────────────────────────
                        $konflik = $this->konflikKursi(
                            $ruangan,
                            $tanggal,
                            $booking->jam_mulai,
                            $booking->jam_selesai,
                            $booking->kursi_dipilih
                        );

                        if (empty($konflik)) {
                            $bisa    = true;
                            $catatan = 'Disetujui otomatis — kursi tersedia.';
                        } else {
                            $catatan = 'Ditolak otomatis — kursi ' . implode(', ', $konflik) .
                                       ' sudah diambil booking dengan prioritas lebih tinggi.';
                        }
                    } else {
                        // ── Cek kapasitas total (booking tanpa kursi spesifik) ──
                        $tersedia = $this->kursiTersedia(
                            $ruangan,
                            $tanggal,
                            $booking->jam_mulai,
                            $booking->jam_selesai
                        );

                        if ($booking->jumlah_kursi <= $tersedia) {
                            $bisa    = true;
                            $catatan = 'Disetujui otomatis — kuota kursi tersedia.';
                        } else {
                            $catatan = "Ditolak otomatis — kuota tidak cukup pada slot ini (sisa {$tersedia} kursi).";
                        }
                    }

                    $booking->update([
                        'status'      => $bisa ? 'disetujui' : 'ditolak',
                        'catatan'     => $catatan,
                        'diproses_at' => now(),
                    ]);

                    $bisa ? $hasil['disetujui']++ : $hasil['ditolak']++;
                }
            }
        });

        return $hasil;
    }
}
