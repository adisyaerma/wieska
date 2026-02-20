<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('stokBarang.barang')->get();
        $stok = StokBarang::all();
        return view('admin.menu', compact('menus', 'stok'));
    }

    public function create()
    {
        $stok = StokBarang::all();
        return view('menu.create', compact('stok'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'harga_jual' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'stok_barang_id' => 'nullable|exists:stok_barang,id',
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('menus', 'public');
        }

        Menu::create([
            'stok_barang_id' => $request->stok_barang_id,
            'harga_jual' => $request->harga_jual,
            'gambar' => $path,
        ]);

        return redirect()->back()->with('success', 'Menu berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $menu = Menu::with('stokBarang')->findOrFail($id);
        return view('menu.show', compact('menu'));
    }

    public function edit(string $id)
    {
        $menu = Menu::findOrFail($id);
        $stok = StokBarang::all();
        return view('menu.edit', compact('menu', 'stok'));
    }

    public function update(Request $request, string $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'harga_jual' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'stok_barang_id' => 'nullable|exists:stok_barang,id',
        ]);

        if ($request->hasFile('gambar')) {
            // hapus file lama jika ada
            if ($menu->gambar) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $menu->gambar = $request->file('gambar')->store('menus', 'public');
        }

        $menu->update([
            'stok_barang_id' => $request->stok_barang_id,
            'harga_jual' => $request->harga_jual,
            'gambar' => $menu->gambar,
        ]);

        return redirect()->back()->with('updated', 'Menu berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $menu = Menu::findOrFail($id);
        if ($menu->gambar) {
            Storage::disk('public')->delete($menu->gambar);
        }
        $menu->delete();

        return redirect()->back()->with('deleted', 'Menu berhasil dihapus');
    }
}
