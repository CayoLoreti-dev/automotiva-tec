<?php

namespace Database\Factories;

use App\Models\Loja;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'loja_id' => Loja::factory(),
            'nome' => fake()->randomElement(['Shampoo automotivo', 'Cera liquida', 'Microfibra', 'Desengraxante']),
            'unidade' => fake()->randomElement(['un', 'lt', 'kg']),
            'quantidade_atual' => fake()->randomFloat(2, 0, 30),
            'quantidade_minima' => fake()->randomFloat(2, 2, 10),
            'preco_custo' => fake()->randomFloat(2, 10, 150),
            'ativo' => true,
        ];
    }
}
