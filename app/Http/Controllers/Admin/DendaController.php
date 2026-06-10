<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\User;

class DendaController extends Controller
{
    public function index()
    {
        $denda = Denda::with(['user', 'peminjaman'])->latest()->paginate(15);
        return view('admin.denda.index', compact('denda'));
    }

    public function lunaskan(Denda $denda)
    {
        $denda->update(['status' => 'lunas', 'dibayar_at' => now()]);

        // Hapus blacklist jika semua denda sudah lunas
        $masihAda = Denda::where('user_id', $denda->user_id)
            ->where('status', 'belum_lunas')->exists();

        if (!$masihAda) {
            User::where('id', $denda->user_id)->update(['is_blacklisted' => false]);
        }

        return back()->with('success', 'Denda ditandai lunas.');
    }
}
