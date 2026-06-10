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
@endphp

{{-- Hero --}}
<div class="rounded-3 overflow-hidden mb-4 shadow-sm position-relative"
     style="height:190px;background:#1e3a5f">
    @if($b->ruangan->foto_url)
    <img src="{{ $b->ruangan->foto_url }}" alt="{{ $b->ruangan->nama }}"
         style="width:100%;height:190px;object-fit:cover;opacity:.55">
    @endif
    <div style="position:absolute;inset:0;
                background:linear-gradient(to right,rgba(30,58,95,.9) 0%,rgba(30,58,95,.45) 100%);
                display:flex;flex-direction:column;justify-content:flex-end;padding:20px 24px">
        <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
            <span class="badge bg-{{ $statusCls }} fs-6 px-3 py-2">{{ ucfirst($b->status) }}</span>
            <code style="color:rgba(255,255,255,.7)">{{ $b->kode_booking }}</code>
        </div>
        <h5 class="text-white fw-bold mb-0">{{ $b->ruangan->nama }}</h5>
        <div style="color:rgba(255,255,255,.75);font-size:.85rem">
            @if($b->ruangan->lokasi)<i class="bi bi-geo-alt me-1"></i>{{ $b->ruangan->lokasi }} &nbsp;·&nbsp; @endif
            <i class="bi bi-grid-3x3 me-1"></i>{{ $b->ruangan->kapasitas_kursi }} kursi kapasitas
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- ── Kolom kiri: detail ─────────────────────────────────────────────── --}}
    <div class="{{ $b->kursi_dipilih ? 'col-lg-5' : 'col-lg-7 mx-auto' }}">

        {{-- Pemohon card --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:46px;height:46px;background:#e8edf5">
                    <i class="bi bi-person-fill" style="color:#1e3a5f;font-size:1.2rem"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-semibold">{{ $b->user->name }}</div>
                    <div class="small text-muted">{{ $b->user->email }}</div>
                </div>
                <div class="text-end">
                    <span class="badge bg-light text-dark border">{{ ucfirst($b->tipe) }}</span>
                    <div class="small text-muted mt-1">Prioritas {{ $b->prioritas }}</div>
                </div>
            </div>
        </div>

        {{-- Info card --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-info-circle text-primary"></i>
                <h6 class="fw-bold mb-0">Detail Booking</h6>
            </div>
            <ul class="list-group list-group-flush">
                @if($b->mata_kuliah)
                <li class="list-group-item px-4 py-3 d-flex align-items-start gap-3">
                    <i class="bi bi-book text-primary mt-1 flex-shrink-0"></i>
                    <div><div class="small text-muted">Mata Kuliah / Kelas</div>
                    <div class="fw-semibold">{{ $b->mata_kuliah }}</div></div>
                </li>
                @endif
                <li class="list-group-item px-4 py-3 d-flex align-items-start gap-3">
                    <i class="bi bi-calendar3 text-primary mt-1 flex-shrink-0"></i>
                    <div><div class="small text-muted">Tanggal</div>
                    <div class="fw-semibold">{{ $b->tanggal->format('d/m/Y') }}</div></div>
                </li>
                <li class="list-group-item px-4 py-3 d-flex align-items-start gap-3">
                    <i class="bi bi-clock text-primary mt-1 flex-shrink-0"></i>
                    <div><div class="small text-muted">Slot Waktu</div>
                    <div class="fw-semibold">{{ substr($b->jam_mulai,0,5) }} – {{ substr($b->jam_selesai,0,5) }}</div></div>
                </li>
                <li class="list-group-item px-4 py-3 d-flex align-items-start gap-3">
                    <i class="bi bi-grid-3x3 text-primary mt-1 flex-shrink-0"></i>
                    <div>
                        <div class="small text-muted">Kursi Diminta</div>
                        <div class="fw-semibold">{{ $b->jumlah_kursi }} kursi</div>
                        @if($b->kursi_dipilih)
                        <div class="mt-1 d-flex flex-wrap gap-1">
                            @php
                                $labels = collect($b->kursi_dipilih)->sort()->map(function($no) {
                                    $r = (int)(($no-1)/8); $c = (($no-1)%8)+1;
                                    return chr(65+$r).$c;
                                });
                            @endphp
                            @foreach($labels as $lbl)
                            <span class="badge" style="background:#1e3a5f">{{ $lbl }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </li>
                <li class="list-group-item px-4 py-3 d-flex align-items-start gap-3">
                    <i class="bi bi-card-text text-primary mt-1 flex-shrink-0"></i>
                    <div><div class="small text-muted">Keperluan</div>
                    <div>{{ $b->keperluan }}</div></div>
                </li>
                @if($b->catatan)
                <li class="list-group-item px-4 py-3 d-flex align-items-start gap-3">
                    <i class="bi bi-chat-left-text text-primary mt-1 flex-shrink-0"></i>
                    <div><div class="small text-muted">Catatan</div>
                    <div>{{ $b->catatan }}</div></div>
                </li>
                @endif
                @if($b->diproses_at)
                <li class="list-group-item px-4 py-3 d-flex align-items-start gap-3">
                    <i class="bi bi-check2-all text-primary mt-1 flex-shrink-0"></i>
                    <div><div class="small text-muted">Diproses</div>
                    <div>{{ $b->diproses_at->format('d/m/Y H:i') }}</div></div>
                </li>
                @endif
            </ul>

            {{-- Actions --}}
            <div class="card-footer bg-white d-flex justify-content-between align-items-center gap-2 flex-wrap">
                <a href="{{ route('admin.booking-ruangan.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>

                @if(in_array($b->status, ['pending', 'disetujui']))
                <div class="d-flex gap-2">
                    @if($b->status === 'pending')
                    <form action="{{ route('admin.booking-ruangan.approve', $b) }}" method="POST">
                        @csrf
                        <button class="btn btn-success btn-sm px-3">
                            <i class="bi bi-check-circle me-1"></i>Setujui
                        </button>
                    </form>
                    @endif
                    <button class="btn btn-danger btn-sm px-3"
                            data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="bi bi-x-circle me-1"></i>Tolak
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Kolom kanan: denah kursi ────────────────────────────────────────── --}}
    @if($b->kursi_dipilih)
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-grid-3x3-gap text-primary"></i>
                    <h6 class="fw-bold mb-0">Denah Kursi</h6>
                </div>
                <span class="small text-muted">
                    {{ $b->tanggal->format('d/m/Y') }}
                    {{ substr($b->jam_mulai,0,5) }}–{{ substr($b->jam_selesai,0,5) }}
                </span>
            </div>
            <div class="card-body">
                @include('components.seat-map', [
                    'kapasitas' => $b->ruangan->kapasitas_kursi,
                    'dipilih'   => $b->kursi_dipilih,
                    'terpakai'  => $kursiLain,
                ])
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Modal tolak --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.booking-ruangan.reject', $b) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-x-circle-fill text-danger me-2"></i>Tolak Booking
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <p class="small text-muted mb-2">
                    Booking <code>{{ $b->kode_booking }}</code> oleh <strong>{{ $b->user->name }}</strong>
                    akan ditolak. Tulis alasan penolakan:
                </p>
                <textarea name="catatan" class="form-control" rows="3"
                          maxlength="500" required
                          placeholder="mis. Slot waktu sudah terisi, ruangan tidak tersedia..."></textarea>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-danger px-4">Tolak Booking</button>
            </div>
        </form>
    </div>
</div>
@endsection
