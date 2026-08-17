<?php

namespace Database\Factories;

use App\Enums\OrdemServicoStatus;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Veiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\OrdemServico>
 */
class OrdemServicoFactory extends Factory
{
    public function definition(): array
    {
        $cliente = Cliente::factory()->create();
        $veiculo = Veiculo::factory()->create([
            'loja_id' => $cliente->loja_id,
            'cliente_id' => $cliente->id,
        ]);
        $funcionario = Funcionario::factory()->create([
            'loja_id' => $cliente->loja_id,
        ]);

        return [
            'loja_id' => $cliente->loja_id,
            'cliente_id' => $cliente->id,
            'veiculo_id' => $veiculo->id,
            'funcionario_id' => $funcionario->id,
            'status' => OrdemServicoStatus::Aguardando,
            'valor_total' => 0,
            'observacoes' => fake()->optional()->sentence(),
            'data_abertura' => now(),
        ];
    }
}
