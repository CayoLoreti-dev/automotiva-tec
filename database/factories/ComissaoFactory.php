<?php

namespace Database\Factories;

use App\Enums\ComissaoStatus;
use App\Models\Funcionario;
use App\Models\OrdemServico;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Comissao>
 */
class ComissaoFactory extends Factory
{
    public function definition(): array
    {
        $ordemServico = OrdemServico::factory()->create();
        $funcionario = Funcionario::factory()->create([
            'loja_id' => $ordemServico->loja_id,
        ]);

        return [
            'loja_id' => $ordemServico->loja_id,
            'ordem_servico_id' => $ordemServico->id,
            'funcionario_id' => $funcionario->id,
            'percentual_aplicado' => 10,
            'valor_comissao' => 50,
            'status' => ComissaoStatus::Pendente,
        ];
    }
}
