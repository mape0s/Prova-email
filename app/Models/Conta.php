<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Conta extends Model implements Auditable
{
    use AuditableTrait;

    protected $fillable = [
        'user_id', 'gerente_conta_id', 'saldo', 'limite', 'status',
        'saldo_cdb', 'saldo_cdi', 'saldo_poupanca',
    ];

    public function cliente()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gerenteConta()
    {
        return $this->belongsTo(User::class, 'gerente_conta_id');
    }

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class);
    }
}
