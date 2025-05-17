<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pertandingan;

class DashboardController extends Controller
{
    public function index()
    {
        $pelatih = Auth::guard('pelatih')->user();
        
        // Hitung statistik
        $statistics = [
            'jumlah_kontingen' => $pelatih->kontingens()->count(),
            'jumlah_peserta' => $pelatih->kontingens()->withCount('pesertas')->get()->sum('pesertas_count'),
        ];
        
        // Ambil status pembayaran
        $pembayarans = $pelatih->kontingens()->with('pembayarans')->get()
            ->pluck('pembayarans')
            ->flatten();
            
        $pembayaranStats = [
            'total_tagihan' => $pembayarans->sum('total_tagihan'),
            'lunas' => $pembayarans->where('status', 'lunas')->count(),
            'menunggu_verifikasi' => $pembayarans->where('status', 'menunggu_verifikasi')->count(),
            'belum_bayar' => $pembayarans->where('status', 'belum_bayar')->count(),
        ];
        
        // Ambil pengumuman/jadwal terbaru
        $upcomingEvents = Pertandingan::where('tanggal_event', '>=', now())
            ->orderBy('tanggal_event')
            ->limit(5)
            ->get();
        
        if (request()->expectsJson()) {
            return response()->json([
                'statistics' => $statistics,
                'pembayaran_stats' => $pembayaranStats,
                'upcoming_events' => $upcomingEvents,
            ]);
        }
        
        return view('pelatih.dashboard', compact('statistics', 'pembayaranStats', 'upcomingEvents'));
    }
}