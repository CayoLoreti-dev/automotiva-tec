<?php

namespace Database\Seeders;

use App\Enums\LojaStatus;
use App\Enums\LojaUserRole;
use App\Enums\OrdemServicoStatus;
use App\Enums\FormaPagamento;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Loja;
use App\Models\OrdemServico;
use App\Models\OrdemServicoItem;
use App\Models\OrdemServicoProduto;
use App\Models\Pagamento;
use App\Models\Produto;
use App\Models\Servico;
use App\Models\User;
use App\Models\Veiculo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
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

        $veiculoMaria = Veiculo::query()->create([
            'loja_id' => $loja->id,
            'cliente_id' => $clienteMaria->id,
            'placa' => 'XYZ9A87',
            'modelo' => 'Compass',
            'marca' => 'Jeep',
            'cor' => 'Preto',
            'ano' => 2023,
        ]);

        $lavagem = Servico::query()->create([
            'loja_id' => $loja->id,
            'nome' => 'Lavagem completa',
            'descricao' => 'Lavagem externa e limpeza interna.',
            'preco' => 90.00,
            'duracao_estimada_minutos' => 90,
            'ativo' => true,
        ]);

        $polimento = Servico::query()->create([
            'loja_id' => $loja->id,
            'nome' => 'Polimento tecnico',
            'descricao' => 'Correcao de pintura em uma etapa.',
            'preco' => 450.00,
            'duracao_estimada_minutos' => 240,
            'ativo' => true,
        ]);

        $vitrificacao = Servico::query()->create([
            'loja_id' => $loja->id,
            'nome' => 'Vitrificacao',
            'descricao' => 'Protecao ceramica de pintura.',
            'preco' => 900.00,
            'duracao_estimada_minutos' => 360,
            'ativo' => true,
        ]);

        $shampoo = Produto::query()->create([
            'loja_id' => $loja->id,
            'nome' => 'Shampoo automotivo',
            'unidade' => 'lt',
            'quantidade_atual' => 12,
            'quantidade_minima' => 5,
            'preco_custo' => 38.50,
            'ativo' => true,
        ]);

        $cera = Produto::query()->create([
            'loja_id' => $loja->id,
            'nome' => 'Cera liquida',
            'unidade' => 'un',
            'quantidade_atual' => 2,
            'quantidade_minima' => 3,
            'preco_custo' => 52.90,
            'ativo' => true,
        ]);

        Produto::query()->create([
            'loja_id' => $loja->id,
            'nome' => 'Pano de microfibra',
            'unidade' => 'un',
            'quantidade_atual' => 25,
            'quantidade_minima' => 10,
            'preco_custo' => 8.90,
            'ativo' => true,
        ]);

        $ana = Funcionario::query()->create([
            'loja_id' => $loja->id,
            'nome' => 'Ana Lima',
            'cargo' => 'Atendente',
            'percentual_comissao' => 5,
            'ativo' => true,
        ]);

        $carlos = Funcionario::query()->create([
            'loja_id' => $loja->id,
            'nome' => 'Carlos Silva',
            'cargo' => 'Polidor',
            'percentual_comissao' => 12,
            'ativo' => true,
        ]);

        Funcionario::query()->create([
            'loja_id' => $loja->id,
            'nome' => 'Paula Mendes',
            'cargo' => 'Lavadora',
            'percentual_comissao' => 8,
            'ativo' => true,
        ]);

        $veiculoJoao = Veiculo::query()->where('placa', 'ABC1D23')->firstOrFail();

        $osAguardando = OrdemServico::query()->create([
            'loja_id' => $loja->id,
            'cliente_id' => $clienteJoao->id,
            'veiculo_id' => $veiculoJoao->id,
            'funcionario_id' => $ana->id,
            'status' => OrdemServicoStatus::Aguardando,
            'observacoes' => 'Cliente pediu atencao especial nas rodas.',
            'data_abertura' => now()->subDay(),
        ]);

        OrdemServicoItem::query()->create([
            'ordem_servico_id' => $osAguardando->id,
            'servico_id' => $lavagem->id,
            'preco_unitario' => 90.00,
            'quantidade' => 1,
        ]);

        OrdemServicoProduto::query()->create([
            'ordem_servico_id' => $osAguardando->id,
            'produto_id' => $shampoo->id,
            'quantidade_utilizada' => 1.5,
        ]);

        Pagamento::query()->create([
            'ordem_servico_id' => $osAguardando->id,
            'forma_pagamento' => FormaPagamento::Pix,
            'valor' => 90.00,
            'data_pagamento' => now()->subHours(20),
            'observacoes' => 'Pagamento integral via Pix.',
        ]);

        $osAndamento = OrdemServico::query()->create([
            'loja_id' => $loja->id,
            'cliente_id' => $clienteMaria->id,
            'veiculo_id' => $veiculoMaria->id,
            'funcionario_id' => $carlos->id,
            'status' => OrdemServicoStatus::EmAndamento,
            'observacoes' => 'Polimento com vitrificacao.',
            'data_abertura' => now()->subHours(4),
        ]);

        OrdemServicoItem::query()->create([
            'ordem_servico_id' => $osAndamento->id,
            'servico_id' => $polimento->id,
            'preco_unitario' => 450.00,
            'quantidade' => 1,
        ]);

        OrdemServicoItem::query()->create([
            'ordem_servico_id' => $osAndamento->id,
            'servico_id' => $vitrificacao->id,
            'preco_unitario' => 900.00,
            'quantidade' => 1,
        ]);

        OrdemServicoProduto::query()->create([
            'ordem_servico_id' => $osAndamento->id,
            'produto_id' => $cera->id,
            'quantidade_utilizada' => 1,
        ]);

        $osAndamento->update([
            'status' => OrdemServicoStatus::Concluido,
            'data_conclusao' => now(),
        ]);
    }
}
