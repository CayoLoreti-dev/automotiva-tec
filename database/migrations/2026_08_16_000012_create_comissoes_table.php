<?php

use App\Enums\ComissaoStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comissoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loja_id')->constrained('lojas')->cascadeOnDelete();
            $table->foreignId('ordem_servico_id')->unique()->constrained('ordens_servico')->cascadeOnDelete();
            $table->foreignId('funcionario_id')->constrained('funcionarios');
            $table->decimal('percentual_aplicado', 5, 2);
            $table->decimal('valor_comissao', 10, 2);
            $table->enum('status', array_column(ComissaoStatus::cases(), 'value'))->default(ComissaoStatus::Pendente->value);
            $table->timestamp('data_pagamento')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comissoes');
    }
};
