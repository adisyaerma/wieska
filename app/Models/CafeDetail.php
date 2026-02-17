<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CafeDetail extends Model
{
    use HasFactory;

    protected $table = 'cafe_detail';

    protected $fillable = [
        'cafe_id',
        'menu_id',
        'jumlah',
        'subtotal',
    ];

    /**
     * Relasi ke cafe (parent transaksi).
     */
    public function cafe()
    {
        return $this->belongsTo(Cafe::class, 'cafe_id');
    }

    /**
     * Relasi ke menu.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /**
     * Hitung subtotal otomatis.
     */
    public function hitungSubtotal()
    {
        $this->subtotal = $this->jumlah * $this->harga_satuan;
        $this->save();

        // update subtotal di parent cafe
        if ($this->cafe) {
            $this->cafe->hitungSubtotal();
        }
    }
}
