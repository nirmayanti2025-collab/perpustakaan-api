<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\LogAktivitas;

class PengembalianController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required',
            'tanggal_kembali' => 'required|date'
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);
        $buku = Buku::findOrFail($peminjaman->buku_id);

        $peminjaman->update([
            'tanggal_kembali' => $request->tanggal_kembali,
            'status' => 'dikembalikan'
        ]);

        // Tambah stok
        $buku->stok += 1;
        $buku->status = 'tersedia';
        $buku->save();

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => $peminjaman->user_id,
            'aktivitas' => 'Pengembalian Buku',
            'keterangan' => 'Mengembalikan buku: ' . $buku->judul
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Buku berhasil dikembalikan',
            'data' => $peminjaman
        ]);
    }
}
