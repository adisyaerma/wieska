<?php

    namespace App\Http\Controllers;

    use Illuminate\Support\Facades\DB;

    class LabaController extends Controller
    {
        public function index()
        {
            // ambil semua tanggal unik dari tiket & cafe
            $tanggal = DB::query()
                ->fromSub(function ($q) {
                    $q->select('tanggal')->from('tiket')
                    ->union(
                        DB::table('cafe')->select('tanggal')
                    );
                }, 't')
                ->orderBy('tanggal', 'desc')
                ->get();

            $laba = $tanggal->map(function ($row) {

                // OMZET TIKET
                $omzetTiket = DB::table('tiket')
                    ->whereDate('tanggal', $row->tanggal)
                    ->sum('subtotal');

                // OMZET CAFE
                $omzetCafe = DB::table('cafe')
                    ->whereDate('tanggal', $row->tanggal)
                    ->sum('subtotal');

                // MODAL CAFE (HPP barang terjual)
                $modalCafe = DB::table('cafe_detail')
                    ->join('cafe', 'cafe.id', '=', 'cafe_detail.cafe_id')
                    ->join('menus', 'menus.id', '=', 'cafe_detail.menu_id')
                    ->join('stok_barang', 'stok_barang.id', '=', 'menus.stok_barang_id')
                    ->whereDate('cafe.tanggal', $row->tanggal)
                    ->selectRaw('
                        SUM(
                            cafe_detail.jumlah * (
                                SELECT bm.harga_satuan
                                FROM barang_masuk bm
                                WHERE bm.stok_barang_id = stok_barang.id
                                ORDER BY bm.created_at DESC
                                LIMIT 1
                            )
                        ) as modal
                    ')
                    ->value('modal') ?? 0;

                // ✅ MODAL TIKET (KEMBALIAN)
                $modalTiket = DB::table('pengeluarans')
                    ->whereDate('tanggal', $row->tanggal)
                    ->where('jenis_pengeluaran', 'Kembalian Tiket')
                    ->where('status', 'Valid')
                    ->sum('nominal_pengeluaran');

                $kembalianCafe = DB::table('pengeluarans')
                    ->whereDate('tanggal', $row->tanggal)
                    ->where('jenis_pengeluaran', 'Kembalian Cafe')
                    ->where('status', 'Valid')
                    ->sum('nominal_pengeluaran');

                $totalOmzet = $omzetTiket + $omzetCafe;
                $totalModal = $modalCafe + $kembalianCafe + $modalTiket;
                $laba = $totalOmzet - $totalModal;

                return (object)[
                    'tanggal' => $row->tanggal,
                    'omzet_tiket' => $omzetTiket,
                    'modal_tiket' => $modalTiket,
                    'omzet_cafe' => $omzetCafe,
                    'modal_cafe' => $modalCafe+$kembalianCafe,
                    'total_omzet' => $totalOmzet,
                    'total_modal' => $totalModal,
                    'laba' => $laba
                ];
            });
            return view('admin.laba', compact('laba'));
        }
    }
