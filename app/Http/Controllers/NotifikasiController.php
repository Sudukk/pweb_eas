<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    /** Daftar notifikasi terbaru + jumlah belum dibaca (untuk dropdown bel via AJAX). */
    public function recent()
    {
        $userId = auth()->id();

        $items = Notifikasi::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit(8)
            ->get()
            ->map(fn ($n) => [
                'id'      => $n->id,
                'judul'   => $n->judul,
                'pesan'   => $n->pesan,
                'tipe'    => $n->tipe,
                'is_read' => (bool) $n->is_read,
                'waktu'   => optional($n->created_at)->diffForHumans(),
            ]);

        $unread = Notifikasi::where('user_id', $userId)->where('is_read', false)->count();

        return response()->json(['unread' => $unread, 'items' => $items]);
    }

    /** Tandai semua notifikasi user sebagai sudah dibaca. */
    public function markAllRead()
    {
        Notifikasi::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }

    /** Halaman daftar semua notifikasi. */
    public function index()
    {
        $userId = auth()->id();

        $notifikasi = Notifikasi::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate(20);

        // Buka halaman = anggap semua terbaca
        Notifikasi::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('notifikasi.index', compact('notifikasi'));
    }
}
