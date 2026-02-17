<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Karyawan;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        Karyawan::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'foto' => null,
                'nama' => 'Admin Utama',
                'jabatan' => 'admin',
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Mawar No. 1',
                'tgl_bergabung' => now(),
                'password' => Hash::make('admin123'),
            ]
        );
    }
}
