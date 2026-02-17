<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawans = Karyawan::all();
        return view('admin.karyawan', compact('karyawans'));
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
        // Validasi input
        $validated = $request->validate([
            'nama' => 'nullable|string|max:225',
            'jabatan' => 'nullable|string|max:225',
            'email' => 'nullable|email|max:225|unique:karyawan,email',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:225',
            'tgl_bergabung' => 'nullable|date',
            'password' => 'nullable|string|max:225',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Simpan foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('karyawan', 'public');
        }

        Karyawan::create([
            'nama' => $validated['nama'],
            'jabatan' => $validated['jabatan'],
            'email' => $validated['email'],
            'no_telp' => $validated['no_telp'],
            'alamat' => $validated['alamat'],
            'tgl_bergabung' => $validated['tgl_bergabung'],
            'password' => !empty($validated['password']) ? Hash::make($validated['password']) : null,
            'foto' => $fotoPath,
        ]);


        return redirect()->back()->with('success', 'Karyawan berhasil ditambahkan!');
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
    public function edit($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        return view('admin.karyawan_detail', compact('karyawan'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:225',
            'jabatan' => 'required|string|max:225',
            'email' => 'required|email|max:225|unique:karyawan,email,' . $id,
            'no_telp' => 'required|string|max:20',
            'alamat' => 'required|string|max:225',
            'tgl_bergabung' => 'required|date',
            'password' => 'nullable|string|min:6',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Hapus password dari array agar tidak diisi otomatis
        $data = collect($validated)->except('password')->toArray();

        $karyawan->fill($data);

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $karyawan->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('karyawan', 'public');
            $karyawan->foto = $path;
        }

        $karyawan->save();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();

        return redirect()->back()->with('deleted', 'Karyawan berhasil dihapus!');
    }

    public function getData()
    {
        $data = Karyawan::select('nama', 'jabatan', 'email', 'no_telp', 'alamat', 'tgl_bergabung')->get();
        return response()->json(['data' => $data]);
    }

}
