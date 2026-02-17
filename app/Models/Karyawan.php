<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Karyawan extends Authenticatable
{
    use Notifiable;

    protected $table = 'karyawan';

    protected $fillable = [
        'foto',
        'nama',
        'jabatan',
        'email',
        'no_telp',
        'alamat',
        'tgl_bergabung',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
