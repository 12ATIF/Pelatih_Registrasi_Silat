<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\JadwalPertandingan;
use App\Models\Pertandingan;
use App\Models\SubkategoriLomba;
use App\Models\KelompokUsia;
use App\Models\Kontingen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalPertandinganController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalPertandingan::with(['pertandingan', 'subkategoriLomba', 'kelompokUsia']);
        
        // Filter
        if ($request->has('pertandingan_id') && $request->pertandingan_id) {
            $query->where('pertandingan_id', $request->pertandingan_id);
        }
        
        if ($request->has('subkategori_id') && $request->subkategori_id) {
            $query->where('subkategori_id', $request->subkategori_id);
        }
        
        if ($request->has('kelompok_usia_id') && $request->kelompok_usia_id) {
            $query->where('kelompok_usia_id', $request->kelompok_usia_id);
        }
        
        // Filter berdasarkan kontingen pelatih
        if ($request->has('kontingen_id') && $request->kontingen_id) {
            $kontingen = Kontingen::findOrFail($request->kontingen_id);
            $pelatihId = Auth::guard('pelatih')->id();
            
            if ($kontingen->pelatih_id !== $pelatihId) {
                abort(403, 'Anda tidak memiliki akses ke kontingen ini.');
            }
            
            // Ambil jadwal yang relevan dengan peserta kontingen
            $subkategoriIds = $kontingen->pesertas()->pluck('subkategori_id')->unique()->toArray();
            $kelompokUsiaIds = $kontingen->pesertas()->pluck('kelompok_usia_id')->unique()->toArray();
            
            $query->where(function($q) use ($subkategoriIds, $kelompokUsiaIds) {
                $q->whereIn('subkategori_id', $subkategoriIds)
                  ->whereIn('kelompok_usia_id', $kelompokUsiaIds);
            });
        }
        
        $jadwals = $query->orderBy('tanggal')->orderBy('waktu_mulai')->get();
        
        // Data untuk filter
        $pertandingans = Pertandingan::all();
        $subkategoris = SubkategoriLomba::with('kategoriLomba')->get();
        $kelompokUsias = KelompokUsia::all();
        $kontingens = Auth::guard('pelatih')->user()->kontingens;
        
        if ($request->expectsJson()) {
            return response()->json([
                'jadwals' => $jadwals,
                'pertandingans' => $pertandingans,
                'subkategoris' => $subkategoris,
                'kelompok_usias' => $kelompokUsias,
                'kontingens' => $kontingens,
            ]);
        }
        
        return view('pelatih.jadwal.index', compact('jadwals', 'pertandingans', 'subkategoris', 'kelompokUsias', 'kontingens'));
    }
    
    public function show($id)
    {
        $jadwal = JadwalPertandingan::with(['pertandingan', 'subkategoriLomba.kategoriLomba', 'kelompokUsia'])->findOrFail($id);
        
        // Hanya tampilkan peserta milik pelatih yang login
        $kontingenIds = Auth::guard('pelatih')->user()->kontingens()->pluck('id')->toArray();
        $pesertaPelatih = \App\Models\Peserta::where('subkategori_id', $jadwal->subkategori_id)
            ->where('kelompok_usia_id', $jadwal->kelompok_usia_id)
            ->where('status_verifikasi', 'valid')
            ->whereIn('kontingen_id', $kontingenIds)
            ->with('kontingen')
            ->get();

        if (request()->expectsJson()) {
            return response()->json([
                'jadwal'          => $jadwal,
                'peserta_pelatih' => $pesertaPelatih,
            ]);
        }

        return view('pelatih.jadwal.show', compact('jadwal', 'pesertaPelatih'));
    }
}