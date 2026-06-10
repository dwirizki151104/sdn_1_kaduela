<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use App\Models\Quiz;
use App\Models\Tugas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuruContentController extends Controller
{
    public function storeModul(Request $request): RedirectResponse
    {
        $guru = $this->currentGuru();
        $mengajarIds = $guru->mengajar()->pluck('id_mengajar')->all();

        $data = $request->validate([
            'id_mengajar' => ['required', Rule::in($mengajarIds)],
            'judul_modul' => ['required', 'string', 'max:255'],
            'file_modul' => ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx,zip,jpg,jpeg,png', 'max:10240'],
        ]);

        Modul::create([
            'id_mengajar' => $data['id_mengajar'],
            'judul_modul' => $data['judul_modul'],
            'file_modul' => $request->file('file_modul')->store('modul', 'public'),
            'tanggal_upload' => now(),
        ]);

        return back()->with('success', 'Materi berhasil diberikan kepada siswa.');
    }

    public function storeTugas(Request $request): RedirectResponse
    {
        $guru = $this->currentGuru();
        $mengajarIds = $guru->mengajar()->pluck('id_mengajar')->all();

        $data = $request->validate([
            'id_mengajar' => ['required', Rule::in($mengajarIds)],
            'judul_tugas' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'deadline' => ['required', 'date', 'after:now'],
        ]);

        Tugas::create($data);

        return back()->with('success', 'Tugas berhasil diberikan kepada siswa.');
    }

    public function storeQuiz(Request $request): RedirectResponse
    {
        $guru = $this->currentGuru();
        $mengajarIds = $guru->mengajar()->pluck('id_mengajar')->all();

        $data = $request->validate([
            'id_mengajar' => ['required', Rule::in($mengajarIds)],
            'judul_quiz' => ['required', 'string', 'max:255'],
            'durasi' => ['required', 'integer', 'min:1', 'max:600'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
            'status' => ['required', Rule::in(['draft', 'aktif'])],
        ]);

        $quiz = Quiz::create($data);

        return redirect()
            ->route('admin.quiz.questions.index', $quiz->id_quiz)
            ->with('success', 'Quiz berhasil dibuat. Silakan tambahkan soal dan pilihan jawaban.');
    }

    private function currentGuru()
    {
        $guru = auth()->user()?->guru;

        abort_if(! $guru || auth()->user()->role !== 'guru', 403);

        return $guru;
    }
}
