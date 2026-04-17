@extends('layouts.pelatih')

@section('title', 'Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Statistik Utama -->
<div class="row">
    <div class="col-xl-3 col-md-6 col-6 mb-4" id="stat-kontingen">
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

    <div class="col-xl-3 col-md-6 col-6 mb-4" id="stat-peserta">
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

    <div class="col-xl-3 col-md-6 col-6 mb-4" id="stat-verifikasi">
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

    <div class="col-xl-3 col-md-6 col-6 mb-4" id="stat-belum-bayar">
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
<div class="card shadow mb-4" id="card-progress-pembayaran">
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
        <div class="mt-2 d-flex justify-content-between flex-column flex-sm-row gap-1 progress-info-mobile">
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
<div class="card shadow mb-4" id="tutorial-quick-actions">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Aksi Cepat</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('pelatih.peserta.create') }}" class="card bg-light h-100 text-decoration-none">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-user-plus fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Tambah Peserta</h5>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('pelatih.pembayaran.index') }}" class="card bg-light h-100 text-decoration-none">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-money-bill-wave fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Kelola Pembayaran</h5>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('pelatih.jadwal.index') }}" class="card bg-light h-100 text-decoration-none">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-calendar-alt fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Lihat Jadwal</h5>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-6 mb-3">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(typeof window.driver === 'undefined') return;

        const driver = window.driver.js.driver;
        const isMobile = window.innerWidth < 768;

        // Steps untuk desktop — target sidebar menu
        const desktopSteps = [
            { popover: { title: 'Selamat Datang!', description: 'Mari kita kenali fitur-fitur di aplikasi Pelatih ini agar Anda lebih mudah menggunakannya.' } },
            { element: '#menu-kontingen', popover: { title: '1. Kontingen', description: 'Tambahkan data kontingen daerah atau perguruan Anda di sini sebelum mendaftarkan peserta.', side: "right", align: 'start' } },
            { element: '#menu-peserta', popover: { title: '2. Peserta', description: 'Setelah mempunyai kontingen, daftarkan atlet/pesilat Anda di menu ini.', side: "right", align: 'start' } },
            { element: '#menu-pembayaran', popover: { title: '3. Pembayaran', description: 'Cek tagihan dan upload mutasi/bukti pembayaran biaya pendaftaran di sini.', side: "right", align: 'start' } },
            { element: '#menu-jadwal', popover: { title: '4. Jadwal', description: 'Pantau jadwal pertandingan yang akan berlangsung pada event.', side: "right", align: 'start' } },
            { element: '#tutorial-quick-actions', popover: { title: 'Aksi Cepat', description: 'Gunakan tombol-tombol jalan pintas ini untuk langsung menuju menu yang paling sering dilakukan.', side: "top", align: 'center' } }
        ];

        // Steps untuk mobile — target elemen dashboard yang terlihat
        const mobileSteps = [
            { popover: { title: 'Selamat Datang! 👋', description: 'Mari kita kenali fitur-fitur di aplikasi Pelatih ini. Geser untuk melanjutkan tutorial.' } },
            { element: '#stat-kontingen', popover: { title: '1. Kontingen', description: 'Lihat jumlah kontingen Anda di sini. Buat kontingen melalui menu navigasi (☰) di pojok kiri atas.', side: "bottom", align: 'start' } },
            { element: '#stat-peserta', popover: { title: '2. Peserta', description: 'Statistik jumlah peserta yang sudah terdaftar di kontingen Anda.', side: "bottom", align: 'start' } },
            { element: '#stat-verifikasi', popover: { title: '3. Status Pembayaran', description: 'Pantau berapa pembayaran yang menunggu verifikasi admin.', side: "bottom", align: 'start' } },
            { element: '#stat-belum-bayar', popover: { title: '4. Belum Bayar', description: 'Jumlah peserta yang belum melakukan pembayaran.', side: "bottom", align: 'start' } },
            { element: '#card-progress-pembayaran', popover: { title: '5. Progress Pembayaran', description: 'Lihat progres keseluruhan pembayaran kontingen Anda di sini.', side: "top", align: 'center' } },
            { element: '#tutorial-quick-actions', popover: { title: '6. Aksi Cepat', description: 'Jalan pintas untuk langsung menambah peserta, bayar, lihat jadwal, dan lainnya.', side: "top", align: 'center' } },
            { popover: { title: 'Menu Navigasi ☰', description: 'Untuk mengakses semua fitur (Kontingen, Peserta, Pembayaran, Jadwal), tap tombol ☰ di pojok kiri atas layar.' } }
        ];

        const driverObj = driver({
            showProgress: true,
            animate: true,
            allowClose: true,
            overlayClickNext: isMobile, // Tap overlay untuk lanjut di mobile
            nextBtnText: isMobile ? 'Lanjut ➜' : 'Lanjut',
            prevBtnText: 'Kembali',
            doneBtnText: 'Selesai ✓',
            popoverOffset: isMobile ? 8 : 10,
            steps: isMobile ? mobileSteps : desktopSteps,
            onDestroyStarted: function() {
                driverObj.destroy();
            }
        });

        const tutorialDone = localStorage.getItem('tutorial_pelatih_completed');
        if (!tutorialDone) {
            setTimeout(() => {
                driverObj.drive();
                localStorage.setItem('tutorial_pelatih_completed', 'true');
            }, 500);
        }

        // Trigger manual
        const startBtns = document.querySelectorAll('.start-tutorial-btn');
        startBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                // Tutup offcanvas mobile jika sedang terbuka
                const offcanvasEl = document.getElementById('sidebarMenu');
                if (offcanvasEl) {
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
                    if (offcanvas) offcanvas.hide();
                }

                setTimeout(() => {
                    driverObj.drive();
                }, 300);
            });
        });
    });
</script>
@endpush