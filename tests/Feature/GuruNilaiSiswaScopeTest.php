<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Mengajar;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruNilaiSiswaScopeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('Ekstensi pdo_sqlite belum aktif, sementara phpunit.xml memakai SQLite in-memory.');
        }

        parent::setUp();
    }

    public function test_guru_only_sees_scores_for_their_taught_class_and_subject(): void
    {
        [$guruUser, $ownSiswa, $otherSiswa] = $this->createScoresForTwoTeachers();

        $this->actingAs($guruUser)
            ->get(route('admin.master.index', 'nilai'))
            ->assertOk()
            ->assertSee($ownSiswa->nama_siswa)
            ->assertSee('Kelas 1')
            ->assertSee('91.00')
            ->assertDontSee($otherSiswa->nama_siswa)
            ->assertDontSee('73.00');
    }

    public function test_guru_cannot_store_score_outside_their_taught_class_and_subject(): void
    {
        [$guruUser, , $otherSiswa, $mapel] = $this->createScoresForTwoTeachers();

        $this->actingAs($guruUser)
            ->post(route('admin.master.store', 'nilai'), [
                'id_siswa' => $otherSiswa->id_siswa,
                'id_mapel' => $mapel->id_mapel,
                'semester' => '1',
                'nilai_akhir' => 80,
            ])
            ->assertForbidden();
    }

    private function createScoresForTwoTeachers(): array
    {
        $mapel = MataPelajaran::create([
            'nama_mapel' => 'Tematik',
            'kategori' => 'tematik',
        ]);

        $kelasOne = Kelas::create(['nama_kelas' => 'Kelas 1']);
        $kelasTwo = Kelas::create(['nama_kelas' => 'Kelas 2']);

        $guruUserOne = User::factory()->create(['role' => 'guru', 'status' => 'aktif']);
        $guruUserTwo = User::factory()->create(['role' => 'guru', 'status' => 'aktif']);
        $siswaUserOne = User::factory()->create(['role' => 'siswa', 'status' => 'aktif']);
        $siswaUserTwo = User::factory()->create(['role' => 'siswa', 'status' => 'aktif']);

        $guruOne = Guru::create([
            'id_user' => $guruUserOne->id_user,
            'nip' => 'NILAI-001',
            'nama_guru' => 'Guru Satu',
            'jenis_guru' => 'bidang_studi',
        ]);

        $guruTwo = Guru::create([
            'id_user' => $guruUserTwo->id_user,
            'nip' => 'NILAI-002',
            'nama_guru' => 'Guru Dua',
            'jenis_guru' => 'bidang_studi',
        ]);

        $siswaOne = Siswa::create([
            'id_user' => $siswaUserOne->id_user,
            'nis' => 'NILAI-SISWA-001',
            'nama_siswa' => 'Siswa Nilai Satu',
            'jk' => 'L',
            'id_kelas' => $kelasOne->id_kelas,
        ]);

        $siswaTwo = Siswa::create([
            'id_user' => $siswaUserTwo->id_user,
            'nis' => 'NILAI-SISWA-002',
            'nama_siswa' => 'Siswa Nilai Dua',
            'jk' => 'P',
            'id_kelas' => $kelasTwo->id_kelas,
        ]);

        Mengajar::create([
            'id_guru' => $guruOne->id_guru,
            'id_kelas' => $kelasOne->id_kelas,
            'id_mapel' => $mapel->id_mapel,
        ]);

        Mengajar::create([
            'id_guru' => $guruTwo->id_guru,
            'id_kelas' => $kelasTwo->id_kelas,
            'id_mapel' => $mapel->id_mapel,
        ]);

        Nilai::create([
            'id_siswa' => $siswaOne->id_siswa,
            'id_mapel' => $mapel->id_mapel,
            'semester' => '1',
            'nilai_tugas' => 90,
            'nilai_quiz' => 92,
            'nilai_uts' => 91,
            'nilai_uas' => 91,
            'nilai_akhir' => 91,
        ]);

        Nilai::create([
            'id_siswa' => $siswaTwo->id_siswa,
            'id_mapel' => $mapel->id_mapel,
            'semester' => '1',
            'nilai_tugas' => 72,
            'nilai_quiz' => 74,
            'nilai_uts' => 73,
            'nilai_uas' => 73,
            'nilai_akhir' => 73,
        ]);

        return [$guruUserOne, $siswaOne, $siswaTwo, $mapel];
    }
}
