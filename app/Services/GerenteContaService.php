<?php

namespace App\Services;

use App\Mail\CredenciaisAcessoMail;
use App\Repositories\GerenteContaRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class GerenteContaService extends BaseService
{
    public function __construct(protected GerenteContaRepository $repository) {}

    protected function getRepository(): mixed
    {
        return $this->repository;
    }

    public function store(array $data)
    {
        $gerente = $this->repository->store([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => 2,
            'email_verified_at' => now(),
        ]);

        Mail::to($gerente->email)->send(
            new CredenciaisAcessoMail(
                $gerente,
                $data['password'],
                'Gerente de Conta'
            )
        );

        return $gerente;
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
