@extends('layouts.app')
@section('title', 'Data Alat')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Data Alat</h5>
    <a href="{{ route('admin.alat.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i><span class="d-none d-sm-inline">Tambah </span>Alat
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="48"></th>
                        <th class="d-none d-md-table-cell">Kode</th>
                        <th>Nama Alat</th>
                        <th>Stok</th>
                        <th class="d-none d-sm-table-cell">Kondisi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alat as $item)
                    <tr>
                        <td class="ps-3">
                            @if($item->foto)
                                <a href="{{ route('admin.alat.show', $item) }}">
                                    <img src="{{ Storage::url($item->foto) }}"
                                         style="width:48px;height:48px;object-fit:cover"
                                         class="rounded shadow-sm">
                                </a>
                            @else
                                <a href="{{ route('admin.alat.show', $item) }}"
                                   class="d-flex align-items-center justify-content-center rounded bg-light"
                                   style="width:48px;height:48px;text-decoration:none">
                                    <i class="bi bi-tools text-muted"></i>
                                </a>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell"><code>{{ $item->kode_alat }}</code></td>
                        <td>
                            <a href="{{ route('admin.alat.show', $item) }}" class="text-decoration-none text-dark fw-medium">
                                {{ $item->nama }}
                            </a>
                            <div class="text-muted d-md-none" style="font-size:.75rem">{{ $item->kode_alat }}</div>
                        </td>
                        <td class="text-nowrap">
                            <span class="{{ $item->jumlah_tersedia > 0 ? 'text-success' : 'text-danger' }} fw-semibold">
                                {{ $item->jumlah_tersedia }}
                            </span>
                            <span class="text-muted">/{{ $item->jumlah_total }}</span>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            @php $k = ['baik'=>'success','rusak_ringan'=>'warning','maintenance'=>'danger']; @endphp
                            <span class="badge bg-{{ $k[$item->kondisi] ?? 'secondary' }}">{{ $item->kondisi }}</span>
                        </td>
                        <td class="text-nowrap">
                            <a href="{{ route('admin.alat.show', $item) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.alat.edit', $item) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.alat.destroy', $item) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus alat ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data alat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($alat->hasPages())
    <div class="card-footer bg-white">{{ $alat->links() }}</div>
    @endif
</div>
@endsection
