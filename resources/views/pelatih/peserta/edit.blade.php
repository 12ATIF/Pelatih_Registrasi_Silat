@extends('layouts.pelatih')

@section('title', 'Edit Peserta')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pelatih.peserta.index') }}">Peserta</a></li>
<li class="breadcrumb-item"><a href="{{ route('pelatih.peserta.show', $peserta->id) }}">{{ $peserta->nama }}</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('pelatih.peserta.update', $peserta->id) }}" method="POST" class="js-loading-form">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="kontingen_id" class="form-label">Kontingen <span class="text-danger">*</span></label>
                    <select class="form-select @error('kontingen_id') is-invalid @enderror" id="kontingen_id" name="kontingen_id" required>
                        <option value="">Pilih Kontingen</option>
                        @foreach($kontingens as $kontingen)
                            <option value="{{ $kontingen->id }}" {{ old('kontingen_id', $peserta->kontingen_id) == $kontingen->id ? 'selected' : '' }}>
                                {{ $kontingen->nama }} ({{ $kontingen->asal_daerah }})
                            </option>
                        @endforeach
                    </select>
                    @error('kontingen_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="nama" class="form-label">Nama Peserta <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $peserta->nama) }}" required>
                    @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $peserta->tanggal_lahir->format('Y-m-d')) }}" required>
                    @error('tanggal_lahir')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <div id="usia_warning" class="alert alert-warning mt-2 py-2 small" style="display:none;">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <span id="usia_warning_msg"></span>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <label for="berat_badan" class="form-label">Berat Badan (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.1" min="20" max="300" inputmode="decimal" class="form-control @error('berat_badan') is-invalid @enderror" id="berat_badan" name="berat_badan" value="{{ old('berat_badan', $peserta->berat_badan) }}" required>
                    @error('berat_badan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="text-muted">Rentang: 20 - 300 kg</small>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="kelompok_usia_id" class="form-label">Kelompok Usia <span class="text-danger">*</span></label>
                    <select class="form-select @error('kelompok_usia_id') is-invalid @enderror" id="kelompok_usia_id" name="kelompok_usia_id" required>
                        <option value="">Pilih Kelompok Usia</option>
                        @foreach($kelompokUsias as $usia)
                            <option value="{{ $usia->id }}" data-min="{{ $usia->rentang_usia_min }}" data-max="{{ $usia->rentang_usia_max }}" {{ old('kelompok_usia_id', $peserta->kelompok_usia_id) == $usia->id ? 'selected' : '' }}>
                                {{ $usia->nama }} ({{ $usia->rentang_usia_min }}-{{ $usia->rentang_usia_max }} tahun)
                            </option>
                        @endforeach
                    </select>
                    @error('kelompok_usia_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="subkategori_id" class="form-label">Subkategori Lomba <span class="text-danger">*</span></label>
                    <select class="form-select @error('subkategori_id') is-invalid @enderror" id="subkategori_id" name="subkategori_id" required>
                        <option value="">Pilih Subkategori</option>
                        @foreach($subkategoris as $subkategori)
                            <option value="{{ $subkategori->id }}" data-jenis="{{ $subkategori->jenis }}" {{ old('subkategori_id', $peserta->subkategori_id) == $subkategori->id ? 'selected' : '' }}>
                                {{ $subkategori->kategoriLomba->nama }} - {{ $subkategori->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('subkategori_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            
            <div class="alert alert-info" id="infoKelasTanding" style="display: none;">
                <strong>Info Kelas Tanding:</strong> <span id="infoKelasTandingText"></span>
            </div>
            
            <div class="d-flex justify-content-between flex-wrap gap-2">
                <a href="{{ route('pelatih.peserta.show', $peserta->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success js-submit-btn">
                    <span class="btn-label"><i class="fas fa-save me-1"></i> Simpan Perubahan</span>
                    <span class="btn-loading d-none"><span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Function to validate age inline (no alert)
        function validateAge() {
            const tanggalLahir = $('#tanggal_lahir').val();
            const kelompokUsiaId = $('#kelompok_usia_id').val();
            const $warn = $('#usia_warning');
            const $msg = $('#usia_warning_msg');

            $warn.hide();
            $('#tanggal_lahir').removeClass('is-invalid');

            if (tanggalLahir && kelompokUsiaId) {
                const selectedOption = $(`#kelompok_usia_id option[value="${kelompokUsiaId}"]`);
                const minUsia = selectedOption.data('min');
                const maxUsia = selectedOption.data('max');
                const usiaName = selectedOption.text().split('(')[0].trim();

                const birthDate = new Date(tanggalLahir);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                if (age < minUsia || age > maxUsia) {
                    $msg.text(`Usia peserta (${age} tahun) tidak sesuai dengan kelompok usia ${usiaName} (${minUsia}-${maxUsia} tahun).`);
                    $warn.show();
                    $('#tanggal_lahir').addClass('is-invalid');
                    return false;
                }
            }
            return true;
        }
        
        // Function to check if subkategori is tanding
        function checkSubkategori() {
            const subkategoriId = $('#subkategori_id').val();
            const jenisTanding = $('#subkategori_id option:selected').data('jenis');
            
            if (subkategoriId && jenisTanding === 'tanding') {
                // Show info for kelas tanding
                $('#infoKelasTanding').show();
                $('#infoKelasTandingText').text('Kelas tanding akan otomatis ditentukan berdasarkan berat badan dan kelompok usia.');
            } else {
                $('#infoKelasTanding').hide();
            }
        }
        
        // Validate on form submit (inline error instead of alert)
        $('form').on('submit', function(e) {
            if (!validateAge()) {
                e.preventDefault();
                if ($('#usia_warning').length) {
                    $('html, body').animate({ scrollTop: $('#usia_warning').offset().top - 100 }, 300);
                }
                return false;
            }
        });

        // Re-validate on field change
        $('#tanggal_lahir, #kelompok_usia_id').on('change', validateAge);
        
        // Check on change
        $('#tanggal_lahir, #kelompok_usia_id').on('change', validateAge);
        $('#subkategori_id').on('change', checkSubkategori);
        
        // Check on page load
        checkSubkategori();
    });
</script>
@endpush