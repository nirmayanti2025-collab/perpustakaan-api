<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;

class PeminjamanController extends Controller
{
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
}
