<?php

use App\Enums\OrdemServicoStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordens_servico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loja_id')->constrained('lojas')->cascadeOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('veiculo_id')->constrained('veiculos');
            $table->foreignId('funcionario_id')->nullable()->constrained('funcionarios');
            $table->enum('status', array_column(OrdemServicoStatus::cases(), 'value'))->default(OrdemServicoStatus::Aguardando->value);
            $table->decimal('valor_total', 10, 2)->default(0);
            $table->text('observacoes')->nullable();
            $table->timestamp('data_abertura')->useCurrent();
            $table->timestamp('data_conclusao')->nullable();
            $table->timestamp('data_entrega')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordens_servico');
    }
};
