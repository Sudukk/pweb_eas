<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar LabPinjam</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: linear-gradient(135deg, #1e3a5f 0%, #0d6efd 100%); min-height: 100vh; }
        .auth-card { max-width: 520px; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5">
    <div class="auth-card w-100 mx-3">
        <div class="text-center mb-4">
            <i class="bi bi-box-seam-fill text-white fs-1"></i>
            <h3 class="text-white fw-bold mt-2">LabPinjam</h3>
            <p class="text-white-50">Sistem Peminjaman Alat Laboratorium</p>
        </div>
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h5 class="card-title fw-bold mb-4">Daftar Akun Baru</h5>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required autofocus>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                            <select name="role" id="roleSelect" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">Pilih Role</option>
                                <option value="mahasiswa" {{ old('role') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                <option value="dosen" {{ old('role') === 'dosen' ? 'selected' : '' }}>Dosen</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- NIM (mahasiswa) --}}
                        <div class="col-md-6" id="nimWrapper" style="display:none">
                            <label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                            <input type="text" name="nim" id="nimInput"
                                class="form-control @error('nim') is-invalid @enderror"
                                value="{{ old('nim') }}" maxlength="20">
                            @error('nim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- NIP (dosen) --}}
                        <div class="col-md-6" id="nipWrapper" style="display:none">
                            <label class="form-label fw-semibold">NIP <span class="text-danger">*</span></label>
                            <input type="text" name="nip" id="nipInput"
                                class="form-control @error('nip') is-invalid @enderror"
                                value="{{ old('nip') }}" maxlength="20">
                            @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Jurusan / Program Studi <span class="text-danger">*</span></label>
                            <input type="text" name="jurusan" class="form-control @error('jurusan') is-invalid @enderror"
                                value="{{ old('jurusan') }}" required maxlength="100">
                            @error('jurusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">No. HP <span class="text-danger">*</span></label>
                            <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                                value="{{ old('no_hp') }}" required maxlength="20">
                            @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required autocomplete="username">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                required autocomplete="new-password">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                            </button>
                        </div>
                    </div>
                </form>

                <hr class="my-3">
                <p class="text-center text-muted mb-0">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-semibold">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const roleSelect = document.getElementById('roleSelect');
        const nimWrapper = document.getElementById('nimWrapper');
        const nipWrapper = document.getElementById('nipWrapper');
        const nimInput  = document.getElementById('nimInput');
        const nipInput  = document.getElementById('nipInput');

        function toggleIdentifier() {
            const role = roleSelect.value;
            if (role === 'mahasiswa') {
                nimWrapper.style.display = '';
                nipWrapper.style.display = 'none';
                nimInput.required = true;
                nipInput.required = false;
                nipInput.value = '';
            } else if (role === 'dosen') {
                nimWrapper.style.display = 'none';
                nipWrapper.style.display = '';
                nipInput.required = true;
                nimInput.required = false;
                nimInput.value = '';
            } else {
                nimWrapper.style.display = 'none';
                nipWrapper.style.display = 'none';
                nimInput.required = false;
                nipInput.required = false;
            }
        }

        roleSelect.addEventListener('change', toggleIdentifier);

        // Restore state on validation error (old value)
        toggleIdentifier();
    </script>
</body>
</html>
