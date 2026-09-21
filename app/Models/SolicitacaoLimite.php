<?php

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SolicitacaoLimite extends Model implements Auditable
{
    use AuditableTrait;

    protected $table = 'solicitacoes_limite';

    protected $fillable = ['conta_id', 'valor_solicitado', 'status', 'aprovado_por'];

    public function conta()
    {
        return $this->belongsTo(Conta::class);
    }
}
