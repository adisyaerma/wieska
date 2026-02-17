<?php

namespace App\Http\Controllers;

use App\Models\Cafe;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CafeDetail;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\StokBarang;


class CafeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cafes = Cafe::with('karyawan', 'details')->latest()->get();
        return view('admin.riwayat_cafe', compact('cafes'));
    }

    public function kasir()
    {
        $menus = Menu::all();
        $kategoris = Kategori::all();
        return view('kasir.kasir_cafe', compact('menus', 'kategoris'));
    }


    public function detail($id)
    {
        // ambil data cafe
        $cafe = Cafe::with(['karyawan', 'details.menu'])->findOrFail($id);

        return view('admin.riwayat_cafe_detail', compact('cafe'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karyawans = Karyawan::all();
        return view('admin.cafe.create', compact('karyawans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $items = json_decode($request->items, true);
            $request->merge(['items' => $items]);

            $request->validate([
                'nama_pelanggan' => 'required|string|max:255',
                'id_karyawan' => 'required|exists:karyawan,id',
                'items' => 'required|array',
                'items.*.menu_id' => 'required|exists:menus,id',
                'items.*.jumlah' => 'required|integer|min:1',
                'items.*.subtotal' => 'required|numeric|min:0',
                'dibayarkan' => 'required|numeric|min:0',
            ]);

            // Cek stok sebelum transaksi
            foreach ($request->items as $item) {
                $menu = Menu::find($item['menu_id']);
                if (!$menu || !$menu->stok_barang_id) {
                    return back()->with('error', "Stok untuk menu ini tidak ditemukan.");
                }

                $stokBarang = StokBarang::find($menu->stok_barang_id);
                if (!$stokBarang) {
                    return back()->with('error', "Stok barang terkait tidak ditemukan.");
                }

                if ($item['jumlah'] > $stokBarang->total_stok) {
                    return back()->with('error', "Stok untuk menu {$stokBarang->nama_barang} tidak cukup. Sisa stok: {$stokBarang->total_stok}");
                }
            }

            $cafe = DB::transaction(function () use ($request) {
                $subtotal = collect($request->items)->sum('subtotal');
                $dibayarkan = $request->dibayarkan;
                $kembalian = $dibayarkan - $subtotal;

                $cafe = Cafe::create([
                    'tanggal' => now(),
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'id_karyawan' => $request->id_karyawan,
                    'subtotal' => $subtotal,
                    'dibayarkan' => $dibayarkan,
                    'kembalian' => $kembalian,
                ]);

                foreach ($request->items as $item) {
                    CafeDetail::create([
                        'cafe_id' => $cafe->id,
                        'menu_id' => $item['menu_id'],
                        'jumlah' => $item['jumlah'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    // Kurangi stok dengan benar lewat stok_barang_id
                    $menu = Menu::find($item['menu_id']);
                    $stokBarang = StokBarang::find($menu->stok_barang_id);
                    $stokBarang->total_stok -= $item['jumlah'];
                    $stokBarang->save();
                }

                return $cafe;
            });

            return redirect()->route('cafe.struk_cafe', $cafe->id)
                ->with('success', 'Transaksi berhasil!');

        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cafe = Cafe::with('details.menu')->findOrFail($id);
        return view('admin.cafe.show', compact('cafe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cafe = Cafe::findOrFail($id);
        $karyawans = Karyawan::all();
        return view('admin.cafe.edit', compact('cafe', 'karyawans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_pelanggan' => 'required|string|max:255',
            'id_karyawan' => 'required|exists:karyawan,id',
        ]);

        $cafe = Cafe::findOrFail($id);
        $cafe->update([
            'tanggal' => $request->tanggal,
            'nama_pelanggan' => $request->nama_pelanggan,
            'id_karyawan' => $request->id_karyawan,
        ]);

        return redirect()->route('cafe.index')->with('success', 'Transaksi cafe berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cafe = Cafe::findOrFail($id);
        $cafe->delete();

        return redirect()->back()->with('deleted', 'Riwayat berhasil di hapus');
    }

    public function cetakStruk($id)
    {
        $cafe = Cafe::with('details.menu.stokBarang', 'karyawan')->findOrFail($id);

        // Ambil nilai dari query parameter
        $dibayarkan = request()->get('dibayarkan', 0);
        $kembalian = request()->get('kembalian', 0);

        return view('admin/struk_cafe', compact('cafe', 'dibayarkan', 'kembalian'));
    }


}
