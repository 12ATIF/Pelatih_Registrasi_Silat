@extends('layouts.pelatih')

@section('title', 'Dokumen Peserta')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pelatih.peserta.index') }}">Peserta</a></li>
<li class="breadcrumb-item"><a href="{{ route('pelatih.peserta.show', $peserta->id) }}">{{ $peserta->nama }}</a></li>
<li class="breadcrumb-item active">Dokumen</li>
@endsection

@section('action-buttons')
<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadDokumenModal">
    <i class="fas fa-upload"></i> Upload Dokumen
</button>
@endsection

@section('content')
<!-- Informasi Peserta -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Informasi Peserta</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Nama:</strong> {{ $peserta->nama }}</p>
                <p><strong>Jenis Kelamin:</strong> {{ $peserta->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                <p><strong>Tanggal Lahir:</strong> {{ $peserta->tanggal_lahir->format('d/m/Y') }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Kontingen:</strong> {{ $peserta->kontingen->nama }}</p>
                <p><strong>Kategori:</strong> {{ $peserta->subkategoriLomba->kategoriLomba->nama }} - {{ $peserta->subkategoriLomba->nama }}</p>
                <p><strong>Status Verifikasi:</strong> 
                    @if($peserta->status_verifikasi == 'valid')
                        <span class="badge bg-success">Valid</span>
                    @elseif($peserta->status_verifikasi == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @else
                        <span class="badge bg-danger">Tidak Valid</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Dokumen -->
<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Daftar Dokumen</h6>
    </div>
    <div class="card-body">
        @if($dokumens->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" id="dokumen-table">
                    <thead>
                        <tr>
                            <th>Jenis Dokumen</th>
                            <th>Tanggal Upload</th>
                            <th>Status Verifikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dokumens as $dokumen)
                        <tr>
                            <td>{{ $dokumen->jenis_dokumen }}</td>
                            <td>{{ $dokumen->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($dokumen->verified_at)
                                    <span class="badge bg-success">Terverifikasi</span>
                                @else
                                    <span class="badge bg-warning">Belum Diverifikasi</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ Storage::url($dokumen->file_path) }}" class="btn btn-sm btn-info" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('pelatih.dokumen.download', ['pesertaId' => $peserta->id, 'id' => $dokumen->id]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    @if(!$dokumen->verified_at && $peserta->status_verifikasi == 'pending')
                                        <button type="button" class="btn btn-sm btn-danger delete-dokumen" data-id="{{ $dokumen->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
                    <i class="fas fa-file-alt fa-4x text-muted"></i>
                </div>
                <h4>Belum ada dokumen</h4>
                <p class="text-muted">Silakan upload dokumen yang diperlukan untuk verifikasi peserta.</p>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadDokumenModal">
                    <i class="fas fa-upload"></i> Upload Dokumen
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Modal Upload Dokumen -->
<div class="modal fade" id="uploadDokumenModal" tabindex="-1" aria-labelledby="uploadDokumenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDokumenModalLabel">Upload Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <form action="{{ route('pelatih.dokumen.store', $peserta->id) }}" method="POST" enctype="multipart/form-data">
               @csrf
               <div class="modal-body">
                   <div class="mb-3">
                       <label for="jenis_dokumen" class="form-label">Jenis Dokumen <span class="text-danger">*</span></label>
                       <select class="form-select" id="jenis_dokumen" name="jenis_dokumen" required>
                           <option value="">Pilih Jenis Dokumen</option>
                           <option value="KTP">KTP</option>
                           <option value="Kartu Pelajar">Kartu Pelajar</option>
                           <option value="Akta Lahir">Akta Lahir</option>
                           <option value="Surat Izin">Surat Izin</option>
                           <option value="Foto">Foto</option>
                           <option value="Lainnya">Lainnya</option>
                       </select>
                   </div>
                   <div class="mb-3">
                       <label for="file" class="form-label">File <span class="text-danger">*</span></label>
                       <input type="file" class="form-control" id="file" name="file" accept=".jpg,.jpeg,.png,.pdf" required>
                       <div class="form-text">Format yang diterima: JPG, JPEG, PNG, atau PDF. Maksimal 5MB.</div>
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

<!-- Form untuk Delete Dokumen -->
<form id="deleteDokumenForm" method="POST" style="display: none;">
   @csrf
   @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
   $(document).ready(function() {
       $('#dokumen-table').DataTable({
           responsive: true
       });
       
       // Delete Dokumen
       $('.delete-dokumen').on('click', function() {
           const id = $(this).data('id');
           
           if (confirm('Apakah Anda yakin ingin menghapus dokumen ini? Tindakan ini tidak dapat dibatalkan.')) {
               $('#deleteDokumenForm').attr('action', `{{ url('pelatih/peserta') }}/{{ $peserta->id }}/dokumen/${id}`);
               $('#deleteDokumenForm').submit();
           }
       });
   });
</script>
@endpush