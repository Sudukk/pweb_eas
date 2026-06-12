@extends('layouts.app')
@section('title', 'Detail Booking')
@section('content')

@php
$b = $bookingRuangan;
$statusCls = match($b->status) {
    'disetujui'  => 'success',
    'pending'    => 'secondary',
    'ditolak'    => 'danger',
    'dibatalkan' => 'warning',
    default      => 'secondary',
};
$statusIcon = match($b->status) {
    'disetujui'  => 'check-circle-fill',
    'pending'    => 'hourglass-split',
    'ditolak'    => 'x-circle-fill',
    'dibatalkan' => 'slash-circle',
    default      => 'circle',
};
@endphp

<a href="{{ route('booking-ruangan.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
    <i class="bi bi-arrow-left me-1"></i>Kembali
</a>

{{-- Banner atas --}}
<div class="rounded-3 overflow-hidden mb-4 shadow-sm position-relative"
     style="height:200px;background:#1e3a5f">
    @if($b->ruangan->foto_url)
    <img src="{{ $b->ruangan->foto_url }}" alt="{{ $b->ruangan->nama }}"
         style="width:100%;height:200px;object-fit:cover;opacity:.6">
    @endif
    <div style="position:absolute;inset:0;background:linear-gradient(to right,rgba(30,58,95,.85) 0%,rgba(30,58,95,.4) 100%);
                display:flex;flex-direction:column;justify-content:flex-end;padding:20px 24px">
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-{{ $statusCls }}">
                <i class="bi bi-{{ $statusIcon }} me-1"></i>{{ ucfirst($b->status) }}
            </span>
            <code style="color:rgba(255,255,255,.7);font-size:.78rem">{{ $b->kode_booking }}</code>
        </div>
        <h5 class="text-white fw-bold mb-0">{{ $b->ruangan->nama }}</h5>
        @if($b->ruangan->lokasi)
        <div style="color:rgba(255,255,255,.7);font-size:.85rem">
            <i class="bi bi-geo-alt me-1"></i>{{ $b->ruangan->lokasi }}
        </div>
        @endif
    </div>
</div>

<div class="row g-3">

    {{-- ── Denah kursi (lebar penuh di HP, kolom kanan di desktop) ──────── --}}
    <div class="col-12 col-lg-7 order-lg-2">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-grid-3x3-gap text-primary"></i>
                    <h6 class="fw-bold mb-0">Denah Kursi</h6>
                </div>
                <div class="text-end small text-muted">
                    {{ $b->tanggal->format('d M Y') }}<br>
                    {{ substr($b->jam_mulai,0,5) }}–{{ substr($b->jam_selesai,0,5) }}
                </div>
            </div>
            <div class="card-body">
                @include('components.seat-map', [
                    'kapasitas' => $b->ruangan->kapasitas_kursi,
                    'dipilih'   => $b->kursi_dipilih ?? [],
                    'terpakai'  => $kursiLain,
                ])
            </div>
        </div>
    </div>

    {{-- ── Info booking (kolom kiri di desktop) ─────────────────────────── --}}
    <div class="col-12 col-lg-5 order-lg-1">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-info-circle text-primary"></i>
                <h6 class="fw-bold mb-0">Informasi Booking</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-4 py-3 d-flex align-items-start gap-3">
                        <i class="bi bi-building text-primary mt-1 flex-shrink-0"></i>
                        <div>
                            <div class="small text-muted">Ruangan</div>
                            <div class="fw-semibold">{{ $b->ruangan->nama }}
                                <span class="text-muted fw-normal">({{ $b->ruangan->kapasitas_kursi }} kursi)</span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item px-4 py-3 d-flex align-items-start gap-3">
                        <i class="bi bi-person-badge text-primary mt-1 flex-shrink-0"></i>
                        <div>
                            <div class="small text-muted">Tipe Booking</div>
                            <div class="fw-semibold">{{ ucfirst($b->tipe) }}
                                <span class="badge bg-light text-dark border ms-1">Prioritas {{ $b->prioritas }}</span>
                            </div>
                        </div>
                    </li>
                    @if($b->mata_kuliah)
                    <li class="list-group-item px-4 py-3 d-flex align-items-start gap-3">
                        <i class="bi bi-book text-primary mt-1 flex-shrink-0"></i>
                        <div>
                            <div class="small text-muted">Mata Kuliah / Kelas</div>
                            <div class="fw-semibold">{{ $b->mata_kuliah }}</div>
                        </div>
                    </li>
                    @endif
                    <li class="list-group-item px-4 py-3 d-flex align-items-start gap-3">
                        <i class="bi bi-calendar3 text-primary mt-1 flex-shrink-0"></i>
                        <div>
                            <div class="small text-muted">Tanggal</div>
                            <div class="fw-semibold">{{ $b->tanggal->format('l, d F Y') }}</div>
                        </div>
                    </li>
                    <li class="list-group-item px-4 py-3 d-flex align-items-start gap-3">
                        <i class="bi bi-clock text-primary mt-1 flex-shrink-0"></i>
                        <div>
                            <div class="small text-muted">Slot Waktu</div>
                            <div class="fw-semibold">
                                {{ substr($b->jam_mulai,0,5) }} – {{ substr($b->jam_selesai,0,5) }}
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item px-4 py-3 d-flex align-items-start gap-3">
                        <i class="bi bi-grid-3x3 text-primary mt-1 flex-shrink-0"></i>
                        <div>
                            <div class="small text-muted">Kursi Dipilih</div>
                            <div class="fw-semibold mb-1">{{ $b->jumlah_kursi }} kursi</div>
                            @if($b->kursi_dipilih)
                            @php
                                $labels = collect($b->kursi_dipilih)->sort()->map(function($no) {
                                    $r = (int)(($no-1)/8); $c = (($no-1)%8)+1;
                                    return chr(65+$r).$c;
                                });
                            @endphp
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($labels as $lbl)
                                <span class="badge" style="background:#1e3a5f">{{ $lbl }}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </li>
                    <li class="list-group-item px-4 py-3 d-flex align-items-start gap-3">
                        <i class="bi bi-card-text text-primary mt-1 flex-shrink-0"></i>
                        <div>
                            <div class="small text-muted">Keperluan</div>
                            <div>{{ $b->keperluan }}</div>
                        </div>
                    </li>
                    @if($b->catatan)
                    <li class="list-group-item px-4 py-3 d-flex align-items-start gap-3">
                        <i class="bi bi-chat-left-text text-primary mt-1 flex-shrink-0"></i>
                        <div>
                            <div class="small text-muted">Catatan Admin</div>
                            <div>{{ $b->catatan }}</div>
                        </div>
                    </li>
                    @endif
                    @if($b->diproses_at)
                    <li class="list-group-item px-4 py-3 d-flex align-items-start gap-3">
                        <i class="bi bi-check2-all text-primary mt-1 flex-shrink-0"></i>
                        <div>
                            <div class="small text-muted">Diproses</div>
                            <div>{{ $b->diproses_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
            @if(in_array($b->status, ['pending', 'disetujui']))
            <div class="card-footer bg-white d-flex justify-content-end align-items-center">
                <form action="{{ route('booking-ruangan.batal', $b) }}" method="POST"
                      onsubmit="return confirm('Batalkan booking ini?')">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-x-circle me-1"></i>Batalkan
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
