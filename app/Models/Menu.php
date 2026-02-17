<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'stok_barang_id',
        'gambar',
        'harga_jual',
    ];

    public function stokBarang()
    {
        return $this->belongsTo(StokBarang::class, 'stok_barang_id');
    }
}
