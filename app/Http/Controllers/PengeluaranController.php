<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use App\Models\Karyawan;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\select;

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
                    ->where('pengeluarans.jenis_pengeluaran', '=', 'Hutang');
            })
            ->select(
                'pengeluarans.*',
                'karyawan.nama as nama',
                'hutang.pihak as pihak'
            )
            ->orderBy('pengeluarans.tanggal', 'desc')
            ->get();
        $karyawan = Karyawan::all();
        $hutang = Hutang::where('status', '!=', 'Lunas')->get();
        $hutang_edit = Hutang::all();
        return view('admin.pengeluaran.index', compact('data', 'karyawan', 'hutang', 'hutang_edit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_pengeluaran' => 'required',
            'status' => 'required'
        ]);

        $nominal = 0;
        $refrensi = null;
        $nominalHutang = 0;

        // 🔹 Tentukan nominal & refrensi
        switch ($request->jenis_pengeluaran) {
            case 'Gaji':
                $nominal = ($request->gaji_pokok ?? 0)
                    - ($request->potongan ?? 0)
                    + ($request->bonus ?? 0);
                $refrensi = $request->refrensi_id_gaji ?? null;
                break;

            case 'Operasional':
            case 'Lainnya':
                $nominal = $request->nominal_pengeluaran ?? 0;
                break;

            case 'Hutang':
                $nominal = $request->nominal_pengeluaran_hutang ?? 0;
                $nominalHutang = $nominal;
                $refrensi = $request->refrensi_id_hutang ?? null;
                break;

            case 'Kembalian':
                $nominal = $request->nominal_kembalian ?? 0;
                $refrensi = 0;
                break;
        }

        // 🔹 Simpan pengeluaran
        $pengeluaran = Pengeluaran::create([
            'tanggal' => $request->tanggal,
            'jenis_pengeluaran' => $request->jenis_pengeluaran,
            'refrensi_id' => $refrensi,
            'tujuan_pengeluaran' => $request->tujuan_pengeluaran ?? $request->jenis_pengeluaran,
            'nominal_pengeluaran' => $nominal,
            'gaji_pokok' => $request->gaji_pokok,
            'potongan' => $request->potongan,
            'bonus' => $request->bonus,
            'status' => $request->status,
        ]);

        // 🔹 Update hutang jika jenis = Hutang
        if ($request->jenis_pengeluaran === 'Hutang' && $request->status !== 'Dibatalkan') {
            $hutang = Hutang::find($refrensi);

            if (!$hutang) {
                return back()->withErrors('Data hutang tidak ditemukan');
            }

            $sisa = $hutang->sisa_hutang - $nominalHutang;
            $hutang->sisa_hutang = max($sisa, 0);

            if ($sisa <= 0) {
                $hutang->status = 'Lunas';
                $hutang->tanggal_bayar = $request->tanggal;
            } else {
                $hutang->status = 'Belum Lunas';
                $hutang->tanggal_bayar = null;
            }

            $hutang->save();
        }

        return redirect()->route('pengeluaran.index')
            ->with('new_pengeluaran_id', $pengeluaran->id);
    }


    public function print($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id); // ambil dulu data pengeluaran

        $data = DB::table('pengeluarans')
            ->leftJoin('karyawan', function ($join) use ($pengeluaran) {
                $join->on('pengeluarans.refrensi_id', '=', 'karyawan.id')
                    ->where('pengeluarans.jenis_pengeluaran', 'Gaji');
            })
            ->leftJoin('hutang', function ($join) use ($pengeluaran) {
                $join->on('pengeluarans.refrensi_id', '=', 'hutang.id')
                    ->where('pengeluarans.jenis_pengeluaran', 'Hutang');
            })
            ->select(
                'pengeluarans.*',
                'karyawan.nama as nama',
                'karyawan.jabatan',
                'hutang.pihak as pihak',
                'hutang.total_hutang',
                'hutang.status as status'
            )
            ->where('pengeluarans.id', $id)
            ->first();
        return view('admin.pengeluaran.print', compact('data'));
    }
    public function gaji($id)
    {
        $data = Pengeluaran::findOrFail($id);
        $data = DB::table('pengeluarans')
            ->leftJoin('karyawan', 'pengeluarans.refrensi_id', '=', 'karyawan.id')
            ->where('pengeluarans.jenis_pengeluaran', 'Gaji')
            ->select(
                'pengeluarans.*',
                'karyawan.nama as nama_karyawan',
                'karyawan.jabatan'
            )
            ->first();
        return view('admin.pengeluaran.detail.gaji', compact('data'));
    }

    public function hutang($id)
    {
        $data = DB::table('pengeluarans')
            ->leftJoin('hutang', 'pengeluarans.refrensi_id', '=', 'hutang.id')
            ->where('pengeluarans.id', $id)   // <- filter berdasarkan id
            ->select(
                'pengeluarans.*',
                'hutang.*'
            )
            ->first();

        return view('admin.pengeluaran.detail.hutang', compact('data'));
        return view('admin.pengeluaran.detail.hutang', compact('data'));
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
                    ->where('pengeluarans.jenis_pengeluaran', 'Hutang');
            })
            ->select(
                'pengeluarans.*',
                'karyawan.nama as nama',
                'hutang.*',
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
                        'Hutang' => 'Hutang - ' . ($item->pihak ?? '-'),
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

        $nominalLama = $pengeluaran->nominal_pengeluaran;
        $statusLama  = $pengeluaran->status;

        // === HITUNG NOMINAL BARU ===
        if ($request->jenis_pengeluaran === 'Hutang') {
            $nominalBaru = $request->nominal_hutang;
        } elseif ($request->jenis_pengeluaran === 'Gaji') {
            $nominalBaru = ($request->gaji_pokok ?? 0)
                - ($request->potongan ?? 0)
                + ($request->bonus ?? 0);
        } elseif ($request->jenis_pengeluaran === 'Kembalian') {
            $nominalBaru = $request->nominal_kembalian;
        } else {
            $nominalBaru = $request->nominal_operasional;
        }

        if ($nominalBaru <= 0) {
            return back()->withErrors('Nominal tidak valid');
        }

        // ================== HUTANG ==================
        if ($pengeluaran->jenis_pengeluaran === 'Hutang') {
            $hutang = Hutang::find($pengeluaran->refrensi_id);

            if (!$hutang) {
                return back()->withErrors('Data hutang tidak ditemukan');
            }

            // 🔁 ROLLBACK JIKA DULU AKTIF
            if ($statusLama !== 'Dibatalkan') {
                $hutang->sisa_hutang += $nominalLama;
            }

            // 🔕 JIKA SEKARANG DIBATALKAN → STOP
            if ($request->status === 'Dibatalkan') {
                $hutang->status = 'Belum Lunas';
                $hutang->tanggal_bayar = null;
                $hutang->save();
            } else {
                // ➖ POTONG ULANG
                $sisaBaru = $hutang->sisa_hutang - $nominalBaru;

                if ($sisaBaru < 0) {
                    return back()->withErrors('Nominal melebihi sisa hutang');
                }

                $hutang->sisa_hutang = $sisaBaru;

                if ($sisaBaru == 0) {
                    $hutang->status = 'Lunas';
                    $hutang->tanggal_bayar = $request->tanggal;
                } else {
                    $hutang->status = 'Belum Lunas';
                    $hutang->tanggal_bayar = null;
                }

                $hutang->save();
            }
        }

        // ================== UPDATE PENGELUARAN ==================
        $pengeluaran->update([
            'tanggal' => $request->tanggal,
            'jenis_pengeluaran' => $request->jenis_pengeluaran,
            'refrensi_id' => $request->refrensi_id ?? 0,
            'tujuan_pengeluaran' => $request->tujuan_pengeluaran ?? $request->jenis_pengeluaran,
            'nominal_pengeluaran' => $nominalBaru,
            'gaji_pokok' => $request->gaji_pokok,
            'potongan' => $request->potongan,
            'bonus' => $request->bonus,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Pengeluaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        if ($pengeluaran->jenis_pengeluaran === 'Hutang') {

            $hutang = Hutang::find($pengeluaran->refrensi_id);

            if ($hutang) {
                $hutang->total_hutang += $pengeluaran->nominal_pengeluaran;

                // Ubah status menjadi Belum Lunas
                $hutang->status = 'Belum Lunas';

                $hutang->save();
            }
        }

        // Hapus data pengeluaran
        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil dihapus dan hutang diperbarui.');
    }
}
