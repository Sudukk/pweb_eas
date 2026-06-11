{{-- Daftar barang yang dipinjam + jumlahnya. Param: $detail (koleksi PeminjamanDetail) --}}
<div class="d-flex flex-wrap gap-1">
    @forelse($detail as $d)
        <span class="badge bg-light text-dark border fw-normal">
            {{ $d->alat->nama ?? 'Alat dihapus' }}
            <span class="text-primary fw-semibold">&times;{{ $d->jumlah }}</span>
        </span>
    @empty
        <span class="text-muted small">-</span>
    @endforelse
</div>
