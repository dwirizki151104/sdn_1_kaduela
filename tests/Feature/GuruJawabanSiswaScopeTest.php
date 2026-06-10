<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\JawabanSiswa;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Mengajar;
use App\Models\PengerjaanQuiz;
use App\Models\PilihanJawaban;
use App\Models\Quiz;
use App\Models\Siswa;
use App\Models\SoalQuiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruJawabanSiswaScopeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('Ekstensi pdo_sqlite belum aktif, sementara phpunit.xml memakai SQLite in-memory.');
        }

        parent::setUp();
    }

    public function test_guru_only_sees_answers_from_their_own_quizzes(): void
    {
        [$guruUser, $ownQuiz, $otherQuiz] = $this->createStudentAnswersForTwoTeachers();

        $this->actingAs($guruUser)
            ->get(route('admin.master.index', 'jawaban-siswa'))
            ->assertOk()
            ->assertSee($ownQuiz->judul_quiz)
            ->assertSee('Soal milik guru satu')
            ->assertDontSee($otherQuiz->judul_quiz)
            ->assertDontSee('Soal milik guru dua');
    }

    public function test_guru_without_quizzes_sees_empty_answer_page(): void
    {
        $this->createStudentAnswersForTwoTeachers();
        $emptyGuruUser = User::factory()->create(['role' => 'guru', 'status' => 'aktif']);
        Guru::create([
            'id_user' => $emptyGuruUser->id_user,
            'nip' => 'JAWABAN-KOSONG',
            'nama_guru' => 'Guru Belum Membuat Quiz',
            'jenis_guru' => 'bidang_studi',
        ]);

        $this->actingAs($emptyGuruUser)
            ->get(route('admin.master.index', 'jawaban-siswa'))
            ->assertOk()
            ->assertSee('Belum ada data.')
            ->assertDontSee('Quiz Jawaban Guru Satu')
            ->assertDontSee('Quiz Jawaban Guru Dua');
    }

    private function createStudentAnswersForTwoTeachers(): array
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
            'nip' => 'JAWABAN-001',
            'nama_guru' => 'Guru Satu',
            'jenis_guru' => 'bidang_studi',
        ]);

        $guruTwo = Guru::create([
            'id_user' => $guruUserTwo->id_user,
            'nip' => 'JAWABAN-002',
            'nama_guru' => 'Guru Dua',
            'jenis_guru' => 'bidang_studi',
        ]);

        $siswaOne = Siswa::create([
            'id_user' => $siswaUserOne->id_user,
            'nis' => 'JAWABAN-SISWA-001',
            'nama_siswa' => 'Siswa Satu',
            'jk' => 'L',
            'id_kelas' => $kelasOne->id_kelas,
        ]);

        $siswaTwo = Siswa::create([
            'id_user' => $siswaUserTwo->id_user,
            'nis' => 'JAWABAN-SISWA-002',
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

        $ownQuiz = Quiz::create([
            'id_mengajar' => $mengajarOne->id_mengajar,
            'judul_quiz' => 'Quiz Jawaban Guru Satu',
            'durasi' => 30,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addDay(),
            'status' => 'aktif',
        ]);

        $otherQuiz = Quiz::create([
            'id_mengajar' => $mengajarTwo->id_mengajar,
            'judul_quiz' => 'Quiz Jawaban Guru Dua',
            'durasi' => 30,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addDay(),
            'status' => 'aktif',
        ]);

        $ownQuestion = SoalQuiz::create([
            'id_quiz' => $ownQuiz->id_quiz,
            'pertanyaan' => 'Soal milik guru satu',
            'bobot' => 1,
        ]);

        $otherQuestion = SoalQuiz::create([
            'id_quiz' => $otherQuiz->id_quiz,
            'pertanyaan' => 'Soal milik guru dua',
            'bobot' => 1,
        ]);

        $ownChoice = PilihanJawaban::create([
            'id_soal' => $ownQuestion->id_soal,
            'opsi' => 'A',
            'isi_pilihan' => 'Jawaban guru satu',
            'is_benar' => true,
        ]);

        $otherChoice = PilihanJawaban::create([
            'id_soal' => $otherQuestion->id_soal,
            'opsi' => 'A',
            'isi_pilihan' => 'Jawaban guru dua',
            'is_benar' => true,
        ]);

        $ownPengerjaan = PengerjaanQuiz::create([
            'id_quiz' => $ownQuiz->id_quiz,
            'id_siswa' => $siswaOne->id_siswa,
            'waktu_mulai' => now()->subHour(),
            'waktu_selesai' => now(),
            'nilai' => 100,
        ]);

        $otherPengerjaan = PengerjaanQuiz::create([
            'id_quiz' => $otherQuiz->id_quiz,
            'id_siswa' => $siswaTwo->id_siswa,
            'waktu_mulai' => now()->subHour(),
            'waktu_selesai' => now(),
            'nilai' => 100,
        ]);

        JawabanSiswa::create([
            'id_pengerjaan' => $ownPengerjaan->id_pengerjaan,
            'id_soal' => $ownQuestion->id_soal,
            'id_pilihan' => $ownChoice->id_pilihan,
        ]);

        JawabanSiswa::create([
            'id_pengerjaan' => $otherPengerjaan->id_pengerjaan,
            'id_soal' => $otherQuestion->id_soal,
            'id_pilihan' => $otherChoice->id_pilihan,
        ]);

        return [$guruUserOne, $ownQuiz, $otherQuiz];
    }
}
