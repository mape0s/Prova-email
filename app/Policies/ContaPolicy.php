<?php

namespace App\Policies;

use App\Models\Conta;
use App\Models\User;

class ContaPolicy
{
    public function bloquear(User $user, Conta $conta): bool
    {
        return $user->role_id === 2;
    }

    public function desbloquear(User $user, Conta $conta): bool
    {
        return $user->role_id === 2;
    }
}
