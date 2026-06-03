<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'admin'],
            ['password' => 'password', 'role' => 'admin', 'status' => 'aktif']
        );

        foreach (range(1, 6) as $tingkat) {
            Kelas::firstOrCreate(['nama_kelas' => "Kelas {$tingkat}"]);
        }

        foreach ([
            ['nama_mapel' => 'Tematik', 'kategori' => 'tematik'],
            ['nama_mapel' => 'PJOK', 'kategori' => 'khusus'],
            ['nama_mapel' => 'PAI', 'kategori' => 'khusus'],
            ['nama_mapel' => 'SBK', 'kategori' => 'khusus'],
        ] as $mapel) {
            MataPelajaran::firstOrCreate(['nama_mapel' => $mapel['nama_mapel']], $mapel);
        }
    }
}
