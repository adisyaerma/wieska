<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HutangController extends Controller
{
    public function index()
    {
        // update otomatis status jatuh tempo
        Hutang::where('status', 'Belum Lunas')
            ->whereDate('jatuh_tempo', '<', now())
            ->update(['status' => 'Jatuh Tempo']);

        $hutangs = Hutang::orderBy('tanggal', 'desc')->get();
        return view('admin.hutang', compact('hutangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'pihak' => 'required',
            'total_hutang' => 'required|integer',
            'jatuh_tempo' => 'required|date',
        ]);

        Hutang::create([
            'tanggal' => $request->tanggal,
            'pihak' => $request->pihak,
            'keterangan' => $request->keterangan,
            'total_hutang' => $request->total_hutang,
            'jatuh_tempo' => $request->jatuh_tempo,
            'status' => 'Belum Lunas',
        ]);

        return back()->with('success', 'Hutang ditambahkan');
    }

    public function update(Request $request, Hutang $hutang)
    {
        $hutang->update($request->all());
        return back()->with('success', 'Hutang diupdate');
    }

    public function destroy(Hutang $hutang)
    {
        $hutang->delete();
        return back()->with('success', 'Hutang dihapus');
    }

    // ✅ konfirmasi lunas
    public function lunas(Hutang $hutang)
    {
        if ($hutang->status === 'Belum Lunas') {
            $hutang->update([
                'status' => 'Lunas',
                'tanggal_bayar' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }
}
