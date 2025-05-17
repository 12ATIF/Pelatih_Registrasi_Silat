<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\Kontingen;
use App\Models\SubkategoriLomba;
use App\Models\KelompokUsia;
use App\Models\KelasTanding;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PesertaController extends Controller
{
    public function index(Request $request, $kontingenId = null)
    {
        $query = Peserta::query();
        
        // Filter berdasarkan kontingen milik pelatih
        $kontingens = Auth::guard('pelatih')->user()->kontingens()->pluck('id');
        $query->whereIn('kontingen_id', $kontingens);
        
        // Filter berdasarkan kontingen tertentu jika ada
        if ($kontingenId) {
            $query->where('kontingen_id', $kontingenId);
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
        $kontingenList = Auth::guard('pelatih')->user()->kontingens;
        $subkategoris = SubkategoriLomba::with('kategoriLomba')->get();
        $kelompokUsias = KelompokUsia::all();
        
        return view('pelatih.peserta.index', compact('pesertas', 'kontingenList', 'subkategoris', 'kelompokUsias'));
    }
    
    public function create()
    {
        $kontingens = Auth::guard('pelatih')->user()->kontingens;
        $subkategoris = SubkategoriLomba::with('kategoriLomba')->get();
        $kelompokUsias = KelompokUsia::all();
        
        return view('pelatih.peserta.create', compact('kontingens', 'subkategoris', 'kelompokUsias'));
    }
    
    public function store(Request $request)
    {
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
        $kontingenIds = Auth::guard('pelatih')->user()->kontingens()->pluck('id')->toArray();
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
            return response()->json(['message' => 'Peserta berhasil ditambahkan.', 'peserta' => $peserta], 201);
        }
        
        return redirect()->route('pelatih.peserta.index')->with('success', 'Peserta berhasil ditambahkan.');
    }
    
    public function show($id)
    {
        // Cari peserta dan pastikan milik pelatih
        $kontingenIds = Auth::guard('pelatih')->user()->kontingens()->pluck('id')->toArray();
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
        $kontingenIds = Auth::guard('pelatih')->user()->kontingens()->pluck('id')->toArray();
        $peserta = Peserta::whereIn('kontingen_id', $kontingenIds)->findOrFail($id);
        
        $kontingens = Auth::guard('pelatih')->user()->kontingens;
        $subkategoris = SubkategoriLomba::with('kategoriLomba')->get();
        $kelompokUsias = KelompokUsia::all();
        
        return view('pelatih.peserta.edit', compact('peserta', 'kontingens', 'subkategoris', 'kelompokUsias'));
    }
    
    public function update(Request $request, $id)
    {
        // Cari peserta dan pastikan milik pelatih
        $kontingenIds = Auth::guard('pelatih')->user()->kontingens()->pluck('id')->toArray();
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
       $kontingenIds = Auth::guard('pelatih')->user()->kontingens()->pluck('id')->toArray();
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
}