<?php

namespace App\Services;

use App\Repositories\ClienteRepository;
use App\Repositories\ContaRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClienteService extends BaseService
{
    public function __construct(
        protected ClienteRepository $repository,
        protected ContaRepository $contaRepository
    ) {}

    protected function getRepository(): mixed
    {
        return $this->repository;
    }

    public function store(array $data)
    {
        $cliente = $this->repository->store([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => 3,
            'email_verified_at' => now(),
        ]);

        $this->contaRepository->store([
            'user_id' => $cliente->id,
            'gerente_conta_id' => Auth::id(),
            'saldo' => $data['saldo'] ?? 0,
            'limite' => $data['limite'] ?? 0,
        ]);

        return $cliente;
    }

    public function update(array $data, int|string $id)
    {
        $update = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (! empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        return $this->repository->update($update, $id);
    }
}
