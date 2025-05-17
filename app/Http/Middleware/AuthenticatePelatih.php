<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatePelatih
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('pelatih')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('pelatih.login.form');
        }
        
        // Periksa apakah pelatih aktif
        if (!Auth::guard('pelatih')->user()->is_active) {
            Auth::guard('pelatih')->logout();
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akun Anda dinonaktifkan. Silakan hubungi admin.'], 403);
            }
            return redirect()->route('pelatih.login.form')
                ->with('error', 'Akun Anda dinonaktifkan. Silakan hubungi admin.');
        }
        
        return $next($request);
    }
}