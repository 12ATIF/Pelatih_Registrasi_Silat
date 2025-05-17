<?php

namespace App\Http\Controllers\Pelatih\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pelatih;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PelatihAuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('pelatih.auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        if (Auth::guard('pelatih')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Verifikasi status aktif
            if (!Auth::guard('pelatih')->user()->is_active) {
                Auth::guard('pelatih')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Akun Anda dinonaktifkan. Silakan hubungi admin.'], 403);
                }
                return redirect()->route('pelatih.login.form')
                    ->with('error', 'Akun Anda dinonaktifkan. Silakan hubungi admin.');
            }
            
            if ($request->expectsJson()) {
                $pelatih = Auth::guard('pelatih')->user();
                $token = $pelatih->createToken('pelatih-api-token')->plainTextToken;
                return response()->json(['token' => $token, 'pelatih' => $pelatih]);
            }
            
            return redirect()->intended(route('pelatih.dashboard'));
        }
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }
        
        return back()->withErrors([
            'email' => 'Email atau password yang diberikan tidak cocok dengan catatan kami.',
        ])->withInput($request->only('email'));
    }
    
    // Proses registrasi
    public function showRegistrationForm()
    {
        return view('pelatih.auth.register');
    }
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'perguruan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15|unique:pelatih,no_hp',
            'email' => 'required|string|email|max:255|unique:pelatih,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput($request->except('password', 'password_confirmation'));
        }

        $pelatih = Pelatih::create([
            'nama' => $request->nama,
            'perguruan' => $request->perguruan,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);
        
        if ($request->expectsJson()) {
            $token = $pelatih->createToken('pelatih-api-token')->plainTextToken;
            return response()->json(['token' => $token, 'pelatih' => $pelatih], 201);
        }
        
        return redirect()->route('pelatih.login.form')
            ->with('success', 'Registrasi berhasil! Silakan login dengan akun yang telah Anda buat.');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::guard('pelatih')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Berhasil logout.']);
        }
        
        return redirect()->route('pelatih.login.form');
    }
}