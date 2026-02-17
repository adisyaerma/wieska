<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiketDetail extends Model
{
    protected $table = 'tiket_detail';

    protected $fillable = [
        'id_tiket',
        'id_jenis_tiket',
        'jumlah',
        'subtotal',
    ];

    public function tiket()
    {
        return $this->belongsTo(Tiket::class, 'id_tiket');
    }

    public function jenisTiket()
    {
        return $this->belongsTo(JenisTiket::class, 'id_jenis_tiket');
    }
}
