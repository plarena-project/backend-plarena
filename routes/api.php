<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PembayaranController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('pengguna', PenggunaController::class);
Route::apiResource('lapangan', LapanganController::class);
Route::apiResource('pemesanan', PemesananController::class);
Route::apiResource('pembayaran', PembayaranController::class);
Route::apiResource('jadwal', JadwalController::class);
