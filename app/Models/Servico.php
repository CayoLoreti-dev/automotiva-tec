<?php

namespace App\Models;

use Database\Factories\ServicoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['loja_id', 'nome', 'descricao', 'preco', 'duracao_estimada_minutos', 'ativo'])]
class Servico extends Model
{
    /** @use HasFactory<ServicoFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'preco' => 'decimal:2',
            'duracao_estimada_minutos' => 'integer',
            'ativo' => 'boolean',
        ];
    }

    public function loja(): BelongsTo
    {
        return $this->belongsTo(Loja::class);
    }
}
