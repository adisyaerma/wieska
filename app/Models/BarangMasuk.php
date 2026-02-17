<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';

    protected $fillable = [
        'tanggal',
        'stok_barang_id',
        'jumlah',
        'satuan_id',
        'harga_satuan',
        'total_harga',
        'catatan',
    ];

    public function stokBarang()
    {
        return $this->belongsTo(StokBarang::class, 'stok_barang_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
