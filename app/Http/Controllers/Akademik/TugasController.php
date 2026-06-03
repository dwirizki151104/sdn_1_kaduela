<?php

namespace App\Http\Controllers\Akademik;

use App\Models\Tugas;
use Illuminate\Database\Eloquent\Model;

class TugasController extends CrudController
{
    protected string $model = Tugas::class;
    protected array $relations = ['mengajar'];

    protected function rules(?Model $record = null): array
    {
        return [
            'id_mengajar' => ['required', 'exists:mengajar,id_mengajar'],
            'judul_tugas' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'deadline' => ['required', 'date'],
        ];
    }
}
