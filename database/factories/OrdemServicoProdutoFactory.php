<?php

namespace Database\Factories;

use App\Models\OrdemServico;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\OrdemServicoProduto>
 */
class OrdemServicoProdutoFactory extends Factory
{
    public function definition(): array
    {
        $ordemServico = OrdemServico::factory()->create();
        $produto = Produto::factory()->create([
            'loja_id' => $ordemServico->loja_id,
            'quantidade_atual' => 20,
        ]);

        return [
            'ordem_servico_id' => $ordemServico->id,
            'produto_id' => $produto->id,
            'quantidade_utilizada' => fake()->randomFloat(2, 1, 5),
        ];
    }
}
