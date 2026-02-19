<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'tanggal',
        'nama',
        'keterangan',
        'kontak',
        'acara',
        'status',
        'harga',
        'id_karyawan',
        'jumlah_orang'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}

