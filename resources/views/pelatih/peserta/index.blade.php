@extends('layouts.pelatih')

@section('title', 'Manajemen Peserta')

@section('breadcrumb')
<li class="breadcrumb-item active">Peserta</li>
@endsection

@section('action-buttons')
<a href="{{ route('pelatih.peserta.create') }}" class="btn btn-success">
    <i class="fas fa-user-plus"></i> Tambah Peserta
</a>
@endsection

@section('content')
<!-- Filter Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Filter Peserta</h6>
    </div>
    <div class="card-body">
        <form id="filter-form" method="GET">
            <div class="row">
                @if(isset($kontingenList) && $kontingenList->count() > 1)
                <div class="col-md-3 mb-3">
                    <label for="kontingen_id" class="form-label">Kontingen</label>
                    <select class="form-select" id="kontingen_id" name="kontingen_id">
                        <option value="">Semua Kontingen</option>
                        @foreach($kontingenList as $kontingen)
                            <option value="{{ $kontingen->id }}" {{ request('kontingen_id') == $kontingen->id ? 'selected' : '' }}>
                                {{ $kontingen->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <div class="col-md-3 mb-3">
                    <label for="subkategori_id" class="form-label">Subkategori</label>
                    <select class="form-select" id="subkategori_id" name="subkategori_id">
                        <option value="">Semua Subkategori</option>
                        @foreach($subkategoris as $subkategori)
                            <option value="{{ $subkategori->id }}" {{ request('subkategori_id') == $subkategori->id ? 'selected' : '' }}>
                                {{ $subkategori->kategoriLomba->nama }} - {{ $subkategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="kelompok_usia_id" class="form-label">Kelompok Usia</label>
                    <select class="form-select" id="kelompok_usia_id" name="kelompok_usia_id">
                        <option value="">Semua Kelompok Usia</option>
                        @foreach($kelompokUsias as $usia)
                            <option value="{{ $usia->id }}" {{ request('kelompok_usia_id') == $usia->id ? 'selected' : '' }}>
                                {{ $usia->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="status_verifikasi" class="form-label">Status Verifikasi</label>
                    <select class="form-select" id="status_verifikasi" name="status_verifikasi">
                        <option value="">Semua Status</option>
                        <option value="valid" {{ request('status_verifikasi') == 'valid' ? 'selected' : '' }}>Valid</option>
                        <option value="pending" {{ request('status_verifikasi') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="tidak_valid" {{ request('status_verifikasi') == 'tidak_valid' ? 'selected' : '' }}>Tidak Valid</option>
                    </select>
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('pelatih.peserta.index') }}" class="btn btn-secondary">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Daftar Peserta -->
<div class="card shadow">
    <div class="card-body">
        @if($pesertas->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" id="peserta-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>Berat Badan</th>
                            <th>Kontingen</th>
                            <th>Kategori</th>
                            <th>Kelompok Usia</th>
                            <th>Kelas Tanding</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesertas as $peserta)
                        <tr>
                            <td>{{ $peserta->nama }}</td>
                            <td>{{ $peserta->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ $peserta->tanggal_lahir->format('d/m/Y') }}</td>
                            <td>{{ $peserta->berat_badan }} kg</td>
                            <td>{{ $peserta->kontingen->nama }}</td>
                            <td>{{ $peserta->subkategoriLomba->kategoriLomba->nama }} - {{ $peserta->subkategoriLomba->nama }}</td>
                            <td>{{ $peserta->kelompokUsia->nama }}</td>
                            <td>{{ $peserta->kelasTanding ? $peserta->kelasTanding->label_keterangan : '-' }}</td>
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
                                        <button type="button" class="btn btn-sm btn-danger delete-peserta" data-id="{{ $peserta->id }}">
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
                    <i class="fas fa-user-graduate fa-4x text-muted"></i>
                </div>
                <h4>Belum ada peserta</h4>
                <p class="text-muted">Silakan tambahkan peserta untuk mulai mendaftarkan ke pertandingan.</p>
                <a href="{{ route('pelatih.peserta.create') }}" class="btn btn-success">
                    <i class="fas fa-user-plus"></i> Tambah Peserta
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Form untuk Delete Peserta -->
<form id="deletePesertaForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#peserta-table').DataTable({
            responsive: true
        });
        
        // Delete Peserta
        $('.delete-peserta').on('click', function() {
            const id = $(this).data('id');
            
            if (confirm('Apakah Anda yakin ingin menghapus peserta ini? Tindakan ini tidak dapat dibatalkan.')) {
                $('#deletePesertaForm').attr('action', `{{ url('pelatih/peserta') }}/${id}`);
                $('#deletePesertaForm').submit();
            }
        });
    });
</script>
@endpush