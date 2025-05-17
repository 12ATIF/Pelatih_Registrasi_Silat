<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\Kontingen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KontingenController extends Controller
{
    public function index()
    {
        $kontingens = Auth::guard('pelatih')->user()->kontingens()->withCount('pesertas')->get();
        
        if (request()->expectsJson()) {
            return response()->json($kontingens);
        }
        
        return view('pelatih.kontingen.index', compact('kontingens'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'asal_daerah' => 'required|string|max:255',
            'kontak_pendamping' => 'nullable|string|max:15',
        ]);
        
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }
        
        $kontingen = Auth::guard('pelatih')->user()->kontingens()->create([
            'nama' => $request->nama,
            'asal_daerah' => $request->asal_daerah,
            'kontak_pendamping' => $request->kontak_pendamping,
            'is_active' => true,
        ]);
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Kontingen berhasil dibuat.', 'kontingen' => $kontingen], 201);
        }
        
        return redirect()->route('pelatih.kontingen.index')->with('success', 'Kontingen berhasil dibuat.');
    }
    
    public function show($id)
    {
        $kontingen = Auth::guard('pelatih')->user()->kontingens()->with(['pesertas', 'pembayarans'])->findOrFail($id);
        
        if (request()->expectsJson()) {
            return response()->json($kontingen);
        }
        
        return view('pelatih.kontingen.show', compact('kontingen'));
    }
    
    public function update(Request $request, $id)
    {
        $kontingen = Auth::guard('pelatih')->user()->kontingens()->findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'asal_daerah' => 'required|string|max:255',
            'kontak_pendamping' => 'nullable|string|max:15',
        ]);
        
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }
        
        $kontingen->update([
            'nama' => $request->nama,
            'asal_daerah' => $request->asal_daerah,
            'kontak_pendamping' => $request->kontak_pendamping,
        ]);
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Kontingen berhasil diperbarui.', 'kontingen' => $kontingen]);
        }
        
        return redirect()->route('pelatih.kontingen.index')->with('success', 'Kontingen berhasil diperbarui.');
    }
    
    public function destroy($id)
    {
        $kontingen = Auth::guard('pelatih')->user()->kontingens()->findOrFail($id);
        
        // Cek apakah sudah ada peserta terdaftar
        if ($kontingen->pesertas()->count() > 0) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Kontingen tidak dapat dihapus karena sudah memiliki peserta terdaftar.'], 422);
            }
            return back()->with('error', 'Kontingen tidak dapat dihapus karena sudah memiliki peserta terdaftar.');
        }
        
        $kontingen->delete();
        
        if (request()->expectsJson()) {
            return response()->json(['message' => 'Kontingen berhasil dihapus.']);
        }
        
        return redirect()->route('pelatih.kontingen.index')->with('success', 'Kontingen berhasil dihapus.');
    }
}