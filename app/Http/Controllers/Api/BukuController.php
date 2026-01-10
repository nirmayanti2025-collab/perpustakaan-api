<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bukus = Buku::with('kategori')->get();
        return response()->json([
            'code' => 200,
            'message' => 'Success',
            'data' => $bukus
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'stok' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validation Error',
                'data' => $validator->errors()
            ], 400);
        }

        $buku = Buku::create([
            'judul' => $request->judul,
            'kategori_id' => $request->kategori_id,
            'stok' => $request->stok,
            'status' => $request->stok > 0 ? 'tersedia' : 'habis',
        ]);

        return response()->json([
            'code' => 201,
            'message' => 'Buku created successfully',
            'data' => $buku
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $buku = Buku::with('kategori')->find($id);

        if (!$buku) {
            return response()->json([
                'code' => 404,
                'message' => 'Buku not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Success',
            'data' => $buku
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'code' => 404,
                'message' => 'Buku not found',
                'data' => null
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'stok' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Validation Error',
                'data' => $validator->errors()
            ], 400);
        }

        $buku->update([
            'judul' => $request->judul,
            'kategori_id' => $request->kategori_id,
            'stok' => $request->stok,
            'status' => $request->stok > 0 ? 'tersedia' : 'habis',
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Buku updated successfully',
            'data' => $buku
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'code' => 404,
                'message' => 'Buku not found',
                'data' => null
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'code' => 200,
            'message' => 'Buku deleted successfully',
            'data' => null
        ]);
    }
}