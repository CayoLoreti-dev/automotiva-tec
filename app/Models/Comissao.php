<?php

namespace App\Models;

use App\Enums\ComissaoStatus;
use Database\Factories\ComissaoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['loja_id', 'ordem_servico_id', 'funcionario_id', 'percentual_aplicado', 'valor_comissao', 'status', 'data_pagamento'])]
class Comissao extends Model
{
    /** @use HasFactory<ComissaoFactory> */
    use HasFactory;

    protected $table = 'comissoes';

    protected function casts(): array
    {
        return [
            'percentual_aplicado' => 'decimal:2',
            'valor_comissao' => 'decimal:2',
            'status' => ComissaoStatus::class,
            'data_pagamento' => 'datetime',
        ];
    }

    public function loja(): BelongsTo
    {
        return $this->belongsTo(Loja::class);
    }

    public function ordemServico(): BelongsTo
    {
        return $this->belongsTo(OrdemServico::class);
    }

    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class);
    }
}
