<?php

namespace Tests\Feature;

use App\Filament\App\Resources\Clientes\ClienteResource;
use App\Filament\App\Resources\Produtos\ProdutoResource;
use App\Filament\App\Resources\Servicos\ServicoResource;
use App\Filament\App\Resources\Veiculos\VeiculoResource;
use App\Models\Cliente;
use App\Models\Loja;
use App\Models\Produto;
use App\Models\Servico;
use App\Models\User;
use App\Models\Veiculo;
use Filament\Facades\Filament;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CadastroBaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_app_resources_are_scoped_to_current_tenant(): void
    {
        $lojaA = Loja::factory()->create();
        $lojaB = Loja::factory()->create();
        $user = User::factory()->create();

        $clienteA = Cliente::factory()->for($lojaA)->create();
        $clienteB = Cliente::factory()->for($lojaB)->create();
        $veiculoA = Veiculo::factory()->create([
            'loja_id' => $lojaA->id,
            'cliente_id' => $clienteA->id,
        ]);
        $veiculoB = Veiculo::factory()->create([
            'loja_id' => $lojaB->id,
            'cliente_id' => $clienteB->id,
        ]);
        $servicoA = Servico::factory()->for($lojaA)->create();
        $servicoB = Servico::factory()->for($lojaB)->create();
        $produtoA = Produto::factory()->for($lojaA)->create();
        $produtoB = Produto::factory()->for($lojaB)->create();

        Filament::setCurrentPanel(Filament::getPanel('app'));
        $this->actingAs($user);
        Filament::setTenant($lojaA);
        Filament::bootCurrentPanel();

        $this->assertSame([$clienteA->id], ClienteResource::getEloquentQuery()->pluck('id')->all());
        $this->assertSame([$veiculoA->id], VeiculoResource::getEloquentQuery()->pluck('id')->all());
        $this->assertSame([$servicoA->id], ServicoResource::getEloquentQuery()->pluck('id')->all());
        $this->assertSame([$produtoA->id], ProdutoResource::getEloquentQuery()->pluck('id')->all());

        $this->assertNotContains($clienteB->id, ClienteResource::getEloquentQuery()->pluck('id')->all());
        $this->assertNotContains($veiculoB->id, VeiculoResource::getEloquentQuery()->pluck('id')->all());
        $this->assertNotContains($servicoB->id, ServicoResource::getEloquentQuery()->pluck('id')->all());
        $this->assertNotContains($produtoB->id, ProdutoResource::getEloquentQuery()->pluck('id')->all());
    }

    public function test_vehicle_plate_is_unique_per_store_only(): void
    {
        $lojaA = Loja::factory()->create();
        $lojaB = Loja::factory()->create();
        $clienteA = Cliente::factory()->for($lojaA)->create();
        $clienteB = Cliente::factory()->for($lojaB)->create();

        Veiculo::query()->create([
            'loja_id' => $lojaA->id,
            'cliente_id' => $clienteA->id,
            'placa' => 'ABC1D23',
            'modelo' => 'Civic',
        ]);

        Veiculo::query()->create([
            'loja_id' => $lojaB->id,
            'cliente_id' => $clienteB->id,
            'placa' => 'ABC1D23',
            'modelo' => 'Corolla',
        ]);

        $this->expectException(QueryException::class);

        Veiculo::query()->create([
            'loja_id' => $lojaA->id,
            'cliente_id' => $clienteA->id,
            'placa' => 'ABC1D23',
            'modelo' => 'Onix',
        ]);
    }

    public function test_vehicle_cannot_be_linked_to_customer_from_another_store(): void
    {
        $lojaA = Loja::factory()->create();
        $lojaB = Loja::factory()->create();
        $clienteB = Cliente::factory()->for($lojaB)->create();

        $this->expectException(ValidationException::class);

        Veiculo::query()->create([
            'loja_id' => $lojaA->id,
            'cliente_id' => $clienteB->id,
            'placa' => 'DEF4G56',
            'modelo' => 'HB20',
        ]);
    }

    public function test_database_seeder_populates_base_registration_data(): void
    {
        $this->seed();

        $this->assertDatabaseHas('clientes', ['nome' => 'Joao Pereira']);
        $this->assertDatabaseHas('veiculos', ['placa' => 'ABC1D23']);
        $this->assertDatabaseHas('servicos', ['nome' => 'Lavagem completa']);
        $this->assertDatabaseHas('produtos', ['nome' => 'Cera liquida']);
    }
}
