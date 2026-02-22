<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // =============================
        // 📊 DATA HARI INI
        // =============================
        $jumlahTiketTerjual = DB::table('tiket_detail')
            ->join('tiket', 'tiket_detail.id_tiket', '=', 'tiket.id')
            ->whereDate('tiket.tanggal', $today)
            ->sum('tiket_detail.jumlah');

        $subtotalTiket = DB::table('tiket')
            ->whereDate('tanggal', $today)
            ->sum('subtotal');

        $jumlahCafeTerjual = DB::table('cafe_detail')
            ->join('cafe', 'cafe_detail.cafe_id', '=', 'cafe.id')
            ->whereDate('cafe.tanggal', $today)
            ->sum('cafe_detail.jumlah');

        $subtotalCafe = DB::table('cafe')
            ->whereDate('tanggal', $today)
            ->sum('subtotal');

        $totalPendapatan = $subtotalTiket + $subtotalCafe;

        // =============================
        // 📊 DATA KEMARIN
        // =============================
        $jumlahTiketKemarin = DB::table('tiket_detail')
            ->join('tiket', 'tiket_detail.id_tiket', '=', 'tiket.id')
            ->whereDate('tiket.tanggal', $yesterday)
            ->sum('tiket_detail.jumlah');

        $subtotalTiketKemarin = DB::table('tiket')
            ->whereDate('tanggal', $yesterday)
            ->sum('subtotal');

        $jumlahCafeKemarin = DB::table('cafe_detail')
            ->join('cafe', 'cafe_detail.cafe_id', '=', 'cafe.id')
            ->whereDate('cafe.tanggal', $yesterday)
            ->sum('cafe_detail.jumlah');

        $subtotalCafeKemarin = DB::table('cafe')
            ->whereDate('tanggal', $yesterday)
            ->sum('subtotal');

        $totalPendapatanKemarin = $subtotalTiketKemarin + $subtotalCafeKemarin;

        // =============================
        // 📈 PERSENTASE PERBANDINGAN
        // =============================
        $persenTiket = $this->hitungPersentase($jumlahTiketKemarin, $jumlahTiketTerjual);
        $persenCafe = $this->hitungPersentase($jumlahCafeKemarin, $jumlahCafeTerjual);
        $persenPendapatan = $this->hitungPersentase($totalPendapatanKemarin, $totalPendapatan);

        $topMenus = DB::table('cafe_detail')
            ->join('menus', 'cafe_detail.menu_id', '=', 'menus.id')
            ->join('stok_barang', 'menus.stok_barang_id', '=', 'stok_barang.id')
            ->join('kategori', 'stok_barang.kategori_id', '=', 'kategori.id')
            ->select(
                'stok_barang.nama_barang',
                'kategori.kategori',
                'menus.harga_jual',
                DB::raw('SUM(cafe_detail.jumlah) as total_terjual')
            )
            ->groupBy('stok_barang.id', 'stok_barang.nama_barang', 'kategori.kategori', 'menus.harga_jual')
            ->orderByDesc('total_terjual')
            ->limit(10)
            ->get();

        // =============================
        // 📅 DATA PENJUALAN TIKET MINGGU INI
        // =============================
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $tiketMingguan = DB::table('tiket_detail')
            ->join('tiket', 'tiket_detail.id_tiket', '=', 'tiket.id')
            ->select(
                DB::raw('DAYNAME(tiket.tanggal) as hari'),
                DB::raw('SUM(tiket_detail.jumlah) as total_tiket')
            )
            ->whereBetween('tiket.tanggal', [$startOfWeek, $endOfWeek])
            ->groupBy('hari')
            ->orderBy(DB::raw('FIELD(hari, "Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday")'))
            ->get();

        // 🔁 Ubah hari Inggris ke Bahasa Indonesia
        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        $hari = $tiketMingguan->pluck('hari')->map(function ($day) use ($hariMap) {
            return $hariMap[$day] ?? $day;
        });

        $total = $tiketMingguan->pluck('total_tiket');
        // =============================
        // 💰 TOTAL PENGHASILAN (CAFE + TIKET) SELAMA 1 MINGGU
        // =============================

        $pendapatanMingguan = DB::table('tiket')
            ->select(
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('SUM(subtotal) as total_tiket'),
                DB::raw('0 as total_cafe') // pastikan kolom sama
            )
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->groupBy('tanggal');

        $cafeMingguan = DB::table('cafe')
            ->select(
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('0 as total_tiket'), // pastikan kolom sama
                DB::raw('SUM(subtotal) as total_cafe')
            )
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->groupBy('tanggal');

        // Gabungkan keduanya dengan struktur kolom yang seragam
        $totalMingguan = DB::query()
            ->fromSub($pendapatanMingguan->unionAll($cafeMingguan), 'combined')
            ->select(
                'tanggal',
                DB::raw('SUM(total_tiket + total_cafe) as total_pendapatan')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();


        // Siapkan data untuk chart
        $tanggalPendapatan = $totalMingguan->pluck('tanggal')->map(function ($tgl) {
            return Carbon::parse($tgl)->locale('id')->translatedFormat('l'); // Senin, Selasa, ...
        });
        $totalPendapatanMingguan = $totalMingguan->pluck('total_pendapatan');

        $pendapatanTiketHarian = DB::table('tiket')
            ->select(
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('SUM(subtotal) as total')
            )
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy('tanggal')
            ->get();

        $tanggalTiket = $pendapatanTiketHarian->pluck('tanggal')->map(function ($tgl) {
            return Carbon::parse($tgl)->translatedFormat('d M Y');
        });

        $totalTiketHarian = $pendapatanTiketHarian->pluck('total');

        $pendapatanCafeHarian = DB::table('cafe')
            ->select(
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('SUM(subtotal) as total')
            )
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy('tanggal')
            ->get();

        $tanggalCafe = $pendapatanCafeHarian->pluck('tanggal')->map(function ($tgl) {
            return Carbon::parse($tgl)->translatedFormat('d M Y');
        });

        $totalCafeHarian = $pendapatanCafeHarian->pluck('total');
        return view('admin.dashboard', [
            'jumlahTiketTerjual' => $jumlahTiketTerjual,
            'subtotalTiket' => $subtotalTiket,
            'jumlahCafeTerjual' => $jumlahCafeTerjual,
            'subtotalCafe' => $subtotalCafe,
            'totalPendapatan' => $totalPendapatan,
            'persenTiket' => $persenTiket,
            'persenCafe' => $persenCafe,
            'persenPendapatan' => $persenPendapatan,
            'topMenus' => $topMenus,
            'tiketMingguanHari' => $hari,
            'tiketMingguanTotal' => $total,
            'tanggalPendapatan' => $tanggalPendapatan,
            'totalPendapatanMingguan' => $totalPendapatanMingguan,
            'tanggalTiket' => $tanggalTiket,
            'totalTiketHarian' => $totalTiketHarian,

            // chart cafe
            'tanggalCafe' => $tanggalCafe,
            'totalCafeHarian' => $totalCafeHarian,
        ]);
    }

    /**
     * Hitung persentase perubahan dari data kemarin ke hari ini.
     */
    private function hitungPersentase($kemarin, $hariIni)
    {
        if ($kemarin == 0) {
            return $hariIni > 0 ? 100 : 0;
        }
        return round((($hariIni - $kemarin) / $kemarin) * 100, 2);
    }
}
