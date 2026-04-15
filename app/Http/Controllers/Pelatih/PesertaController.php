<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\DokumenPeserta;
use App\Models\Peserta;
use App\Models\Kontingen;
use App\Models\SubkategoriLomba;
use App\Models\KelompokUsia;
use App\Models\KelasTanding;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PesertaController extends Controller
{
    /**
     * Get the currently authenticated pelatih.
     *
     * @return \App\Models\Pelatih
     */
    protected function getPelatih()
    {
        /** @var \App\Models\Pelatih $pelatih */
        $pelatih = Auth::guard('pelatih')->user();
        return $pelatih;
    }

    public function index(Request $request, $kontingenId = null)
    {
        $query = Peserta::query();
        
        // Filter berdasarkan kontingen milik pelatih
        $kontingens = $this->getPelatih()->kontingens()->pluck('id');
        $query->whereIn('kontingen_id', $kontingens);
        
        // Filter berdasarkan kontingen tertentu jika ada
        if ($kontingenId) {
            $query->where('kontingen_id', $kontingenId);
        } elseif ($request->has('kontingen_id') && $request->kontingen_id) {
            $query->where('kontingen_id', $request->kontingen_id);
        }
        
        // Filter tambahan
        if ($request->has('status_verifikasi') && $request->status_verifikasi) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }
        
        if ($request->has('subkategori_id') && $request->subkategori_id) {
            $query->where('subkategori_id', $request->subkategori_id);
        }
        
        if ($request->has('kelompok_usia_id') && $request->kelompok_usia_id) {
            $query->where('kelompok_usia_id', $request->kelompok_usia_id);
        }
        
        // Load relationships
        $pesertas = $query->with(['kontingen', 'subkategoriLomba.kategoriLomba', 'kelompokUsia', 'kelasTanding'])->get();
        
        if ($request->expectsJson()) {
            return response()->json($pesertas);
        }
        
        // Data untuk filter
        $kontingenList = $this->getPelatih()->kontingens;
        $subkategoris = SubkategoriLomba::with('kategoriLomba')->get();
        $kelompokUsias = KelompokUsia::all();
        
        return view('pelatih.peserta.index', compact('pesertas', 'kontingenList', 'subkategoris', 'kelompokUsias'));
    }
    
    public function create()
    {
        $kontingens = $this->getPelatih()->kontingens;
        $subkategoris = SubkategoriLomba::with('kategoriLomba')->get();
        $kelompokUsias = KelompokUsia::all();
        $kelasTandings = KelasTanding::with('kelompokUsia')->get();
        
        return view('pelatih.peserta.create', compact('kontingens', 'subkategoris', 'kelompokUsias', 'kelasTandings'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kontingen_id' => 'required|exists:kontingen,id',
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|size:16',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'berat_badan' => 'required|numeric|min:0',
            'tinggi_badan' => 'required|numeric|min:0',
            'subkategori_id' => 'required|exists:subkategori_lomba,id',
            'kelompok_usia_id' => 'required|exists:kelompok_usia,id',
            // Dokumen wajib
            'dokumen_kk' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'dokumen_foto' => 'required|file|mimes:jpeg,png,jpg|max:5120',
            // Dokumen tambahan (opsional)
            'dokumen' => 'nullable|array',
            'dokumen.*.jenis_dokumen' => 'required_with:dokumen.*.file|string|max:255',
            'dokumen.*.file' => 'required_with:dokumen.*.jenis_dokumen|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ], [
            'nik.required' => 'Nomor NIK wajib diisi.',
            'nik.size' => 'Nomor NIK harus 16 digit.',
            'tinggi_badan.required' => 'Tinggi badan wajib diisi.',
            'dokumen_kk.required' => 'Upload Kartu Keluarga (KK) wajib.',
            'dokumen_kk.mimes' => 'File KK harus berformat JPG, JPEG, PNG, atau PDF.',
            'dokumen_kk.max' => 'File KK maksimal 5MB.',
            'dokumen_foto.required' => 'Upload Foto Peserta wajib.',
            'dokumen_foto.mimes' => 'Foto Peserta harus berformat JPG, JPEG, atau PNG.',
            'dokumen_foto.max' => 'Foto Peserta maksimal 5MB.',
        ]);
        
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }
        
        // Periksa apakah kontingen milik pelatih
        $kontingenIds = $this->getPelatih()->kontingens()->pluck('id')->toArray();
        if (!in_array($request->kontingen_id, $kontingenIds)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Anda tidak memiliki akses ke kontingen ini.'], 403);
            }
            return back()->with('error', 'Anda tidak memiliki akses ke kontingen ini.');
        }
        
        // Validasi usia sesuai dengan kelompok usia
        $kelompokUsia = KelompokUsia::findOrFail($request->kelompok_usia_id);
        $usia = Carbon::parse($request->tanggal_lahir)->age;
        
        if ($usia < $kelompokUsia->rentang_usia_min || $usia > $kelompokUsia->rentang_usia_max) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Usia peserta ({$usia} tahun) tidak sesuai dengan kelompok usia {$kelompokUsia->nama} ({$kelompokUsia->rentang_usia_min}-{$kelompokUsia->rentang_usia_max} tahun)."
                ], 422);
            }
            return back()->with('error', "Usia peserta ({$usia} tahun) tidak sesuai dengan kelompok usia {$kelompokUsia->nama} ({$kelompokUsia->rentang_usia_min}-{$kelompokUsia->rentang_usia_max} tahun).")->withInput();
        }
        
        // Validasi subkategori dengan kelompok usia
        $subkategori = SubkategoriLomba::findOrFail($request->subkategori_id);
        $kelompokUsiaIds = $subkategori->kelompokUsias()->pluck('kelompok_usia.id')->toArray();
        
        if (!in_array($request->kelompok_usia_id, $kelompokUsiaIds)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Subkategori {$subkategori->nama} tidak tersedia untuk kelompok usia {$kelompokUsia->nama}."
                ], 422);
            }
            return back()->with('error', "Subkategori {$subkategori->nama} tidak tersedia untuk kelompok usia {$kelompokUsia->nama}.")->withInput();
        }
        
        // Tentukan kelas tanding jika jenis kategori adalah tanding
        $kelasTandingId = null;
        if ($subkategori->jenis === 'tanding') {
            $jenisKelamin = $request->jenis_kelamin === 'L' ? 'putra' : 'putri';
            $kelasTanding = KelasTanding::where('kelompok_usia_id', $request->kelompok_usia_id)
                ->where('jenis_kelamin', $jenisKelamin)
                ->where(function($query) use ($request) {
                    $query->where(function($q) use ($request) {
                        $q->where('berat_min', '<=', $request->berat_badan)
                          ->where('berat_max', '>=', $request->berat_badan);
                    })->orWhere('is_open_class', true);
                })
                ->first();
                
            if ($kelasTanding) {
                $kelasTandingId = $kelasTanding->id;
            }
        }
        
        // Simpan peserta
        $peserta = Peserta::create([
            'kontingen_id' => $request->kontingen_id,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'subkategori_id' => $request->subkategori_id,
            'kelompok_usia_id' => $request->kelompok_usia_id,
            'kelas_tanding_id' => $kelasTandingId,
            'is_manual_override' => false,
            'status_verifikasi' => 'pending',
        ]);
        
        // Upload dokumen wajib (KK dan Foto)
        $dokumenCount = 0;
        
        // Upload Kartu Keluarga
        if ($request->hasFile('dokumen_kk')) {
            $fileKk = $request->file('dokumen_kk');
            $fileNameKk = time() . '_kk_' . $fileKk->getClientOriginalName();
            $filePathKk = $fileKk->storeAs('dokumen_peserta/' . $peserta->id, $fileNameKk, 'public');
            DokumenPeserta::create([
                'peserta_id' => $peserta->id,
                'jenis_dokumen' => 'Kartu Keluarga',
                'file_path' => $filePathKk,
            ]);
            $dokumenCount++;
        }
        
        // Upload Foto Peserta
        if ($request->hasFile('dokumen_foto')) {
            $fileFoto = $request->file('dokumen_foto');
            $fileNameFoto = time() . '_foto_' . $fileFoto->getClientOriginalName();
            $filePathFoto = $fileFoto->storeAs('dokumen_peserta/' . $peserta->id, $fileNameFoto, 'public');
            DokumenPeserta::create([
                'peserta_id' => $peserta->id,
                'jenis_dokumen' => 'Foto Peserta',
                'file_path' => $filePathFoto,
            ]);
            $dokumenCount++;
        }
        
        // Upload dokumen tambahan (opsional)
        if ($request->has('dokumen')) {
            foreach ($request->input('dokumen') as $index => $dokumenData) {
                if ($request->hasFile("dokumen.{$index}.file") && !empty($dokumenData['jenis_dokumen'])) {
                    $file = $request->file("dokumen.{$index}.file");
                    $fileName = time() . '_' . $index . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('dokumen_peserta/' . $peserta->id, $fileName, 'public');
                    
                    DokumenPeserta::create([
                        'peserta_id' => $peserta->id,
                        'jenis_dokumen' => $dokumenData['jenis_dokumen'],
                        'file_path' => $filePath,
                    ]);
                    $dokumenCount++;
                }
            }
        }
        
        $message = 'Peserta berhasil ditambahkan.';
        if ($dokumenCount > 0) {
            $message = "Peserta berhasil ditambahkan beserta {$dokumenCount} dokumen.";
        }
        
        if ($request->expectsJson()) {
            return response()->json(['message' => $message, 'peserta' => $peserta], 201);
        }
        
        return redirect()->route('pelatih.peserta.index')->with('success', $message);
    }
    
    public function show($id)
    {
        // Cari peserta dan pastikan milik pelatih
        $kontingenIds = $this->getPelatih()->kontingens()->pluck('id')->toArray();
        $peserta = Peserta::with(['kontingen', 'subkategoriLomba.kategoriLomba', 'kelompokUsia', 'kelasTanding', 'dokumenPesertas'])
            ->whereIn('kontingen_id', $kontingenIds)
            ->findOrFail($id);
        
        if (request()->expectsJson()) {
            return response()->json($peserta);
        }
        
        return view('pelatih.peserta.show', compact('peserta'));
    }
    
    public function edit($id)
    {
        // Cari peserta dan pastikan milik pelatih
        $kontingenIds = $this->getPelatih()->kontingens()->pluck('id')->toArray();
        $peserta = Peserta::whereIn('kontingen_id', $kontingenIds)->findOrFail($id);
        
        $kontingens = $this->getPelatih()->kontingens;
        $subkategoris = SubkategoriLomba::with('kategoriLomba')->get();
        $kelompokUsias = KelompokUsia::all();
        
        return view('pelatih.peserta.edit', compact('peserta', 'kontingens', 'subkategoris', 'kelompokUsias'));
    }
    
    public function update(Request $request, $id)
    {
        // Cari peserta dan pastikan milik pelatih
        $kontingenIds = $this->getPelatih()->kontingens()->pluck('id')->toArray();
        $peserta = Peserta::whereIn('kontingen_id', $kontingenIds)->findOrFail($id);
        
        // Hanya dapat mengedit jika status masih pending
        if ($peserta->status_verifikasi !== 'pending') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Tidak dapat mengedit peserta yang sudah diverifikasi.'], 422);
            }
            return back()->with('error', 'Tidak dapat mengedit peserta yang sudah diverifikasi.');
        }
        
        $validator = Validator::make($request->all(), [
            'kontingen_id' => 'required|exists:kontingen,id',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'berat_badan' => 'required|numeric|min:0',
            'subkategori_id' => 'required|exists:subkategori_lomba,id',
            'kelompok_usia_id' => 'required|exists:kelompok_usia,id',
        ]);
        
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }
        
        // Periksa apakah kontingen milik pelatih
        if (!in_array($request->kontingen_id, $kontingenIds)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Anda tidak memiliki akses ke kontingen ini.'], 403);
            }
            return back()->with('error', 'Anda tidak memiliki akses ke kontingen ini.');
        }
        
        // Validasi usia sesuai dengan kelompok usia
        $kelompokUsia = KelompokUsia::findOrFail($request->kelompok_usia_id);
        $usia = Carbon::parse($request->tanggal_lahir)->age;
        
        if ($usia < $kelompokUsia->rentang_usia_min || $usia > $kelompokUsia->rentang_usia_max) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Usia peserta ({$usia} tahun) tidak sesuai dengan kelompok usia {$kelompokUsia->nama} ({$kelompokUsia->rentang_usia_min}-{$kelompokUsia->rentang_usia_max} tahun)."
                ], 422);
            }
            return back()->with('error', "Usia peserta ({$usia} tahun) tidak sesuai dengan kelompok usia {$kelompokUsia->nama} ({$kelompokUsia->rentang_usia_min}-{$kelompokUsia->rentang_usia_max} tahun).")->withInput();
        }
        
        // Validasi subkategori dengan kelompok usia
        $subkategori = SubkategoriLomba::findOrFail($request->subkategori_id);
        $kelompokUsiaIds = $subkategori->kelompokUsias()->pluck('kelompok_usia.id')->toArray();
        
        if (!in_array($request->kelompok_usia_id, $kelompokUsiaIds)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Subkategori {$subkategori->nama} tidak tersedia untuk kelompok usia {$kelompokUsia->nama}."
                ], 422);
            }
            return back()->with('error', "Subkategori {$subkategori->nama} tidak tersedia untuk kelompok usia {$kelompokUsia->nama}.")->withInput();
        }
        
        // Tentukan kelas tanding jika jenis kategori adalah tanding
        $kelasTandingId = null;
        if ($subkategori->jenis === 'tanding') {
            $jenisKelamin = $request->jenis_kelamin === 'L' ? 'putra' : 'putri';
            $kelasTanding = KelasTanding::where('kelompok_usia_id', $request->kelompok_usia_id)
               ->where('jenis_kelamin', $jenisKelamin)
               ->where(function($query) use ($request) {
                   $query->where(function($q) use ($request) {
                       $q->where('berat_min', '<=', $request->berat_badan)
                         ->where('berat_max', '>=', $request->berat_badan);
                   })->orWhere('is_open_class', true);
               })
               ->first();
               
           if ($kelasTanding) {
               $kelasTandingId = $kelasTanding->id;
           }
       }
       
       // Reset status verifikasi karena ada perubahan data
       $peserta->update([
           'kontingen_id' => $request->kontingen_id,
           'nama' => $request->nama,
           'jenis_kelamin' => $request->jenis_kelamin,
           'tanggal_lahir' => $request->tanggal_lahir,
           'berat_badan' => $request->berat_badan,
           'subkategori_id' => $request->subkategori_id,
           'kelompok_usia_id' => $request->kelompok_usia_id,
           'kelas_tanding_id' => $kelasTandingId,
           'is_manual_override' => false,
           'status_verifikasi' => 'pending',
       ]);
       
       if ($request->expectsJson()) {
           return response()->json(['message' => 'Peserta berhasil diperbarui.', 'peserta' => $peserta]);
       }
       
       return redirect()->route('pelatih.peserta.index')->with('success', 'Peserta berhasil diperbarui.');
   }
   
   public function destroy($id)
   {
       // Cari peserta dan pastikan milik pelatih
       $kontingenIds = $this->getPelatih()->kontingens()->pluck('id')->toArray();
       $peserta = Peserta::whereIn('kontingen_id', $kontingenIds)->findOrFail($id);
       
       // Hanya dapat menghapus jika status masih pending
       if ($peserta->status_verifikasi !== 'pending') {
           if (request()->expectsJson()) {
               return response()->json(['message' => 'Tidak dapat menghapus peserta yang sudah diverifikasi.'], 422);
           }
           return back()->with('error', 'Tidak dapat menghapus peserta yang sudah diverifikasi.');
       }
       
       // Hapus dokumen terkait terlebih dahulu
       foreach ($peserta->dokumenPesertas as $dokumen) {
           $dokumen->delete();
       }
       
       $peserta->delete();
       
       if (request()->expectsJson()) {
           return response()->json(['message' => 'Peserta berhasil dihapus.']);
       }
       
       return redirect()->route('pelatih.peserta.index')->with('success', 'Peserta berhasil dihapus.');
   }
   
   /**
    * Get kelas tanding via AJAX based on kelompok_usia_id, jenis_kelamin, and berat_badan.
    */
   public function getKelasTanding(Request $request)
   {
       $kelompokUsiaId = $request->query('kelompok_usia_id');
       $jenisKelamin = $request->query('jenis_kelamin');
       $beratBadan = $request->query('berat_badan');
       
       if (!$kelompokUsiaId || !$jenisKelamin || !$beratBadan) {
           return response()->json(['kelas_tanding' => null]);
       }
       
       $jenisKelaminKelas = $jenisKelamin === 'L' ? 'putra' : 'putri';
       
       $kelasTanding = KelasTanding::where('kelompok_usia_id', $kelompokUsiaId)
           ->where('jenis_kelamin', $jenisKelaminKelas)
           ->where(function($query) use ($beratBadan) {
               $query->where(function($q) use ($beratBadan) {
                   $q->where('berat_min', '<=', $beratBadan)
                     ->where('berat_max', '>=', $beratBadan);
               })->orWhere('is_open_class', true);
           })
           ->first();
       
       if ($kelasTanding) {
           return response()->json([
               'kelas_tanding' => [
                   'id' => $kelasTanding->id,
                   'kode_kelas' => $kelasTanding->kode_kelas,
                   'label_keterangan' => $kelasTanding->label_keterangan,
                   'berat_min' => $kelasTanding->berat_min,
                   'berat_max' => $kelasTanding->berat_max,
                   'is_open_class' => $kelasTanding->is_open_class,
               ],
           ]);
       }
       
       return response()->json(['kelas_tanding' => null]);
   }
}