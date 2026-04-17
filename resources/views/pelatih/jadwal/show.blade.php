@extends('layouts.pelatih')

@section('title', 'Detail Jadwal')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pelatih.jadwal.index') }}">Jadwal</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('action-buttons')
<a href="{{ route('pelatih.jadwal.index') }}" class="btn btn-sm btn-secondary">
    <i class="fas fa-arrow-left me-1"></i> Kembali
</a>
@endsection

@section('content')
<!-- Info Jadwal -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Informasi Jadwal</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless table-sm jadwal-detail-table">
                    <tr>
                        <td class="fw-bold text-muted" style="width: 40%;">Pertandingan</td>
                        <td>{{ $jadwal->pertandingan->nama_event }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Kategori</td>
                        <td>{{ $jadwal->subkategoriLomba->kategoriLomba->nama }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Subkategori</td>
                        <td>{{ $jadwal->subkategoriLomba->nama }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Kelompok Usia</td>
                        <td>
                            <span class="badge bg-success">{{ $jadwal->kelompokUsia->nama }}</span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless table-sm jadwal-detail-table">
                    <tr>
                        <td class="fw-bold text-muted" style="width: 40%;">Tanggal</td>
                        <td>
                            <i class="fas fa-calendar-day text-primary me-1"></i>
                            {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Waktu</td>
                        <td>
                            <i class="fas fa-clock text-warning me-1"></i>
                            {{ $jadwal->waktu_mulai }} - {{ $jadwal->waktu_selesai ?: 'Selesai' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Lokasi</td>
                        <td>
                            <i class="fas fa-map-marker-alt text-danger me-1"></i>
                            {{ $jadwal->lokasi_detail ?: $jadwal->pertandingan->lokasi_umum }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Peserta Saya di Jadwal Ini -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-user-check me-1"></i> Peserta Saya
            <span class="badge bg-primary ms-1">{{ $pesertaPelatih->count() }}</span>
        </h6>
    </div>
    <div class="card-body">
        @if($pesertaPelatih->count() > 0)
            {{-- Desktop --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama Peserta</th>
                            <th>Kontingen</th>
                            <th>Jenis Kelamin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesertaPelatih as $i => $peserta)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $peserta->nama }}</td>
                            <td>{{ $peserta->kontingen->nama }}</td>
                            <td>{{ $peserta->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Mobile --}}
            <div class="d-md-none">
                @foreach($pesertaPelatih as $i => $peserta)
                <div class="d-flex align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.8rem;">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-bold text-truncate" style="font-size: 0.9rem;">{{ $peserta->nama }}</div>
                        <div class="text-muted" style="font-size: 0.78rem;">
                            {{ $peserta->kontingen->nama }} &middot; {{ $peserta->jenis_kelamin == 'L' ? 'L' : 'P' }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-3">
                <i class="fas fa-info-circle text-muted fa-2x mb-2"></i>
                <p class="mb-0 text-muted">Tidak ada peserta Anda yang terdaftar di jadwal ini.</p>
            </div>
        @endif
    </div>
</div>

<!-- Semua Peserta di Jadwal Ini -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-users me-1"></i> Semua Peserta
            <span class="badge bg-secondary ms-1">{{ $allPesertas->count() }}</span>
        </h6>
    </div>
    <div class="card-body">
        @if($allPesertas->count() > 0)
            {{-- Desktop --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama Peserta</th>
                            <th>Kontingen</th>
                            <th>Jenis Kelamin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allPesertas as $i => $peserta)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $peserta->nama }}</td>
                            <td>{{ $peserta->kontingen->nama }}</td>
                            <td>{{ $peserta->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Mobile --}}
            <div class="d-md-none">
                @foreach($allPesertas as $i => $peserta)
                <div class="d-flex align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.8rem;">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-bold text-truncate" style="font-size: 0.9rem;">{{ $peserta->nama }}</div>
                        <div class="text-muted" style="font-size: 0.78rem;">
                            {{ $peserta->kontingen->nama }} &middot; {{ $peserta->jenis_kelamin == 'L' ? 'L' : 'P' }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-3">
                <i class="fas fa-info-circle text-muted fa-2x mb-2"></i>
                <p class="mb-0 text-muted">Belum ada peserta terdaftar di jadwal ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .jadwal-detail-table td {
            font-size: 0.85rem;
            padding: 6px 4px !important;
        }
        .jadwal-detail-table td:first-child {
            width: 35% !important;
        }
    }
</style>
@endpush
