<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use App\Models\Karyawan;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengeluaranController extends Controller
{
    public function index()
    {
        $data = DB::table('pengeluarans')
            ->leftJoin('karyawan', function ($join) {
                $join->on('pengeluarans.refrensi_id', '=', 'karyawan.id')
                    ->where('pengeluarans.jenis_pengeluaran', '=', 'Gaji');
            })
            ->leftJoin('hutang', function ($join) {
                $join->on('pengeluarans.refrensi_id', '=', 'hutang.id')
                    ->where('pengeluarans.jenis_pengeluaran', '=', 'Bayar Hutang');
            })
            ->select(
                'pengeluarans.*',
                'karyawan.nama as nama',
                'hutang.pihak as pihak'
            )
            ->orderBy('pengeluarans.tanggal', 'desc')
            ->get();
        $karyawan = Karyawan::all();
        $hutang = Hutang::all();
        return view('admin.pengeluaran.index', compact('data', 'karyawan', 'hutang'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'jenis_pengeluaran' => 'required',
            'status' => 'required'
        ]);

        $nominal = 0;

        if ($request->jenis_pengeluaran == 'Gaji') {
            $nominal = ($request->gaji_pokok ?? 0)
                - ($request->potongan ?? 0)
                + ($request->bonus ?? 0);
        }

        if (in_array($request->jenis_pengeluaran, ['Operasional', 'Lainnya'])) {
            $nominal = $request->nominal_pengeluaran;
        }
        if ($request->jenis_pengeluaran == 'Bayar Hutang') {
            $nominal = $request->nominal_pengeluaranHutang;
        }

        $pengeluaran = Pengeluaran::create([
            'tanggal' => $request->tanggal,
            'jenis_pengeluaran' => $request->jenis_pengeluaran,
            'refrensi_id' => $request->refrensi_id ?? 0,
            'tujuan_pengeluaran' => $request->tujuan_pengeluaran ?? $request->jenis_pengeluaran,
            'nominal_pengeluaran' => $nominal,
            'gaji_pokok' => $request->gaji_pokok,
            'potongan' => $request->potongan,
            'bonus' => $request->bonus,
            'status' => $request->status,
        ]);

        // Update hutang jika bayar hutang
        if ($request->jenis_pengeluaran == 'Hutang') {
            $hutang = Hutang::find($request->refrensi_id);
            $hutang->sisa_hutang -= $nominal;

            if ($hutang->sisa_hutang <= 0) {
                $hutang->sisa_hutang = 0;
                $hutang->status = 'Lunas';
            }

            $hutang->save();
        }

        return back()->with('success', 'Pengeluaran berhasil disimpan');
    }

    public function show(Pengeluaran $pengeluaran)
    {
        //
    }

    public function filterTanggal(Request $request)
    {
        $query = DB::table('pengeluarans')
            ->leftJoin('karyawan', function ($join) {
                $join->on('pengeluarans.refrensi_id', '=', 'karyawan.id')
                    ->where('pengeluarans.jenis_pengeluaran', 'Gaji');
            })
            ->leftJoin('hutang', function ($join) {
                $join->on('pengeluarans.refrensi_id', '=', 'hutang.id')
                    ->where('pengeluarans.jenis_pengeluaran', 'Bayar Hutang');
            })
            ->select(
                'pengeluarans.*',
                'karyawan.nama as nama',
                'hutang.pihak as pihak'
            );

        $tanggalAwal = Carbon::createFromFormat('Y-m-', $request->tanggal_awal)
            ->format('Y-m-d');

        $tanggalAkhir = Carbon::createFromFormat('Y-m-', $request->tanggal_akhir)
            ->format('Y-m-d');

        // FILTER DATE RANGE
        if ($request->tanggal_awal && $request->tanggal_akhir) {
            $query->whereBetween('pengeluarans.tanggal', [
                $tanggalAwal,
                $tanggalAkhir
            ]);
        }

        $data = $query->orderBy('pengeluarans.tanggal', 'desc')->get();

        return response()->json([
            'data' => $data->map(function ($item, $i) {
                return [
                    'no' => $i + 1,
                    'tanggal' => $item->tanggal,
                    'jenis_pengeluaran' => $item->jenis_pengeluaran,
                    'tujuan' => match ($item->jenis_pengeluaran) {
                        'Gaji' => 'Gaji - ' . ($item->nama ?? '-'),
                        'Bayar Hutang' => 'Bayar Hutang - ' . ($item->pihak ?? '-'),
                        default => $item->tujuan_pengeluaran
                    },
                    'nominal' => 'Rp' . number_format($item->nominal_pengeluaran, 0, ',', '.'),
                    'aksi' => '<span>-</span>'
                ];
            })
        ]);
    }

    public function update(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        $nominal = 0;

        // === GAJI ===
        if ($request->jenis_pengeluaran === 'Gaji') {
            $nominal = ($request->gaji_pokok ?? 0)
                - ($request->potongan ?? 0)
                + ($request->bonus ?? 0);
        }

        // === HUTANG ===
        elseif ($request->jenis_pengeluaran === 'Hutang') {
            $nominal = $request->nominal_hutang;
        }

        // === OPERASIONAL / LAINNYA ===
        else {
            $nominal = $request->nominal_operasional;
        }

        if ($nominal <= 0) {
            return back()->withErrors('Nominal tidak valid');
        }

        $pengeluaran->update([
            'tanggal' => $request->tanggal,
            'jenis_pengeluaran' => $request->jenis_pengeluaran,
            'refrensi_id' => $request->refrensi_id ?? 0,
            'tujuan_pengeluaran' => $request->tujuan_pengeluaran ?? $request->jenis_pengeluaran,
            'nominal_pengeluaran' => $nominal,
            'gaji_pokok' => $request->gaji_pokok,
            'potongan' => $request->potongan,
            'bonus' => $request->bonus,
            'status' => $request->status,
        ]);

        // UPDATE STATUS HUTANG
        if ($request->jenis_pengeluaran === 'Hutang') {
            $hutang = Hutang::find($request->refrensi_id);
            $hutang->sisa_hutang -= $nominal;

            if ($hutang->sisa_hutang <= 0) {
                $hutang->sisa_hutang = 0;
                $hutang->status = 'Lunas';
            }
            $hutang->save();
        }

        return back()->with('success', 'Pengeluaran berhasil diperbarui');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        //
    }
}
