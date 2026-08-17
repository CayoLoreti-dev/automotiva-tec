<?php

namespace Database\Factories;

use App\Models\Loja;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Servico>
 */
class ServicoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'loja_id' => Loja::factory(),
            'nome' => fake()->randomElement(['Lavagem completa', 'Polimento tecnico', 'Vitrificacao', 'Higienizacao interna']),
            'descricao' => fake()->optional()->sentence(),
            'preco' => fake()->randomFloat(2, 60, 900),
            'duracao_estimada_minutos' => fake()->randomElement([60, 90, 120, 180, 240]),
            'ativo' => true,
        ];
    }
}
