<?php

namespace Database\Factories;

use App\Enums\LojaStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Loja>
 */
class LojaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => fake()->company(),
            'cnpj' => fake()->unique()->numerify('##.###.###/####-##'),
            'plano' => 'starter',
            'status' => LojaStatus::Ativa,
            'data_vencimento' => now()->addMonth()->toDateString(),
            'valor_mensalidade' => 199.90,
        ];
    }
}
