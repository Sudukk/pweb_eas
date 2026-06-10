<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with('detail.alat')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        if (auth()->user()->is_blacklisted) {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Akun Anda diblacklist karena denda belum lunas.');
        }

        $alat = Alat::where('jumlah_tersedia', '>', 0)
            ->where('kondisi', '!=', 'maintenance')
            ->get();

        return view('peminjaman.create', compact('alat'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->is_blacklisted) {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Akun Anda diblacklist karena denda belum lunas.');
        }

        $request->validate([
            'alat'                    => 'required|array|min:1',
            'alat.*'                  => 'exists:alat,id',
            'jumlah.*'                => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'keperluan'               => 'required|string|max:500',
            'dokumen_pendukung'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            // Generate kode pinjam
            $urutan    = Peminjaman::whereDate('created_at', today())->count() + 1;
            $kodePinjam = 'PJM-' . now()->format('Ymd') . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);

            $dokumen = null;
            if ($request->hasFile('dokumen_pendukung')) {
                $dokumen = $request->file('dokumen_pendukung')->store('dokumen', 'public');
            }

            $peminjaman = Peminjaman::create([
                'kode_pinjam'             => $kodePinjam,
                'user_id'                 => auth()->id(),
                'tanggal_pinjam'          => $request->tanggal_pinjam,
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'keperluan'               => $request->keperluan,
                'dokumen_pendukung'       => $dokumen,
                'status'                  => 'menunggu',
            ]);

            foreach ($request->alat as $alatId) {
                $jumlah = $request->jumlah[$alatId] ?? 1;
                $alat   = Alat::findOrFail($alatId);

                if ($alat->jumlah_tersedia < $jumlah) {
                    throw new \Exception('Stok ' . $alat->nama . ' tidak mencukupi.');
                }

                PeminjamanDetail::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id'       => $alatId,
                    'jumlah'        => $jumlah,
                ]);

                $alat->decrement('jumlah_tersedia', $jumlah);
            }
        });

        return redirect()->route('peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan admin.');
    }

    public function show(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        $peminjaman->load(['detail.alat', 'adminReviewer', 'denda']);
        return view('peminjaman.show', compact('peminjaman'));
    }
}
