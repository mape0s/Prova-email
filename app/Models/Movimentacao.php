<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimentacao extends Model
{
    protected $table = 'movimentacoes';

    protected $fillable = ['conta_id', 'tipo', 'valor', 'natureza', 'descricao'];

    public function conta()
    {
        return $this->belongsTo(Conta::class);
    }
}
