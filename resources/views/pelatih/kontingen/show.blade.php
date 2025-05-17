@extends('layouts.pelatih')

@section('title', 'Detail Kontingen')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pelatih.kontingen.index') }}">Kontingen</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('action-buttons')
<a href="{{ route('pelatih.peserta.create') }}?kontingen_id={{ $kontingen->id }}" class="btn btn-success">
    <i class="fas fa-user-plus"></i> Tambah Peserta
</a>
@endsection

@section('content')
<div class="row">
    <!-- Informasi Kontingen -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Informasi Kontingen</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="d-inline-block bg-success text-white rounded-circle p-3 mb-2" style="width: 100px; height: 100px;">
                        <i class="fas fa-users fa-3x mt-2"></i>
                    </div>
                    <h5>{{ $kontingen->nama }}</h5>
                    <p class="text-muted">{{ $kontingen->asal_daerah }}</p>
                    <span class="badge {{ $kontingen->is_active ? 'bg-success' : 'bg-danger' }}">
                        {{ $kontingen->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Pelatih:</h6>
                    <p>{{ $kontingen->pelatih->nama }} ({{ $kontingen->pelatih->perguruan }})</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Kontak Pendamping:</h6>
                    <p>{{ $kontingen->kontak_pendamping ?: '-' }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Jumlah Peserta:</h6>
                    <p>{{ $kontingen->pesertas->count() }} peserta</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="font-weight-bold">Tanggal Registrasi:</h6>
                    <p>{{ $kontingen->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editKontingenModal">
                        <i class="fas fa-edit me-1"></i> Edit Kontingen
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Status Pembayaran -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Status Pembayaran</h6>
                <a href="{{ route('pelatih.pembayaran.index') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-money-bill-wave"></i> Kelola Pembayaran
                </a>
            </div>
            <div class="card-body">
                @if($kontingen->pembayarans->count() > 0)
                    @php
                        $pembayaran = $kontingen->pembayarans->sortByDesc('created_at')->first();
                    @endphp
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Status Pembayaran</h6>
                                    <div class="mt-2">
                                        @if($pembayaran->status == 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @elseif($pembayaran->status == 'menunggu_verifikasi')
                                            <span class="badge bg-warning">Menunggu Verifikasi</span>
                                        @else
                                            <span class="badge bg-danger">Belum Bayar</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Total Tagihan</h6>
                                    <h4 class="mt-2">Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($pembayaran->status == 'menunggu_verifikasi' || $pembayaran->status == 'lunas')
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Bukti Transfer</h6>
                                @if($pembayaran->bukti_transfer)
                                    <div class="text-center mt-3">
                                        <img src="{{ Storage::url($pembayaran->bukti_transfer) }}" alt="Bukti Transfer" class="img-fluid" style="max-height: 300px;">
                                    </div>
                                @else
                                    <p class="text-muted">Belum ada bukti transfer.</p>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    @if($pembayaran->status == 'belum_bayar')
                        <div class="text-center">
                            <a href="{{ route('pelatih.pembayaran.show', $pembayaran->id) }}" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i> Upload Bukti Pembayaran
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <p class="text-muted mb-3">Belum ada informasi pembayaran.</p>
                        <a href="{{ route('pelatih.pembayaran.index') }}" class="btn btn-success">
                            <i class="fas fa-money-bill-wave me-1"></i> Kelola Pembayaran
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Daftar Peserta -->
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Daftar Peserta</h6>
                <a href="{{ route('pelatih.peserta.create') }}?kontingen_id={{ $kontingen->id }}" class="btn btn-sm btn-success">
                    <i class="fas fa-user-plus"></i> Tambah Peserta
                </a>
            </div>
            <div class="card-body">
                @if($kontingen->pesertas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="peserta-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Kategori</th>
                                    <th>Kelompok Usia</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kontingen->pesertas as $peserta)
                                <tr>
                                    <td>{{ $peserta->nama }}</td>
                                    <td>{{ $peserta->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                   <td>{{ $peserta->subkategoriLomba->kategoriLomba->nama }} - {{ $peserta->subkategoriLomba->nama }}</td>
                                   <td>{{ $peserta->kelompokUsia->nama }}</td>
                                   <td>
                                       @if($peserta->status_verifikasi == 'valid')
                                           <span class="badge bg-success">Valid</span>
                                       @elseif($peserta->status_verifikasi == 'pending')
                                           <span class="badge bg-warning">Pending</span>
                                       @else
                                           <span class="badge bg-danger">Tidak Valid</span>
                                       @endif
                                   </td>
                                   <td>
                                       <div class="btn-group" role="group">
                                           <a href="{{ route('pelatih.peserta.show', $peserta->id) }}" class="btn btn-sm btn-info">
                                               <i class="fas fa-eye"></i>
                                           </a>
                                           @if($peserta->status_verifikasi == 'pending')
                                               <a href="{{ route('pelatih.peserta.edit', $peserta->id) }}" class="btn btn-sm btn-warning">
                                                   <i class="fas fa-edit"></i>
                                               </a>
                                               <a href="{{ route('pelatih.dokumen.index', $peserta->id) }}" class="btn btn-sm btn-success">
                                                   <i class="fas fa-file-alt"></i>
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
                   <div class="text-center py-4">
                       <p class="text-muted mb-3">Belum ada peserta terdaftar.</p>
                       <a href="{{ route('pelatih.peserta.create') }}?kontingen_id={{ $kontingen->id }}" class="btn btn-success">
                           <i class="fas fa-user-plus me-1"></i> Tambah Peserta
                       </a>
                   </div>
               @endif
           </div>
       </div>
   </div>
</div>

<!-- Modal Edit Kontingen -->
<div class="modal fade" id="editKontingenModal" tabindex="-1" aria-labelledby="editKontingenModalLabel" aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="editKontingenModalLabel">Edit Kontingen</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <form action="{{ route('pelatih.kontingen.update', $kontingen->id) }}" method="POST">
               @csrf
               @method('PUT')
               <div class="modal-body">
                   <div class="mb-3">
                       <label for="nama" class="form-label">Nama Kontingen <span class="text-danger">*</span></label>
                       <input type="text" class="form-control" id="nama" name="nama" value="{{ $kontingen->nama }}" required>
                   </div>
                   <div class="mb-3">
                       <label for="asal_daerah" class="form-label">Asal Daerah <span class="text-danger">*</span></label>
                       <input type="text" class="form-control" id="asal_daerah" name="asal_daerah" value="{{ $kontingen->asal_daerah }}" required>
                   </div>
                   <div class="mb-3">
                       <label for="kontak_pendamping" class="form-label">Kontak Pendamping</label>
                       <input type="text" class="form-control" id="kontak_pendamping" name="kontak_pendamping" value="{{ $kontingen->kontak_pendamping }}">
                       <div class="form-text">Nomor telepon pendamping kontingen (opsional).</div>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                   <button type="submit" class="btn btn-success">Simpan Perubahan</button>
               </div>
           </form>
       </div>
   </div>
</div>
@endsection

@push('scripts')
<script>
   $(document).ready(function() {
       $('#peserta-table').DataTable({
           responsive: true
       });
   });
</script>
@endpush