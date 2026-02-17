<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    protected $table = 'tiket';

    protected $fillable = [
        'tanggal',
        'id_karyawan',
        'subtotal',
        'nama_pelanggan',
    ];

    public function details()
    {
        return $this->hasMany(TiketDetail::class, 'id_tiket');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
