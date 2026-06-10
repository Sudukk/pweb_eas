<?php

namespace App\Http\Controllers;

use App\Models\Denda;

class DendaController extends Controller
{
    public function index()
    {
        $denda = Denda::with('peminjaman')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        $totalBelumLunas = Denda::where('user_id', auth()->id())
            ->where('status', 'belum_lunas')
            ->sum('nominal');

        return view('denda.index', compact('denda', 'totalBelumLunas'));
    }
}
