<?php

namespace Database\Factories;

use App\Models\OrdemServico;
use App\Models\Servico;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\OrdemServicoItem>
 */
class OrdemServicoItemFactory extends Factory
{
    public function definition(): array
    {
        $ordemServico = OrdemServico::factory()->create();
        $servico = Servico::factory()->create([
            'loja_id' => $ordemServico->loja_id,
        ]);

        return [
            'ordem_servico_id' => $ordemServico->id,
            'servico_id' => $servico->id,
            'preco_unitario' => $servico->preco,
            'quantidade' => fake()->numberBetween(1, 3),
        ];
    }
}
