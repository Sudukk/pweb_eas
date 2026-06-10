@extends('layouts.app')
@section('title', 'Ruangan')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Manajemen Ruangan</h5>
    <a href="{{ route('admin.ruangan.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Tambah
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="d-none d-md-table-cell" style="width:60px"></th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th class="d-none d-md-table-cell">Lokasi</th>
                        <th class="text-center">Kursi</th>
                        <th class="text-center d-none d-sm-table-cell">Booking Aktif</th>
                        <th class="text-center">Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ruangan as $r)
                    <tr>
                        <td class="d-none d-md-table-cell py-1">
                            @if($r->foto_url)
                                <img src="{{ $r->foto_url }}" alt="" class="rounded" style="width:52px;height:36px;object-fit:cover">
                            @endif
                        </td>
                        <td><code class="small">{{ $r->kode_ruangan }}</code></td>
                        <td class="small">{{ $r->nama }}</td>
                        <td class="small d-none d-md-table-cell">{{ $r->lokasi ?: '-' }}</td>
                        <td class="text-center">{{ $r->kapasitas_kursi }}</td>
                        <td class="text-center d-none d-sm-table-cell">{{ $r->booking_aktif_count }}</td>
                        <td class="text-center">
                            @if($r->aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            <a href="{{ route('admin.ruangan.edit', $r) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.ruangan.destroy', $r) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus ruangan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-3">Belum ada ruangan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($ruangan->hasPages())
    <div class="card-footer bg-white">{{ $ruangan->links() }}</div>
    @endif
</div>
@endsection
