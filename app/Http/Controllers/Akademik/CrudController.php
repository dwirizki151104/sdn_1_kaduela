<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

abstract class CrudController extends Controller
{
    protected string $model;

    protected array $relations = [];

    abstract protected function rules(?Model $record = null): array;

    public function index(): JsonResponse
    {
        return response()->json($this->query()->latest()->paginate(15));
    }

    public function store(Request $request): JsonResponse
    {
        $record = $this->model::create($request->validate($this->rules()));

        return response()->json($record->load($this->relations), 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->find($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $record = $this->find($id);
        $record->update($request->validate($this->rules($record)));

        return response()->json($record->refresh()->load($this->relations));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->find($id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    protected function unique(string $table, string $column, ?Model $record = null): Rule
    {
        $rule = Rule::unique($table, $column);

        return $record ? $rule->ignore($record->getKey(), $record->getKeyName()) : $rule;
    }

    protected function query()
    {
        return $this->model::query()->with($this->relations);
    }

    protected function find(int $id): Model
    {
        return $this->query()->findOrFail($id);
    }
}
