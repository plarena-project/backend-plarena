<?php
// app/Http/Controllers/LapanganController.php
namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    // GET /lapangan
    public function index()
    {
        $data = Lapangan::all();
        return response()->json([
            'success' => true,
            'message' => 'Daftar semua lapangan',
            'data' => $data
        ]);
    }

    // POST /lapangan
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:emas,perak,perunggu',
            'harga' => 'required|integer',
        ]);

        $lapangan = Lapangan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data lapangan berhasil ditambahkan',
            'data' => $lapangan
        ], 201);
    }

    // GET /lapangan/{id}
    public function show($id)
    {
        $lapangan = Lapangan::find($id);

        if (!$lapangan) {
            return response()->json([
                'success' => false,
                'message' => 'Data lapangan tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail data lapangan',
            'data' => $lapangan
        ]);
    }

    // PUT/PATCH /lapangan/{id}
    public function update(Request $request, $id)
    {
        $lapangan = Lapangan::find($id);

        if (!$lapangan) {
            return response()->json([
                'success' => false,
                'message' => 'Data lapangan tidak ditemukan',
                'data' => null
            ], 404);
        }

        $request->validate([
            'jenis' => 'sometimes|required|in:emas,perak,perunggu',
            'harga' => 'sometimes|required|integer',
        ]);

        $lapangan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data lapangan berhasil diperbarui',
            'data' => $lapangan
        ]);
    }

    // DELETE /lapangan/{id}
    public function destroy($id)
    {
        $lapangan = Lapangan::find($id);

        if (!$lapangan) {
            return response()->json([
                'success' => false,
                'message' => 'Data lapangan tidak ditemukan',
                'data' => null
            ], 404);
        }

        $lapangan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data lapangan berhasil dihapus',
            'data' => null
        ]);
    }
}
