@extends('layouts.pelatih')

@section('title', 'Tambah Peserta')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pelatih.peserta.index') }}">Peserta</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('pelatih.peserta.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="kontingen_id" class="form-label">Kontingen <span class="text-danger">*</span></label>
                    <select class="form-select @error('kontingen_id') is-invalid @enderror" id="kontingen_id"
                        name="kontingen_id" required>
                        <option value="">Pilih Kontingen</option>
                        @foreach ($kontingens as $kontingen)
                        <option value="{{ $kontingen->id }}"
                            {{ old('kontingen_id', request('kontingen_id')) == $kontingen->id ? 'selected' : '' }}>
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
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                        name="nama" value="{{ old('nama') }}" required>
                    @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nik" class="form-label">Nomor NIK <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nik') is-invalid @enderror" id="nik"
                        name="nik" value="{{ old('nik') }}" maxlength="16" minlength="16"
                        placeholder="Masukkan 16 digit NIK" required>
                    @error('nik')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                    <small class="text-muted">NIK harus 16 digit angka</small>
                </div>

                <div class="col-md-6">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span
                            class="text-danger">*</span></label>
                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin"
                        name="jenis_kelamin" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span
                            class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                        id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                    @error('tanggal_lahir')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="berat_badan" class="form-label">Berat Badan (kg) <span
                            class="text-danger">*</span></label>
                    <input type="number" step="0.1" class="form-control @error('berat_badan') is-invalid @enderror"
                        id="berat_badan" name="berat_badan" value="{{ old('berat_badan') }}" required>
                    @error('berat_badan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="tinggi_badan" class="form-label">Tinggi Badan (cm) <span
                            class="text-danger">*</span></label>
                    <input type="number" step="0.1"
                        class="form-control @error('tinggi_badan') is-invalid @enderror" id="tinggi_badan"
                        name="tinggi_badan" value="{{ old('tinggi_badan') }}" required>
                    @error('tinggi_badan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="kelompok_usia_id" class="form-label">Kelompok Usia <span
                            class="text-danger">*</span></label>
                    <select class="form-select @error('kelompok_usia_id') is-invalid @enderror" id="kelompok_usia_id"
                        name="kelompok_usia_id" required>
                        <option value="">Pilih Kelompok Usia</option>
                        @foreach ($kelompokUsias as $usia)
                        <option value="{{ $usia->id }}" data-min="{{ $usia->rentang_usia_min }}"
                            data-max="{{ $usia->rentang_usia_max }}"
                            {{ old('kelompok_usia_id') == $usia->id ? 'selected' : '' }}>
                            {{ $usia->nama }} ({{ $usia->rentang_usia_min }}-{{ $usia->rentang_usia_max }}
                            tahun)
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
                    <label for="subkategori_id" class="form-label">Subkategori Lomba <span
                            class="text-danger">*</span></label>
                    <select class="form-select @error('subkategori_id') is-invalid @enderror" id="subkategori_id"
                        name="subkategori_id" required>
                        <option value="">Pilih Subkategori</option>
                        @foreach ($subkategoris as $subkategori)
                        <option value="{{ $subkategori->id }}" data-jenis="{{ $subkategori->jenis }}"
                            {{ old('subkategori_id') == $subkategori->id ? 'selected' : '' }}>
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

            {{-- Kelas Tanding Display --}}
            <div id="kelasTandingSection" style="display: none;">
                <div class="alert alert-success d-flex align-items-center" id="kelasTandingResult">
                    <i class="fas fa-trophy me-2 fs-4"></i>
                    <div>
                        <strong>Kelas Tanding:</strong>
                        <span id="kelasTandingLabel" class="ms-1"></span>
                        <br>
                        <small id="kelasTandingDetail" class="text-muted"></small>
                    </div>
                </div>
            </div>
            <div class="alert alert-warning" id="kelasTandingNotFound" style="display: none;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Kelas Tanding tidak ditemukan</strong> untuk kombinasi berat badan, kelompok usia, dan jenis
                kelamin yang dipilih.
            </div>
            <div class="alert alert-info" id="kelasTandingInfo" style="display: none;">
                <i class="fas fa-info-circle me-2"></i>
                Kelas tanding akan otomatis ditentukan berdasarkan berat badan, kelompok usia, dan jenis kelamin.
            </div>

            {{-- Section Upload Dokumen Wajib --}}
            <hr class="my-4">
            <h5 class="mb-3"><i class="fas fa-file-upload me-2"></i>Upload Dokumen Wajib <span
                    class="text-danger">*</span></h5>
            <p class="text-muted small mb-3">Upload dokumen yang diperlukan untuk verifikasi peserta. Format: JPG, JPEG,
                PNG, atau PDF. Maksimal 5MB per file.</p>

            @if ($errors->has('dokumen_kk') || $errors->has('dokumen_foto'))
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @error('dokumen_kk')
                    <li>{{ $message }}</li>
                    @enderror
                    @error('dokumen_foto')
                    <li>{{ $message }}</li>
                    @enderror
                </ul>
            </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card card-body bg-light">
                        <label for="dokumen_kk" class="form-label">
                            <i class="fas fa-id-card me-1"></i>Kartu Keluarga (KK)
                            <span class="text-danger">*</span>
                        </label>
                        <input type="file"
                            class="form-control @error('dokumen_kk') is-invalid @enderror"
                            id="dokumen_kk" name="dokumen_kk" accept=".jpg,.jpeg,.png,.pdf" required>
                        @error('dokumen_kk')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted mt-1">Format: JPG, JPEG, PNG, atau PDF. Maks 5MB.</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-body bg-light">
                        <label for="dokumen_foto" class="form-label">
                            <i class="fas fa-camera me-1"></i>Foto Peserta
                            <span class="text-danger">*</span>
                        </label>
                        <input type="file"
                            class="form-control @error('dokumen_foto') is-invalid @enderror"
                            id="dokumen_foto" name="dokumen_foto" accept=".jpg,.jpeg,.png" required>
                        @error('dokumen_foto')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted mt-1">Format: JPG, JPEG, atau PNG. Maks 5MB.</small>
                        <div id="foto-preview" class="mt-2" style="display: none;">
                            <img id="foto-preview-img" src="" alt="Preview Foto"
                                class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    </div>
                </div>
            </div>

            {{--
                ===============================================================
                Section Upload Dokumen Tambahan (Opsional)
                Dinonaktifkan sementara. Uncomment block ini untuk mengaktifkan
                kembali fitur upload dokumen tambahan.
                ===============================================================
                
                <hr class="my-4">
                <h5 class="mb-3"><i class="fas fa-plus-circle me-2"></i>Upload Dokumen Tambahan <span
                        class="text-muted fw-normal fs-6">(Opsional)</span></h5>

                @if ($errors->has('dokumen.*'))
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->get('dokumen.*') as $messages)
                                @foreach ($messages as $message)
                                    <li>{{ $message }}</li>
            @endforeach
            @endforeach
            </ul>
    </div>
    @endif

    <div id="dokumen-container">
        <div class="dokumen-row card card-body bg-light mb-2">
            <div class="row align-items-end g-2">
                <div class="col-md-4 col-12">
                    <label class="form-label">Jenis Dokumen</label>
                    <select class="form-select" name="dokumen[0][jenis_dokumen]">
                        <option value="">Pilih Jenis Dokumen</option>
                        <option value="KTP">KTP</option>
                        <option value="Kartu Pelajar">Kartu Pelajar</option>
                        <option value="Akta Lahir">Akta Lahir</option>
                        <option value="Surat Izin">Surat Izin</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="col-md-6 col-12">
                    <label class="form-label">File</label>
                    <input type="file" class="form-control" name="dokumen[0][file]"
                        accept=".jpg,.jpeg,.png,.pdf">
                </div>
                <div class="col-md-2 col-12">
                    <button type="button" class="btn btn-outline-danger btn-remove-dokumen w-100"
                        title="Hapus">
                        <i class="fas fa-times"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-outline-success btn-sm mb-4" id="btn-add-dokumen">
        <i class="fas fa-plus me-1"></i> Tambah Dokumen
    </button>
    --}}

    <div class="d-flex justify-content-between">
        <a href="{{ route('pelatih.peserta.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i> Simpan
        </button>
    </div>
    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // === Dokumen Upload Dynamic Rows (Dinonaktifkan sementara) ===
        // Uncomment block ini jika section Upload Dokumen Tambahan diaktifkan kembali
        /*
        let dokumenIndex = 1;

        $('#btn-add-dokumen').on('click', function() {
            const newRow = `
            <div class="dokumen-row card card-body bg-light mb-2">
                <div class="row align-items-end g-2">
                    <div class="col-md-4 col-12">
                        <label class="form-label">Jenis Dokumen</label>
                        <select class="form-select" name="dokumen[${dokumenIndex}][jenis_dokumen]">
                            <option value="">Pilih Jenis Dokumen</option>
                            <option value="KTP">KTP</option>
                            <option value="Kartu Pelajar">Kartu Pelajar</option>
                            <option value="Akta Lahir">Akta Lahir</option>
                            <option value="Surat Izin">Surat Izin</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label">File</label>
                        <input type="file" class="form-control" name="dokumen[${dokumenIndex}][file]" accept=".jpg,.jpeg,.png,.pdf">
                    </div>
                    <div class="col-md-2 col-12">
                        <button type="button" class="btn btn-outline-danger btn-remove-dokumen w-100" title="Hapus">
                            <i class="fas fa-times"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        `;
            $('#dokumen-container').append(newRow);
            dokumenIndex++;
        });

        // Remove dokumen row
        $(document).on('click', '.btn-remove-dokumen', function() {
            $(this).closest('.dokumen-row').remove();
        });
        */

        // === Foto Preview ===
        $('#dokumen_foto').on('change', function() {
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#foto-preview-img').attr('src', e.target.result);
                    $('#foto-preview').show();
                };
                reader.readAsDataURL(file);
            } else {
                $('#foto-preview').hide();
            }
        });

        // === NIK Numeric Only ===
        $('#nik').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 16);
        });

        // === Age Validation ===
        function validateAge() {
            const tanggalLahir = $('#tanggal_lahir').val();
            const kelompokUsiaId = $('#kelompok_usia_id').val();

            if (tanggalLahir && kelompokUsiaId) {
                const selectedOption = $(`#kelompok_usia_id option[value="${kelompokUsiaId}"]`);
                const minUsia = selectedOption.data('min');
                const maxUsia = selectedOption.data('max');
                const usiaName = selectedOption.text().split('(')[0].trim();

                // Calculate age
                const birthDate = new Date(tanggalLahir);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();

                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                // Check if age is within range
                if (age < minUsia || age > maxUsia) {
                    alert(
                        `Usia peserta (${age} tahun) tidak sesuai dengan kelompok usia ${usiaName} (${minUsia}-${maxUsia} tahun)`
                    );
                    return false;
                }
            }
            return true;
        }

        // === Subkategori Check & Kelas Tanding Lookup ===
        function checkSubkategori() {
            const subkategoriId = $('#subkategori_id').val();
            const jenisTanding = $('#subkategori_id option:selected').data('jenis');

            if (subkategoriId && jenisTanding === 'tanding') {
                $('#kelasTandingInfo').show();
                lookupKelasTanding();
            } else {
                $('#kelasTandingInfo').hide();
                $('#kelasTandingSection').hide();
                $('#kelasTandingNotFound').hide();
            }
        }

        // === Kelas Tanding AJAX Lookup ===
        function lookupKelasTanding() {
            const kelompokUsiaId = $('#kelompok_usia_id').val();
            const jenisKelamin = $('#jenis_kelamin').val();
            const beratBadan = $('#berat_badan').val();
            const subkategoriId = $('#subkategori_id').val();
            const jenisTanding = $('#subkategori_id option:selected').data('jenis');

            // Only lookup if subkategori is "tanding" and all fields are filled
            if (!subkategoriId || jenisTanding !== 'tanding' || !kelompokUsiaId || !jenisKelamin ||
                !beratBadan) {
                $('#kelasTandingSection').hide();
                $('#kelasTandingNotFound').hide();
                return;
            }

            $.ajax({
                url: '{{ route("pelatih.peserta.kelas-tanding") }}',
                method: 'GET',
                data: {
                    kelompok_usia_id: kelompokUsiaId,
                    jenis_kelamin: jenisKelamin,
                    berat_badan: beratBadan,
                },
                success: function(response) {
                    if (response.kelas_tanding) {
                        const kt = response.kelas_tanding;
                        let label = kt.label_keterangan || ('Kelas ' + kt.kode_kelas);
                        let detail = '';

                        if (kt.is_open_class) {
                            detail = 'Kelas Bebas (Open Class)';
                        } else {
                            detail = `Berat ${kt.berat_min} - ${kt.berat_max} Kg`;
                        }

                        $('#kelasTandingLabel').text(label);
                        $('#kelasTandingDetail').text(detail);
                        $('#kelasTandingSection').show();
                        $('#kelasTandingNotFound').hide();
                        $('#kelasTandingInfo').hide();
                    } else {
                        $('#kelasTandingSection').hide();
                        $('#kelasTandingNotFound').show();
                        $('#kelasTandingInfo').hide();
                    }
                },
                error: function() {
                    $('#kelasTandingSection').hide();
                    $('#kelasTandingNotFound').hide();
                }
            });
        }

        // Validate on form submit
        $('form').on('submit', function(e) {
            if (!validateAge()) {
                e.preventDefault();
                return false;
            }

            // Remove empty dokumen rows before submit (rows without file selected)
            $('.dokumen-row').each(function() {
                const fileInput = $(this).find('input[type="file"]');
                const selectInput = $(this).find('select');
                if (!fileInput.val() && !selectInput.val()) {
                    $(this).remove();
                }
            });
        });

        // Check on change
        $('#tanggal_lahir, #kelompok_usia_id').on('change', function() {
            validateAge();
            lookupKelasTanding();
        });
        $('#subkategori_id').on('change', checkSubkategori);
        $('#jenis_kelamin').on('change', lookupKelasTanding);
        $('#berat_badan').on('change', lookupKelasTanding);

        // Check on page load
        checkSubkategori();
    });
</script>
@endpush