<?php
namespace App\Http\Controllers;

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
        $request->validate([
            'nama_kategori' => 'required'
        ]);

        $kategori = Kategori::create($request->all());

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required'
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->all());

        return response()->json([
            'message' => 'Kategori berhasil diperbarui',
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
