<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk PinjamIn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: linear-gradient(135deg, #1e3a5f 0%, #0d6efd 100%); min-height: 100vh; }
        .auth-card { max-width: 420px; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
    <div class="auth-card w-100 mx-3">
        <div class="text-center mb-4">
            <i class="bi bi-box-seam-fill text-white fs-1"></i>
            <h3 class="text-white fw-bold mt-2">PinjamIn</h3>
            <p class="text-white-50">Sistem Peminjaman Alat Laboratorium</p>
        </div>
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h5 class="card-title fw-bold mb-4">Masuk ke Akun</h5>

                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required autofocus autocomplete="username"
                            placeholder="email@contoh.com">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            required autocomplete="current-password">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                    </button>
                </form>

                <hr class="my-3">
                <p class="text-center text-muted mb-0">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-primary fw-semibold">Daftar di sini</a>
                </p>
                <!-- <div class="mt-3 p-3 bg-light rounded small text-muted">
                    <strong>Akun Demo:</strong><br>
                    Admin: admin@lab.ac.id / password<br>
                    Dosen: budi@lab.ac.id / password<br>
                    Mahasiswa: andi@student.ac.id / password
                </div> -->
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
