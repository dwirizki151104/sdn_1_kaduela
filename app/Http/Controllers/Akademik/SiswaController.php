<?php

namespace App\Http\Controllers\Akademik;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class SiswaController extends CrudController
{
    protected string $model = Siswa::class;
    protected array $relations = ['user', 'kelas'];

    protected function rules(?Model $record = null): array
    {
        return [
            'id_user' => ['required', 'exists:users,id_user', $this->unique('siswa', 'id_user', $record)],
            'nis' => ['required', 'string', 'max:30', $this->unique('siswa', 'nis', $record)],
            'nama_siswa' => ['required', 'string', 'max:255'],
            'jk' => ['required', Rule::in(['L', 'P'])],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string'],
            'id_kelas' => ['required', 'exists:kelas,id_kelas'],
        ];
    }
}
