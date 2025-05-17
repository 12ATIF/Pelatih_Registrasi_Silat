@extends('layouts.pelatih')

@section('title', 'Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Statistik Utama -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Jumlah Kontingen</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['jumlah_kontingen'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Jumlah Peserta</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['jumlah_peserta'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Menunggu Verifikasi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pembayaranStats['menunggu_verifikasi'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Belum Bayar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pembayaranStats['belum_bayar'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Pembayaran -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Progress Pembayaran</h6>
    </div>
    <div class="card-body">
        @php
            $progressPembayaran = $pembayaranStats['total_tagihan'] > 0 ? round(($pembayaranStats['sudah_lunas'] / $pembayaranStats['total_tagihan']) * 100) : 0;
        @endphp
        <h4 class="small font-weight-bold">Total Lunas <span class="float-end">{{ $progressPembayaran }}%</span></h4>
        <div class="progress mb-4">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressPembayaran }}%" aria-valuenow="{{ $progressPembayaran }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div class="mt-2 d-flex justify-content-between">
            <span>Total Tagihan: Rp {{ number_format($pembayaranStats['total_tagihan'], 0, ',', '.') }}</span>
            <span>Total Lunas: Rp {{ number_format($pembayaranStats['sudah_lunas'], 0, ',', '.') }}</span>
        </div>
    </div>
</div>

<div class="row">
    <!-- Pertandingan Mendatang -->
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Pertandingan Mendatang</h6>
                <a href="{{ route('pelatih.jadwal.index') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-calendar-alt me-1"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($upcomingEvents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Event</th>
                                    <th>Tanggal</th>
                                    <th>Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingEvents as $event)
                                <tr>
                                    <td>{{ $event->nama_event }}</td>
                                    <td>{{ $event->tanggal_event->format('d/m/Y') }}</td>
                                    <td>{{ $event->lokasi_umum }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <p class="mb-0">Tidak ada pertandingan yang akan datang</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Kontingen Saya -->
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Kontingan Saya</h6>
                <a href="{{ route('pelatih.kontingen.index') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-users me-1"></i> Kelola Kontingen
                </a>
            </div>
            <div class="card-body">
                @php
                    $kontingens = Auth::guard('pelatih')->user()->kontingens()->withCount('pesertas')->get();
                @endphp
                
                @if($kontingens->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Kontingen</th>
                                    <th>Asal Daerah</th>
                                    <th>Jumlah Peserta</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kontingens as $kontingen)
                                <tr>
                                    <td>{{ $kontingen->nama }}</td>
                                    <td>{{ $kontingen->asal_daerah }}</td>
                                    <td>{{ $kontingen->pesertas_count }}</td>
                                    <td>
                                        <span class="badge {{ $kontingen->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $kontingen->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <p class="mb-0">Anda belum memiliki kontingen</p>
                        <a href="{{ route('pelatih.kontingen.index') }}" class="btn btn-success mt-2">
                            <i class="fas fa-plus"></i> Tambah Kontingen
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Aksi Cepat</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('pelatih.peserta.create') }}" class="card bg-light h-100 text-decoration-none">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-user-plus fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Tambah Peserta</h5>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('pelatih.pembayaran.index') }}" class="card bg-light h-100 text-decoration-none">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-money-bill-wave fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Kelola Pembayaran</h5>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('pelatih.jadwal.index') }}" class="card bg-light h-100 text-decoration-none">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-calendar-alt fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Lihat Jadwal</h5>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('pelatih.peserta.index') }}" class="card bg-light h-100 text-decoration-none">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-clipboard-list fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Status Peserta</h5>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection