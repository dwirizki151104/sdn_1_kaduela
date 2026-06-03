<?php

namespace App\Http\Controllers\Akademik;

use App\Models\Nilai;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class NilaiController extends CrudController
{
    protected string $model = Nilai::class;
    protected array $relations = ['siswa', 'mataPelajaran'];

    protected function rules(?Model $record = null): array
    {
        return [
            'id_siswa' => ['required', 'exists:siswa,id_siswa'],
            'id_mapel' => ['required', 'exists:mata_pelajaran,id_mapel'],
            'semester' => ['required', Rule::in(['1', '2'])],
            'nilai_tugas' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_quiz' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_uts' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_uas' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_akhir' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
