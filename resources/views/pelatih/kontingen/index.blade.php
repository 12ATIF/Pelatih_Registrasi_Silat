@extends('layouts.pelatih')

@section('title', 'Manajemen Kontingen')

@section('breadcrumb')
<li class="breadcrumb-item active">Kontingen</li>
@endsection

@section('action-buttons')
<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahKontingenModal">
    <i class="fas fa-plus"></i> Tambah Kontingen
</button>
@endsection

@section('content')
<!-- Daftar Kontingen -->
<div class="card shadow">
    <div class="card-body">
        @if($kontingens->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" id="kontingen-table">
                    <thead>
                        <tr>
                            <th>Nama Kontingen</th>
                            <th>Asal Daerah</th>
                            <th>Kontak Pendamping</th>
                            <th>Jumlah Peserta</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kontingens as $kontingen)
                        <tr>
                            <td>{{ $kontingen->nama }}</td>
                            <td>{{ $kontingen->asal_daerah }}</td>
                            <td>{{ $kontingen->kontak_pendamping ?: '-' }}</td>
                            <td>{{ $kontingen->pesertas_count }}</td>
                            <td>
                                <span class="badge {{ $kontingen->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $kontingen->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('pelatih.kontingen.show', $kontingen->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-warning edit-kontingen" 
                                            data-id="{{ $kontingen->id }}"
                                            data-nama="{{ $kontingen->nama }}"
                                            data-asal="{{ $kontingen->asal_daerah }}"
                                            data-kontak="{{ $kontingen->kontak_pendamping }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="{{ route('pelatih.kontingen.peserta', $kontingen->id) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    @if($kontingen->pesertas_count == 0)
                                        <button type="button" class="btn btn-sm btn-danger delete-kontingen" data-id="{{ $kontingen->id }}">
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
                    <i class="fas fa-users fa-4x text-muted"></i>
                </div>
                <h4>Belum ada kontingen</h4>
                <p class="text-muted">Silakan tambahkan kontingen untuk mulai mendaftarkan peserta.</p>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahKontingenModal">
                    <i class="fas fa-plus"></i> Tambah Kontingen
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Kontingen -->
<div class="modal fade" id="tambahKontingenModal" tabindex="-1" aria-labelledby="tambahKontingenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahKontingenModalLabel">Tambah Kontingen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pelatih.kontingen.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Kontingen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="asal_daerah" class="form-label">Asal Daerah <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="asal_daerah" name="asal_daerah" required>
                    </div>
                    <div class="mb-3">
                        <label for="kontak_pendamping" class="form-label">Kontak Pendamping</label>
                        <input type="text" class="form-control" id="kontak_pendamping" name="kontak_pendamping">
                        <div class="form-text">Nomor telepon pendamping kontingen (opsional).</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
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
            <form id="editKontingenForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label">Nama Kontingen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_asal_daerah" class="form-label">Asal Daerah <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_asal_daerah" name="asal_daerah" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_kontak_pendamping" class="form-label">Kontak Pendamping</label>
                        <input type="text" class="form-control" id="edit_kontak_pendamping" name="kontak_pendamping">
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

<!-- Form untuk Delete Kontingen -->
<form id="deleteKontingenForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#kontingen-table').DataTable({
            responsive: true
        });
        
        // Edit Kontingen
        $('.edit-kontingen').on('click', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const asal = $(this).data('asal');
            const kontak = $(this).data('kontak');
            
            $('#edit_nama').val(nama);
            $('#edit_asal_daerah').val(asal);
            $('#edit_kontak_pendamping').val(kontak);
            
            $('#editKontingenForm').attr('action', `{{ url('pelatih/kontingen') }}/${id}`);
            $('#editKontingenModal').modal('show');
        });
        
        // Delete Kontingen
        $('.delete-kontingen').on('click', function() {
            const id = $(this).data('id');
            
            if (confirm('Apakah Anda yakin ingin menghapus kontingen ini? Tindakan ini tidak dapat dibatalkan.')) {
                $('#deleteKontingenForm').attr('action', `{{ url('pelatih/kontingen') }}/${id}`);
                $('#deleteKontingenForm').submit();
            }
        });
    });
</script>
@endpush