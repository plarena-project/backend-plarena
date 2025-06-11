<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    // GET /jadwal
    public function index()
    {
        $data = Jadwal::with('pemesanan')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar semua jadwal',
            'data' => $data
        ]);
    }

    // POST /jadwal
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pesan'  => 'required|date',
            'nama_pemesan'   => 'required|string|max:255',
            'jenis_lapangan' => 'required|in:emas,perak,perunggu',
            'jam_main'       => 'required|date_format:H:i:s',
            'lama_sewa'      => 'required|date_format:H:i:s',
            'jam_habis'      => 'required|date_format:H:i:s|after:jam_main',
            'status'         => 'required|in:booked,available',
            'id_pemesanan'   => 'required|exists:pemesanan,id_pemesanan',
        ]);

        $jadwal = Jadwal::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil ditambahkan',
            'data' => $jadwal
        ], 201);
    }

    // GET /jadwal/{id}
    public function show($id)
    {
        $jadwal = Jadwal::with('pemesanan')->find($id);

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail jadwal',
            'data' => $jadwal
        ]);
    }

    // PUT /jadwal/{id}
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::find($id);

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan',
                'data' => null
            ], 404);
        }

        $request->validate([
            'tanggal_pesan'  => 'sometimes|date',
            'nama_pemesan'   => 'sometimes|string|max:255',
            'jenis_lapangan' => 'sometimes|in:emas,perak,perunggu',
            'jam_main'       => 'sometimes|date_format:H:i:s',
            'lama_sewa'      => 'sometimes|date_format:H:i:s',
            'jam_habis'      => 'sometimes|date_format:H:i:s|after:jam_main',
            'status'         => 'sometimes|in:booked,available',
            'id_pemesanan'   => 'sometimes|exists:pemesanan,id_pemesanan',
        ]);

        $jadwal->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diperbarui',
            'data' => $jadwal
        ]);
    }

    // DELETE /jadwal/{id}
    public function destroy($id)
    {
        $jadwal = Jadwal::find($id);

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan',
                'data' => null
            ], 404);
        }

        $jadwal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus'
        ]);
    }
}
