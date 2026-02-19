<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    // 📄 tampilkan semua booking
    public function index()
    {

        Booking::where('status', 'Pending')
            ->whereDate('tanggal', '<', Carbon::today())
            ->update(['status' => 'Batal']); 
        $bookings = Booking::with('karyawan')->orderBy('tanggal', 'desc')->get();
        $karyawans = Karyawan::all();

        return view('admin.booking', compact('bookings', 'karyawans'));
    }

    // 💾 simpan booking baru
     public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama' => 'required|string',
            'kontak' => 'required|string',
            'acara' => 'required|string',
            'harga' => 'required|string',
            'jumlah_orang' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        Booking::create([
            'tanggal' => $request->tanggal,
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'kontak' => $request->kontak,
            'acara' => $request->acara,
            'status' => 'Pending', // default
            'harga' => $request->harga,
            'jumlah_orang' => $request->jumlah_orang,
            'id_karyawan' => auth()->id(), // 🔥 OTOMATIS DARI LOGIN
        ]);

        return redirect()->back()->with('success', 'Booking berhasil ditambahkan');
    }

    // ✏️ update booking
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama' => 'required|string',
            'kontak' => 'required|string',
            'acara' => 'required|string',
            'harga' => 'required|string',
            'jumlah_orang' => 'required|integer|min:1',
            'status' => 'required|in:Pending,Hadir,Batal',
            'keterangan' => 'nullable|string',
        ]);

        $booking = Booking::findOrFail($id);

        $booking->update([
            'tanggal' => $request->tanggal,
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'kontak' => $request->kontak,
            'acara' => $request->acara,
            'status' => $request->status,
            'harga' => $request->harga,
            'jumlah_orang' => $request->jumlah_orang,
            'id_karyawan' => auth()->id(), // 🔒 TETAP AMBIL DARI LOGIN
        ]);

        return redirect()->back()->with('updated', 'Booking berhasil diperbarui');
    }


    // ❌ hapus booking
    public function destroy($id)
    {
        Booking::findOrFail($id)->delete();

        return redirect()->back()->with('deleted', 'Booking berhasil dihapus');
    }

    // 🔁 ubah status cepat
    public function updateStatus($id, $status)
    {
        if (!in_array($status, ['Pending', 'Hadir', 'Batal'])) {
            abort(404);
        }

        Booking::findOrFail($id)->update([
            'status' => $status
        ]);

        return redirect()->back()->with('updated', 'Status booking diperbarui');
    }

    public function hadir($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'Hadir';
        $booking->save();

        return response()->json(['success' => true]);
    }

}
