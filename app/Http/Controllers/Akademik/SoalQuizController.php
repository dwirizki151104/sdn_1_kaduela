<?php

namespace App\Http\Controllers\Akademik;

use App\Models\SoalQuiz;
use Illuminate\Database\Eloquent\Model;

class SoalQuizController extends CrudController
{
    protected string $model = SoalQuiz::class;
    protected array $relations = ['quiz', 'pilihanJawaban'];

    protected function rules(?Model $record = null): array
    {
        return [
            'id_quiz' => ['required', 'exists:quiz,id_quiz'],
            'pertanyaan' => ['required', 'string'],
            'bobot' => ['required', 'numeric', 'min:0.01', 'max:100'],
        ];
    }
}
