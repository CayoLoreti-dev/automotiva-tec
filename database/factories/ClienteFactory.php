<?php

namespace Database\Factories;

use App\Models\Loja;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'loja_id' => Loja::factory(),
            'nome' => fake()->name(),
            'telefone' => fake()->phoneNumber(),
            'cpf_cnpj' => fake()->numerify('###.###.###-##'),
            'email' => fake()->safeEmail(),
            'observacoes' => fake()->optional()->sentence(),
        ];
    }
}
