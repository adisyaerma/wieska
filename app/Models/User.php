<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nama',
        'role',
        'alamat',
        'no_telpon',
        'email',
        'tgl_bergabung',
        'foto',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
