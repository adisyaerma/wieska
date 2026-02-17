<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $satuans = Satuan::all();
        return view('admin.satuan', compact('satuans'));
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
        $request->validate([
            'satuan' => 'required|string|max:225',
        ]);

        Satuan::create([
            'satuan' => $request->satuan,
        ]);

        return redirect()->back()->with('success', 'Satuan berhasil ditambahkan');
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
        $request->validate([
            'satuan' => 'required|string|max:255',
        ]);

        $satuan = Satuan::findOrFail($id);
        $satuan->update([
            'satuan' => $request->satuan,
        ]);

        return redirect()->back()->with('updated', 'Satuan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $satuan = Satuan::findOrFail($id);
        $satuan->delete();

        return redirect()->back()->with('deleted', 'Satuan berhasil dihapus');
    }
}
