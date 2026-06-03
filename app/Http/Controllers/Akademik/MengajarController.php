<?php

namespace App\Http\Controllers\Akademik;

use App\Models\Mengajar;
use Illuminate\Database\Eloquent\Model;

class MengajarController extends CrudController
{
    protected string $model = Mengajar::class;
    protected array $relations = ['guru', 'kelas', 'mataPelajaran'];

    protected function rules(?Model $record = null): array
    {
        return [
            'id_guru' => ['required', 'exists:guru,id_guru'],
            'id_kelas' => ['required', 'exists:kelas,id_kelas'],
            'id_mapel' => ['required', 'exists:mata_pelajaran,id_mapel'],
        ];
    }
}
