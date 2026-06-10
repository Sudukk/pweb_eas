<x-guest-layout>
    <h5 class="fw-bold mb-3">Verifikasi Email</h5>
    <p class="text-muted small mb-4">Cek email Anda dan klik link verifikasi yang telah dikirim.</p>

    @if(session('status') == 'verification-link-sent')
        <div class="alert alert-success small">Link verifikasi baru telah dikirim ke email Anda.</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary w-100 mb-3">Kirim Ulang Email Verifikasi</button>
    </form>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-secondary w-100">Logout</button>
    </form>
</x-guest-layout>
