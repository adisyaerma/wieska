<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisTiket;

class JenisTiketController extends Controller
{
    public function index()
    {
        $jenis_tikets = JenisTiket::all();
        return view('admin.jenis_tiket', compact('jenis_tikets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_tiket' => 'required|string|max:225',
            'harga' => 'required|numeric|min:0',
        ]);

        JenisTiket::create([
            'jenis_tiket' => $request->jenis_tiket,
            'harga' => $request->harga,
        ]);

        return redirect()->back()->with('success', 'Jenis Tiket berhasil ditambahkan');

    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'jenis_tiket' => 'required|string|max:225',
            'harga' => 'required|numeric|min:0',
        ]);

        $jenis_tiket = JenisTiket::findOrFail($id);
        $jenis_tiket->update([
            'jenis_tiket' => $request->jenis_tiket,
            'harga' => $request->harga,
        ]);

        return redirect()->back()->with('updated', 'Jenis Tiket berhasil diperbarui');

    }

    public function destroy(string $id)
    {
        $jenis_tiket = JenisTiket::findOrFail($id);
        $jenis_tiket->delete();

        return redirect()->back()->with('deleted', 'Jenis Tiket berhasil dihapus');

    }
}
