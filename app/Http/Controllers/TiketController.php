<?php

namespace App\Http\Controllers;

use App\Models\JenisTiket;
use App\Models\Tiket;
use App\Models\TiketDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TiketController extends Controller
{
    public function index()
    {
        $jenis_tikets = JenisTiket::all();
        return view('kasir.kasir_tiket', compact('jenis_tikets'));
    }

    public function store(Request $request)
    {
        try {
            $items = json_decode($request->items, true);
            $request->merge(['items' => $items]);

            $request->validate([
                'nama_pelanggan' => 'nullable|string|max:255',
                'items' => 'required|array|min:1',
                'items.*.id' => 'required|exists:jenis_tiket,id',
                'items.*.qty' => 'required|integer|min:1',
                'items.*.subtotal' => 'required|numeric|min:0',
                'subtotal' => 'required|numeric|min:0',
            ]);

            $tiket = null;

            \DB::transaction(function () use ($request, &$tiket) {
                $idKaryawan = auth()->user()->id_karyawan ?? auth()->id();

                $tiket = Tiket::create([
                    'tanggal' => now(),
                    'id_karyawan' => $idKaryawan,
                    'nama_pelanggan' => $request->nama_pelanggan, // tambah kolom
                    'subtotal' => $request->subtotal,
                ]);

                foreach ($request->items as $item) {
                    TiketDetail::create([
                        'id_tiket' => $tiket->id,
                        'id_jenis_tiket' => $item['id'],
                        'jumlah' => $item['qty'],
                        'subtotal' => $item['subtotal'],
                    ]);
                }
            });

            return response()->json([
                'success' => true,
                'tiket_id' => $tiket->id
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function riwayat()
    {
        // Ambil semua tiket beserta karyawan
        $tiket = Tiket::with('karyawan')->latest()->get();
        return view('admin.riwayat_tiket', compact('tiket'));
    }


    public function riwayatDetail($id)
    {
        // Ambil tiket + detail
        $tiket = Tiket::with(['karyawan', 'details.jenisTiket'])->findOrFail($id);
        $jenisTiket = JenisTiket::all();
        return view('admin.riwayat_tiket_detail', compact('tiket', 'jenisTiket'));
    }

    public function destroy($id)
    {
        $tiket = Tiket::findOrFail($id);
        $tiket->delete();
        return redirect()->back()->with('success', 'Riwayat Tiket berhasil dihapus');
    }

    public function destroyDetail($id)
    {
        DB::transaction(function () use ($id) {
            $detail = TiketDetail::findOrFail($id);

            // ambil parent tiket sebelum delete
            $tiket = $detail->tiket;

            // hapus detail
            $detail->delete();

            // hitung ulang subtotal dari DB (pakai query, bukan cached relation)
            $totalBaru = $tiket->details()->sum('subtotal'); // ini menjalankan query SUM()

            // bila tidak ada detail tersisa set 0
            $tiket->update(['subtotal' => $totalBaru ?? 0]);
        });

        return redirect()->back()->with('success', 'Detail tiket berhasil dihapus');
    }

    public function updateDetail(Request $request, $id)
    {
        $request->validate([
            'jenis_tiket' => 'required|exists:jenis_tiket,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $detail = TiketDetail::findOrFail($id);

        // Ambil harga tiket baru dari tabel jenis_tiket
        $jenisTiket = JenisTiket::findOrFail($request->jenis_tiket);

        // Hitung subtotal baru utk detail
        $subtotal = $jenisTiket->harga * $request->jumlah;

        // Update detail (pakai kolom id_jenis_tiket yg sesuai migration)
        $detail->update([
            'id_jenis_tiket' => $request->jenis_tiket, // ✅ update jenis tiket baru
            'jumlah' => $request->jumlah,
            'subtotal' => $subtotal,
        ]);

        // === Update subtotal tiket induk ===
        $tiket = $detail->tiket; // relasi ke model Tiket
        $totalBaru = $tiket->details()->sum('subtotal');
        $tiket->update([
            'subtotal' => $totalBaru
        ]);

        return redirect()->back()->with('updated', 'Detail tiket berhasil diperbarui.');
    }


    public function struk($id)
    {
        $tiket = Tiket::with(['details.jenisTiket', 'karyawan'])->findOrFail($id);

        if ($tiket->details->isEmpty()) {
            abort(404, 'Detail tiket tidak ditemukan');
        }

        $tiketMasukList = collect();

        foreach ($tiket->details as $detail) {
            for ($i = 1; $i <= $detail->jumlah; $i++) {
                $tiketMasukList->push([
                    'no_tiket' => strtoupper(uniqid('WSK-')),
                    'harga' => $detail->jenisTiket->harga,
                    'tanggal' => $tiket->tanggal,
                    'kasir' => $tiket->karyawan->nama ?? 'Kasir',
                    'nama_pelanggan' => $tiket->nama_pelanggan ?? 'Tamu',
                    'jenis_tiket' => $detail->jenisTiket->jenis_tiket,
                ]);
            }
        }

        return view('admin.struk_tiket', compact('tiket', 'tiketMasukList'));
    }
}
