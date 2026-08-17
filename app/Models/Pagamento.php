<?php

namespace App\Models;

use App\Enums\FormaPagamento;
use App\Enums\OrdemServicoStatus;
use Database\Factories\PagamentoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

#[Fillable(['loja_id', 'ordem_servico_id', 'forma_pagamento', 'valor', 'data_pagamento', 'observacoes'])]
class Pagamento extends Model
{
    /** @use HasFactory<PagamentoFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'forma_pagamento' => FormaPagamento::class,
            'valor' => 'decimal:2',
            'data_pagamento' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Pagamento $pagamento): void {
            $ordemServico = OrdemServico::query()->whereKey($pagamento->ordem_servico_id)->first();

            if (! $ordemServico) {
                return;
            }

            if ($ordemServico->status === OrdemServicoStatus::Cancelado) {
                throw ValidationException::withMessages([
                    'ordem_servico_id' => 'Nao e possivel registrar pagamento em uma OS cancelada.',
                ]);
            }

            $pagamento->loja_id = $ordemServico->loja_id;
        });
    }

    public function loja(): BelongsTo
    {
        return $this->belongsTo(Loja::class);
    }

    public function ordemServico(): BelongsTo
    {
        return $this->belongsTo(OrdemServico::class);
    }
}
