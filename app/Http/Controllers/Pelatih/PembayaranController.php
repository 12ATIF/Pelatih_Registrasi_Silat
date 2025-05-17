<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Kontingen;
use App\Models\SubkategoriLomba;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PembayaranController extends Controller
{
    public function index()
    {
        $kontingens = Auth::guard('pelatih')->user()->kontingens()->withCount('pesertas')->get();
        $pembayarans = [];
        
        foreach ($kontingens as $kontingen) {
            $pembayaran = $kontingen->pembayarans()->latest()->first();
            
            if (!$pembayaran) {
                // Hitung total tagihan
                $totalTagihan = 0;
                foreach ($kontingen->pesertas as $peserta) {
                    $totalTagihan += $peserta->subkategoriLomba->harga_pendaftaran;
                }
                
                // Buat entry pembayaran baru jika ada peserta
                if ($kontingen->pesertas_count > 0 && $totalTagihan > 0) {
                    $pembayaran = Pembayaran::create([
                        'kontingen_id' => $kontingen->id,
                        'total_tagihan' => $totalTagihan,
                        'status' => 'belum_bayar',
                    ]);
                }
            }
            
            if ($pembayaran) {
                $pembayarans[] = $pembayaran;
            }
        }
        
        if (request()->expectsJson()) {
            return response()->json($pembayarans);
        }
        
        return view('pelatih.pembayaran.index', compact('pembayarans'));
    }
    
    public function show($id)
    {
        // Pastikan pembayaran terkait kontingen pelatih
        $kontingenIds = Auth::guard('pelatih')->user()->kontingens()->pluck('id')->toArray();
        $pembayaran = Pembayaran::whereIn('kontingen_id', $kontingenIds)->findOrFail($id);
        
        $kontingen = $pembayaran->kontingen;
        $pesertas = $kontingen->pesertas;
        
        // Hitung detail tagihan
        $detailTagihan = [];
        foreach ($pesertas as $peserta) {
            $detailTagihan[] = [
                'peserta_id' => $peserta->id,
                'nama' => $peserta->nama,
                'subkategori' => $peserta->subkategoriLomba->nama,
                'harga' => $peserta->subkategoriLomba->harga_pendaftaran,
            ];
        }
        
        if (request()->expectsJson()) {
            return response()->json([
                'pembayaran' => $pembayaran,
                'kontingen' => $kontingen,
                'detail_tagihan' => $detailTagihan,
            ]);
        }
        
        return view('pelatih.pembayaran.show', compact('pembayaran', 'kontingen', 'detailTagihan'));
    }
    
    public function uploadBukti(Request $request, $id)
    {
        // Pastikan pembayaran terkait kontingen pelatih
        $kontingenIds = Auth::guard('pelatih')->user()->kontingens()->pluck('id')->toArray();
        $pembayaran = Pembayaran::whereIn('kontingen_id', $kontingenIds)->findOrFail($id);
        
        // Hanya dapat upload jika status belum lunas
        if ($pembayaran->status === 'lunas') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Pembayaran sudah diverifikasi sebagai lunas.'], 422);
            }
            return back()->with('error', 'Pembayaran sudah diverifikasi sebagai lunas.');
        }
        
        $validator = Validator::make($request->all(), [
            'bukti_transfer' => 'required|file|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }
        
        // Hapus bukti transfer lama jika ada
        if ($pembayaran->bukti_transfer && Storage::exists($pembayaran->bukti_transfer)) {
            Storage::delete($pembayaran->bukti_transfer);
        }
        
        // Upload file
        $file = $request->file('bukti_transfer');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('bukti_transfer', $fileName, 'public');
        
        // Update pembayaran
        $pembayaran->bukti_transfer = $filePath;
        $pembayaran->status = 'menunggu_verifikasi';
        $pembayaran->save();
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Bukti transfer berhasil diunggah.', 'pembayaran' => $pembayaran]);
        }
        
        return redirect()->route('pelatih.pembayaran.show', $pembayaran->id)->with('success', 'Bukti transfer berhasil diunggah.');
    }
    
    public function hitungUlangTagihan($id)
    {
        // Pastikan pembayaran terkait kontingen pelatih
        $kontingenIds = Auth::guard('pelatih')->user()->kontingens()->pluck('id')->toArray();
        $pembayaran = Pembayaran::whereIn('kontingen_id', $kontingenIds)->findOrFail($id);
        
        // Hanya dapat hitung ulang jika status belum lunas
        if ($pembayaran->status === 'lunas') {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Pembayaran sudah diverifikasi sebagai lunas.'], 422);
            }
            return back()->with('error', 'Pembayaran sudah diverifikasi sebagai lunas.');
        }
        
        $kontingen = $pembayaran->kontingen;
        $pesertas = $kontingen->pesertas;
        
        // Hitung total tagihan
        $totalTagihan = 0;
        foreach ($pesertas as $peserta) {
            $totalTagihan += $peserta->subkategoriLomba->harga_pendaftaran;
        }
        
        // Update pembayaran
        $pembayaran->total_tagihan = $totalTagihan;
        $pembayaran->save();
        
        if (request()->expectsJson()) {
            return response()->json(['message' => 'Total tagihan berhasil dihitung ulang.', 'pembayaran' => $pembayaran]);
        }
        
        return redirect()->route('pelatih.pembayaran.show', $pembayaran->id)->with('success', 'Total tagihan berhasil dihitung ulang.');
    }
}