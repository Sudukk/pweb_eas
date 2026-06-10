<?php

namespace App\Http\Controllers;

use App\Models\BookingRuangan;
use App\Models\PengaturanBooking;
use App\Models\Ruangan;
use App\Services\AlokasiKursiService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class BookingRuanganController extends Controller
{
    public function index()
    {
        $booking = BookingRuangan::with('ruangan')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('booking-ruangan.index', compact('booking'));
    }

    public function create()
    {
        $ruangan    = Ruangan::where('aktif', true)->orderBy('nama')->get();
        $jamAlokasi = substr(PengaturanBooking::jamAlokasi(), 0, 5); // "HH:MM"
        $minTanggal = now()->addDay()->toDateString();

        return view('booking-ruangan.create', compact('ruangan', 'jamAlokasi', 'minTanggal'));
    }

    /** AJAX: kembalikan daftar nomor kursi yang sudah terpakai pada slot tertentu. */
    public function kursiTerpakai(Request $request)
    {
        $request->validate([
            'ruangan_id' => 'required|exists:ruangan,id',
            'tanggal'    => 'required|date',
            'jam_mulai'  => 'required',
            'jam_selesai'=> 'required',
        ]);

        $terpakai = BookingRuangan::where('ruangan_id', $request->ruangan_id)
            ->whereDate('tanggal', $request->tanggal)
            ->where('status', 'disetujui')
            ->where('jam_mulai', '<', $request->jam_selesai)
            ->where('jam_selesai', '>', $request->jam_mulai)
            ->whereNotNull('kursi_dipilih')
            ->pluck('kursi_dipilih')
            ->flatten()
            ->unique()
            ->values()
            ->toArray();

        return response()->json(['terpakai' => $terpakai]);
    }

    public function store(Request $request, AlokasiKursiService $alokasi)
    {
        $role = auth()->user()->role;
        $tipeBoleh = $role === 'dosen' ? ['dosen', 'kelas'] : ['mahasiswa'];

        $data = $request->validate([
            'ruangan_id'   => 'required|exists:ruangan,id',
            'tipe'         => ['required', 'in:' . implode(',', $tipeBoleh)],
            'mata_kuliah'  => 'nullable|required_if:tipe,kelas|string|max:150',
            'keperluan'    => 'required|string|max:500',
            'tanggal'      => 'required|date|after_or_equal:tomorrow',
            'jam_mulai'    => 'required|date_format:H:i',
            'jam_selesai'  => 'required|date_format:H:i|after:jam_mulai',
            'kursi_dipilih'=> 'required|array|min:1',
            'kursi_dipilih.*' => 'required|integer|min:1',
        ]);

        $ruangan = Ruangan::findOrFail($data['ruangan_id']);

        // Kursi yang dipilih tidak boleh melebihi kapasitas.
        foreach ($data['kursi_dipilih'] as $no) {
            if ($no > $ruangan->kapasitas_kursi) {
                throw ValidationException::withMessages([
                    'kursi_dipilih' => "Kursi nomor {$no} tidak ada pada ruangan ini.",
                ]);
            }
        }

        // Cutoff: pengajuan harus sebelum jam_alokasi pada H-1.
        $jamAlokasi = PengaturanBooking::jamAlokasi(); // "HH:MM:SS"
        [$ch, $cm]  = array_map('intval', explode(':', $jamAlokasi));
        $cutoff = Carbon::parse($data['tanggal'])->subDay()->setTime($ch, $cm);
        if (now()->greaterThan($cutoff)) {
            throw ValidationException::withMessages([
                'tanggal' => 'Pengajuan untuk tanggal ini sudah ditutup. Batas pengajuan adalah pukul ' .
                    substr($jamAlokasi, 0, 5) . ' pada H-1 (' . $cutoff->format('d/m/Y H:i') . ').',
            ]);
        }

        // Pastikan kursi yang dipilih belum diambil booking lain (disetujui).
        $terpakaiNow = BookingRuangan::where('ruangan_id', $ruangan->id)
            ->whereDate('tanggal', $data['tanggal'])
            ->where('status', 'disetujui')
            ->where('jam_mulai', '<', $data['jam_selesai'])
            ->where('jam_selesai', '>', $data['jam_mulai'])
            ->whereNotNull('kursi_dipilih')
            ->pluck('kursi_dipilih')
            ->flatten()
            ->unique()
            ->values()
            ->toArray();

        $konflik = array_intersect($data['kursi_dipilih'], $terpakaiNow);
        if (! empty($konflik)) {
            throw ValidationException::withMessages([
                'kursi_dipilih' => 'Kursi ' . implode(', ', $konflik) . ' sudah diambil orang lain. Silakan pilih kursi lain.',
            ]);
        }

        $urutan = BookingRuangan::whereDate('created_at', today())->count() + 1;
        $kode   = 'BKR-' . now()->format('Ymd') . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);

        sort($data['kursi_dipilih']);

        BookingRuangan::create([
            'kode_booking'  => $kode,
            'user_id'       => auth()->id(),
            'ruangan_id'    => $ruangan->id,
            'tipe'          => $data['tipe'],
            'prioritas'     => BookingRuangan::PRIORITAS[$data['tipe']],
            'mata_kuliah'   => $data['tipe'] === 'kelas' ? $data['mata_kuliah'] : null,
            'keperluan'     => $data['keperluan'],
            'tanggal'       => $data['tanggal'],
            'jam_mulai'     => $data['jam_mulai'],
            'jam_selesai'   => $data['jam_selesai'],
            'jumlah_kursi'  => count($data['kursi_dipilih']),
            'kursi_dipilih' => $data['kursi_dipilih'],
            'status'        => 'pending',
        ]);

        return redirect()->route('booking-ruangan.index')
            ->with('success', 'Pengajuan booking ruangan terkirim. Alokasi otomatis dilakukan pukul 22:00 pada H-1 sesuai prioritas & kuota kursi.');
    }

    public function show(BookingRuangan $bookingRuangan)
    {
        if ($bookingRuangan->user_id !== auth()->id()) {
            abort(403);
        }

        $bookingRuangan->load('ruangan');

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

        return view('booking-ruangan.show', compact('bookingRuangan', 'kursiLain'));
    }

    public function batal(BookingRuangan $bookingRuangan)
    {
        if ($bookingRuangan->user_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($bookingRuangan->status, ['pending', 'disetujui'])) {
            return back()->with('error', 'Booking ini tidak dapat dibatalkan.');
        }

        $bookingRuangan->update(['status' => 'dibatalkan', 'catatan' => 'Dibatalkan oleh peminjam.']);

        return back()->with('success', 'Booking dibatalkan.');
    }
}
