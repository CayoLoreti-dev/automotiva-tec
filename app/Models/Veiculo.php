<?php

namespace App\Models;

use Database\Factories\VeiculoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

#[Fillable(['loja_id', 'cliente_id', 'placa', 'modelo', 'marca', 'cor', 'ano', 'observacoes'])]
class Veiculo extends Model
{
    /** @use HasFactory<VeiculoFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'ano' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Veiculo $veiculo): void {
            $clienteLojaId = Cliente::query()
                ->whereKey($veiculo->cliente_id)
                ->value('loja_id');

            if ($clienteLojaId !== null && (int) $clienteLojaId !== (int) $veiculo->loja_id) {
                throw ValidationException::withMessages([
                    'cliente_id' => 'O cliente selecionado pertence a outra loja.',
                ]);
            }
        });
    }

    protected function placa(): Attribute
    {
        return Attribute::set(fn (?string $value): ?string => $value ? strtoupper($value) : null);
    }

    public function loja(): BelongsTo
    {
        return $this->belongsTo(Loja::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
