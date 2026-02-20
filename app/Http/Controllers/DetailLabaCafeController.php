<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DetailLabaCafeController extends Controller
{
    public function index($tanggal)
    {
        $detail = DB::table('cafe_detail')
            ->join('cafe', 'cafe.id', '=', 'cafe_detail.cafe_id')
            ->join('menus', 'menus.id', '=', 'cafe_detail.menu_id')
            ->join('stok_barang', 'stok_barang.id', '=', 'menus.stok_barang_id')
            ->join('karyawan', 'karyawan.id', '=', 'cafe.id_karyawan')
            ->whereDate('cafe.tanggal', $tanggal)
            ->select(
                'stok_barang.nama_barang',
                'karyawan.nama AS kasir',
                'cafe_detail.jumlah',
                'menus.harga_jual',

                // HARGA BELI TERAKHIR (BENAR)
                DB::raw('(
                    SELECT bm.harga_satuan
                    FROM barang_masuk bm
                    WHERE bm.stok_barang_id = stok_barang.id
                    ORDER BY bm.created_at DESC
                    LIMIT 1
                ) AS harga_beli'),

                'cafe_detail.subtotal',

                // LABA BENAR
                DB::raw('
                    cafe_detail.subtotal -
                    (cafe_detail.jumlah * (
                        SELECT bm.harga_satuan
                        FROM barang_masuk bm
                        WHERE bm.stok_barang_id = stok_barang.id
                        ORDER BY bm.created_at DESC
                        LIMIT 1
                    ))
                    AS laba
                ')
            )
            ->get();

        return view('admin.detail_laba_cafe', compact('detail', 'tanggal'));
    }
}
