<?php

namespace App\Http\Controllers;

use App\Models\StokBarang;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Kategori;
use Illuminate\Http\Request;

class StokBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stokBarangs = StokBarang::with(['satuan', 'kategori'])->get();
        $satuans = Satuan::all();
        $kategoris = Kategori::all();

        return view('admin.stok_barang', compact('stokBarangs', 'satuans', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'kode_barang' => 'required|string|max:50|unique:stok_barang,kode_barang',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id'   => 'required|exists:satuan,id',
            'total_stok'  => 'required|integer|min:0',
        ]);

        StokBarang::create($request->only('nama_barang', 'kode_barang', 'kategori_id', 'satuan_id', 'total_stok'));

        return redirect()->back()->with('success', 'Stok barang berhasil ditambahkan');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'kode_barang' => 'required|string|max:50|unique:stok_barang,kode_barang,' . $id,
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id'   => 'required|exists:satuan,id',
            'total_stok'  => 'required|integer|min:0',
        ]);

        $stok = StokBarang::findOrFail($id);
        $stok->update($request->only('nama_barang', 'kode_barang', 'kategori_id', 'satuan_id', 'total_stok'));

        return redirect()->back()->with('updated', 'Stok barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stok = StokBarang::findOrFail($id);
        $stok->delete();

        return redirect()->back()->with('deleted', 'Stok barang berhasil dihapus');
    }
}
