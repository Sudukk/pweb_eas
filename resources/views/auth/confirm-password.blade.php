<x-guest-layout>
    <h5 class="fw-bold mb-3">Konfirmasi Password</h5>
    <p class="text-muted small mb-4">Area aman. Konfirmasi password Anda sebelum melanjutkan.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">Konfirmasi</button>
    </form>
</x-guest-layout>
