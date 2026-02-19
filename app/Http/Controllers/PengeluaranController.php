<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        $data = Pengeluaran::all();
        $karyawan = Karyawan::all();
        // $hutang = Hutang::all();
        return view('admin.pengeluaran.index', compact('data', 'karyawan'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['tanggal'] = now();
        $data['status'] = 'Valid';

        Pengeluaran::create($data);

        // Jika bayar hutang
        if ($request->jenis_pengeluaran === 'Hutang') {
            $hutang = Hutang::find($request->refrensi_id);

            $hutang->sisa_hutang -= $request->nominal_pengeluaran;

            if ($hutang->sisa_hutang <= 0) {
                $hutang->status = 'Lunas';
                $hutang->sisa_hutang = 0;
            }

            $hutang->save();
        }

        return redirect()->back()->with('success', 'Pengeluaran berhasil ditambahkan');
    }


    public function show(Pengeluaran $pengeluaran)
    {
        //
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        //
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        //
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        //
    }
}
