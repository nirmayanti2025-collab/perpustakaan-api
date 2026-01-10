<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['buku', 'user'])->get();
        return response()->json([
            'code' => 200,
            'message' => 'Success',
            'data' => $peminjamans
        ]);
    }

    public function pinjam(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'buku_id' => 'required',
            'tanggal_pinjam' => 'required|date'
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->stok <= 0) {
            return response()->json(['message' => 'Stok buku habis'], 400);
        }

        // Simpan peminjaman
        Peminjaman::create([
            'user_id' => $request->user_id,
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => $request->tanggal_pinjam
        ]);

        // Update stok
        $buku->stok -= 1;
        $buku->status = $buku->stok == 0 ? 'habis' : 'tersedia';
        $buku->save();

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => $request->user_id,
            'aktivitas' => 'Peminjaman Buku',
            'keterangan' => 'Meminjam buku: ' . $buku->judul
        ]);

        return response()->json(['message' => 'Buku berhasil dipinjam']);
    }

    public function store(Request $request)
    {
        return response()->json([
            'message' => 'Peminjaman berhasil',
            'data' => $request->all()
        ]);
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['buku', 'user'])->find($id);

        if (!$peminjaman) {
            return response()->json([
                'code' => 404,
                'message' => 'Peminjaman not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Success',
            'data' => $peminjaman
        ]);
    }
}
