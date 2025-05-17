<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\DokumenPeserta;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DokumenPesertaController extends Controller
{
    public function index($pesertaId)
    {
        // Cari peserta dan pastikan milik pelatih
        $kontingenIds = Auth::guard('pelatih')->user()->kontingens()->pluck('id')->toArray();
        $peserta = Peserta::whereIn('kontingen_id', $kontingenIds)->findOrFail($pesertaId);
        
        $dokumens = $peserta->dokumenPesertas;
        
        if (request()->expectsJson()) {
            return response()->json($dokumens);
        }
        
        return view('pelatih.dokumen.index', compact('peserta', 'dokumens'));
    }
    
    public function store(Request $request, $pesertaId)
    {
        // Cari peserta dan pastikan milik pelatih
        $kontingenIds = Auth::guard('pelatih')->user()->kontingens()->pluck('id')->toArray();
        $peserta = Peserta::whereIn('kontingen_id', $kontingenIds)->findOrFail($pesertaId);
        
        $validator = Validator::make($request->all(), [
            'jenis_dokumen' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);
        
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }
        
        // Upload file
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('dokumen_peserta/' . $peserta->id, $fileName, 'public');
        
        // Simpan dokumen
        $dokumen = DokumenPeserta::create([
            'peserta_id' => $peserta->id,
            'jenis_dokumen' => $request->jenis_dokumen,
            'file_path' => $filePath,
        ]);
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Dokumen berhasil diunggah.', 'dokumen' => $dokumen], 201);
        }
        
        return redirect()->route('pelatih.dokumen.index', $peserta->id)->with('success', 'Dokumen berhasil diunggah.');
    }
    
    public function show($pesertaId, $id)
    {
        // Cari peserta dan pastikan milik pelatih
        $kontingenIds = Auth::guard('pelatih')->user()->kontingens()->pluck('id')->toArray();
        Peserta::whereIn('kontingen_id', $kontingenIds)->findOrFail($pesertaId);
        
        // Cari dokumen
        $dokumen = DokumenPeserta::where('peserta_id', $pesertaId)->findOrFail($id);
        
        if (!Storage::exists($dokumen->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }
        
        if (request()->expectsJson()) {
            return response()->json(['dokumen' => $dokumen, 'url' => Storage::url($dokumen->file_path)]);
        }
        
        return view('pelatih.dokumen.show', compact('dokumen'));
    }
    
    public function download($pesertaId, $id)
    {
        // Cari peserta dan pastikan milik pelatih
        $kontingenIds = Auth::guard('pelatih')->user()->kontingens()->pluck('id')->toArray();
        Peserta::whereIn('kontingen_id', $kontingenIds)->findOrFail($pesertaId);
        
        // Cari dokumen
        $dokumen = DokumenPeserta::where('peserta_id', $pesertaId)->findOrFail($id);
        
        if (!Storage::exists($dokumen->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }
        
        return Storage::download($dokumen->file_path);
    }
    
    public function destroy($pesertaId, $id)
    {
        // Cari peserta dan pastikan milik pelatih
        $kontingenIds = Auth::guard('pelatih')->user()->kontingens()->pluck('id')->toArray();
        $peserta = Peserta::whereIn('kontingen_id', $kontingenIds)->findOrFail($pesertaId);
        
        // Hanya dapat menghapus jika status verifikasi masih pending
        if ($peserta->status_verifikasi !== 'pending') {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Tidak dapat menghapus dokumen peserta yang sudah diverifikasi.'], 422);
            }
            return back()->with('error', 'Tidak dapat menghapus dokumen peserta yang sudah diverifikasi.');
        }
        
        // Cari dokumen
        $dokumen = DokumenPeserta::where('peserta_id', $pesertaId)->findOrFail($id);
        
        // Hapus file
        if (Storage::exists($dokumen->file_path)) {
            Storage::delete($dokumen->file_path);
        }
        
        // Hapus dokumen
        $dokumen->delete();
        
        if (request()->expectsJson()) {
            return response()->json(['message' => 'Dokumen berhasil dihapus.']);
        }
        
        return redirect()->route('pelatih.dokumen.index', $peserta->id)->with('success', 'Dokumen berhasil dihapus.');
    }
}