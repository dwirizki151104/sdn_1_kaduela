<?php

namespace App\Http\Controllers;

use App\Models\JawabanSiswa;
use App\Models\PengerjaanQuiz;
use App\Models\PengumpulanTugas;
use App\Models\PilihanJawaban;
use App\Models\Quiz;
use App\Models\Tugas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SiswaContentController extends Controller
{
    public function submitTugas(Request $request, Tugas $tugas): RedirectResponse
    {
        $siswa = $this->currentSiswa();
        $this->authorizeClassAccess($tugas);

        $data = $request->validate([
            'file_jawaban' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,ppt,pptx,zip,jpg,jpeg,png,mp4,mov,avi,mkv,webm',
                'max:51200',
            ],
        ]);

        $pengumpulan = PengumpulanTugas::where('id_tugas', $tugas->id_tugas)
            ->where('id_siswa', $siswa->id_siswa)
            ->first();

        if ($pengumpulan && filled($pengumpulan->file_jawaban)) {
            Storage::disk('public')->delete($pengumpulan->file_jawaban);
        }

        PengumpulanTugas::updateOrCreate(
            [
                'id_tugas' => $tugas->id_tugas,
                'id_siswa' => $siswa->id_siswa,
            ],
            [
                'file_jawaban' => $data['file_jawaban']->store('pengumpulan-tugas', 'public'),
                'tanggal_kumpul' => now(),
                'nilai' => $pengumpulan?->nilai,
            ],
        );

        return back()->with('success', 'Jawaban tugas berhasil dikirim.');
    }

    public function showQuiz(Quiz $quiz): View|RedirectResponse
    {
        $siswa = $this->currentSiswa();
        $this->authorizeClassAccess($quiz);

        $quiz->load(['mengajar.mataPelajaran', 'soal.pilihanJawaban']);

        if ($quiz->status !== 'aktif') {
            return redirect()->route('dashboard')->withErrors(['quiz' => 'Quiz belum aktif.']);
        }

        if (now()->lt($quiz->tanggal_mulai)) {
            return redirect()->route('dashboard')->withErrors(['quiz' => 'Quiz belum dimulai.']);
        }

        if (now()->gt($quiz->tanggal_selesai)) {
            return redirect()->route('dashboard')->withErrors(['quiz' => 'Quiz sudah melewati jadwal.']);
        }

        if ($quiz->soal->isEmpty()) {
            return redirect()->route('dashboard')->withErrors(['quiz' => 'Quiz belum memiliki soal.']);
        }

        $pengerjaan = PengerjaanQuiz::firstOrCreate(
            [
                'id_quiz' => $quiz->id_quiz,
                'id_siswa' => $siswa->id_siswa,
            ],
            [
                'waktu_mulai' => now(),
            ],
        );

        if ($pengerjaan->waktu_selesai) {
            return redirect()->route('dashboard')->withErrors(['quiz' => 'Quiz ini sudah dikerjakan.']);
        }

        return view('dashboard.siswa-quiz', compact('quiz', 'pengerjaan', 'siswa'));
    }

    public function submitQuiz(Request $request, Quiz $quiz): RedirectResponse
    {
        $siswa = $this->currentSiswa();
        $this->authorizeClassAccess($quiz);

        abort_if($quiz->status !== 'aktif', 403);
        abort_if(now()->lt($quiz->tanggal_mulai) || now()->gt($quiz->tanggal_selesai), 403);

        $quiz->load('soal.pilihanJawaban');
        abort_if($quiz->soal->isEmpty(), 422, 'Quiz belum memiliki soal.');

        $existing = PengerjaanQuiz::where('id_quiz', $quiz->id_quiz)
            ->where('id_siswa', $siswa->id_siswa)
            ->first();

        if ($existing?->waktu_selesai) {
            return back()->withErrors(['quiz' => 'Quiz ini sudah dikerjakan.']);
        }

        $questionIds = $quiz->soal->pluck('id_soal')->all();
        $request->validate([
            'answers' => ['required', 'array'],
            'answers.*' => ['required', 'integer', Rule::exists('pilihan_jawaban', 'id_pilihan')],
        ]);

        $answers = collect($request->input('answers'))
            ->mapWithKeys(fn ($choiceId, $questionId) => [(int) $questionId => (int) $choiceId])
            ->only($questionIds)
            ->mapWithKeys(fn ($choiceId, $questionId) => [(int) $questionId => (int) $choiceId]);

        if ($answers->count() !== count($questionIds)) {
            return back()->withErrors(['quiz' => 'Semua soal quiz harus dijawab.']);
        }

        DB::transaction(function () use ($answers, $quiz, $siswa) {
            $pengerjaan = PengerjaanQuiz::firstOrCreate(
                [
                    'id_quiz' => $quiz->id_quiz,
                    'id_siswa' => $siswa->id_siswa,
                ],
                [
                    'waktu_mulai' => now(),
                ],
            );

            $totalBobot = $quiz->soal->sum(fn ($soal) => (float) $soal->bobot);
            $bobotBenar = 0;

            foreach ($quiz->soal as $soal) {
                $choiceId = $answers[$soal->id_soal];
                $choice = PilihanJawaban::where('id_soal', $soal->id_soal)
                    ->where('id_pilihan', $choiceId)
                    ->firstOrFail();

                if ($choice->is_benar) {
                    $bobotBenar += (float) $soal->bobot;
                }

                JawabanSiswa::updateOrCreate(
                    [
                        'id_pengerjaan' => $pengerjaan->id_pengerjaan,
                        'id_soal' => $soal->id_soal,
                    ],
                    [
                        'id_pilihan' => $choice->id_pilihan,
                    ],
                );
            }

            $pengerjaan->update([
                'waktu_selesai' => now(),
                'nilai' => $totalBobot > 0 ? round(($bobotBenar / $totalBobot) * 100, 2) : 0,
            ]);
        });

        return back()->with('success', 'Quiz berhasil dikirim dan nilai sudah tersimpan.');
    }

    private function currentSiswa()
    {
        $siswa = auth()->user()?->siswa;

        abort_if(! $siswa || auth()->user()->role !== 'siswa', 403);

        return $siswa;
    }

    private function authorizeClassAccess(Tugas|Quiz $content): void
    {
        $siswa = $this->currentSiswa();
        $content->loadMissing('mengajar');

        abort_if((int) $content->mengajar->id_kelas !== (int) $siswa->id_kelas, 403);
    }
}
