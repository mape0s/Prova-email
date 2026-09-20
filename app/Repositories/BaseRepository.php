<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    abstract protected function getModel(): mixed;

    public function list()
    {
        return $this->getModel()->orderBy('id')->get();
    }

    public function find(int|string $id): ?Model
    {
        return $this->getModel()->find($id);
    }

    public function store(array $data): ?Model
    {
        return $this->getModel()->create($data);
    }

    public function update(array $data, int|string $id): ?Model
    {
        $row = $this->getModel()->findOrFail($id);
        $row->update($data);
        return $row;
    }

    public function remove(int|string $id): bool
    {
        $row = $this->getModel()->findOrFail($id);
        return (bool) $row->delete();
    }
}
