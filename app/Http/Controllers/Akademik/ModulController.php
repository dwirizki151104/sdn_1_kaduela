<?php

namespace App\Http\Controllers\Akademik;

use App\Models\Modul;
use Illuminate\Database\Eloquent\Model;

class ModulController extends CrudController
{
    protected string $model = Modul::class;
    protected array $relations = ['mengajar'];

    protected function rules(?Model $record = null): array
    {
        return [
            'id_mengajar' => ['required', 'exists:mengajar,id_mengajar'],
            'judul_modul' => ['required', 'string', 'max:255'],
            'file_modul' => ['required', 'string', 'max:255'],
            'tanggal_upload' => ['required', 'date'],
        ];
    }
}
