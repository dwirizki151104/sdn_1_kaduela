<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Mengajar;
use App\Models\PilihanJawaban;
use App\Models\Quiz;
use App\Models\Siswa;
use App\Models\SoalQuiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SiswaQuizFonnteNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('Ekstensi pdo_sqlite belum aktif, sementara phpunit.xml memakai SQLite in-memory.');
        }

        parent::setUp();
    }

    public function test_siswa_receives_quiz_score_via_fonnte_after_submitting_quiz(): void
    {
        config([
            'services.fonnte.token' => 'fonnte-test-token',
            'services.fonnte.base_url' => 'https://api.fonnte.com',
        ]);

        Http::fake([
            'api.fonnte.com/send' => Http::response(['status' => true], 200),
        ]);

        [$siswaUser, $quiz, $correctChoice, $wrongChoice] = $this->createActiveQuiz();

        $this->actingAs($siswaUser)
            ->post(route('siswa.quiz.submit', $quiz), [
                'answers' => [
                    $correctChoice->id_soal => $correctChoice->id_pilihan,
                    $wrongChoice->id_soal => $wrongChoice->id_pilihan,
                ],
            ])
            ->assertRedirect();

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.fonnte.com/send'
                && $request->hasHeader('Authorization', 'fonnte-test-token')
                && $request['target'] === '6281234567890'
                && str_contains($request['message'], 'Nilai untuk Quiz Matematika')
                && str_contains($request['message'], '50,00');
        });
    }

    private function createActiveQuiz(): array
    {
        $mapel = MataPelajaran::create([
            'nama_mapel' => 'Matematika',
            'kategori' => 'tematik',
        ]);

        $kelas = Kelas::create(['nama_kelas' => 'Kelas 1']);

        $guruUser = User::factory()->create(['role' => 'guru', 'status' => 'aktif']);
        $guru = Guru::create([
            'id_user' => $guruUser->id_user,
            'nip' => 'FONNTE-GURU',
            'nama_guru' => 'Guru Fonnte',
            'jenis_guru' => 'bidang_studi',
        ]);

        $siswaUser = User::factory()->create(['role' => 'siswa', 'status' => 'aktif']);
        Siswa::create([
            'id_user' => $siswaUser->id_user,
            'nis' => 'FONNTE-SISWA',
            'nama_siswa' => 'Siswa Fonnte',
            'jk' => 'L',
            'no_hp' => '0812-3456-7890',
            'id_kelas' => $kelas->id_kelas,
        ]);

        $mengajar = Mengajar::create([
            'id_guru' => $guru->id_guru,
            'id_kelas' => $kelas->id_kelas,
            'id_mapel' => $mapel->id_mapel,
        ]);

        $quiz = Quiz::create([
            'id_mengajar' => $mengajar->id_mengajar,
            'judul_quiz' => 'Quiz Matematika',
            'durasi' => 30,
            'tanggal_mulai' => now()->subHour(),
            'tanggal_selesai' => now()->addHour(),
            'status' => 'aktif',
        ]);

        $firstQuestion = SoalQuiz::create([
            'id_quiz' => $quiz->id_quiz,
            'pertanyaan' => '1 + 1?',
            'bobot' => 1,
        ]);

        $secondQuestion = SoalQuiz::create([
            'id_quiz' => $quiz->id_quiz,
            'pertanyaan' => '2 + 2?',
            'bobot' => 1,
        ]);

        $correctChoice = PilihanJawaban::create([
            'id_soal' => $firstQuestion->id_soal,
            'opsi' => 'A',
            'isi_pilihan' => '2',
            'is_benar' => true,
        ]);

        PilihanJawaban::create([
            'id_soal' => $firstQuestion->id_soal,
            'opsi' => 'B',
            'isi_pilihan' => '3',
            'is_benar' => false,
        ]);

        PilihanJawaban::create([
            'id_soal' => $secondQuestion->id_soal,
            'opsi' => 'A',
            'isi_pilihan' => '4',
            'is_benar' => true,
        ]);

        $wrongChoice = PilihanJawaban::create([
            'id_soal' => $secondQuestion->id_soal,
            'opsi' => 'B',
            'isi_pilihan' => '5',
            'is_benar' => false,
        ]);

        return [$siswaUser, $quiz, $correctChoice, $wrongChoice];
    }
}
