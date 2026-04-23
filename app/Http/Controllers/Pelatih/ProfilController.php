<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function index()
    {
        $pelatih = Auth::guard('pelatih')->user();
        return view('pelatih.profil.index', compact('pelatih'));
    }

    public function update(Request $request)
    {
        $pelatih = Auth::guard('pelatih')->user();

        $request->validate([
            'nama'     => 'required|string|max:255',
            'perguruan'=> 'required|string|max:255',
            'no_hp'    => ['required','string','max:15', Rule::unique('pelatih','no_hp')->ignore($pelatih->id)],
            'email'    => ['required','email','max:255', Rule::unique('pelatih','email')->ignore($pelatih->id)],
        ], [
            'no_hp.unique'  => 'Nomor HP sudah digunakan akun lain.',
            'email.unique'  => 'Email sudah digunakan akun lain.',
        ]);

        $pelatih->nama      = $request->nama;
        $pelatih->perguruan = $request->perguruan;
        $pelatih->no_hp     = $request->no_hp;
        $pelatih->email     = $request->email;
        $pelatih->save();

        return redirect()->route('pelatih.profil.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $pelatih = Auth::guard('pelatih')->user();

        $request->validate([
            'password_lama'         => 'required|string',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',
        ], [
            'password.min'          => 'Password baru minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->password_lama, $pelatih->getAttributes()['password'])) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        // Model punya cast 'hashed', cukup assign plain text - akan di-hash otomatis
        $pelatih->password = $request->password;
        $pelatih->save();

        return redirect()->route('pelatih.profil.index')
            ->with('success', 'Password berhasil diperbarui.');
    }
}
