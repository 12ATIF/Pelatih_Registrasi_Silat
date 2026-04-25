@extends('layouts.pelatih')

@section('title', 'Detail Pembayaran')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pelatih.pembayaran.index') }}">Pembayaran</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@push('styles')
<style>
    .status-card {
        border-radius: 16px;
        overflow: hidden;
    }
    .status-header-lunas      { background: linear-gradient(135deg, #198754, #20c997); }
    .status-header-menunggu   { background: linear-gradient(135deg, #fd7e14, #ffc107); }
    .status-header-belum      { background: linear-gradient(135deg, #dc3545, #e35d6a); }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.9rem;
    }
    .info-item:last-child { border-bottom: none; }
    .info-item .label { color: #6c757d; }
    .info-item .value { font-weight: 600; color: #212529; }

    .rekening-card {
        background: linear-gradient(135deg, #1a1a2e, #16213e);
        border-radius: 16px;
        color: white;
        padding: 24px;
        position: relative;
        overflow: hidden;
    }
    .rekening-card::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 150px; height: 150px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .rekening-card::after {
        content: '';
        position: absolute;
        bottom: -60px; left: -20px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }
    .rekening-bank-name {
        font-size: 0.8rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        opacity: 0.7;
    }
    .rekening-number {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 4px;
        margin: 8px 0;
    }
    .rekening-atas-nama {
        font-size: 0.85rem;
        opacity: 0.8;
    }
    .copy-btn {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        font-size: 0.75rem;
        border-radius: 20px;
        padding: 3px 12px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .copy-btn:hover { background: rgba(255,255,255,0.25); color: white; }

    .step-flow {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .step-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 500;
    }
    .step-item.done    { background: #d1fae5; color: #065f46; }
    .step-item.active  { background: #fef3c7; color: #92400e; }
    .step-item.pending { background: #f3f4f6; color: #9ca3af; }
    .step-divider { color: #d1d5db; font-size: 0.8rem; }

    .bukti-img-wrapper {
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        padding: 12px;
        background: #f8f9fa;
    }
    .bukti-img-wrapper img {
        border-radius: 8px;
        max-height: 350px;
        width: 100%;
        object-fit: contain;
    }

    .table-tagihan th { background: #f8f9fa; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6c757d; }
    .table-tagihan td { vertical-align: middle; font-size: 0.9rem; }
    .table-tagihan tfoot th { background: #d1fae5; color: #065f46; font-size: 0.95rem; }

    .upload-area {
        border: 2px dashed #ced4da;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #fafafa;
    }
    .upload-area:hover, .upload-area.dragover {
        border-color: #0d6efd;
        background: #f0f4ff;
    }
    .upload-area input[type=file] { display: none; }

    @media (max-width: 767.98px) {
        .rekening-number { font-size: 1.1rem; letter-spacing: 2px; }
        .rekening-card { padding: 18px; }
        .step-flow { gap: 4px; justify-content: flex-start; overflow-x: auto; flex-wrap: nowrap; padding-bottom: 4px; }
        .step-flow::-webkit-scrollbar { display: none; }
        .step-item { font-size: 0.72rem; padding: 5px 10px; white-space: nowrap; }
        .step-divider { display: none; }
        .info-item { flex-direction: column; align-items: flex-start; gap: 2px; }
        .info-item .value { font-size: 0.9rem; }
        .d-flex.gap-2.flex-wrap .btn { width: 100%; justify-content: center; }
        .bukti-img-wrapper img { max-height: 240px; }
        .table-tagihan th, .table-tagihan td { font-size: 0.8rem; padding: 8px 6px; }
        .table-tagihan .ps-4 { padding-left: 10px !important; }
        .table-tagihan .pe-4 { padding-right: 10px !important; }
        .modal-dialog { margin: 10px; }
        .upload-area { padding: 20px; }
    }
</style>
@endpush

@section('content')

{{-- Session Alert --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
    <i class="fas fa-check-circle fa-lg"></i>
    <span>{{ session('success') }}</span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
    <i class="fas fa-exclamation-circle fa-lg"></i>
    <span>{{ session('error') }}</span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">

    {{-- ===== KOLOM KIRI: Ringkasan ===== --}}
    <div class="col-lg-4">
        <div class="card shadow-sm status-card mb-4">
            {{-- Header berwarna sesuai status --}}
            <div class="text-white text-center py-4
                @if($pembayaran->status == 'lunas') status-header-lunas
                @elseif($pembayaran->status == 'menunggu_verifikasi') status-header-menunggu
                @else status-header-belum @endif">
                <div class="mb-2">
                    @if($pembayaran->status == 'lunas')
                        <i class="fas fa-check-circle fa-3x"></i>
                    @elseif($pembayaran->status == 'menunggu_verifikasi')
                        <i class="fas fa-hourglass-half fa-3x"></i>
                    @else
                        <i class="fas fa-file-invoice-dollar fa-3x"></i>
                    @endif
                </div>
                <h5 class="mb-0 fw-bold">{{ $kontingen->nama }}</h5>
                <small class="opacity-75">{{ $kontingen->asal_daerah }}</small>
                <div class="mt-2">
                    <span class="badge bg-white
                        @if($pembayaran->status == 'lunas') text-success
                        @elseif($pembayaran->status == 'menunggu_verifikasi') text-warning
                        @else text-danger @endif fw-semibold px-3 py-2">
                        @if($pembayaran->status == 'lunas') ✓ Lunas
                        @elseif($pembayaran->status == 'menunggu_verifikasi') ⏳ Menunggu Verifikasi
                        @else ✗ Belum Bayar @endif
                    </span>
                </div>
            </div>

            <div class="card-body">
                {{-- Total Tagihan --}}
                <div class="text-center py-3 mb-3" style="background:#f8f9fa; border-radius:12px;">
                    <div class="text-muted small mb-1">Total Tagihan</div>
                    <div class="fw-bold" style="font-size:1.6rem; color:#212529;">
                        Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}
                    </div>
                </div>

                {{-- Info Items --}}
                <div class="info-item">
                    <span class="label"><i class="fas fa-users me-1"></i> Jumlah Peserta</span>
                    <span class="value">{{ $kontingen->pesertas->count() }} orang</span>
                </div>
                <div class="info-item">
                    <span class="label"><i class="fas fa-calendar me-1"></i> Dibuat</span>
                    <span class="value">{{ $pembayaran->created_at->format('d/m/Y') }}</span>
                </div>
                @if($pembayaran->verified_at)
                <div class="info-item">
                    <span class="label"><i class="fas fa-check me-1"></i> Diverifikasi</span>
                    <span class="value text-success">{{ $pembayaran->verified_at->format('d/m/Y H:i') }}</span>
                </div>
                @endif

                {{-- Tombol Hitung Ulang --}}
                @if($pembayaran->status != 'lunas')
                <form action="{{ route('pelatih.pembayaran.recalculate', $pembayaran->id) }}" method="POST" class="mt-3 js-loading-form">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary w-100 btn-sm js-submit-btn">
                        <span class="btn-label"><i class="fas fa-sync me-1"></i> Hitung Ulang Tagihan</span>
                        <span class="btn-loading d-none"><span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Memproses...</span>
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    {{-- ===== KOLOM KANAN ===== --}}
    <div class="col-lg-8">

        {{-- Step Flow Indicator --}}
        <div class="step-flow mb-2">
            <span class="step-item done"><i class="fas fa-check-circle"></i> Daftar Peserta</span>
            <span class="step-divider">›</span>
            <span class="step-item done"><i class="fas fa-check-circle"></i> Tagihan Dibuat</span>
            <span class="step-divider">›</span>
            <span class="step-item {{ in_array($pembayaran->status, ['menunggu_verifikasi','lunas']) ? 'done' : 'active' }}">
                <i class="fas fa-{{ in_array($pembayaran->status, ['menunggu_verifikasi','lunas']) ? 'check-circle' : 'upload' }}"></i> Upload Bukti
            </span>
            <span class="step-divider">›</span>
            <span class="step-item {{ $pembayaran->status == 'lunas' ? 'done' : 'pending' }}">
                <i class="fas fa-{{ $pembayaran->status == 'lunas' ? 'check-circle' : 'clock' }}"></i> Terverifikasi
            </span>
        </div>

        {{-- ===== STATUS: LUNAS ===== --}}
        @if($pembayaran->status == 'lunas')
        <div class="card shadow-sm mb-4" style="border-left: 4px solid #198754;">
            <div class="card-body">
                <div class="text-center py-3">
                    <div class="mb-3" style="font-size: 4rem;">🎉</div>
                    <h4 class="text-success fw-bold">Pembayaran Lunas!</h4>
                    <p class="text-muted">Pembayaran telah diverifikasi oleh admin. Peserta dapat mengikuti pertandingan.</p>
                </div>
                @if($pembayaran->bukti_transfer)
                <hr>
                <h6 class="fw-semibold mb-3"><i class="fas fa-image me-1"></i> Bukti Transfer</h6>
                <div class="bukti-img-wrapper text-center">
                    <img src="{{ Storage::url($pembayaran->bukti_transfer) }}" alt="Bukti Transfer">
                </div>
                @endif
            </div>
        </div>

        {{-- ===== STATUS: MENUNGGU VERIFIKASI ===== --}}
        @elseif($pembayaran->status == 'menunggu_verifikasi')
        <div class="card shadow-sm mb-4" style="border-left: 4px solid #ffc107;">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded" style="background:#fffbeb;">
                    <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                    <div>
                        <div class="fw-bold">Menunggu Verifikasi Admin</div>
                        <div class="text-muted small">Bukti transfer Anda sudah diterima dan sedang diperiksa.</div>
                    </div>
                </div>

                @if($pembayaran->bukti_transfer)
                <h6 class="fw-semibold mb-2"><i class="fas fa-image me-1"></i> Bukti Transfer Diunggah</h6>
                <div class="bukti-img-wrapper text-center mb-3">
                    <img src="{{ Storage::url($pembayaran->bukti_transfer) }}" alt="Bukti Transfer">
                </div>
                @endif

                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#uploadBuktiModal">
                        <i class="fas fa-upload me-1"></i> Upload Ulang
                    </button>
                    <a href="https://wa.me/6285722703725?text={{ urlencode('Halo, saya ' . auth()->guard('pelatih')->user()->nama . ' sudah upload bukti transfer pembayaran untuk kontingen ' . $kontingen->nama . '. Mohon segera diverifikasi. Terima kasih.') }}"
                        target="_blank" class="btn btn-success">
                        <i class="fab fa-whatsapp me-1"></i> Konfirmasi ke WhatsApp
                    </a>
                </div>
            </div>
        </div>

        {{-- ===== STATUS: BELUM BAYAR ===== --}}
        @else
        <div class="card shadow-sm mb-4" style="border-left: 4px solid #dc3545;">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="fas fa-university me-1"></i> Informasi Rekening Pembayaran</h6>

                {{-- Bank Card --}}
                <div class="rekening-card mb-4">
                    <div class="rekening-bank-name">SeaBank Indonesia</div>
                    <div class="rekening-number" id="norek">9014 6338 1712</div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rekening-atas-nama">a/n Negi Rahmah Azizia</div>
                        <button class="copy-btn" onclick="copyNorek()">
                            <i class="fas fa-copy me-1"></i> Salin
                        </button>
                    </div>
                </div>

                <div class="alert alert-warning d-flex gap-2 align-items-start py-2 mb-4" style="border-radius:10px;">
                    <i class="fas fa-info-circle mt-1"></i>
                    <small>Transfer sesuai nominal tagihan, lalu upload bukti transfer dan konfirmasi ke WhatsApp panitia.</small>
                </div>

                <button type="button" class="btn btn-danger px-4" data-bs-toggle="modal" data-bs-target="#uploadBuktiModal">
                    <i class="fas fa-upload me-2"></i> Upload Bukti Transfer
                </button>
            </div>
        </div>
        @endif

        {{-- ===== DETAIL TAGIHAN ===== --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center gap-2">
                <i class="fas fa-receipt text-primary"></i>
                <h6 class="m-0 fw-bold">Rincian Tagihan</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-tagihan mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">#</th>
                                <th class="py-3">Nama Peserta</th>
                                <th class="py-3">Subkategori</th>
                                <th class="py-3 text-end pe-4">Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalBiaya = 0; $no = 1; @endphp
                            @foreach($detailTagihan as $item)
                            <tr>
                                <td class="ps-4 text-muted">{{ $no++ }}</td>
                                <td class="fw-semibold">{{ $item['nama'] }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $item['subkategori'] }}</span></td>
                                <td class="text-end pe-4">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                            </tr>
                            @php $totalBiaya += $item['harga']; @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end py-3 pe-4">Total Tagihan</th>
                                <th class="text-end pe-4 py-3 text-success" style="font-size:1rem;">
                                    Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ===== MODAL UPLOAD ===== --}}
<div class="modal fade" id="uploadBuktiModal" tabindex="-1" aria-labelledby="uploadBuktiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="uploadBuktiModalLabel">
                    <i class="fas fa-upload me-2 text-success"></i>Upload Bukti Transfer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pelatih.pembayaran.upload', $pembayaran->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body pt-2">
                    <div class="upload-area" id="uploadArea" onclick="document.getElementById('bukti_transfer').click()">
                        <input type="file" id="bukti_transfer" name="bukti_transfer" accept=".jpg,.jpeg,.png" required>
                        <div id="uploadPlaceholder">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                            <div class="fw-semibold">Klik atau seret file ke sini</div>
                            <div class="text-muted small mt-1">JPG, JPEG, PNG — Maks. 5MB</div>
                        </div>
                        <div id="uploadPreview" style="display:none;">
                            <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height:200px;">
                            <div class="mt-2 text-muted small" id="fileName"></div>
                        </div>
                    </div>
                    <div id="bukti_transfer_error" class="alert alert-danger mt-3 py-2" style="display:none; border-radius:10px;">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <span id="bukti_transfer_error_msg"></span>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4" id="btn_upload_bukti" disabled>
                        <span class="btn-label"><i class="fas fa-upload me-1"></i> Upload</span>
                        <span class="btn-loading d-none"><span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Mengupload...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
@if(session('show_wa'))
const waMsg = encodeURIComponent('Halo, saya {{ auth()->guard("pelatih")->user()->nama }} sudah upload bukti transfer pembayaran untuk kontingen {{ $kontingen->nama }}. Mohon segera diverifikasi. Terima kasih.');
window.open('https://wa.me/6285722703725?text=' + waMsg, '_blank');
@endif

// Upload area interactivity
const fileInput   = document.getElementById('bukti_transfer');
const uploadArea  = document.getElementById('uploadArea');
const placeholder = document.getElementById('uploadPlaceholder');
const preview     = document.getElementById('uploadPreview');
const previewImg  = document.getElementById('previewImg');
const fileNameEl  = document.getElementById('fileName');
const errorDiv    = document.getElementById('bukti_transfer_error');
const errorMsg    = document.getElementById('bukti_transfer_error_msg');
const submitBtn   = document.getElementById('btn_upload_bukti');

fileInput.addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;

    if (file.size > 5 * 1024 * 1024) {
        errorMsg.textContent = `Ukuran file terlalu besar (${(file.size/1024/1024).toFixed(2)} MB). Maksimal 5MB.`;
        errorDiv.style.display = 'block';
        submitBtn.disabled = true;
        this.value = '';
        return;
    }

    errorDiv.style.display = 'none';
    submitBtn.disabled = false;

    const reader = new FileReader();
    reader.onload = e => {
        previewImg.src = e.target.result;
        fileNameEl.textContent = file.name;
        placeholder.style.display = 'none';
        preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
});

// Drag & drop
uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('dragover'); });
uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
uploadArea.addEventListener('drop', e => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    fileInput.files = e.dataTransfer.files;
    fileInput.dispatchEvent(new Event('change'));
});

// Loading state on upload submit — prevent double submit
const uploadForm = fileInput.closest('form');
if (uploadForm) {
    uploadForm.addEventListener('submit', function() {
        if (submitBtn.disabled) return;
        submitBtn.disabled = true;
        const lbl = submitBtn.querySelector('.btn-label');
        const ldg = submitBtn.querySelector('.btn-loading');
        if (lbl) lbl.classList.add('d-none');
        if (ldg) ldg.classList.remove('d-none');
    });
}

// Copy norek
function copyNorek() {
    navigator.clipboard.writeText('901463381712').then(() => {
        const btn = document.querySelector('.copy-btn');
        btn.innerHTML = '<i class="fas fa-check me-1"></i> Tersalin!';
        setTimeout(() => btn.innerHTML = '<i class="fas fa-copy me-1"></i> Salin', 2000);
    });
}
</script>
@endpush
