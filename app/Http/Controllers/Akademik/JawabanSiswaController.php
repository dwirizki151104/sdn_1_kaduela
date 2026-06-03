<?php

namespace App\Http\Controllers\Akademik;

use App\Models\JawabanSiswa;
use Illuminate\Database\Eloquent\Model;

class JawabanSiswaController extends CrudController
{
    protected string $model = JawabanSiswa::class;
    protected array $relations = ['pengerjaan', 'soal', 'pilihan'];

    protected function rules(?Model $record = null): array
    {
        return [
            'id_pengerjaan' => ['required', 'exists:pengerjaan_quiz,id_pengerjaan'],
            'id_soal' => ['required', 'exists:soal_quiz,id_soal'],
            'id_pilihan' => ['nullable', 'exists:pilihan_jawaban,id_pilihan'],
        ];
    }
}
