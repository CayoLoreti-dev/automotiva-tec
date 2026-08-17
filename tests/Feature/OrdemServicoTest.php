<?php

namespace Tests\Feature;

use App\Filament\App\Resources\Funcionarios\FuncionarioResource;
use App\Filament\App\Resources\OrdemServicos\OrdemServicoResource;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Loja;
use App\Models\OrdemServico;
use App\Models\OrdemServicoItem;
use App\Models\Servico;
use App\Models\User;
use App\Models\Veiculo;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class OrdemServicoTest extends TestCase
{
    use RefreshDatabase;

    public function test_operation_resources_are_scoped_to_current_tenant(): void
    {
        $lojaA = Loja::factory()->create();
        $lojaB = Loja::factory()->create();
        $user = User::factory()->create();

        $funcionarioA = Funcionario::factory()->for($lojaA)->create();
        $funcionarioB = Funcionario::factory()->for($lojaB)->create();
        $ordemA = $this->createOrdemServicoForLoja($lojaA, $funcionarioA);
        $ordemB = $this->createOrdemServicoForLoja($lojaB, $funcionarioB);

        Filament::setCurrentPanel(Filament::getPanel('app'));
        $this->actingAs($user);
        Filament::setTenant($lojaA);
        Filament::bootCurrentPanel();

        $this->assertSame([$funcionarioA->id], FuncionarioResource::getEloquentQuery()->pluck('id')->all());
        $this->assertSame([$ordemA->id], OrdemServicoResource::getEloquentQuery()->pluck('id')->all());
        $this->assertNotContains($funcionarioB->id, FuncionarioResource::getEloquentQuery()->pluck('id')->all());
        $this->assertNotContains($ordemB->id, OrdemServicoResource::getEloquentQuery()->pluck('id')->all());
    }

    public function test_order_total_is_recalculated_when_items_are_created_updated_and_deleted(): void
    {
        $loja = Loja::factory()->create();
        $ordem = $this->createOrdemServicoForLoja($loja);
        $lavagem = Servico::factory()->for($loja)->create(['preco' => 100]);
        $polimento = Servico::factory()->for($loja)->create(['preco' => 250]);

        $item = OrdemServicoItem::query()->create([
            'ordem_servico_id' => $ordem->id,
            'servico_id' => $lavagem->id,
            'preco_unitario' => 100,
            'quantidade' => 2,
        ]);

        $this->assertSame('200.00', $ordem->fresh()->valor_total);

        $secondItem = OrdemServicoItem::query()->create([
            'ordem_servico_id' => $ordem->id,
            'servico_id' => $polimento->id,
            'preco_unitario' => 250,
            'quantidade' => 1,
        ]);

        $this->assertSame('450.00', $ordem->fresh()->valor_total);

        $item->update(['quantidade' => 3]);

        $this->assertSame('550.00', $ordem->fresh()->valor_total);

        $secondItem->delete();

        $this->assertSame('300.00', $ordem->fresh()->valor_total);
    }

    public function test_vehicle_from_another_customer_cannot_be_linked_to_order(): void
    {
        $loja = Loja::factory()->create();
        $clienteA = Cliente::factory()->for($loja)->create();
        $clienteB = Cliente::factory()->for($loja)->create();
        $veiculoB = Veiculo::factory()->create([
            'loja_id' => $loja->id,
            'cliente_id' => $clienteB->id,
        ]);

        $this->expectException(ValidationException::class);

        OrdemServico::query()->create([
            'loja_id' => $loja->id,
            'cliente_id' => $clienteA->id,
            'veiculo_id' => $veiculoB->id,
        ]);
    }

    public function test_employee_from_another_store_cannot_be_linked_to_order(): void
    {
        $lojaA = Loja::factory()->create();
        $lojaB = Loja::factory()->create();
        $cliente = Cliente::factory()->for($lojaA)->create();
        $veiculo = Veiculo::factory()->create([
            'loja_id' => $lojaA->id,
            'cliente_id' => $cliente->id,
        ]);
        $funcionarioB = Funcionario::factory()->for($lojaB)->create();

        $this->expectException(ValidationException::class);

        OrdemServico::query()->create([
            'loja_id' => $lojaA->id,
            'cliente_id' => $cliente->id,
            'veiculo_id' => $veiculo->id,
            'funcionario_id' => $funcionarioB->id,
        ]);
    }

    public function test_service_from_another_store_cannot_be_added_to_order(): void
    {
        $lojaA = Loja::factory()->create();
        $lojaB = Loja::factory()->create();
        $ordem = $this->createOrdemServicoForLoja($lojaA);
        $servicoB = Servico::factory()->for($lojaB)->create();

        $this->expectException(ValidationException::class);

        OrdemServicoItem::query()->create([
            'ordem_servico_id' => $ordem->id,
            'servico_id' => $servicoB->id,
            'preco_unitario' => 100,
            'quantidade' => 1,
        ]);
    }

    public function test_database_seeder_populates_operation_data(): void
    {
        $this->seed();

        $this->assertDatabaseHas('funcionarios', ['nome' => 'Ana Lima']);
        $this->assertDatabaseHas('funcionarios', ['nome' => 'Carlos Silva']);
        $this->assertDatabaseCount('ordens_servico', 2);
        $this->assertDatabaseCount('ordem_servico_itens', 3);
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
