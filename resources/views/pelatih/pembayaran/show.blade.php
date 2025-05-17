@extends('layouts.pelatih')

@section('title', 'Detail Pembayaran')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pelatih.pembayaran.index') }}">Pembayaran</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row">
    <!-- Informasi Pembayaran -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Informasi Pembayaran</h6>
            </div>
            <div class="card-body">
                <div class="mb-4 text-center">
                    <div class="d-inline-block bg-success text-white rounded-circle p-3 mb-2" style="width: 100px; height: 100px;">
                        <i class="fas fa-money-bill-wave fa-3x mt-2"></i>
                    </div>
                    <h5>{{ $kontingen->nama }}</h5>
                    <p class="text-muted">{{ $kontingen->asal_daerah }}</p>
                    <h3>Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</h3>
                    @if($pembayaran->status == 'lunas')
                        <span class="badge bg-success">Lunas</span>
                    @elseif($pembayaran->status == 'menunggu_verifikasi')
                        <span class="badge bg-warning">Menunggu Verifikasi</span>
                    @else
                        <span class="badge bg-danger">Belum Bayar</span>
                    @endif
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Jumlah Peserta:</h6>
                    <p>{{ $kontingen->pesertas->count() }} peserta</p>
                </div>
                
                @if($pembayaran->verified_at)
                <div class="mb-3">
                    <h6 class="font-weight-bold">Diverifikasi pada:</h6>
                    <p>{{ $pembayaran->verified_at->format('d/m/Y H:i') }}</p>
                </div>
                @endif
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Dibuat pada:</h6>
                    <p>{{ $pembayaran->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                @if($pembayaran->status != 'lunas')
                <div class="d-grid gap-2">
                    <form action="{{ route('pelatih.pembayaran.recalculate', $pembayaran->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-sync me-1"></i> Hitung Ulang Tagihan
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-8 mb-4">
        <!-- Bukti Pembayaran -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Bukti Pembayaran</h6>
            </div>
            <div class="card-body">
                @if($pembayaran->status == 'lunas')
                    <div class="text-center py-3">
                        <div class="mb-3">
                            <i class="fas fa-check-circle fa-4x text-success"></i>
                        </div>
                        <h4>Pembayaran Sudah Lunas</h4>
                        <p class="text-muted">Pembayaran telah diverifikasi oleh admin.</p>
                        
                        @if($pembayaran->bukti_transfer)
                        <div class="mt-4">
                            <h5>Bukti Transfer</h5>
                            <img src="{{ Storage::url($pembayaran->bukti_transfer) }}" alt="Bukti Transfer" class="img-fluid mt-2" style="max-height: 400px;">
                        </div>
                        @endif
                    </div>
                @elseif($pembayaran->status == 'menunggu_verifikasi')
                    <div class="text-center py-3">
                        <div class="mb-3">
                            <i class="fas fa-clock fa-4x text-warning"></i>
                        </div>
                        <h4>Menunggu Verifikasi</h4>
                        <p class="text-muted">Bukti pembayaran Anda sedang dalam proses verifikasi oleh admin.</p>
                        
                        @if($pembayaran->bukti_transfer)
                        <div class="mt-4">
                            <h5>Bukti Transfer Yang Diunggah</h5>
                            <img src="{{ Storage::url($pembayaran->bukti_transfer) }}" alt="Bukti Transfer" class="img-fluid mt-2" style="max-height: 400px;">
                            
                            <div class="mt-3">
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#uploadBuktiModal">
                                    <i class="fas fa-upload me-1"></i> Upload Ulang
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-3">
                        <div class="mb-3">
                            <i class="fas fa-exclamation-circle fa-4x text-danger"></i>
                        </div>
                        <h4>Belum Bayar</h4>
                        <p class="text-muted">Silahkan lakukan pembayaran sesuai dengan total tagihan dan upload bukti transfer.</p>
                        
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5>Informasi Pembayaran</h5>
                                <p>Transfer ke rekening berikut:</p>
                                <div class="d-flex justify-content-center">
                                    <div class="text-start">
                                        <p><strong>Bank:</strong> Bank XYZ</p>
                                        <p><strong>No. Rekening:</strong> 1234567890</p>
                                        <p><strong>Atas Nama:</strong> Panitia Pencak Silat</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadBuktiModal">
                            <i class="fas fa-upload me-1"></i> Upload Bukti Transfer
                        </button>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Detail Tagihan -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Detail Tagihan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Peserta</th>
                                <th>Subkategori</th>
                                <th>Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalBiaya = 0; @endphp
                            @foreach($detailTagihan as $item)
                            <tr>
                                <td>{{ $item['nama'] }}</td>
                                <td>{{ $item['subkategori'] }}</td>
                                <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                            </tr>
                            @php $totalBiaya += $item['harga']; @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-success">
                                <th colspan="2" class="text-end">Total</th>
                                <th>Rp {{ number_format($totalBiaya, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Bukti -->
<div class="modal fade" id="uploadBuktiModal" tabindex="-1" aria-labelledby="uploadBuktiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadBuktiModalLabel">Upload Bukti Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pelatih.pembayaran.upload', $pembayaran->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bukti_transfer" class="form-label">Bukti Transfer <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="bukti_transfer" name="bukti_transfer" accept=".jpg,.jpeg,.png" required>
                        <div class="form-text">Format yang diterima: JPG, JPEG, atau PNG. Maksimal 5MB.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection