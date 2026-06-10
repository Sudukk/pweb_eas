@extends('layouts.app')
@section('title', 'Data Users')
@section('content')

<h5 class="fw-bold mb-3">Data Users (Mahasiswa & Dosen)</h5>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th class="d-none d-md-table-cell">Email</th>
                        <th class="d-none d-lg-table-cell">NIM/NIP</th>
                        <th>Role</th>
                        <th class="d-none d-xl-table-cell">Jurusan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-medium">{{ $user->name }}</div>
                            <div class="text-muted d-md-none" style="font-size:.75rem">{{ $user->email }}</div>
                        </td>
                        <td class="small d-none d-md-table-cell">{{ $user->email }}</td>
                        <td class="small d-none d-lg-table-cell">{{ $user->nim ?? $user->nip ?? '-' }}</td>
                        <td><span class="badge bg-primary">{{ $user->role }}</span></td>
                        <td class="small d-none d-xl-table-cell">{{ $user->jurusan }}</td>
                        <td>
                            @if($user->is_blacklisted)
                                <span class="badge bg-danger">Blacklist</span>
                            @else
                                <span class="badge bg-success">Aktif</span>
                            @endif
                        </td>
                        <td>
                            @if($user->is_blacklisted)
                            <form action="{{ route('admin.users.unblacklist', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-outline-warning"
                                        onclick="return confirm('Hapus blacklist user ini?')">
                                    Unblacklist
                                </button>
                            </form>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-3">Belum ada user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-white">{{ $users->links() }}</div>
    @endif
</div>
@endsection
