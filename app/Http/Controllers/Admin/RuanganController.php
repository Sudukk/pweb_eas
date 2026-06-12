<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangan = Ruangan::withCount(['booking as booking_aktif_count' => function ($q) {
            $q->whereIn('status', ['pending', 'disetujui']);
        }])->latest()->paginate(15);

        return view('admin.ruangan.index', compact('ruangan'));
    }

    public function create()
    {
        return view('admin.ruangan.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['foto_url'] = $this->saveFoto($request);
        Ruangan::create($data);

        return redirect()->route('admin.ruangan.index')->with('success', 'Ruangan ditambahkan.');
    }

    public function edit(Ruangan $ruangan)
    {
        return view('admin.ruangan.edit', compact('ruangan'));
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $data = $this->validateData($request, $ruangan->id);

        $newFoto = $this->saveFoto($request);
        if ($newFoto) {
            // Hapus foto lama kalau memang hasil unggahan (bukan path bawaan seeder)
            if ($ruangan->foto_url && str_starts_with($ruangan->foto_url, '/images/ruangan/uploads/')) {
                $old = public_path($ruangan->foto_url);
                if (file_exists($old)) unlink($old);
            }
            $data['foto_url'] = $newFoto;
        }

        $ruangan->update($data);

        return redirect()->route('admin.ruangan.index')->with('success', 'Ruangan diperbarui.');
    }

    public function destroy(Ruangan $ruangan)
    {
        if ($ruangan->foto_url && str_starts_with($ruangan->foto_url, '/images/ruangan/uploads/')) {
            $path = public_path($ruangan->foto_url);
            if (file_exists($path)) unlink($path);
        }
        $ruangan->delete();

        return back()->with('success', 'Ruangan dihapus.');
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'kode_ruangan'    => 'required|string|max:50|unique:ruangan,kode_ruangan' . ($id ? ",{$id}" : ''),
            'nama'            => 'required|string|max:150',
            'lokasi'          => 'nullable|string|max:150',
            'kapasitas_kursi' => 'required|integer|min:1',
            'deskripsi'       => 'nullable|string|max:500',
            'foto'            => 'nullable|image|max:2048',
        ], [
            'foto.max'      => 'Ukuran foto maksimal 2 MB.',
            'foto.uploaded' => 'Gagal mengunggah foto: ukuran melebihi batas server. Maksimal 2 MB.',
            'foto.image'    => 'File harus berupa gambar (jpg, jpeg, atau png).',
        ]);

        $data['aktif'] = $request->boolean('aktif');
        unset($data['foto']);

        return $data;
    }

    private function saveFoto(Request $request): ?string
    {
        if (!$request->hasFile('foto')) return null;

        $dir  = public_path('images/ruangan/uploads');
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $file = $request->file('foto');
        $name = uniqid('ruangan_') . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);

        return '/images/ruangan/uploads/' . $name;
    }
}
