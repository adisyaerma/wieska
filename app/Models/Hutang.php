<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hutang extends Model
{
    use HasFactory;

    protected $table = 'hutang';

    protected $fillable = [
        'tanggal',
        'pihak',
        'keterangan',
        'total_hutang',
        'tanggal_bayar',
        'jatuh_tempo',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_bayar' => 'date',
        'jatuh_tempo' => 'date',
        'total_hutang' => 'integer',
    ];
}
