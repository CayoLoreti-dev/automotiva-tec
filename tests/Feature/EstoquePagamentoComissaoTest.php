<?php

namespace Tests\Feature;

use App\Enums\ComissaoStatus;
use App\Enums\FormaPagamento;
use App\Enums\OrdemServicoStatus;
use App\Filament\App\Resources\Comissaos\ComissaoResource;
use App\Models\Cliente;
use App\Models\Comissao;
use App\Models\Funcionario;
use App\Models\Loja;
use App\Models\OrdemServico;
use App\Models\OrdemServicoItem;
use App\Models\OrdemServicoProduto;
use App\Models\Pagamento;
use App\Models\Produto;
use App\Models\Servico;
use App\Models\User;
use App\Models\Veiculo;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class EstoquePagamentoComissaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_is_decreased_adjusted_and_restored_for_order_products(): void
    {
        $loja = Loja::factory()->create();
        $ordem = $this->createOrdemServicoForLoja($loja);
        $produto = Produto::factory()->for($loja)->create(['quantidade_atual' => 10]);

        $uso = OrdemServicoProduto::query()->create([
            'ordem_servico_id' => $ordem->id,
            'produto_id' => $produto->id,
            'quantidade_utilizada' => 3,
        ]);

        $this->assertSame('7.00', $produto->fresh()->quantidade_atual);

        $uso->update(['quantidade_utilizada' => 5]);

        $this->assertSame('5.00', $produto->fresh()->quantidade_atual);

        $uso->update(['quantidade_utilizada' => 2]);

        $this->assertSame('8.00', $produto->fresh()->quantidade_atual);

        $uso->delete();

        $this->assertSame('10.00', $produto->fresh()->quantidade_atual);
    }

    public function test_using_more_product_than_available_is_blocked(): void
    {
        $loja = Loja::factory()->create();
        $ordem = $this->createOrdemServicoForLoja($loja);
        $produto = Produto::factory()->for($loja)->create(['quantidade_atual' => 1]);

        $this->expectException(ValidationException::class);

        OrdemServicoProduto::query()->create([
            'ordem_servico_id' => $ordem->id,
            'produto_id' => $produto->id,
            'quantidade_utilizada' => 2,
        ]);
    }

    public function test_product_from_another_store_cannot_be_used_on_order(): void
    {
        $lojaA = Loja::factory()->create();
        $lojaB = Loja::factory()->create();
        $ordem = $this->createOrdemServicoForLoja($lojaA);
        $produtoB = Produto::factory()->for($lojaB)->create(['quantidade_atual' => 10]);

        $this->expectException(ValidationException::class);

        OrdemServicoProduto::query()->create([
            'ordem_servico_id' => $ordem->id,
            'produto_id' => $produtoB->id,
            'quantidade_utilizada' => 1,
        ]);
    }

    public function test_payment_cannot_be_created_for_canceled_order(): void
    {
        $loja = Loja::factory()->create();
        $ordem = $this->createOrdemServicoForLoja($loja);
        $ordem->update(['status' => OrdemServicoStatus::Cancelado]);

        $this->expectException(ValidationException::class);

        Pagamento::query()->create([
            'ordem_servico_id' => $ordem->id,
            'forma_pagamento' => FormaPagamento::Pix,
            'valor' => 100,
        ]);
    }

    public function test_completed_order_generates_commission_and_paid_commission_is_not_overwritten(): void
    {
        $loja = Loja::factory()->create();
        $funcionario = Funcionario::factory()->for($loja)->create(['percentual_comissao' => 10]);
        $ordem = $this->createOrdemServicoForLoja($loja, $funcionario);
        $servico = Servico::factory()->for($loja)->create(['preco' => 200]);

        OrdemServicoItem::query()->create([
            'ordem_servico_id' => $ordem->id,
            'servico_id' => $servico->id,
            'preco_unitario' => 200,
            'quantidade' => 2,
        ]);

        $ordem->update(['status' => OrdemServicoStatus::Concluido]);

        $comissao = Comissao::query()->where('ordem_servico_id', $ordem->id)->firstOrFail();

        $this->assertSame('10.00', $comissao->percentual_aplicado);
        $this->assertSame('40.00', $comissao->valor_comissao);

        $comissao->update([
            'status' => ComissaoStatus::Paga,
            'data_pagamento' => now(),
        ]);

        $funcionario->update(['percentual_comissao' => 30]);
        $ordem->update(['observacoes' => 'Salvar novamente nao altera comissao paga.']);

        $this->assertSame('10.00', $comissao->fresh()->percentual_aplicado);
        $this->assertSame('40.00', $comissao->fresh()->valor_comissao);
    }

    public function test_canceling_order_removes_pending_commission_but_keeps_paid_commission(): void
    {
        $loja = Loja::factory()->create();
        $funcionario = Funcionario::factory()->for($loja)->create(['percentual_comissao' => 10]);
        $ordemPendente = $this->createConcludedOrderWithCommission($loja, $funcionario);

        $this->assertDatabaseHas('comissoes', [
            'ordem_servico_id' => $ordemPendente->id,
            'status' => ComissaoStatus::Pendente->value,
        ]);

        $ordemPendente->update(['status' => OrdemServicoStatus::Cancelado]);

        $this->assertDatabaseMissing('comissoes', [
            'ordem_servico_id' => $ordemPendente->id,
        ]);

        $ordemPaga = $this->createConcludedOrderWithCommission($loja, $funcionario);
        $ordemPaga->comissao()->first()->update([
            'status' => ComissaoStatus::Paga,
            'data_pagamento' => now(),
        ]);

        $ordemPaga->update(['status' => OrdemServicoStatus::Cancelado]);

        $this->assertDatabaseHas('comissoes', [
            'ordem_servico_id' => $ordemPaga->id,
            'status' => ComissaoStatus::Paga->value,
        ]);
    }

    public function test_commission_resource_is_scoped_to_current_tenant(): void
    {
        $lojaA = Loja::factory()->create();
        $lojaB = Loja::factory()->create();
        $user = User::factory()->create();

        $comissaoA = $this->createConcludedOrderWithCommission($lojaA)->comissao()->first();
        $comissaoB = $this->createConcludedOrderWithCommission($lojaB)->comissao()->first();

        Filament::setCurrentPanel(Filament::getPanel('app'));
        $this->actingAs($user);
        Filament::setTenant($lojaA);
        Filament::bootCurrentPanel();

        $this->assertSame([$comissaoA->id], ComissaoResource::getEloquentQuery()->pluck('id')->all());
        $this->assertNotContains($comissaoB->id, ComissaoResource::getEloquentQuery()->pluck('id')->all());
    }

    public function test_database_seeder_populates_mvp_financial_data(): void
    {
        $this->seed();

        $this->assertDatabaseHas('ordem_servico_produtos', ['quantidade_utilizada' => 1.5]);
        $this->assertDatabaseHas('pagamentos', ['forma_pagamento' => FormaPagamento::Pix->value]);
        $this->assertDatabaseCount('comissoes', 1);
    }

    private function createConcludedOrderWithCommission(Loja $loja, ?Funcionario $funcionario = null): OrdemServico
    {
        $funcionario ??= Funcionario::factory()->for($loja)->create(['percentual_comissao' => 10]);
        $ordem = $this->createOrdemServicoForLoja($loja, $funcionario);
        $servico = Servico::factory()->for($loja)->create(['preco' => 100]);

        OrdemServicoItem::query()->create([
            'ordem_servico_id' => $ordem->id,
            'servico_id' => $servico->id,
            'preco_unitario' => 100,
            'quantidade' => 1,
        ]);

        $ordem->update(['status' => OrdemServicoStatus::Concluido]);

        return $ordem->fresh();
    }

    private function createOrdemServicoForLoja(Loja $loja, ?Funcionario $funcionario = null): OrdemServico
    {
        $cliente = Cliente::factory()->for($loja)->create();
        $veiculo = Veiculo::factory()->create([
            'loja_id' => $loja->id,
            'cliente_id' => $cliente->id,
        ]);

        $funcionario ??= Funcionario::factory()->for($loja)->create();

        return OrdemServico::query()->create([
            'loja_id' => $loja->id,
            'cliente_id' => $cliente->id,
            'veiculo_id' => $veiculo->id,
            'funcionario_id' => $funcionario->id,
        ]);
    }
}
