<?php

namespace App\Models;

use App\Enums\LojaStatus;
use Database\Factories\LojaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    protected function name(): Attribute
    {
        return Attribute::get(fn (): string => $this->nome);
    }
}
