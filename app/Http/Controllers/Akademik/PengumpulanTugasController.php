<?php

namespace App\Http\Controllers\Akademik;

use App\Models\PengumpulanTugas;
use Illuminate\Database\Eloquent\Model;

class PengumpulanTugasController extends CrudController
{
    protected string $model = PengumpulanTugas::class;
    protected array $relations = ['tugas', 'siswa'];

    protected function rules(?Model $record = null): array
    {
        return [
            'id_tugas' => ['required', 'exists:tugas,id_tugas'],
            'id_siswa' => ['required', 'exists:siswa,id_siswa'],
            'file_jawaban' => ['required', 'string', 'max:255'],
            'tanggal_kumpul' => ['required', 'date'],
            'nilai' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
