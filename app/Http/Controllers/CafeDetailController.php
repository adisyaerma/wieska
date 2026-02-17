<?php

namespace App\Http\Controllers;

use App\Models\Cafe;
use App\Models\CafeDetail;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CafeDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($cafe_id)
    {
        $cafe = Cafe::with('details.menu')->findOrFail($cafe_id);
        $menus = Menu::all();
        return view('admin.riwayat_cafe_detail', compact('cafe', 'menus'));
    }

    public function edit(string $id)
    {
        $detail = CafeDetail::findOrFail($id);
        $menus = Menu::all();

        return view('admin.riwayat_cafe_detail', compact('detail', 'menus'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $id) {
            $detail = CafeDetail::findOrFail($id);
            $menuLama = Menu::findOrFail($detail->menu_id);
            $menuBaru = Menu::findOrFail($request->menu_id);

            // 🔹 1. Kembalikan stok lama terlebih dahulu
            if ($menuLama->stok_barang_id) {
                DB::table('stok_barang')
                    ->where('id', $menuLama->stok_barang_id)
                    ->increment('total_stok', $detail->jumlah);
            }

            // 🔹 2. Kurangi stok baru
            if ($menuBaru->stok_barang_id) {
                $stokBaru = DB::table('stok_barang')
                    ->where('id', $menuBaru->stok_barang_id)
                    ->first();

                if (!$stokBaru) {
                    throw new \Exception("Stok untuk menu baru tidak ditemukan!");
                }

                if ($stokBaru->total_stok < $request->jumlah) {
                    throw new \Exception("Stok untuk menu {$menuBaru->nama_menu} tidak mencukupi!");
                }

                DB::table('stok_barang')
                    ->where('id', $menuBaru->stok_barang_id)
                    ->decrement('total_stok', $request->jumlah);
            }

            // 🔹 3. Update data detail
            $detail->update([
                'menu_id' => $request->menu_id,
                'jumlah' => $request->jumlah,
                'subtotal' => $request->jumlah * $menuBaru->harga_jual,
            ]);

            // 🔹 4. Update subtotal di tabel cafe
            $cafe = Cafe::findOrFail($detail->cafe_id);
            $cafe->subtotal = $cafe->details()->sum('subtotal');
            $cafe->save();
        });

        return redirect()->back()->with('updated', 'Detail transaksi & stok berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {
            $detail = CafeDetail::findOrFail($id);
            $menu = Menu::findOrFail($detail->menu_id);

            // 🔹 1. Kembalikan stok saat detail dihapus
            if ($menu->stok_barang_id) {
                DB::table('stok_barang')
                    ->where('id', $menu->stok_barang_id)
                    ->increment('total_stok', $detail->jumlah);
            }

            // 🔹 2. Hapus detail
            $cafe_id = $detail->cafe_id;
            $detail->delete();

            // 🔹 3. Update subtotal di tabel cafe
            $cafe = Cafe::findOrFail($cafe_id);
            $cafe->subtotal = $cafe->details()->sum('subtotal');
            $cafe->save();
        });

        return redirect()->back()->with('deleted', 'Detail transaksi & stok berhasil dihapus');
    }


    public function detail($id)
    {
        $cafe = Cafe::with([
            'karyawan',
            'details.menu.stokBarang' // load sampai barang
        ])->findOrFail($id);

        return redirect()->back()->with('success', 'Karyawan berhasil di hapus');
    }

}

