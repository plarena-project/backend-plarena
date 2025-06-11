<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    // GET /pemesanan
    public function index()
    {
        $data = Pemesanan::with(['pengguna', 'lapangan'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar semua pemesanan',
            'data' => $data
        ]);
    }

    // POST /pemesanan
    public function store(Request $request)
    {
        $request->validate([
            'id_pengguna'    => 'required|exists:pengguna,id_pengguna',
            'id_lapangan'    => 'required|exists:lapangan,id_lapangan',
            'tanggal_pesan'  => 'required|date',
            'jam_main'       => 'required|date_format:H:i:s',
            'lama_sewa'      => 'required|date_format:H:i:s',
            'jam_habis'      => 'required|date_format:H:i:s|after:jam_main',
            'status'         => 'required|in:menunggu pembayaran,dibayar',
        ]);

        // 1. Buat data pemesanan
        $pemesanan = Pemesanan::create($request->all());

        // 2. Hitung total pembayaran
        $lapangan = Lapangan::findOrFail($request->id_lapangan);

        // Konversi lama_sewa (time) ke jam
        $lamaSewaJam = \Carbon\Carbon::parse($request->lama_sewa)->format('H') +
            (\Carbon\Carbon::parse($request->lama_sewa)->format('i') / 60);

        $totalBayar = $lapangan->harga * $lamaSewaJam;

        // 3. Simpan data pembayaran
        Pembayaran::create([
            'tanggal_pesan'   => $request->tanggal_pesan,
            'jenis_lapangan'  => $lapangan->jenis,
            'jam_main'        => $request->jam_main,
            'lama_sewa'       => $request->lama_sewa,
            'jam_habis'       => $request->jam_habis,
            'total'           => $totalBayar,
            'bukti_bayar'     => null,
            'id_pemesanan'    => $pemesanan->id_pemesanan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pemesanan dan pembayaran berhasil dibuat',
            'data' => $pemesanan
        ], 201);
    }


    // GET /pemesanan/{id}
    public function show($id)
    {
        $pemesanan = Pemesanan::with(['pengguna', 'lapangan'])->find($id);

        if (!$pemesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pemesanan tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pemesanan',
            'data' => $pemesanan
        ]);
    }

    // PUT /pemesanan/{id}
    public function update(Request $request, $id)
    {
        $pemesanan = Pemesanan::find($id);

        if (!$pemesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pemesanan tidak ditemukan',
                'data' => null
            ], 404);
        }

        $request->validate([
            'id_pengguna'    => 'sometimes|exists:pengguna,id_pengguna',
            'id_lapangan'    => 'sometimes|exists:lapangan,id_lapangan',
            'tanggal_pesan'  => 'sometimes|date',
            'jam_main'       => 'sometimes|date_format:H:i:s',
            'lama_sewa'      => 'sometimes|date_format:H:i:s',
            'jam_habis'      => 'sometimes|date_format:H:i:s|after:jam_main',
            'status'         => 'sometimes|in:menunggu pembayaran,dibayar',
        ]);

        $pemesanan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pemesanan berhasil diperbarui',
            'data' => $pemesanan
        ]);
    }

    // DELETE /pemesanan/{id}
    public function destroy($id)
    {
        $pemesanan = Pemesanan::find($id);

        if (!$pemesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pemesanan tidak ditemukan',
                'data' => null
            ], 404);
        }

        $pemesanan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pemesanan berhasil dihapus'
        ]);
    }
}
