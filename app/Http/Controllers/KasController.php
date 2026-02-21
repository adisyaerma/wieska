<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasController extends Controller
{
    public function index()
    {
        /*
    |--------------------------------------------------------------------------
    | KAS MASUK
    |--------------------------------------------------------------------------
    */

        // Hutang (hanya yang Lunas)
        $hutang = DB::table('hutang')
            ->select(
                'tanggal',
                DB::raw('SUM(total_hutang) as total')
            )
            ->where('status', 'Lunas')
            ->groupBy('tanggal');

        // Tiket
        $tiket = DB::table('tiket')
            ->select(
                'tanggal',
                DB::raw('SUM(subtotal) as total')
            )
            ->groupBy('tanggal');

        // Cafe
        $cafe = DB::table('cafe')
            ->select(
                'tanggal',
                DB::raw('SUM(subtotal) as total')
            )
            ->groupBy('tanggal');

        // Booking (hanya Hadir)
        $booking = DB::table('bookings')
            ->select(
                'tanggal',
                DB::raw('SUM(harga) as total')
            )
            ->where('status', 'Hadir')
            ->groupBy('tanggal');

        // Gabung semua kas masuk
        $kasMasukUnion = $hutang
            ->unionAll($tiket)
            ->unionAll($cafe)
            ->unionAll($booking);

        $kasMasuk = DB::table(DB::raw("({$kasMasukUnion->toSql()}) as masuk"))
            ->mergeBindings($kasMasukUnion)
            ->select(
                'tanggal',
                DB::raw('SUM(total) as kas_masuk')
            )
            ->groupBy('tanggal')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | KAS KELUAR
    |--------------------------------------------------------------------------
    */

        // Pengeluaran (VALID saja)
        $pengeluaran = DB::table('pengeluarans')
            ->select(
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('SUM(nominal_pengeluaran) as total')
            )
            ->where('status', 'Valid')
            ->groupBy(DB::raw('DATE(tanggal)'));

        // Barang Masuk
        $barangMasuk = DB::table('barang_masuk')
            ->select(
                'tanggal',
                DB::raw('SUM(total_harga) as total')
            )
            ->groupBy('tanggal');

        // Gabung kas keluar
        $kasKeluarUnion = $pengeluaran
            ->unionAll($barangMasuk);

        $kasKeluar = DB::table(DB::raw("({$kasKeluarUnion->toSql()}) as keluar"))
            ->mergeBindings($kasKeluarUnion)
            ->select(
                'tanggal',
                DB::raw('SUM(total) as kas_keluar')
            )
            ->groupBy('tanggal')
            ->get();


        $dates = $kasMasuk->pluck('tanggal')
            ->merge($kasKeluar->pluck('tanggal'))
            ->unique()
            ->sort();

        $saldo = 0;
        $dataKas = [];

        foreach ($dates as $tanggal) {
            $masuk = $kasMasuk->firstWhere('tanggal', $tanggal)->kas_masuk ?? 0;
            $keluar = $kasKeluar->firstWhere('tanggal', $tanggal)->kas_keluar ?? 0;

            $saldo += ($masuk - $keluar);

            $dataKas[] = [
                'tanggal'     => $tanggal,
                'kas_masuk'   => $masuk,
                'kas_keluar'  => $keluar,
                'saldo_akhir' => $saldo,
            ];
        }

        return view('admin.kas.index', compact('dataKas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
