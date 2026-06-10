<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $aktif  = Peminjaman::where('user_id', $userId)
            ->whereIn('status', ['menunggu', 'dipinjam'])->count();
        $selesai = Peminjaman::where('user_id', $userId)
            ->where('status', 'selesai')->count();
        $denda  = Denda::where('user_id', $userId)
            ->where('status', 'belum_lunas')->sum('nominal');

        $riwayat = Peminjaman::where('user_id', $userId)
            ->latest()->limit(5)->get();

        return view('dashboard.mahasiswa', compact('aktif', 'selesai', 'denda', 'riwayat'));
    }
}
