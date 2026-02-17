<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua barang beserta kategori
        $barangs = Barang::with('kategori')->get();
        $kategoris = Kategori::all();

        return view('admin.barang', compact('barangs', 'kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.barang-create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang'  => 'required|string|max:255',
            'kode_barang'  => 'required|string|max:100|unique:barang,kode_barang',
            'kategori_id'  => 'required|exists:kategori,id',
        ]);

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'kode_barang' => $request->kode_barang,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $barang = Barang::with('kategori')->findOrFail($id);
        return view('admin.barang-show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $barang = Barang::findOrFail($id);
        $kategoris = Kategori::all();
        return view('admin.barang-edit', compact('barang', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_barang'  => 'required|string|max:255',
            'kode_barang'  => 'required|string|max:100|unique:barang,kode_barang,' . $id,
            'kategori_id'  => 'required|exists:kategori,id',
        ]);

        $barang = Barang::findOrFail($id);

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'kode_barang' => $request->kode_barang,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->back()->with('updated', 'Barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->back()->with('deleted', 'Barang berhasil dihapus');
    }
}
