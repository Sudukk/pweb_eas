<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhere('kode_pinjam', 'like', '%' . $request->search . '%');
        }

        $peminjaman = $query->latest()->paginate(15)->withQueryString();
        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user', 'detail.alat', 'adminReviewer', 'denda']);
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman tidak bisa disetujui.');
        }

        $peminjaman->update([
            'status'           => 'dipinjam',
            'reviewed_by_admin'=> auth()->id(),
            'admin_reviewed_at'=> now(),
        ]);

        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function reject(Request $request, Peminjaman $peminjaman)
    {
        $request->validate(['catatan_penolakan' => 'required|string|max:500']);

        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman tidak bisa ditolak.');
        }

        DB::transaction(function () use ($peminjaman, $request) {
            foreach ($peminjaman->detail as $d) {
                $d->alat->increment('jumlah_tersedia', $d->jumlah);
            }
            $peminjaman->update([
                'status'           => 'ditolak',
                'catatan_penolakan'=> $request->catatan_penolakan,
                'reviewed_by_admin'=> auth()->id(),
                'admin_reviewed_at'=> now(),
            ]);
        });

        return back()->with('success', 'Peminjaman ditolak.');
    }

    public function formKembali(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return redirect()->route('admin.peminjaman.index')
                ->with('error', 'Peminjaman tidak sedang dipinjam.');
        }
        $peminjaman->load(['user', 'detail.alat']);
        return view('admin.peminjaman.kembali', compact('peminjaman'));
    }

    public function prosesKembali(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'tanggal_kembali_aktual'    => 'required|date',
            'kondisi'                   => 'required|array',
            'kondisi.*.kondisi_kembali' => 'required|in:baik,rusak,hilang',
            'kondisi.*.catatan'         => 'nullable|string|max:255',
            'denda_nominal'             => 'nullable|numeric|min:0',
            'denda_keterangan'          => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($peminjaman, $request) {
            // Update kondisi tiap alat & kembalikan stok
            foreach ($peminjaman->detail as $detail) {
                $kondisiData = $request->kondisi[$detail->id] ?? [];
                $kondisi     = $kondisiData['kondisi_kembali'] ?? 'baik';
                $catatan     = $kondisiData['catatan'] ?? null;

                $detail->update([
                    'kondisi_saat_kembali' => $kondisi,
                    'catatan_kondisi'      => $catatan,
                ]);

                // Kembalikan stok kecuali hilang
                if ($kondisi !== 'hilang') {
                    $detail->alat->increment('jumlah_tersedia', $detail->jumlah);
                } else {
                    $detail->alat->decrement('jumlah_total');
                }

                if ($kondisi === 'rusak') {
                    $detail->alat->update(['kondisi' => 'rusak_ringan']);
                }
            }

            $adaDenda = $request->filled('denda_nominal') && $request->denda_nominal > 0;

            $peminjaman->update([
                'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
                'status'                 => $adaDenda ? 'dikembalikan' : 'selesai',
            ]);

            // Buat record denda jika ada
            if ($adaDenda) {
                Denda::create([
                    'peminjaman_id' => $peminjaman->id,
                    'user_id'       => $peminjaman->user_id,
                    'jenis'         => 'kerusakan',
                    'nominal'       => $request->denda_nominal,
                    'keterangan'    => $request->denda_keterangan,
                    'status'        => 'belum_lunas',
                ]);
                $peminjaman->user->update(['is_blacklisted' => true]);
            }
        });

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Pengembalian berhasil diproses.');
    }
}
