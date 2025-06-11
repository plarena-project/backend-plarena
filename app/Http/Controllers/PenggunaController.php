<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PenggunaController extends Controller
{
    // GET /api/pengguna
    public function index()
    {
        $pengguna = Pengguna::all();

        return response()->json([
            'success' => true,
            'message' => 'Data pengguna berhasil diambil',
            'data' => $pengguna,
        ]);
    }

    // POST /api/pengguna
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|string',
            'role' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Hash password
        $data['password'] = bcrypt($data['password']);

        // Simpan foto jika ada
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto', 'public');
            $data['foto'] = $path;
        }

        $pengguna = Pengguna::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data' => $pengguna,
        ], 201);
    }

    // GET /api/pengguna/{id}
    public function show($id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditemukan',
            'data' => $pengguna,
        ]);
    }

    // PUT /api/pengguna/{id}
public function update(Request $request, $id)
{
    $pengguna = Pengguna::find($id);

    if (!$pengguna) {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan',
            'data' => null,
        ], 404);
    }

    $data = $request->validate([
        'nama' => 'sometimes|required|string',
        'no_hp' => 'sometimes|required|string|max:15',
        'email' => 'sometimes|required|email|unique:pengguna,email,' . $id . ',id_pengguna',
        'password' => 'nullable|string',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Hash password jika diubah
    if (isset($data['password'])) {
        $data['password'] = bcrypt($data['password']);
    } else {
        unset($data['password']);
    }

    // Ganti foto jika ada
    if ($request->hasFile('foto')) {
        if ($pengguna->foto && Storage::disk('public')->exists($pengguna->foto)) {
            Storage::disk('public')->delete($pengguna->foto);
        }

        $path = $request->file('foto')->store('foto', 'public');
        $data['foto'] = $path;
    }

    $pengguna->update($data);

    return response()->json([
        'success' => true,
        'message' => 'Data berhasil diperbarui',
        'data' => $pengguna,
    ]);
}

    // DELETE /api/pengguna/{id}
    public function destroy($id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        // Cegah penghapusan jika masih ada relasi (opsional)
        if ($pengguna->pemesanan()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna memiliki pemesanan, tidak dapat dihapus.',
            ], 400);
        }

        // Hapus foto dari storage jika ada
        if ($pengguna->foto && Storage::disk('public')->exists($pengguna->foto)) {
            Storage::disk('public')->delete($pengguna->foto);
        }

        $pengguna->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus',
        ]);
    }
}
