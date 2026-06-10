<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Mengajar;
use App\Models\Modul;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruModulScopeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('Ekstensi pdo_sqlite belum aktif, sementara phpunit.xml memakai SQLite in-memory.');
        }

        parent::setUp();
    }

    public function test_guru_only_sees_their_own_modules(): void
    {
        [$guruUser, $ownModul, $otherModul] = $this->createModulesForTwoTeachers();

        $this->actingAs($guruUser)
            ->get(route('admin.master.index', 'modul'))
            ->assertOk()
            ->assertSee($ownModul->judul_modul)
            ->assertDontSee($otherModul->judul_modul);
    }

    public function test_guru_without_modules_sees_empty_module_page(): void
    {
        $this->createModulesForTwoTeachers();
        $emptyGuruUser = User::factory()->create(['role' => 'guru', 'status' => 'aktif']);
        Guru::create([
            'id_user' => $emptyGuruUser->id_user,
            'nip' => 'MODUL-KOSONG',
            'nama_guru' => 'Guru Belum Memberi Materi',
            'jenis_guru' => 'bidang_studi',
        ]);

        $this->actingAs($emptyGuruUser)
            ->get(route('admin.master.index', 'modul'))
            ->assertOk()
            ->assertSee('Belum ada data.')
            ->assertDontSee('Materi Guru Satu')
            ->assertDontSee('Materi Guru Dua');
    }

    private function createModulesForTwoTeachers(): array
    {
        $mapel = MataPelajaran::create([
            'nama_mapel' => 'Tematik',
            'kategori' => 'tematik',
        ]);

        $kelasOne = Kelas::create(['nama_kelas' => 'Kelas 1']);
        $kelasTwo = Kelas::create(['nama_kelas' => 'Kelas 2']);

        $guruUserOne = User::factory()->create(['role' => 'guru', 'status' => 'aktif']);
        $guruUserTwo = User::factory()->create(['role' => 'guru', 'status' => 'aktif']);

        $guruOne = Guru::create([
            'id_user' => $guruUserOne->id_user,
            'nip' => 'MODUL-001',
            'nama_guru' => 'Guru Satu',
            'jenis_guru' => 'bidang_studi',
        ]);

        $guruTwo = Guru::create([
            'id_user' => $guruUserTwo->id_user,
            'nip' => 'MODUL-002',
            'nama_guru' => 'Guru Dua',
            'jenis_guru' => 'bidang_studi',
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

        $ownModul = Modul::create([
            'id_mengajar' => $mengajarOne->id_mengajar,
            'judul_modul' => 'Materi Guru Satu',
            'file_modul' => 'modul/materi-satu.pdf',
            'tanggal_upload' => now(),
        ]);

        $otherModul = Modul::create([
            'id_mengajar' => $mengajarTwo->id_mengajar,
            'judul_modul' => 'Materi Guru Dua',
            'file_modul' => 'modul/materi-dua.pdf',
            'tanggal_upload' => now(),
        ]);

        return [$guruUserOne, $ownModul, $otherModul];
    }
}
