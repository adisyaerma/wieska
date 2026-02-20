<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DetailLabaTiketController extends Controller
{
    public function index($tanggal)
    {
        $detail = DB::table('tiket_detail')
            ->join('tiket', 'tiket.id', '=', 'tiket_detail.id_tiket')
            ->join('jenis_tiket', 'jenis_tiket.id', '=', 'tiket_detail.id_jenis_tiket')
            ->join('karyawan', 'karyawan.id', '=', 'tiket.id_karyawan')
            ->whereDate('tiket.tanggal', $tanggal)
            ->select(
                'jenis_tiket.jenis_tiket AS jenis_tiket',
                'karyawan.nama AS kasir',
                'tiket_detail.jumlah',
                'jenis_tiket.harga AS harga_satuan',
                'tiket_detail.subtotal'
            )
            ->get();

        return view('admin.detail_laba_tiket', compact('detail', 'tanggal'));
    }
}
