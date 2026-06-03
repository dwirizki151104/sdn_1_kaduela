<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Mengajar;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
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

            $guruUser = User::firstOrCreate(
                ['username' => 'guru1'],
                ['password' => 'password', 'role' => 'guru', 'status' => 'aktif']
            );

            $guru = Guru::firstOrCreate(
                ['id_user' => $guruUser->id_user],
                [
                    'nip' => '198701012026001',
                    'nama_guru' => 'Guru Wali Kelas 1',
                    'jenis_guru' => 'wali_kelas',
                    'no_hp' => '081234567890',
                    'alamat' => 'Kaduela',
                ]
            );

            $kelasSatu = Kelas::where('nama_kelas', 'Kelas 1')->first();
            $kelasSatu->update(['id_wali_kelas' => $guru->id_guru]);

            $tematik = MataPelajaran::where('nama_mapel', 'Tematik')->first();
            Mengajar::firstOrCreate([
                'id_guru' => $guru->id_guru,
                'id_kelas' => $kelasSatu->id_kelas,
                'id_mapel' => $tematik->id_mapel,
            ]);

            $siswaUser = User::firstOrCreate(
                ['username' => 'siswa1'],
                ['password' => 'password', 'role' => 'siswa', 'status' => 'aktif']
            );

            Siswa::firstOrCreate(
                ['id_user' => $siswaUser->id_user],
                [
                    'nis' => '2026001',
                    'nama_siswa' => 'Siswa Contoh',
                    'jk' => 'L',
                    'tanggal_lahir' => '2019-01-10',
                    'alamat' => 'Kaduela',
                    'id_kelas' => $kelasSatu->id_kelas,
                ]
            );
        });
    }
}
