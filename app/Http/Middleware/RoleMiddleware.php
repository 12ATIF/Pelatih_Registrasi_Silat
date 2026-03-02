<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Cek apakah user yang login memiliki role yang sesuai.
     *
     * @param  string  $role  Role yang dibutuhkan (e.g. 'user')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek guard pelatih
        if (! Auth::guard('pelatih')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('pelatih.login.form');
        }

        // Cek role
        if (Auth::guard('pelatih')->user()->role !== $role) {
            Auth::guard('pelatih')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Anda tidak memiliki akses ke halaman ini.'], 403);
            }

            return redirect()->route('pelatih.login.form')
                ->withErrors(['role' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        return $next($request);
    }
}
