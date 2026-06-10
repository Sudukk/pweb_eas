<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAlat       = Alat::count();
        $menunggu        = Peminjaman::where('status', 'menunggu')->count();
        $dipinjam        = Peminjaman::where('status', 'dipinjam')->count();
        $totalUser       = User::whereIn('role', ['mahasiswa', 'dosen'])->count();
        $dendaBelumLunas = Denda::where('status', 'belum_lunas')->sum('nominal');

        $peminjamanTerbaru = Peminjaman::with('user')
            ->latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalAlat', 'menunggu', 'dipinjam',
            'totalUser', 'dendaBelumLunas', 'peminjamanTerbaru'
        ));
    }
}
