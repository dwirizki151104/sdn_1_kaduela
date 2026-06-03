<?php

namespace App\Http\Controllers\Akademik;

use App\Models\PengerjaanQuiz;
use Illuminate\Database\Eloquent\Model;

class PengerjaanQuizController extends CrudController
{
    protected string $model = PengerjaanQuiz::class;
    protected array $relations = ['quiz', 'siswa', 'jawaban'];

    protected function rules(?Model $record = null): array
    {
        return [
            'id_quiz' => ['required', 'exists:quiz,id_quiz'],
            'id_siswa' => ['required', 'exists:siswa,id_siswa'],
            'waktu_mulai' => ['required', 'date'],
            'waktu_selesai' => ['nullable', 'date', 'after_or_equal:waktu_mulai'],
            'nilai' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
