<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'nama_barang',
        'kode_barang',
        'kategori_id',
    ];

    /**
     * Relasi ke tabel kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function stokBarang()
    {
        return $this->hasMany(StokBarang::class, 'barang_id');
    }
}
