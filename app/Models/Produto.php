<?php

namespace App\Models;

use Database\Factories\ProdutoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['loja_id', 'nome', 'unidade', 'quantidade_atual', 'quantidade_minima', 'preco_custo', 'ativo'])]
class Produto extends Model
{
    /** @use HasFactory<ProdutoFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'quantidade_atual' => 'decimal:2',
            'quantidade_minima' => 'decimal:2',
            'preco_custo' => 'decimal:2',
            'ativo' => 'boolean',
        ];
    }

    public function loja(): BelongsTo
    {
        return $this->belongsTo(Loja::class);
    }

    public function ordemServicoProdutos(): HasMany
    {
        return $this->hasMany(OrdemServicoProduto::class);
    }

    public function isEstoqueBaixo(): bool
    {
        return $this->quantidade_minima !== null
            && (float) $this->quantidade_atual <= (float) $this->quantidade_minima;
    }
}
