<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        return response()->json(Kategori::all());
    }

    public function store(Request $request)
    {
        // Robustly extract nama_kategori from JSON, form-data or form-urlencoded
        $nama = $request->input('nama_kategori');
        if (empty($nama)) {
            $nama = $request->input('nama');
        }
        if (empty($nama)) {
            $nama = $request->input('name');
        }

        // If still empty, inspect raw body
        if (empty($nama)) {
            $raw = file_get_contents('php://input');
            // try JSON
            $json = @json_decode($raw, true);
            if (is_array($json) && !empty($json)) {
                foreach (['nama_kategori', 'nama', 'name'] as $k) {
                    if (isset($json[$k]) && is_string($json[$k]) && trim($json[$k]) !== '') {
                        $nama = $json[$k];
                        break;
                    }
                }
            }
            // try parse_str for form-encoded bodies like "nama_kategori=Novel"
            if (empty($nama) && is_string($raw) && strpos($raw, '=') !== false) {
                parse_str($raw, $parsed);
                foreach (['nama_kategori', 'nama', 'name'] as $k) {
                    if (isset($parsed[$k]) && is_string($parsed[$k]) && trim($parsed[$k]) !== '') {
                        $nama = $parsed[$k];
                        break;
                    }
                }
            }
        }

        // Validate
        $validator = \Validator::make(['nama_kategori' => $nama], [
            'nama_kategori' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => $validator->errors()->first('nama_kategori'),
                'data' => null
            ], 422);
        }

        $kategori = Kategori::create([
            'nama_kategori' => $nama
        ]);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori
        ]);
    }

    public function update(Request $request, $id)
    {
        $nama = $request->input('nama_kategori') ?? $request->input('nama') ?? $request->input('name');
        if (empty($nama)) {
            $raw = file_get_contents('php://input');
            $json = @json_decode($raw, true);
            if (is_array($json) && !empty($json)) {
                foreach (['nama_kategori', 'nama', 'name'] as $k) {
                    if (isset($json[$k]) && is_string($json[$k]) && trim($json[$k]) !== '') {
                        $nama = $json[$k];
                        break;
                    }
                }
            }
            if (empty($nama) && is_string($raw) && strpos($raw, '=') !== false) {
                parse_str($raw, $parsed);
                $nama = $parsed['nama_kategori'] ?? $parsed['nama'] ?? $parsed['name'] ?? null;
            }
        }

        $validator = \Validator::make(['nama_kategori' => $nama], [
            'nama_kategori' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => $validator->errors()->first('nama_kategori'),
                'data' => null
            ], 422);
        }

        $kategori = Kategori::findOrFail($id);
        $kategori->update(['nama_kategori' => $nama]);

        return response()->json([
            'message' => 'Kategori berhasil diperbarui',
            'data' => $kategori
        ]);
    }

    public function show($id)
    {
        $kategori = Kategori::findOrFail($id);

        return response()->json([
            'data' => $kategori
        ]);
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}
