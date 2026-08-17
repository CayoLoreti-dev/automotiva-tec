<?php

namespace Database\Seeders;

use App\Enums\LojaStatus;
use App\Enums\LojaUserRole;
use App\Models\Cliente;
use App\Models\Loja;
use App\Models\Produto;
use App\Models\Servico;
use App\Models\User;
use App\Models\Veiculo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin SaaS',
            'email' => 'admin@sistema.test',
            'password' => 'password',
            'is_super_admin' => true,
        ]);

        $loja = Loja::query()->create([
            'nome' => 'Loja Teste',
            'cnpj' => '00.000.000/0001-00',
            'plano' => 'starter',
            'status' => LojaStatus::Ativa,
            'data_vencimento' => now()->addMonth()->toDateString(),
            'valor_mensalidade' => 199.90,
        ]);

        $dono = User::factory()->create([
            'name' => 'Dono Loja Teste',
            'email' => 'dono@lojateste.test',
            'password' => 'password',
        ]);

        $dono->attachLoja($loja, LojaUserRole::Dono);

        $clienteJoao = Cliente::query()->create([
            'loja_id' => $loja->id,
            'nome' => 'Joao Pereira',
            'telefone' => '(11) 99999-1000',
            'cpf_cnpj' => '123.456.789-00',
            'email' => 'joao@example.test',
            'observacoes' => 'Cliente recorrente para lavagem completa.',
        ]);

        $clienteMaria = Cliente::query()->create([
            'loja_id' => $loja->id,
            'nome' => 'Maria Souza',
            'telefone' => '(11) 99999-2000',
            'cpf_cnpj' => '987.654.321-00',
            'email' => 'maria@example.test',
        ]);

        Veiculo::query()->create([
            'loja_id' => $loja->id,
            'cliente_id' => $clienteJoao->id,
            'placa' => 'ABC1D23',
            'modelo' => 'Civic',
            'marca' => 'Honda',
            'cor' => 'Prata',
            'ano' => 2021,
        ]);

        Veiculo::query()->create([
            'loja_id' => $loja->id,
            'cliente_id' => $clienteMaria->id,
            'placa' => 'XYZ9A87',
            'modelo' => 'Compass',
            'marca' => 'Jeep',
            'cor' => 'Preto',
            'ano' => 2023,
        ]);

        Servico::query()->insert([
            [
                'loja_id' => $loja->id,
                'nome' => 'Lavagem completa',
                'descricao' => 'Lavagem externa e limpeza interna.',
                'preco' => 90.00,
                'duracao_estimada_minutos' => 90,
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'loja_id' => $loja->id,
                'nome' => 'Polimento tecnico',
                'descricao' => 'Correcao de pintura em uma etapa.',
                'preco' => 450.00,
                'duracao_estimada_minutos' => 240,
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'loja_id' => $loja->id,
                'nome' => 'Vitrificacao',
                'descricao' => 'Protecao ceramica de pintura.',
                'preco' => 900.00,
                'duracao_estimada_minutos' => 360,
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Produto::query()->insert([
            [
                'loja_id' => $loja->id,
                'nome' => 'Shampoo automotivo',
                'unidade' => 'lt',
                'quantidade_atual' => 12,
                'quantidade_minima' => 5,
                'preco_custo' => 38.50,
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'loja_id' => $loja->id,
                'nome' => 'Cera liquida',
                'unidade' => 'un',
                'quantidade_atual' => 2,
                'quantidade_minima' => 3,
                'preco_custo' => 52.90,
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'loja_id' => $loja->id,
                'nome' => 'Pano de microfibra',
                'unidade' => 'un',
                'quantidade_atual' => 25,
                'quantidade_minima' => 10,
                'preco_custo' => 8.90,
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
