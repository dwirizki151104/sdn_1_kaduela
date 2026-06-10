<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Mengajar;
use App\Models\PengumpulanTugas;
use App\Models\Siswa;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruTugasScopeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('Ekstensi pdo_sqlite belum aktif, sementara phpunit.xml memakai SQLite in-memory.');
        }

        parent::setUp();
    }

    public function test_guru_only_sees_their_own_tasks(): void
    {
        [$guruUser, $ownTugas, $otherTugas] = $this->createTasksForTwoTeachers();

        $this->actingAs($guruUser)
            ->get(route('admin.master.index', 'tugas'))
            ->assertOk()
            ->assertSee($ownTugas->judul_tugas)
            ->assertDontSee($otherTugas->judul_tugas);
    }

    public function test_guru_only_sees_submissions_for_their_own_tasks(): void
    {
        [$guruUser, $ownTugas, $otherTugas] = $this->createTasksForTwoTeachers();

        $this->actingAs($guruUser)
            ->get(route('admin.master.index', 'pengumpulan-tugas'))
            ->assertOk()
            ->assertSee($ownTugas->judul_tugas)
            ->assertSee('90.00')
            ->assertDontSee($otherTugas->judul_tugas)
            ->assertDontSee('70.00');
    }

    public function test_guru_without_tasks_sees_empty_task_and_submission_pages(): void
    {
        $this->createTasksForTwoTeachers();
        $emptyGuruUser = User::factory()->create(['role' => 'guru', 'status' => 'aktif']);
        Guru::create([
            'id_user' => $emptyGuruUser->id_user,
            'nip' => 'TUGAS-KOSONG',
            'nama_guru' => 'Guru Belum Membuat Tugas',
            'jenis_guru' => 'bidang_studi',
        ]);

        $this->actingAs($emptyGuruUser)
            ->get(route('admin.master.index', 'tugas'))
            ->assertOk()
            ->assertSee('Belum ada data.')
            ->assertDontSee('Tugas Guru Satu')
            ->assertDontSee('Tugas Guru Dua');

        $this->actingAs($emptyGuruUser)
            ->get(route('admin.master.index', 'pengumpulan-tugas'))
            ->assertOk()
            ->assertSee('Belum ada data.')
            ->assertDontSee('Tugas Guru Satu')
            ->assertDontSee('Tugas Guru Dua');
    }

    private function createTasksForTwoTeachers(): array
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
            'nip' => 'TUGAS-001',
            'nama_guru' => 'Guru Satu',
            'jenis_guru' => 'bidang_studi',
        ]);

        $guruTwo = Guru::create([
            'id_user' => $guruUserTwo->id_user,
            'nip' => 'TUGAS-002',
            'nama_guru' => 'Guru Dua',
            'jenis_guru' => 'bidang_studi',
        ]);

        $siswaOne = Siswa::create([
            'id_user' => $siswaUserOne->id_user,
            'nis' => 'TUGAS-SISWA-001',
            'nama_siswa' => 'Siswa Satu',
            'jk' => 'L',
            'id_kelas' => $kelasOne->id_kelas,
        ]);

        $siswaTwo = Siswa::create([
            'id_user' => $siswaUserTwo->id_user,
            'nis' => 'TUGAS-SISWA-002',
            'nama_siswa' => 'Siswa Dua',
            'jk' => 'P',
            'id_kelas' => $kelasTwo->id_kelas,
        ]);

        $mengajarOne = Mengajar::create([
            'id_guru' => $guruOne->id_guru,
            'id_kelas' => $kelasOne->id_kelas,
            'id_mapel' => $mapel->id_mapel,
        ]);

        $mengajarTwo = Mengajar::create([
            'id_guru' => $guruTwo->id_guru,
            'id_kelas' => $kelasTwo->id_kelas,
            'id_mapel' => $mapel->id_mapel,
        ]);

        $ownTugas = Tugas::create([
            'id_mengajar' => $mengajarOne->id_mengajar,
            'judul_tugas' => 'Tugas Guru Satu',
            'deskripsi' => 'Tugas milik guru satu',
            'deadline' => now()->addDay(),
        ]);

        $otherTugas = Tugas::create([
            'id_mengajar' => $mengajarTwo->id_mengajar,
            'judul_tugas' => 'Tugas Guru Dua',
            'deskripsi' => 'Tugas milik guru dua',
            'deadline' => now()->addDay(),
        ]);

        PengumpulanTugas::create([
            'id_tugas' => $ownTugas->id_tugas,
            'id_siswa' => $siswaOne->id_siswa,
            'file_jawaban' => 'pengumpulan-tugas/jawaban-satu.pdf',
            'tanggal_kumpul' => now(),
            'nilai' => 90,
        ]);

        PengumpulanTugas::create([
            'id_tugas' => $otherTugas->id_tugas,
            'id_siswa' => $siswaTwo->id_siswa,
            'file_jawaban' => 'pengumpulan-tugas/jawaban-dua.pdf',
            'tanggal_kumpul' => now(),
            'nilai' => 70,
        ]);

        return [$guruUserOne, $ownTugas, $otherTugas];
    }
}
