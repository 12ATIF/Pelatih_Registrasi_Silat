@extends('layouts.pelatih')

@section('title', 'Pembayaran')

@section('breadcrumb')
<li class="breadcrumb-item active">Pembayaran</li>
@endsection

@section('content')
<!-- Progress Pembayaran -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Progress Pembayaran</h6>
    </div>
    <div class="card-body">
        @php
            $totalTagihan = 0;
            $totalLunas = 0;
            $menungguVerifikasi = 0;
            $belumBayar = 0;
            
            foreach ($pembayarans as $p) {
                $totalTagihan += $p->total_tagihan;
                if ($p->status == 'lunas') {
                    $totalLunas += $p->total_tagihan;
                } elseif ($p->status == 'menunggu_verifikasi') {
                    $menungguVerifikasi++;
                } else {
                    $belumBayar++;
                }
            }
            
            $progressPembayaran = $totalTagihan > 0 ? round(($totalLunas / $totalTagihan) * 100) : 0;
        @endphp
        <h4 class="small font-weight-bold">Total Lunas <span class="float-end">{{ $progressPembayaran }}%</span></h4>
        <div class="progress mb-4">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressPembayaran }}%" aria-valuenow="{{ $progressPembayaran }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Tagihan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
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
                                    Sudah Lunas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalLunas, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $menungguVerifikasi }} pembayaran</div>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $belumBayar }} pembayaran</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Pembayaran -->
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Daftar Pembayaran</h6>
    </div>
    <div class="card-body">
        @if(count($pembayarans) > 0)
            <div class="table-responsive">
                <table class="table table-bordered" id="pembayaran-table">
                    <thead>
                        <tr>
                            <th>Kontingen</th>
                            <th>Asal Daerah</th>
                            <th>Jumlah Peserta</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                            <th>Verifikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembayarans as $pembayaran)
                        <tr>
                            <td>{{ $pembayaran->kontingen->nama }}</td>
                            <td>{{ $pembayaran->kontingen->asal_daerah }}</td>
                            <td>{{ $pembayaran->kontingen->pesertas->count() }}</td>
                            <td>Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</td>
                            <td>
                                @if($pembayaran->status == 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($pembayaran->status == 'menunggu_verifikasi')
                                    <span class="badge bg-warning">Menunggu Verifikasi</span>
                                @else
                                    <span class="badge bg-danger">Belum Bayar</span>
                                @endif
                            </td>
                            <td>
                                {{ $pembayaran->verified_at ? $pembayaran->verified_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('pelatih.pembayaran.show', $pembayaran->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($pembayaran->status != 'lunas')
                                        <a href="{{ route('pelatih.pembayaran.show', $pembayaran->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-upload"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-money-bill-wave fa-4x text-muted"></i>
                </div>
                <h4>Belum ada pembayaran</h4>
                <p class="text-muted">Silakan tambahkan peserta terlebih dahulu untuk melakukan pembayaran.</p>
                <a href="{{ route('pelatih.peserta.create') }}" class="btn btn-success">
                    <i class="fas fa-user-plus"></i> Tambah Peserta
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#pembayaran-table').DataTable({
            responsive: true
        });
    });
</script>
@endpush