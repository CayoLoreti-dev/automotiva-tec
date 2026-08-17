<?php

namespace App\Models;

use Database\Factories\OrdemServicoItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

#[Fillable(['ordem_servico_id', 'servico_id', 'preco_unitario', 'quantidade'])]
class OrdemServicoItem extends Model
{
    /** @use HasFactory<OrdemServicoItemFactory> */
    use HasFactory;

    protected $table = 'ordem_servico_itens';

    protected function casts(): array
    {
        return [
            'preco_unitario' => 'decimal:2',
            'quantidade' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (OrdemServicoItem $item): void {
            $servico = Servico::query()->whereKey($item->servico_id)->first(['loja_id', 'preco']);
            $ordemServico = OrdemServico::query()->whereKey($item->ordem_servico_id)->first(['id', 'loja_id']);

            if ($servico && $ordemServico && (int) $servico->loja_id !== (int) $ordemServico->loja_id) {
                throw ValidationException::withMessages([
                    'servico_id' => 'O servico selecionado pertence a outra loja.',
                ]);
            }

            if ($servico && blank($item->preco_unitario)) {
                $item->preco_unitario = $servico->preco;
            }
        });

        static::saved(fn (OrdemServicoItem $item): mixed => $item->ordemServico?->recalcularValorTotal());
        static::deleted(fn (OrdemServicoItem $item): mixed => $item->ordemServico?->recalcularValorTotal());
    }

    public function ordemServico(): BelongsTo
    {
        return $this->belongsTo(OrdemServico::class);
    }

    public function servico(): BelongsTo
    {
        return $this->belongsTo(Servico::class);
    }
}
