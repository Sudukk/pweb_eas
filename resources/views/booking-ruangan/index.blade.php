@extends('layouts.app')
@section('title', 'Booking Ruangan')
@section('content')

@php $jamAlokasi = substr(\App\Models\PengaturanBooking::jamAlokasi(), 0, 5); @endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
            Booking Ruangan
            <span tabindex="0"
                  data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true"
                  title="Alokasi otomatis pukul <strong>{{ $jamAlokasi }} H-1</strong>.<br>Prioritas: <strong>kelas &rsaquo; dosen &rsaquo; mahasiswa</strong>."
                  style="cursor:pointer;color:#6c757d">
                <i class="bi bi-info-circle-fill" style="font-size:.85rem"></i>
            </span>
        </h5>
        <p class="text-muted small mb-0">Riwayat dan status pengajuan ruangan Anda</p>
    </div>
    <a href="{{ route('booking-ruangan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i><span class="d-none d-sm-inline">Booking </span>Baru
    </a>
</div>

@if($booking->isEmpty())
<div class="text-center py-5">
    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
         style="width:72px;height:72px;background:#e8edf5">
        <i class="bi bi-building-x" style="font-size:2rem;color:#1e3a5f"></i>
    </div>
    <h6 class="fw-semibold">Belum Ada Booking</h6>
    <p class="text-muted small mb-3">Ajukan booking ruangan untuk keperluan kuliah atau kegiatan Anda.</p>
    <a href="{{ route('booking-ruangan.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Buat Booking Pertama
    </a>
</div>

@else
<div class="row g-3">
    @foreach($booking as $b)
    <div class="col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100 overflow-hidden">

            {{-- Room photo --}}
            <div style="position:relative;height:130px;overflow:hidden;background:#e8edf5;flex-shrink:0">
                @if($b->ruangan->foto_url)
                <img src="{{ $b->ruangan->foto_url }}" alt="{{ $b->ruangan->nama }}"
                     style="width:100%;height:130px;object-fit:cover">
                <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.35) 0%,transparent 60%)"></div>
                @else
                <div class="d-flex align-items-center justify-content-center h-100">
                    <i class="bi bi-building" style="font-size:2.5rem;color:#b0bec5"></i>
                </div>
                @endif

                {{-- Status badge --}}
                <div style="position:absolute;top:8px;right:10px">
                    @include('components.status-badge', ['status' => $b->status])
                </div>

                {{-- Date chip --}}
                <div style="position:absolute;bottom:8px;left:10px;
                            background:rgba(0,0,0,.52);border-radius:6px;
                            padding:2px 8px;color:#fff;font-size:.73rem;backdrop-filter:blur(4px)">
                    <i class="bi bi-calendar3 me-1"></i>{{ $b->tanggal->format('d M Y') }}
                </div>
            </div>

            <div class="card-body pb-2">
                <div class="fw-semibold mb-1" style="font-size:.93rem">{{ $b->ruangan->nama }}</div>
                @if($b->ruangan->lokasi)
                <div class="small text-muted mb-2">
                    <i class="bi bi-geo-alt me-1"></i>{{ $b->ruangan->lokasi }}
                </div>
                @endif

                <div class="d-flex flex-wrap gap-1 mb-2">
                    <span class="badge rounded-pill"
                          style="background:#e8edf5;color:#1e3a5f;border:1px solid #c5d3e8;font-weight:500">
                        <i class="bi bi-clock me-1"></i>{{ substr($b->jam_mulai,0,5) }}–{{ substr($b->jam_selesai,0,5) }}
                    </span>
                    <span class="badge rounded-pill"
                          style="background:#e8edf5;color:#1e3a5f;border:1px solid #c5d3e8;font-weight:500">
                        <i class="bi bi-person-check me-1"></i>{{ $b->jumlah_kursi }} kursi
                    </span>
                    <span class="badge rounded-pill bg-light text-dark border">{{ ucfirst($b->tipe) }}</span>
                </div>

                <p class="small text-muted mb-0"
                   style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.4">
                    {{ $b->keperluan }}
                </p>
            </div>

            <div class="card-footer bg-white border-0 pt-0 d-flex justify-content-between align-items-center">
                <code class="text-muted" style="font-size:.7rem">{{ $b->kode_booking }}</code>
                <a href="{{ route('booking-ruangan.show', $b) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye me-1"></i>Detail
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($booking->hasPages())
<div class="mt-4">{{ $booking->links() }}</div>
@endif
@endif
@endsection
