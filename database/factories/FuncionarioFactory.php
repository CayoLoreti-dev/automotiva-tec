<?php

namespace Database\Factories;

use App\Models\Loja;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Funcionario>
 */
class FuncionarioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'loja_id' => Loja::factory(),
            'nome' => fake()->name(),
            'cargo' => fake()->randomElement(['Lavador', 'Polidor', 'Vitrificador', 'Atendente']),
            'percentual_comissao' => fake()->randomFloat(2, 5, 20),
            'ativo' => true,
        ];
    }
}
