@extends('layouts.app')
@section('title', 'Data Peminjaman')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Data Peminjaman</h5>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-5">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari nama peminjam / kode..." value="{{ request('search') }}">
            </div>
            <div class="col-8 col-md-4">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    @foreach(['menunggu','dipinjam','dikembalikan','selesai','ditolak'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-4 col-md-auto d-flex gap-1">
                <button class="btn btn-sm btn-primary flex-grow-1">Cari</button>
                <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Peminjam</th>
                        <th>Barang Dipinjam</th>
                        <th class="d-none d-md-table-cell">Tgl Pinjam</th>
                        <th class="d-none d-lg-table-cell">Tgl Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $p)
                    <tr>
                        <td><code class="small">{{ $p->kode_pinjam }}</code></td>
                        <td class="small">{{ $p->user->name }}</td>
                        <td style="min-width:160px">@include('partials.barang-pinjam', ['detail' => $p->detail])</td>
                        <td class="small d-none d-md-table-cell">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td class="small d-none d-lg-table-cell">{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                        <td>@include('components.status-badge', ['status' => $p->status])</td>
                        <td class="text-nowrap">
                            <a href="{{ route('admin.peminjaman.show', $p) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            @if($p->status === 'dipinjam')
                            <a href="{{ route('admin.peminjaman.kembali', $p) }}" class="btn btn-sm btn-outline-success">Kembalikan</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($peminjaman->hasPages())
    <div class="card-footer bg-white">{{ $peminjaman->links() }}</div>
    @endif
</div>
@endsection
