<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lokasi;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::first();
        return view('admin.lokasi.index', compact('lokasi'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:10'
        ]);

        Lokasi::updateOrCreate(
            ['id' => 1],
            [
                'nama_lokasi' => 'RBTV',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'radius' => $request->radius
            ]
        );

        return back()->with('success', 'Lokasi berhasil disimpan');
    }
}