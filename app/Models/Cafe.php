<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cafe extends Model
{
    use HasFactory;

    protected $table = 'cafe';

    protected $fillable = [
        'tanggal',
        'nama_pelanggan',
        'id_karyawan',
        'subtotal',
        'dibayarkan', 
        'kembalian',
    ];

    // ✅ Cast kolom tanggal ke datetime
    protected $casts = [
        'tanggal' => 'datetime',
    ];

    /**
     * Relasi ke karyawan (kasir).
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    /**
     * Relasi ke detail cafe.
     */
    public function details()
    {
        return $this->hasMany(CafeDetail::class, 'cafe_id');
    }

    /**
     * Hitung ulang subtotal dari cafe_detail.
     */
    public function hitungSubtotal()
    {
        $this->subtotal = $this->details()->sum('subtotal');
        $this->save();
    }
}
