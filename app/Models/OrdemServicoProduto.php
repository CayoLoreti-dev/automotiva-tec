<?php

namespace App\Models;

use Database\Factories\OrdemServicoProdutoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

#[Fillable(['ordem_servico_id', 'produto_id', 'quantidade_utilizada'])]
class OrdemServicoProduto extends Model
{
    /** @use HasFactory<OrdemServicoProdutoFactory> */
    use HasFactory;

    protected $table = 'ordem_servico_produtos';

    protected float $quantidadeOriginalParaEstoque = 0.0;

    protected function casts(): array
    {
        return [
            'quantidade_utilizada' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (OrdemServicoProduto $item): void {
            $produto = Produto::query()->whereKey($item->produto_id)->first(['id', 'loja_id', 'nome', 'quantidade_atual']);
            $ordemServico = OrdemServico::query()->whereKey($item->ordem_servico_id)->first(['id', 'loja_id']);

            if ($produto && $ordemServico && (int) $produto->loja_id !== (int) $ordemServico->loja_id) {
                throw ValidationException::withMessages([
                    'produto_id' => 'O produto selecionado pertence a outra loja.',
                ]);
            }

            $original = $item->exists ? (float) $item->getOriginal('quantidade_utilizada') : 0.0;
            $item->quantidadeOriginalParaEstoque = $original;
            $diferenca = (float) $item->quantidade_utilizada - $original;

            if ($produto && $diferenca > 0 && (float) $produto->quantidade_atual < $diferenca) {
                throw ValidationException::withMessages([
                    'quantidade_utilizada' => "Estoque insuficiente para o produto {$produto->nome}.",
                ]);
            }
        });

        static::saved(function (OrdemServicoProduto $item): void {
            $original = $item->quantidadeOriginalParaEstoque;
            $diferenca = (float) $item->quantidade_utilizada - $original;

            if ($diferenca > 0) {
                Produto::whereKey($item->produto_id)->decrement('quantidade_atual', $diferenca);
            } elseif ($diferenca < 0) {
                Produto::whereKey($item->produto_id)->increment('quantidade_atual', abs($diferenca));
            }
        });

        static::deleted(function (OrdemServicoProduto $item): void {
            Produto::whereKey($item->produto_id)->increment('quantidade_atual', (float) $item->quantidade_utilizada);
        });
    }

    public function ordemServico(): BelongsTo
    {
        return $this->belongsTo(OrdemServico::class);
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }
}
