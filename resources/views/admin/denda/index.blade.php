@extends('layouts.app')
@section('title', 'Data Denda')
@section('content')

<h5 class="fw-bold mb-3">Data Denda</h5>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Peminjam</th>
                        <th class="d-none d-md-table-cell">Kode Pinjam</th>
                        <th class="d-none d-lg-table-cell">Keterangan</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($denda as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="small">{{ $d->user->name }}</td>
                        <td class="d-none d-md-table-cell"><code class="small">{{ $d->peminjaman->kode_pinjam }}</code></td>
                        <td class="small d-none d-lg-table-cell">{{ $d->keterangan ?? $d->jenis }}</td>
                        <td class="text-nowrap small">Rp {{ number_format($d->nominal, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $d->status == 'lunas' ? 'bg-success':'bg-danger' }}">
                                {{ $d->status == 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                            </span>
                        </td>
                        <td>
                            @if($d->status === 'belum_lunas')
                            <form action="{{ route('admin.denda.lunas', $d) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Tandai denda ini lunas?')">
                                @csrf
                                <button class="btn btn-sm btn-success">Lunaskan</button>
                            </form>
                            @else
                            <span class="text-muted small">{{ $d->dibayar_at?->format('d/m/Y') }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">Belum ada denda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($denda->hasPages())
    <div class="card-footer bg-white">{{ $denda->links() }}</div>
    @endif
</div>
@endsection
