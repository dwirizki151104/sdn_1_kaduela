<?php

namespace App\Http\Controllers\Akademik;

use App\Models\Guru;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class GuruController extends CrudController
{
    protected string $model = Guru::class;
    protected array $relations = ['user', 'kelasWali'];

    protected function rules(?Model $record = null): array
    {
        return [
            'id_user' => ['required', 'exists:users,id_user', $this->unique('guru', 'id_user', $record)],
            'nip' => ['nullable', 'string', 'max:30', $this->unique('guru', 'nip', $record)],
            'nama_guru' => ['required', 'string', 'max:255'],
            'jenis_guru' => ['required', Rule::in(['wali_kelas', 'bidang_studi'])],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ];
    }
}
