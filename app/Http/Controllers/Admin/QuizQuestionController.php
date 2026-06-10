<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PilihanJawaban;
use App\Models\Quiz;
use App\Models\SoalQuiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class QuizQuestionController extends Controller
{
    public function index(Quiz $quiz): View
    {
        $this->authorizeQuizAccess($quiz);

        $quiz->load([
            'mengajar.guru',
            'mengajar.kelas',
            'mengajar.mataPelajaran',
            'soal.pilihanJawaban',
        ]);

        return view('admin.quiz.questions', compact('quiz'));
    }

    public function store(Request $request, Quiz $quiz): RedirectResponse
    {
        $this->authorizeQuizAccess($quiz);

        $data = $this->validated($request);

        DB::transaction(function () use ($quiz, $data) {
            $soal = $quiz->soal()->create([
                'pertanyaan' => $data['pertanyaan'],
                'bobot' => $data['bobot'],
            ]);

            $this->syncChoices($soal, $data['choices'], $data['correct_answer']);
        });

        return back()->with('success', 'Soal quiz berhasil ditambahkan.');
    }

    public function update(Request $request, Quiz $quiz, SoalQuiz $question): RedirectResponse
    {
        $this->authorizeQuizAccess($quiz);
        abort_if((int) $question->id_quiz !== (int) $quiz->id_quiz, 404);

        $data = $this->validated($request);

        DB::transaction(function () use ($question, $data) {
            $question->update([
                'pertanyaan' => $data['pertanyaan'],
                'bobot' => $data['bobot'],
            ]);

            $this->syncChoices($question, $data['choices'], $data['correct_answer']);
        });

        return back()->with('success', 'Soal quiz berhasil diperbarui.');
    }

    public function destroy(Quiz $quiz, SoalQuiz $question): RedirectResponse
    {
        $this->authorizeQuizAccess($quiz);
        abort_if((int) $question->id_quiz !== (int) $quiz->id_quiz, 404);

        $question->delete();

        return back()->with('success', 'Soal quiz berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'pertanyaan' => ['required', 'string'],
            'bobot' => ['required', 'numeric', 'min:0.01', 'max:100'],
            'choices' => ['required', 'array'],
            'choices.A' => ['required', 'string'],
            'choices.B' => ['required', 'string'],
            'choices.C' => ['required', 'string'],
            'choices.D' => ['required', 'string'],
            'correct_answer' => ['required', Rule::in(['A', 'B', 'C', 'D'])],
        ]);
    }

    private function syncChoices(SoalQuiz $question, array $choices, string $correctAnswer): void
    {
        foreach (['A', 'B', 'C', 'D'] as $option) {
            PilihanJawaban::updateOrCreate(
                [
                    'id_soal' => $question->id_soal,
                    'opsi' => $option,
                ],
                [
                    'isi_pilihan' => $choices[$option],
                    'is_benar' => $option === $correctAnswer,
                ],
            );
        }
    }

    private function authorizeQuizAccess(Quiz $quiz): void
    {
        $user = auth()->user();

        if ($user?->role === 'admin') {
            return;
        }

        if ($user?->role === 'guru') {
            $ownsQuiz = $quiz->mengajar()
                ->where('id_guru', $user->guru?->id_guru)
                ->exists();

            abort_if(! $ownsQuiz, 403);

            return;
        }

        abort(403);
    }
}
