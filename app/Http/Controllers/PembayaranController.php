<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{

    public function uploadBuktiBayar(Request $request, $id_pemesanan)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Cari data pembayaran dan pemesanan
        $pemesanan = Pemesanan::find($id_pemesanan);
        $pembayaran = Pembayaran::where('id_pemesanan', $id_pemesanan)->first();

        if (!$pembayaran || !$pemesanan) {
            return response()->json(['message' => 'Pembayaran atau pemesanan tidak ditemukan'], 404);
        }

        // Upload file
        $file = $request->file('bukti_bayar');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('bukti_bayar', $filename, 'public');

        // Update pembayaran
        $pembayaran->update([
            'bukti_bayar' => $path,
        ]);

        // Update status pemesanan
        $pemesanan->update([
            'status' => 'dibayar',
        ]);

        // Tambah data ke jadwal
        Jadwal::create([
            'tanggal_pesan' => $pemesanan->tanggal_pesan,
            'nama_pemesan' => $pemesanan->pengguna->nama ?? 'Guest',
            'jenis_lapangan' => $pemesanan->lapangan->jenis ?? 'perunggu',
            'jam_main' => $pemesanan->jam_main,
            'lama_sewa' => $pemesanan->lama_sewa,
            'jam_habis' => $pemesanan->jam_habis,
            'status' => 'booked',
            'id_pemesanan' => $pemesanan->id_pemesanan,
        ]);

        return response()->json([
            'message' => 'Bukti pembayaran berhasil diupload dan status diperbarui',
            'bukti_bayar_path' => $path,
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
