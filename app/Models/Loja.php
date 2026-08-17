<?php

namespace App\Models;

use App\Enums\LojaStatus;
use Database\Factories\LojaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nome', 'cnpj', 'plano', 'status', 'data_vencimento', 'valor_mensalidade'])]
class Loja extends Model
{
    /** @use HasFactory<LojaFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => LojaStatus::class,
            'data_vencimento' => 'date',
            'valor_mensalidade' => 'decimal:2',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class);
    }

    public function veiculos(): HasMany
    {
        return $this->hasMany(Veiculo::class);
    }

    public function servicos(): HasMany
    {
        return $this->hasMany(Servico::class);
    }

    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class);
    }

    public function funcionarios(): HasMany
    {
        return $this->hasMany(Funcionario::class);
    }

    public function ordensServico(): HasMany
    {
        return $this->hasMany(OrdemServico::class);
    }

    protected function name(): Attribute
    {
        return Attribute::get(fn (): string => $this->nome);
    }
}
