<?php

namespace App\Http\Controllers\Akademik;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends CrudController
{
    protected string $model = User::class;
    protected array $relations = ['guru', 'siswa'];

    protected function rules(?Model $record = null): array
    {
        $passwordRule = $record ? ['nullable', 'string', 'min:8'] : ['required', 'string', 'min:8'];

        return [
            'username' => ['required', 'string', 'max:255', $this->unique('users', 'username', $record)],
            'password' => $passwordRule,
            'role' => ['required', Rule::in(['admin', 'guru', 'siswa'])],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ];
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $record = $this->find($id);
        $data = $request->validate($this->rules($record));

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $record->update($data);

        return response()->json($record->refresh()->load($this->relations));
    }
}
