@extends('layouts.app')
@section('title', 'Booking Ruangan')
@section('content')

<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h5 class="fw-bold mb-0">Booking Ruangan</h5>
        <p class="text-muted small mb-0">Manajemen pengajuan booking kursi ruangan</p>
    </div>
</div>

{{-- Alokasi otomatis card --}}
<div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #1e3a5f !important">
    <div class="card-body py-3">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-clock-history text-primary"></i>
            <span class="fw-semibold">Alokasi Otomatis</span>
            @if($pengaturan?->last_ran_date)
            <span class="badge bg-success ms-1">
                <i class="bi bi-check-circle me-1"></i>Berjalan {{ $pengaturan->last_ran_date->format('d/m/Y') }}
            </span>
            @else
            <span class="badge bg-secondary ms-1">Belum pernah berjalan</span>
            @endif
        </div>
        <form action="{{ route('admin.booking-ruangan.pengaturan') }}" method="POST"
              class="d-flex align-items-center gap-2 flex-wrap">
            @csrf
            <label class="small text-muted mb-0">Jam alokasi:</label>
            <input type="time" name="jam_alokasi" class="form-control form-control-sm"
                   style="width:120px"
                   value="{{ substr($pengaturan->jam_alokasi ?? '22:00:00', 0, 5) }}" required>
            <button class="btn btn-sm btn-primary px-3">Simpan</button>
            <span tabindex="0"
                  data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true"
                  title="Proses semua pending setiap hari pada jam ini. Prioritas: <strong>kelas &rsaquo; dosen &rsaquo; mahasiswa</strong>.<br>Jalankan: <code>php artisan schedule:work</code>"
                  style="cursor:pointer;color:#6c757d">
                <i class="bi bi-info-circle-fill" style="font-size:1rem"></i>
            </span>
        </form>
    </div>
</div>

{{-- Status quick-filter tabs --}}
@php
$statusTabs = [
    ''           => 'Semua',
    'pending'    => 'Pending',
    'disetujui'  => 'Disetujui',
    'ditolak'    => 'Ditolak',
    'dibatalkan' => 'Dibatalkan',
];
$activeStatus = request('status', '');
@endphp
<div class="d-flex gap-1 flex-wrap mb-3">
    @foreach($statusTabs as $val => $label)
    <a href="{{ request()->fullUrlWithQuery(['status' => $val, 'page' => null]) }}"
       class="btn btn-sm {{ $activeStatus === $val ? 'btn-primary' : 'btn-outline-secondary' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- Search + date filter --}}
<form method="GET" class="row g-2 mb-3">
    @if($activeStatus)
    <input type="hidden" name="status" value="{{ $activeStatus }}">
    @endif
    <div class="col-sm-5 col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control border-start-0" placeholder="Cari kode / nama pemohon">
        </div>
    </div>
    <div class="col-sm-4 col-md-3">
        <input type="date" name="tanggal" value="{{ request('tanggal') }}"
               class="form-control form-control-sm">
    </div>
    <div class="col-auto">
        <button class="btn btn-sm btn-secondary">Filter</button>
        <a href="{{ route('admin.booking-ruangan.index') }}" class="btn btn-sm btn-light">Reset</a>
    </div>
</form>

{{-- Table (desktop) --}}
<div class="card border-0 shadow-sm d-none d-md-block">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem">
            <thead style="background:#f0f4fa">
                <tr>
                    <th class="ps-4" style="width:160px">Kode</th>
                    <th>Pemohon</th>
                    <th>Ruangan</th>
                    <th>Tanggal</th>
                    <th>Slot</th>
                    <th class="text-center" style="width:70px">Kursi</th>
                    <th class="text-center" style="width:55px">Prio</th>
                    <th style="width:110px">Status</th>
                    <th style="width:80px"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($booking as $b)
                <tr>
                    <td class="ps-4">
                        <code class="small" style="color:#1e3a5f">{{ $b->kode_booking }}</code>
                    </td>
                    <td>
                        <div class="fw-semibold" style="font-size:.85rem">{{ $b->user->name }}</div>
                        <div class="text-muted" style="font-size:.78rem">{{ ucfirst($b->tipe) }}</div>
                    </td>
                    <td>
                        <div style="font-size:.85rem">{{ $b->ruangan->nama }}</div>
                        @if($b->ruangan->lokasi)
                        <div class="text-muted" style="font-size:.78rem">{{ $b->ruangan->lokasi }}</div>
                        @endif
                    </td>
                    <td class="small">{{ $b->tanggal->format('d/m/Y') }}</td>
                    <td class="small text-nowrap">
                        {{ substr($b->jam_mulai,0,5) }}–{{ substr($b->jam_selesai,0,5) }}
                    </td>
                    <td class="text-center">{{ $b->jumlah_kursi }}</td>
                    <td class="text-center">
                        <span class="badge rounded-pill bg-light text-dark border">{{ $b->prioritas }}</span>
                    </td>
                    <td>@include('components.status-badge', ['status' => $b->status])</td>
                    <td>
                        <a href="{{ route('admin.booking-ruangan.show', $b) }}"
                           class="btn btn-sm btn-outline-primary">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="bi bi-inbox d-block mb-1" style="font-size:1.5rem"></i>
                        Tidak ada data booking.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($booking->hasPages())
    <div class="card-footer bg-white">{{ $booking->links() }}</div>
    @endif
</div>

{{-- Card list (mobile) --}}
<div class="d-md-none">
    @forelse($booking as $b)
    <div class="card border-0 shadow-sm mb-2">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <code class="small" style="color:#1e3a5f">{{ $b->kode_booking }}</code>
                    <div class="fw-semibold" style="font-size:.9rem">{{ $b->user->name }}</div>
                    <div class="small text-muted">{{ ucfirst($b->tipe) }} · Prio {{ $b->prioritas }}</div>
                </div>
                @include('components.status-badge', ['status' => $b->status])
            </div>
            <div class="small text-muted d-flex flex-wrap gap-2 mb-2">
                <span><i class="bi bi-building me-1"></i>{{ $b->ruangan->nama }}</span>
                <span><i class="bi bi-calendar3 me-1"></i>{{ $b->tanggal->format('d/m/Y') }}</span>
                <span><i class="bi bi-clock me-1"></i>{{ substr($b->jam_mulai,0,5) }}–{{ substr($b->jam_selesai,0,5) }}</span>
                <span><i class="bi bi-person me-1"></i>{{ $b->jumlah_kursi }} kursi</span>
            </div>
            <a href="{{ route('admin.booking-ruangan.show', $b) }}"
               class="btn btn-sm btn-outline-primary w-100">
                <i class="bi bi-eye me-1"></i>Lihat Detail
            </a>
        </div>
    </div>
    @empty
    <div class="text-center text-muted py-4">
        <i class="bi bi-inbox d-block mb-1" style="font-size:1.5rem"></i>
        Tidak ada data booking.
    </div>
    @endforelse

    @if($booking->hasPages())
    <div class="mt-2">{{ $booking->links() }}</div>
    @endif
</div>
@endsection
