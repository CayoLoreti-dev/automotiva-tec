<?php

namespace App\Models;

use App\Enums\ComissaoStatus;
use App\Enums\OrdemServicoStatus;
use Database\Factories\OrdemServicoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

        static::saved(fn (OrdemServico $ordemServico): mixed => $ordemServico->sincronizarComissao());
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

    public function produtos(): HasMany
    {
        return $this->hasMany(OrdemServicoProduto::class);
    }

    public function pagamentos(): HasMany
    {
        return $this->hasMany(Pagamento::class);
    }

    public function comissao(): HasOne
    {
        return $this->hasOne(Comissao::class);
    }

    public function recalcularValorTotal(): void
    {
        $total = $this->itens()
            ->selectRaw('COALESCE(SUM(preco_unitario * quantidade), 0) as total')
            ->value('total');

        $this->forceFill(['valor_total' => $total])->saveQuietly();
        $this->sincronizarComissao();
    }

    public function sincronizarComissao(): void
    {
        $comissao = Comissao::query()->where('ordem_servico_id', $this->getKey())->first();

        if ($this->status === OrdemServicoStatus::Cancelado) {
            if ($comissao?->status === ComissaoStatus::Pendente) {
                $comissao->delete();
            }

            return;
        }

        if (! in_array($this->status, [OrdemServicoStatus::Concluido, OrdemServicoStatus::Entregue], true)) {
            return;
        }

        if (! $this->funcionario_id) {
            return;
        }

        $funcionario = $this->funcionario()->first();
        $percentual = (float) ($funcionario?->percentual_comissao ?? 0);

        if ($percentual <= 0) {
            return;
        }

        if ($comissao?->status === ComissaoStatus::Paga) {
            return;
        }

        $valorTotal = (float) static::query()
            ->whereKey($this->getKey())
            ->value('valor_total');

        Comissao::query()->updateOrCreate(
            ['ordem_servico_id' => $this->getKey()],
            [
                'loja_id' => $this->loja_id,
                'funcionario_id' => $this->funcionario_id,
                'percentual_aplicado' => $percentual,
                'valor_comissao' => ($valorTotal * $percentual) / 100,
                'status' => ComissaoStatus::Pendente,
            ],
        );
    }
}
