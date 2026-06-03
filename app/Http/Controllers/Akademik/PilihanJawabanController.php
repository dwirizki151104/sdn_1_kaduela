<?php

namespace App\Http\Controllers\Akademik;

use App\Models\PilihanJawaban;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class PilihanJawabanController extends CrudController
{
    protected string $model = PilihanJawaban::class;
    protected array $relations = ['soal'];

    protected function rules(?Model $record = null): array
    {
        return [
            'id_soal' => ['required', 'exists:soal_quiz,id_soal'],
            'opsi' => ['required', Rule::in(['A', 'B', 'C', 'D'])],
            'isi_pilihan' => ['required', 'string'],
            'is_benar' => ['required', 'boolean'],
        ];
    }
}
