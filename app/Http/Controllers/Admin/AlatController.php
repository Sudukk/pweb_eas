<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlatController extends Controller
{
    public function index()
    {
        $alat = Alat::paginate(15);
        return view('admin.alat.index', compact('alat'));
    }

    public function create()
    {
        return view('admin.alat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_alat'    => 'required|string|max:50|unique:alat,kode_alat',
            'nama'         => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'jumlah_total' => 'required|integer|min:1',
            'kondisi'      => 'required|in:baik,rusak_ringan,maintenance',
        ], $this->fotoMessages());

        $data = $request->only('kode_alat', 'nama', 'deskripsi', 'jumlah_total', 'kondisi');
        $data['jumlah_tersedia'] = $data['jumlah_total'];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('alat', 'public');
        }

        Alat::create($data);

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil ditambahkan.');
    }

    public function show(Alat $alat)
    {
        $alat->load('peminjamanDetail.peminjaman.user');
        return view('admin.alat.show', compact('alat'));
    }

    public function edit(Alat $alat)
    {
        return view('admin.alat.edit', compact('alat'));
    }

    public function update(Request $request, Alat $alat)
    {
        $request->validate([
            'kode_alat'       => 'required|string|max:50|unique:alat,kode_alat,' . $alat->id,
            'nama'            => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'foto'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'jumlah_total'    => 'required|integer|min:1',
            'jumlah_tersedia' => 'required|integer|min:0',
            'kondisi'         => 'required|in:baik,rusak_ringan,maintenance',
        ], $this->fotoMessages());

        $data = $request->only('kode_alat', 'nama', 'deskripsi', 'jumlah_total', 'jumlah_tersedia', 'kondisi');

        if ($request->hasFile('foto')) {
            if ($alat->foto) Storage::disk('public')->delete($alat->foto);
            $data['foto'] = $request->file('foto')->store('alat', 'public');
        }

        $alat->update($data);

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil diperbarui.');
    }

    public function destroy(Alat $alat)
    {
        if ($alat->foto) Storage::disk('public')->delete($alat->foto);
        $alat->delete();
        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil dihapus.');
    }

    /**
     * Pesan validasi khusus untuk unggahan foto (termasuk kasus ukuran melebihi batas).
     */
    private function fotoMessages(): array
    {
        return [
            'foto.max'      => 'Ukuran foto maksimal 2 MB.',
            'foto.uploaded' => 'Gagal mengunggah foto: ukuran melebihi batas server. Maksimal 2 MB.',
            'foto.image'    => 'File harus berupa gambar (jpg, jpeg, atau png).',
            'foto.mimes'    => 'Format foto harus jpg, jpeg, atau png.',
        ];
    }
}
