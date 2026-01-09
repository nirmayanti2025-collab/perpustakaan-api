<?php namespace App\Http\Controllers;

use App\Models\LogAktivitas;

class LogController extends Controller
{
    public function index()
    {
        return response()->json(LogAktivitas::orderBy('created_at', 'desc')->get());
    }
}
