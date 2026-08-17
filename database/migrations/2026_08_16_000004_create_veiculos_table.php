<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('veiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loja_id')->constrained('lojas')->cascadeOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->string('placa');
            $table->string('modelo');
            $table->string('marca')->nullable();
            $table->string('cor')->nullable();
            $table->integer('ano')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->unique(['loja_id', 'placa']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('veiculos');
    }
};
