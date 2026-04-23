@extends('layouts.pelatih')

@section('title', 'Profil Saya')

@section('breadcrumb')
<li class="breadcrumb-item active">Profil</li>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <!-- Info Profil -->
    <div class="col-md-4 mb-4">
        <div class="card shadow">
            <div class="card-body text-center py-4">
                <div class="bg-warning text-dark rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                    style="width:80px;height:80px;">
                    <i class="fas fa-user-tie fa-2x"></i>
                </div>
                <h5 class="mb-1">{{ $pelatih->nama }}</h5>
                <p class="text-muted mb-1">{{ $pelatih->perguruan }}</p>
                <span class="badge bg-{{ $pelatih->is_active ? 'success' : 'danger' }}">
                    {{ $pelatih->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
                <hr>
                <div class="text-start">
                    <div class="mb-2">
                        <small class="text-muted">Email</small>
                        <div><i class="fas fa-envelope me-2 text-primary"></i>{{ $pelatih->email }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Nomor HP</small>
                        <div><i class="fas fa-phone me-2 text-success"></i>{{ $pelatih->no_hp ?: '-' }}</div>
                    </div>
                    <div>
                        <small class="text-muted">Terdaftar Sejak</small>
                        <div><i class="fas fa-calendar me-2 text-info"></i>{{ $pelatih->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Form Update Data Profil -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-edit me-2"></i>Update Data Profil</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pelatih.profil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                            id="nama" name="nama" value="{{ old('nama', $pelatih->nama) }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="perguruan" class="form-label">Perguruan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('perguruan') is-invalid @enderror"
                            id="perguruan" name="perguruan" value="{{ old('perguruan', $pelatih->perguruan) }}" required>
                        @error('perguruan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                            id="no_hp" name="no_hp" value="{{ old('no_hp', $pelatih->no_hp) }}" required>
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="email" name="email" value="{{ old('email', $pelatih->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form Ganti Password -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-lock me-2"></i>Ganti Password</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pelatih.profil.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="password_lama" class="form-label">Password Lama <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password_lama') is-invalid @enderror"
                            id="password_lama" name="password_lama" required>
                        @error('password_lama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="password_confirmation" name="password_confirmation" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>Ganti Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
