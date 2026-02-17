<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    protected $table = 'stok_barang';
    protected $fillable = ['nama_barang', 'kode_barang', 'kategori_id', 'satuan_id', 'total_stok'];

    // relasi
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // accessor untuk status stok otomatis
    public function getStatusStokAttribute()
    {
        if ($this->total_stok == 0) {
            return 'Habis';
        } elseif ($this->total_stok < 10) {
            return 'Hampir Habis';
        } else {
            return 'Tersedia';
        }
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'stok_barang_id');
    }
}
