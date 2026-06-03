<?php

namespace App\Http\Controllers\Akademik;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class QuizController extends CrudController
{
    protected string $model = Quiz::class;
    protected array $relations = ['mengajar', 'soal.pilihanJawaban'];

    protected function rules(?Model $record = null): array
    {
        return [
            'id_mengajar' => ['required', 'exists:mengajar,id_mengajar'],
            'judul_quiz' => ['required', 'string', 'max:255'],
            'durasi' => ['required', 'integer', 'min:1', 'max:600'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
            'status' => ['required', Rule::in(['draft', 'aktif', 'selesai'])],
        ];
    }
}
