<?php

use Illuminate\Support\Facades\Route;

// Guest routes for pelatih
Route::prefix('pelatih')->group(function () {
    Route::get('login', [App\Http\Controllers\Pelatih\Auth\PelatihAuthController::class, 'showLoginForm'])->name('pelatih.login.form');
    Route::post('login', [App\Http\Controllers\Pelatih\Auth\PelatihAuthController::class, 'login'])->name('pelatih.login');
    Route::get('register', [App\Http\Controllers\Pelatih\Auth\PelatihAuthController::class, 'showRegistrationForm'])->name('pelatih.register.form');
    Route::post('register', [App\Http\Controllers\Pelatih\Auth\PelatihAuthController::class, 'register'])->name('pelatih.register');
});

// Protected pelatih routes
Route::prefix('pelatih')->middleware('auth.pelatih')->group(function () {
    Route::post('logout', [App\Http\Controllers\Pelatih\Auth\PelatihAuthController::class, 'logout'])->name('pelatih.logout');
    
    // Dashboard
    Route::get('dashboard', [App\Http\Controllers\Pelatih\DashboardController::class, 'index'])->name('pelatih.dashboard');
    
    // Kontingen
    Route::get('kontingen', [App\Http\Controllers\Pelatih\KontingenController::class, 'index'])->name('pelatih.kontingen.index');
    Route::post('kontingen', [App\Http\Controllers\Pelatih\KontingenController::class, 'store'])->name('pelatih.kontingen.store');
    Route::get('kontingen/{id}', [App\Http\Controllers\Pelatih\KontingenController::class, 'show'])->name('pelatih.kontingen.show');
    Route::put('kontingen/{id}', [App\Http\Controllers\Pelatih\KontingenController::class, 'update'])->name('pelatih.kontingen.update');
    Route::delete('kontingen/{id}', [App\Http\Controllers\Pelatih\KontingenController::class, 'destroy'])->name('pelatih.kontingen.destroy');
    
    // Peserta
    Route::get('peserta', [App\Http\Controllers\Pelatih\PesertaController::class, 'index'])->name('pelatih.peserta.index');
    Route::get('kontingen/{id}/peserta', [App\Http\Controllers\Pelatih\PesertaController::class, 'index'])->name('pelatih.kontingen.peserta');
    Route::get('peserta/create', [App\Http\Controllers\Pelatih\PesertaController::class, 'create'])->name('pelatih.peserta.create');
    Route::post('peserta', [App\Http\Controllers\Pelatih\PesertaController::class, 'store'])->name('pelatih.peserta.store');
    Route::get('peserta/{id}', [App\Http\Controllers\Pelatih\PesertaController::class, 'show'])->name('pelatih.peserta.show');
    Route::get('peserta/{id}/edit', [App\Http\Controllers\Pelatih\PesertaController::class, 'edit'])->name('pelatih.peserta.edit');
    Route::put('peserta/{id}', [App\Http\Controllers\Pelatih\PesertaController::class, 'update'])->name('pelatih.peserta.update');
    Route::delete('peserta/{id}', [App\Http\Controllers\Pelatih\PesertaController::class, 'destroy'])->name('pelatih.peserta.destroy');
    
    // Dokumen
    Route::get('peserta/{pesertaId}/dokumen', [App\Http\Controllers\Pelatih\DokumenPesertaController::class, 'index'])->name('pelatih.dokumen.index');
    Route::post('peserta/{pesertaId}/dokumen', [App\Http\Controllers\Pelatih\DokumenPesertaController::class, 'store'])->name('pelatih.dokumen.store');
    Route::get('peserta/{pesertaId}/dokumen/{id}', [App\Http\Controllers\Pelatih\DokumenPesertaController::class, 'show'])->name('pelatih.dokumen.show');
    Route::get('peserta/{pesertaId}/dokumen/{id}/download', [App\Http\Controllers\Pelatih\DokumenPesertaController::class, 'download'])->name('pelatih.dokumen.download');
    Route::delete('peserta/{pesertaId}/dokumen/{id}', [App\Http\Controllers\Pelatih\DokumenPesertaController::class, 'destroy'])->name('pelatih.dokumen.destroy');
    
    // Pembayaran
    Route::get('pembayaran', [App\Http\Controllers\Pelatih\PembayaranController::class, 'index'])->name('pelatih.pembayaran.index');
    Route::get('pembayaran/{id}', [App\Http\Controllers\Pelatih\PembayaranController::class, 'show'])->name('pelatih.pembayaran.show');
    Route::post('pembayaran/{id}/upload', [App\Http\Controllers\Pelatih\PembayaranController::class, 'uploadBukti'])->name('pelatih.pembayaran.upload');
    Route::post('pembayaran/{id}/recalculate', [App\Http\Controllers\Pelatih\PembayaranController::class, 'hitungUlangTagihan'])->name('pelatih.pembayaran.recalculate');
    
    // Jadwal
    Route::get('jadwal', [App\Http\Controllers\Pelatih\JadwalPertandinganController::class, 'index'])->name('pelatih.jadwal.index');
    Route::get('jadwal/{id}', [App\Http\Controllers\Pelatih\JadwalPertandinganController::class, 'show'])->name('pelatih.jadwal.show');
});