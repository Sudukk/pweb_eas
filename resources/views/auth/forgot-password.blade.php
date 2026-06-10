<x-guest-layout>
    <h5 class="fw-bold mb-3">Lupa Password</h5>
    <p class="text-muted small mb-4">Masukkan email Anda untuk mendapat link reset password.</p>

    @if(session('status'))
        <div class="alert alert-success small">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger small">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
        </div>
        <button type="submit" class="btn btn-primary w-100">Kirim Link Reset</button>
    </form>
    <div class="text-center mt-3">
        <a href="{{ route('login') }}" class="small text-muted">← Kembali ke Login</a>
    </div>
</x-guest-layout>
