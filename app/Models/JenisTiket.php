<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisTiket extends Model
{
    protected $table = 'jenis_tiket'; 
    protected $fillable = [
        'jenis_tiket',
        'harga',
    ];
}
