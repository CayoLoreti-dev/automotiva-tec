<?php

namespace Database\Seeders;

use App\Enums\LojaStatus;
use App\Enums\LojaUserRole;
use App\Models\Loja;
use App\Models\User;
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
    }
}
