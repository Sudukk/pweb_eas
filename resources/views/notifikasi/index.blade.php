@extends('layouts.app')
@section('title', 'Notifikasi')
@section('content')

@php
$ikon = [
    'peminjaman'   => ['bi-journal-text',       'primary'],
    'approval'     => ['bi-check-circle',        'success'],
    'pengembalian' => ['bi-box-arrow-in-left',   'info'],
    'denda'        => ['bi-cash-coin',           'danger'],
];
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Notifikasi</h5>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @forelse($notifikasi as $n)
                @php [$ic, $col] = $ikon[$n->tipe] ?? ['bi-bell', 'secondary']; @endphp
                <div class="list-group-item d-flex gap-3 px-4 py-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:42px;height:42px;background:var(--bs-{{ $col }}-bg-subtle, #e9ecef)">
                        <i class="bi {{ $ic }} text-{{ $col }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $n->judul }}</div>
                        <div class="text-muted small">{{ $n->pesan }}</div>
                        <div class="text-muted" style="font-size:.72rem">
                            {{ optional($n->created_at)->translatedFormat('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-bell-slash d-block mb-2" style="font-size:2rem"></i>
                    Belum ada notifikasi.
                </div>
            @endforelse
        </div>
    </div>
    @if($notifikasi->hasPages())
    <div class="card-footer bg-white">{{ $notifikasi->links() }}</div>
    @endif
</div>
@endsection
