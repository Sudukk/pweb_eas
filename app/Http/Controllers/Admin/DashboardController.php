<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\BookingRuangan;
use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\Ruangan;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $totalAlat       = Alat::count();
        $menunggu        = Peminjaman::where('status', 'menunggu')->count();
        $dipinjam        = Peminjaman::where('status', 'dipinjam')->count();
        $totalUser       = User::whereIn('role', ['mahasiswa', 'dosen'])->count();
        $dendaBelumLunas = Denda::where('status', 'belum_lunas')->sum('nominal');

        $peminjamanTerbaru = Peminjaman::with(['user', 'detail.alat'])
            ->latest()->limit(5)->get();

        // ── Data untuk chart: distribusi status peminjaman ──
        $statusList    = ['menunggu', 'dipinjam', 'selesai', 'ditolak'];
        $statusCounts  = Peminjaman::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')->pluck('total', 'status');
        $chartStatus = collect($statusList)->mapWithKeys(
            fn ($s) => [$s => (int) ($statusCounts[$s] ?? 0)]
        );

        // ── Distribusi status booking ruangan ──
        $bookingStatusList   = ['pending', 'disetujui', 'ditolak', 'dibatalkan'];
        $bookingStatusCounts = BookingRuangan::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')->pluck('total', 'status');
        $chartBookingStatus = collect($bookingStatusList)->mapWithKeys(
            fn ($s) => [$s => (int) ($bookingStatusCounts[$s] ?? 0)]
        );

        // ── Kondisi alat ──
        $kondisiList   = ['baik', 'rusak_ringan', 'maintenance'];
        $kondisiCounts = Alat::selectRaw('kondisi, COUNT(*) as total')
            ->groupBy('kondisi')->pluck('total', 'kondisi');
        $chartKondisi = collect($kondisiList)->mapWithKeys(
            fn ($k) => [$k => (int) ($kondisiCounts[$k] ?? 0)]
        );

        // ── Top 5 alat paling sering dipinjam (berdasar total jumlah) ──
        $topAlat = PeminjamanDetail::selectRaw('alat_id, SUM(jumlah) as total')
            ->groupBy('alat_id')->orderByDesc('total')->limit(5)
            ->with('alat')->get();
        $topAlatLabels = $topAlat->map(fn ($r) => $r->alat->nama ?? 'Alat dihapus')->values();
        $topAlatData   = $topAlat->map(fn ($r) => (int) $r->total)->values();

        // ── Statistik ringkas tambahan ──
        $totalRuangan      = Ruangan::count();
        $bookingMenunggu   = BookingRuangan::where('status', 'pending')->count();
        $totalPeminjaman   = Peminjaman::count();
        $totalBooking      = BookingRuangan::count();

        // ── Data untuk chart: tren alat vs ruangan dengan filter rentang waktu ──
        $rangeOptions = [
            'today'    => 'Hari ini',
            '7d'       => '7 hari terakhir',
            '1m'       => '1 bulan terakhir',
            '3m'       => '3 bulan terakhir',
            '1sem'     => '1 semester (6 bulan)',
            'all'      => 'All time',
        ];
        $range = $request->input('range', '3m');
        if (! array_key_exists($range, $rangeOptions)) {
            $range = '3m';
        }

        [$trenLabels, $trenAlat, $trenRuangan] = $this->buildTren($range);

        return view('admin.dashboard', compact(
            'totalAlat', 'menunggu', 'dipinjam',
            'totalUser', 'dendaBelumLunas', 'peminjamanTerbaru',
            'chartStatus', 'trenLabels', 'trenAlat', 'trenRuangan',
            'rangeOptions', 'range',
            'chartBookingStatus', 'chartKondisi', 'topAlatLabels', 'topAlatData',
            'totalRuangan', 'bookingMenunggu', 'totalPeminjaman', 'totalBooking'
        ));
    }

    /**
     * Bangun data tren (label + jumlah peminjaman alat & booking ruangan) sesuai
     * rentang waktu. Bucket harian untuk rentang pendek, bulanan untuk rentang panjang.
     *
     * @return array{0: array<string>, 1: array<int>, 2: array<int>}
     */
    private function buildTren(string $range): array
    {
        $labels = [];
        $alat   = [];
        $ruangan = [];

        $countAlat = fn ($from, $to) => Peminjaman::whereBetween('created_at', [$from, $to])->count();
        $countRuangan = fn ($from, $to) => BookingRuangan::whereBetween('created_at', [$from, $to])->count();

        if ($range === 'today') {
            // Bucket per jam (0..23)
            $hari = Carbon::today();
            for ($h = 0; $h < 24; $h++) {
                $from = $hari->copy()->addHours($h);
                $to   = $from->copy()->addHour()->subSecond();
                $labels[]  = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
                $alat[]    = $countAlat($from, $to);
                $ruangan[] = $countRuangan($from, $to);
            }
            return [$labels, $alat, $ruangan];
        }

        // Bucket harian untuk rentang pendek
        $hariMap = ['7d' => 7, '1m' => 30, '3m' => 90];
        if (isset($hariMap[$range])) {
            $jumlahHari = $hariMap[$range];
            for ($i = $jumlahHari - 1; $i >= 0; $i--) {
                $tgl = Carbon::today()->subDays($i);
                $labels[]  = $tgl->isoFormat('D MMM');
                $alat[]    = $countAlat($tgl->copy()->startOfDay(), $tgl->copy()->endOfDay());
                $ruangan[] = $countRuangan($tgl->copy()->startOfDay(), $tgl->copy()->endOfDay());
            }
            return [$labels, $alat, $ruangan];
        }

        // Bucket bulanan: 1 semester (6 bulan) atau all time
        if ($range === '1sem') {
            $jumlahBulan = 6;
        } else { // all time
            $awal = Peminjaman::min('created_at');
            $awalBk = BookingRuangan::min('created_at');
            $paling = collect([$awal, $awalBk])->filter()->min();
            $jumlahBulan = $paling
                ? Carbon::parse($paling)->startOfMonth()->diffInMonths(Carbon::now()->startOfMonth()) + 1
                : 6;
            $jumlahBulan = max(1, min($jumlahBulan, 36)); // batasi maksimal 36 bulan
        }

        for ($i = $jumlahBulan - 1; $i >= 0; $i--) {
            $bulan = Carbon::now()->startOfMonth()->subMonths($i);
            $labels[]  = $bulan->isoFormat('MMM YYYY');
            $alat[]    = $countAlat($bulan->copy()->startOfMonth(), $bulan->copy()->endOfMonth());
            $ruangan[] = $countRuangan($bulan->copy()->startOfMonth(), $bulan->copy()->endOfMonth());
        }

        return [$labels, $alat, $ruangan];
    }
}
