<?php

namespace App\Services;

abstract class BaseService
{
    abstract protected function getRepository(): mixed;

    public function all()
    {
        return $this->getRepository()->list();
    }

    public function find(int|string $id)
    {
        return $this->getRepository()->find($id);
    }

    public function store(array $data)
    {
        return $this->getRepository()->store($data);
    }

    public function update(array $data, int|string $id)
    {
        return $this->getRepository()->update($data, $id);
    }

    public function remove(int|string $id)
    {
        return $this->getRepository()->remove($id);
    }
}
