<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\StokBarang;
use App\Models\Satuan;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    /**
     * Tampilkan daftar barang masuk
     */
    public function index()
    {
        $barangMasuks = BarangMasuk::with(['stokBarang', 'satuan'])->latest()->get();
        $stokBarangs = StokBarang::all();
        $satuans = Satuan::all();

        return view('admin.barang_masuk', compact('barangMasuks', 'stokBarangs', 'satuans'));
    }

    /**
     * Simpan barang masuk baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'stok_barang_id' => 'required|exists:stok_barang,id',
            'jumlah' => 'required|integer|min:1',
            'satuan_id' => 'required|exists:satuan,id',
            'harga_satuan' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $totalHarga = $request->jumlah * $request->harga_satuan;

        $barangMasuk = BarangMasuk::create([
            'tanggal' => $request->tanggal,
            'stok_barang_id' => $request->stok_barang_id,
            'jumlah' => $request->jumlah,
            'satuan_id' => $request->satuan_id,
            'harga_satuan' => $request->harga_satuan,
            'total_harga' => $totalHarga,
            'catatan' => $request->catatan,
        ]);

        // ✅ Tambahkan jumlah ke stok barang
        $stok = StokBarang::findOrFail($request->stok_barang_id);
        $stok->total_stok += $request->jumlah;
        $stok->save();

        return redirect()->back()->with('success', 'Data barang masuk berhasil ditambahkan');
    }

    /**
     * Update barang masuk
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'stok_barang_id' => 'required|exists:stok_barang,id',
            'jumlah' => 'required|integer|min:1',
            'satuan_id' => 'required|exists:satuan,id',
            'harga_satuan' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $barangMasuk = BarangMasuk::findOrFail($id);
        $totalHarga = $request->jumlah * $request->harga_satuan;

        // ✅ Kembalikan stok lama dulu
        $stokLama = StokBarang::findOrFail($barangMasuk->stok_barang_id);
        $stokLama->total_stok -= $barangMasuk->jumlah;
        $stokLama->save();

        // ✅ Update barang masuk
        $barangMasuk->update([
            'tanggal' => $request->tanggal,
            'stok_barang_id' => $request->stok_barang_id,
            'jumlah' => $request->jumlah,
            'satuan_id' => $request->satuan_id,
            'harga_satuan' => $request->harga_satuan,
            'total_harga' => $totalHarga,
            'catatan' => $request->catatan,
        ]);

        // ✅ Tambahkan stok baru
        $stokBaru = StokBarang::findOrFail($request->stok_barang_id);
        $stokBaru->total_stok += $request->jumlah;
        $stokBaru->save();

        return redirect()->route('barang_masuk.index')->with('success', 'Data barang masuk berhasil diperbarui dan stok diperbarui');
    }


    /**
     * Hapus barang masuk
     */
    public function destroy($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        $barangMasuk->delete();

        return redirect()->route('barang_masuk.index')->with('success', 'Data barang masuk berhasil dihapus');
    }
}
