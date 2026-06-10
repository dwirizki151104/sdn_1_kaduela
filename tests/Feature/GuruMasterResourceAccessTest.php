<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruMasterResourceAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('Ekstensi pdo_sqlite belum aktif, sementara phpunit.xml memakai SQLite in-memory.');
        }

        parent::setUp();
    }

    public function test_guru_can_only_open_allowed_master_resources(): void
    {
        $guruUser = $this->createGuruUser();

        foreach (['modul', 'tugas', 'pengumpulan-tugas', 'quiz', 'jawaban-siswa', 'nilai-quiz', 'nilai'] as $resource) {
            $this->actingAs($guruUser)
                ->get(route('admin.master.index', $resource))
                ->assertOk();
        }

        foreach (['users', 'guru', 'siswa', 'kelas', 'mata-pelajaran', 'mengajar'] as $resource) {
            $this->actingAs($guruUser)
                ->get(route('admin.master.index', $resource))
                ->assertForbidden();
        }
    }

    public function test_guru_sidebar_only_shows_allowed_master_resources(): void
    {
        $guruUser = $this->createGuruUser();

        $this->actingAs($guruUser)
            ->get(route('admin.master.index', 'modul'))
            ->assertOk()
            ->assertSee('Modul')
            ->assertSee('Tugas')
            ->assertSee('Pengumpulan Tugas')
            ->assertSee('Quiz')
            ->assertSee('Jawaban Siswa')
            ->assertSee('Nilai Quiz')
            ->assertSee('Nilai')
            ->assertDontSee('Data User')
            ->assertDontSee('Data Guru')
            ->assertDontSee('Data Siswa')
            ->assertDontSee('Data Kelas')
            ->assertDontSee('Mata Pelajaran')
            ->assertDontSee('Data Mengajar');
    }

    public function test_guru_cannot_open_academic_api_resources(): void
    {
        $guruUser = $this->createGuruUser();

        foreach (['users', 'guru', 'kelas', 'siswa', 'mata-pelajaran', 'mengajar'] as $resource) {
            $this->actingAs($guruUser)
                ->get("/akademik/{$resource}")
                ->assertForbidden();
        }
    }

    private function createGuruUser(): User
    {
        $guruUser = User::factory()->create(['role' => 'guru', 'status' => 'aktif']);

        Guru::create([
            'id_user' => $guruUser->id_user,
            'nip' => 'AKSES-GURU-001',
            'nama_guru' => 'Guru Akses',
            'jenis_guru' => 'bidang_studi',
        ]);

        return $guruUser;
    }
}
