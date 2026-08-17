<?php

namespace Database\Factories;

use App\Enums\FormaPagamento;
use App\Models\OrdemServico;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Pagamento>
 */
class PagamentoFactory extends Factory
{
    public function definition(): array
    {
        $ordemServico = OrdemServico::factory()->create();

        return [
            'loja_id' => $ordemServico->loja_id,
            'ordem_servico_id' => $ordemServico->id,
            'forma_pagamento' => FormaPagamento::Pix,
            'valor' => fake()->randomFloat(2, 50, 500),
            'data_pagamento' => now(),
            'observacoes' => fake()->optional()->sentence(),
        ];
    }
}
