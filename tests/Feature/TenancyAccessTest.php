<?php

namespace Tests\Feature;

use App\Enums\LojaStatus;
use App\Enums\LojaUserRole;
use App\Models\Loja;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenancyAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_master_panel_blocks_non_super_admin_users(): void
    {
        $loja = Loja::factory()->create();
        $user = User::factory()->create();
        $user->attachLoja($loja, LojaUserRole::Dono);

        $this->assertFalse($user->canAccessPanel(Filament::getPanel('master')));
    }

    public function test_master_panel_allows_super_admin_users(): void
    {
        $user = User::factory()->create([
            'is_super_admin' => true,
        ]);

        $this->assertTrue($user->canAccessPanel(Filament::getPanel('master')));
    }

    public function test_suspended_tenant_is_blocked_from_app_panel(): void
    {
        $loja = Loja::factory()->create([
            'status' => LojaStatus::Suspensa,
        ]);
        $user = User::factory()->create();
        $user->attachLoja($loja, LojaUserRole::Dono);

        $this->assertFalse($user->canAccessPanel(Filament::getPanel('app')));
        $this->assertFalse($user->canAccessTenant($loja));
    }

    public function test_database_seeder_runs_without_errors(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', [
            'email' => 'admin@sistema.test',
            'is_super_admin' => true,
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'dono@lojateste.test',
        ]);
        $this->assertDatabaseHas('lojas', [
            'nome' => 'Loja Teste',
            'status' => LojaStatus::Ativa->value,
        ]);
        $this->assertDatabaseHas('loja_user', [
            'role' => LojaUserRole::Dono->value,
        ]);
    }
}
