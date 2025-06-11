<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index()
    {
        $data = Pembayaran::with('pemesanan')->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function uploadBuktiBayar(Request $request, $id_pemesanan)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pemesanan = Pemesanan::findOrFail($id_pemesanan);

        // Simpan file
        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $path = $file->store('bukti-bayar', 'public');

            // Update tabel pembayaran
            $pembayaran = Pembayaran::where('id_pemesanan', $id_pemesanan)->first();
            if ($pembayaran) {
                $pembayaran->update(['bukti_bayar' => $path]);
            }

            // Update status pemesanan menjadi ditinjau
            $pemesanan->update(['status' => 'ditinjau']);

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diupload',
                'path' => $path,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal upload bukti',
        ], 400);
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::find($id);
        if (!$pembayaran) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        if ($pembayaran->bukti_bayar && Storage::disk('public')->exists($pembayaran->bukti_bayar)) {
            Storage::disk('public')->delete($pembayaran->bukti_bayar);
        }

        $pembayaran->delete();

        return response()->json(['success' => true, 'message' => 'Pembayaran dihapus']);
    }
}
