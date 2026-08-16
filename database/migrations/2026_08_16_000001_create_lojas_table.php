<?php

use App\Enums\LojaStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lojas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cnpj')->unique();
            $table->string('plano')->nullable();
            $table->enum('status', array_column(LojaStatus::cases(), 'value'))->default(LojaStatus::Trial->value);
            $table->date('data_vencimento')->nullable();
            $table->decimal('valor_mensalidade', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lojas');
    }
};
