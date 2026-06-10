@php
$map = [
    'menunggu'     => ['bg-secondary', 'Menunggu'],
    'dipinjam'     => ['bg-success',   'Dipinjam'],
    'dikembalikan' => ['bg-warning text-dark', 'Dikembalikan'],
    'selesai'      => ['bg-dark',      'Selesai'],
    'ditolak'      => ['bg-danger',    'Ditolak'],
];
[$cls, $label] = $map[$status] ?? ['bg-secondary', ucfirst($status)];
@endphp
<span class="badge {{ $cls }}">{{ $label }}</span>
