<?php

namespace App\Http\Controllers\Akademik;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Model;

class KelasController extends CrudController
{
    protected string $model = Kelas::class;
    protected array $relations = ['waliKelas', 'siswa'];

    protected function rules(?Model $record = null): array
    {
        return [
            'nama_kelas' => ['required', 'string', 'max:20', $this->unique('kelas', 'nama_kelas', $record)],
            'id_wali_kelas' => ['nullable', 'exists:guru,id_guru', $this->unique('kelas', 'id_wali_kelas', $record)],
        ];
    }
}
