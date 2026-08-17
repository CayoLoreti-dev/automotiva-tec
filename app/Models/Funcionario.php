<?php

namespace App\Models;

use Database\Factories\FuncionarioFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['loja_id', 'nome', 'cargo', 'percentual_comissao', 'ativo'])]
class Funcionario extends Model
{
    /** @use HasFactory<FuncionarioFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'percentual_comissao' => 'decimal:2',
            'ativo' => 'boolean',
        ];
    }

    public function loja(): BelongsTo
    {
        return $this->belongsTo(Loja::class);
    }

    public function ordensServico(): HasMany
    {
        return $this->hasMany(OrdemServico::class);
    }

    public function comissoes(): HasMany
    {
        return $this->hasMany(Comissao::class);
    }
}
