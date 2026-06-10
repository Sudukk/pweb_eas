<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingRuangan;
use App\Models\PengaturanBooking;
use App\Services\AlokasiKursiService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookingRuanganController extends Controller
{
    public function index(Request $request)
    {
        $pengaturan = PengaturanBooking::first();
        $query      = BookingRuangan::with(['user', 'ruangan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_booking', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        $booking = $query->orderBy('tanggal')
            ->orderBy('prioritas')
            ->orderBy('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.booking-ruangan.index', compact('booking', 'pengaturan'));
    }

    public function show(BookingRuangan $bookingRuangan)
    {
        $bookingRuangan->load(['user', 'ruangan']);

        // Kursi terpakai oleh booking LAIN yang disetujui pada slot yang sama
        $kursiLain = BookingRuangan::where('ruangan_id', $bookingRuangan->ruangan_id)
            ->whereDate('tanggal', $bookingRuangan->tanggal)
            ->where('status', 'disetujui')
            ->where('id', '!=', $bookingRuangan->id)
            ->where('jam_mulai', '<', $bookingRuangan->jam_selesai)
            ->where('jam_selesai', '>', $bookingRuangan->jam_mulai)
            ->whereNotNull('kursi_dipilih')
            ->pluck('kursi_dipilih')
            ->flatten()
            ->unique()
            ->values()
            ->map(fn($n) => (int) $n)
            ->toArray();

        return view('admin.booking-ruangan.show', compact('bookingRuangan', 'kursiLain'));
    }

    public function approve(BookingRuangan $bookingRuangan, AlokasiKursiService $alokasi)
    {
        if ($bookingRuangan->status !== 'pending') {
            return back()->with('error', 'Booking sudah diproses.');
        }

        $ruangan = $bookingRuangan->ruangan;

        if ($bookingRuangan->kursi_dipilih && count($bookingRuangan->kursi_dipilih) > 0) {
            $konflik = $alokasi->konflikKursi(
                $ruangan,
                $bookingRuangan->tanggal,
                $bookingRuangan->jam_mulai,
                $bookingRuangan->jam_selesai,
                $bookingRuangan->kursi_dipilih,
                $bookingRuangan->id
            );

            if (! empty($konflik)) {
                return back()->with('error', 'Kursi ' . implode(', ', $konflik) . ' sudah terisi oleh booking lain pada slot ini.');
            }
        } else {
            $tersedia = $alokasi->kursiTersedia(
                $ruangan,
                $bookingRuangan->tanggal,
                $bookingRuangan->jam_mulai,
                $bookingRuangan->jam_selesai,
                $bookingRuangan->id
            );

            if ($bookingRuangan->jumlah_kursi > $tersedia) {
                return back()->with('error', "Kuota tidak cukup. Sisa kursi pada slot ini: {$tersedia}.");
            }
        }

        $bookingRuangan->update([
            'status'      => 'disetujui',
            'catatan'     => 'Disetujui manual oleh admin.',
            'diproses_at' => now(),
        ]);

        return back()->with('success', 'Booking disetujui.');
    }

    public function reject(Request $request, BookingRuangan $bookingRuangan)
    {
        $request->validate(['catatan' => 'required|string|max:500']);

        if (! in_array($bookingRuangan->status, ['pending', 'disetujui'])) {
            return back()->with('error', 'Booking tidak dapat ditolak.');
        }

        $bookingRuangan->update([
            'status'      => 'ditolak',
            'catatan'     => $request->catatan,
            'diproses_at' => now(),
        ]);

        return back()->with('success', 'Booking ditolak.');
    }

    public function simpanPengaturan(Request $request)
    {
        $request->validate(['jam_alokasi' => 'required|date_format:H:i']);

        $p = PengaturanBooking::firstOrNew([]);
        $p->jam_alokasi   = $request->jam_alokasi . ':00';
        $p->last_ran_date = null; // reset so it can run again today at the new time
        $p->updated_by    = auth()->id();
        $p->updated_at    = now();
        $p->save();

        return back()->with('success',
            'Jam alokasi otomatis disimpan: ' . $request->jam_alokasi . '. Scheduler akan berjalan tepat waktu tersebut setiap hari.');
    }
}
