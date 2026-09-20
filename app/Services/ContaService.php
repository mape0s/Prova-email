<?php

namespace App\Services;

use App\Repositories\ContaRepository;

class ContaService extends BaseService
{
    public function __construct(protected ContaRepository $repository) {}

    protected function getRepository(): mixed
    {
        return $this->repository;
    }

    public function bloquear(int|string $id)
    {
        return $this->update(['status' => 'bloqueada'], $id);
    }

    public function desbloquear(int|string $id)
    {
        return $this->update(['status' => 'ativa'], $id);
    }
}
