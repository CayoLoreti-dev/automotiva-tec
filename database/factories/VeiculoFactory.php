<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Loja;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Veiculo>
 */
class VeiculoFactory extends Factory
{
    public function definition(): array
    {
        $cliente = Cliente::factory()->create();

        return [
            'loja_id' => $cliente->loja_id,
            'cliente_id' => $cliente->id,
            'placa' => strtoupper(fake()->bothify('???#?##')),
            'modelo' => fake()->randomElement(['Onix', 'Civic', 'Corolla', 'HB20', 'Compass']),
            'marca' => fake()->randomElement(['Chevrolet', 'Honda', 'Toyota', 'Hyundai', 'Jeep']),
            'cor' => fake()->safeColorName(),
            'ano' => fake()->numberBetween(2005, 2026),
            'observacoes' => fake()->optional()->sentence(),
        ];
    }

    public function forLoja(Loja $loja): static
    {
        return $this->state(fn (): array => [
            'loja_id' => $loja->id,
            'cliente_id' => Cliente::factory()->for($loja)->create()->id,
        ]);
    }
}
