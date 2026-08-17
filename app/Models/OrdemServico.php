<?php

namespace App\Models;

use App\Enums\OrdemServicoStatus;
use Database\Factories\OrdemServicoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

#[Fillable(['loja_id', 'cliente_id', 'veiculo_id', 'funcionario_id', 'status', 'valor_total', 'observacoes', 'data_abertura', 'data_conclusao', 'data_entrega'])]
class OrdemServico extends Model
{
    /** @use HasFactory<OrdemServicoFactory> */
    use HasFactory;

    protected $table = 'ordens_servico';

    protected function casts(): array
    {
        return [
            'status' => OrdemServicoStatus::class,
            'valor_total' => 'decimal:2',
            'data_abertura' => 'datetime',
            'data_conclusao' => 'datetime',
            'data_entrega' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (OrdemServico $ordemServico): void {
            $clienteLojaId = Cliente::query()->whereKey($ordemServico->cliente_id)->value('loja_id');

            if ($clienteLojaId !== null && (int) $clienteLojaId !== (int) $ordemServico->loja_id) {
                throw ValidationException::withMessages([
                    'cliente_id' => 'O cliente selecionado pertence a outra loja.',
                ]);
            }

            $veiculo = Veiculo::query()
                ->whereKey($ordemServico->veiculo_id)
                ->first(['cliente_id', 'loja_id']);

            if ($veiculo && (int) $veiculo->cliente_id !== (int) $ordemServico->cliente_id) {
                throw ValidationException::withMessages([
                    'veiculo_id' => 'O veiculo selecionado nao pertence ao cliente da OS.',
                ]);
            }

            if ($veiculo && (int) $veiculo->loja_id !== (int) $ordemServico->loja_id) {
                throw ValidationException::withMessages([
                    'veiculo_id' => 'O veiculo selecionado pertence a outra loja.',
                ]);
            }

            if ($ordemServico->funcionario_id) {
                $funcionarioLojaId = Funcionario::query()
                    ->whereKey($ordemServico->funcionario_id)
                    ->value('loja_id');

                if ($funcionarioLojaId !== null && (int) $funcionarioLojaId !== (int) $ordemServico->loja_id) {
                    throw ValidationException::withMessages([
                        'funcionario_id' => 'O funcionario selecionado pertence a outra loja.',
                    ]);
                }
            }
        });
    }

    public function loja(): BelongsTo
    {
        return $this->belongsTo(Loja::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function veiculo(): BelongsTo
    {
        return $this->belongsTo(Veiculo::class);
    }

    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(OrdemServicoItem::class);
    }

    public function recalcularValorTotal(): void
    {
        $total = $this->itens()
            ->selectRaw('COALESCE(SUM(preco_unitario * quantidade), 0) as total')
            ->value('total');

        $this->forceFill(['valor_total' => $total])->saveQuietly();
    }
}
