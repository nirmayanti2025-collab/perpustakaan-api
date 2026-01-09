<?php
namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        return response()->json(
            Buku::with('kategori')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'kategori_id' => 'required|exists:kategori,id',
            'stok' => 'required|integer'
        ]);

        $status = $request->stok > 0 ? 'tersedia' : 'habis';

        $buku = Buku::create([
            'judul' => $request->judul,
            'kategori_id' => $request->kategori_id,
            'stok' => $request->stok,
            'status' => $status
        ]);

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data' => $buku
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'kategori_id' => 'required|exists:kategori,id',
            'stok' => 'required|integer'
        ]);

        $buku = Buku::findOrFail($id);

        $status = $request->stok > 0 ? 'tersedia' : 'habis';

        $buku->update([
            'judul' => $request->judul,
            'kategori_id' => $request->kategori_id,
            'stok' => $request->stok,
            'status' => $status
        ]);

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data' => $buku
        ]);
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);

        return response()->json([
            'data' => $buku
        ]);
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus'
        ]);
    }
}
