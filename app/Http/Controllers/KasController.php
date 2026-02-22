<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Cafe;
use App\Models\Hutang;
use App\Models\Pengeluaran;
use App\Models\Tiket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasController extends Controller
{
    public function index()
    {

        // Hutang (hanya yang Lunas)
        $hutang = DB::table('hutang')
            ->select(
                'tanggal',
                DB::raw('SUM(total_hutang) as total')
            )
            // ->where('status', 'Belum Lunas')
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


    public function detail($tanggal)
    {
        $tanggal = Carbon::parse($tanggal)->format('Y-m-d');
        // TIKET
        $tiket = Tiket::with(['karyawan', 'details.jenisTiket'])
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->map(function ($t) {
                return [
                    'tanggal'     => $t->tanggal,
                    'sumber'      => 'Tiket',
                    'keterangan'  => $t->details->pluck('jenisTiket.jenis_tiket')->join(', '),
                    'total'       => $t->subtotal,
                    'petugas'     => optional($t->karyawan)->nama,
                ];
            });

        // CAFE
        $cafe = Cafe::with(['karyawan', 'details.menu.stokBarang'])
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->map(function ($c) {
                return [
                    'tanggal'     => $c->tanggal,
                    'sumber'      => 'Cafe',
                    'keterangan'  => $c->details->pluck('menu.stokBarang.nama_barang')->join(', '),
                    'total'       => $c->subtotal,
                    'petugas'     => optional($c->karyawan)->nama,
                ];
            });
            // dd( $cafe);

        // BOOKING
        $booking = Booking::whereDate('tanggal', $tanggal)
            ->where('status', 'Hadir')
            ->get()
            ->map(function ($b) {
                return [
                    'tanggal'     => $b->tanggal,
                    'sumber'      => 'Booking',
                    'keterangan'  => $b->nama,
                    'total'       => $b->harga,
                    'petugas'     => '-',
                ];
            });

        // HUTANG DIBAYAR (MASUK)
        $hutang = Hutang::whereDate('tanggal', $tanggal)

            ->get()
            ->map(function ($h) {
                return [
                    'tanggal'     => $h->tanggal,
                    'sumber'      => 'Hutang',
                    'keterangan'  => 'Pembayaran Hutang',
                    'total'       => $h->total_hutang,
                    'petugas'     => '-',
                ];
            });

        // 🔥 GABUNG SEMUA (COLLECTION)
        $kasMasuk = collect()
            ->merge($tiket)
            ->merge($cafe)
            ->merge($booking)
            ->merge($hutang)
            ->sortBy('tanggal')
            ->values();

        $kasKeluar = DB::table('pengeluarans')
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'Valid')
            ->get();


        return view('admin.kas.detail', compact(
            'tanggal',
            'kasMasuk',
            'kasKeluar'
        ));
    }
}
