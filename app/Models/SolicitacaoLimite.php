<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class SolicitacaoLimite extends Model implements Auditable
{
    use AuditableTrait;

    protected $table = 'solicitacoes_limite';

    protected $fillable = [
        'conta_id',
        'valor_solicitado',
        'status',
        'aprovado_por',
    ];

    public function conta()
    {
        return $this->belongsTo(Conta::class);
    }

    public function aprovadoPor()
    {
        return $this->belongsTo(User::class, 'aprovado_por');
    }
}
