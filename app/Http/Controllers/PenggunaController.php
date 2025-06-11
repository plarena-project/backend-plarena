<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function index()
    {
        $pengguna = Pengguna::all();

        return response()->json([
            'success' => true,
            'message' => 'Data pengguna berhasil diambil',
            'data' => $pengguna
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|string',
            'foto' => 'nullable|string',
            'role' => 'required|string'
        ]);

        // Simpan data
        $data['password'] = bcrypt($data['password']);
        $pengguna = Pengguna::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data' => $pengguna
        ], 201);
    }


    public function show($id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditemukan',
            'data' => $pengguna
        ]);
    }

    public function update(Request $request, $id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null
            ], 404);
        }

        $data = $request->validate([
            'nama' => 'sometimes|required|string',
            'no_hp' => 'sometimes|required|string|max:15',
            'email' => 'sometimes|required|email|unique:pengguna,email,' . $id,
            'password' => 'nullable|string',
            'foto' => 'nullable|string',
            'role' => 'sometimes|required|string'
        ]);

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']); // Jika tidak diisi, jangan update password
        }

        $pengguna->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui',
            'data' => $pengguna
        ]);
    }


    public function destroy($id)
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $pengguna->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
