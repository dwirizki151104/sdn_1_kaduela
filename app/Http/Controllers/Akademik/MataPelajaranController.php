<?php

namespace App\Http\Controllers\Akademik;

use App\Models\MataPelajaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class MataPelajaranController extends CrudController
{
    protected string $model = MataPelajaran::class;

    protected function rules(?Model $record = null): array
    {
        return [
            'nama_mapel' => ['required', 'string', 'max:100', $this->unique('mata_pelajaran', 'nama_mapel', $record)],
            'kategori' => ['required', Rule::in(['tematik', 'khusus'])],
        ];
    }
}
