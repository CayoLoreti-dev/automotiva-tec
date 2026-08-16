<?php

use App\Enums\LojaUserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loja_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loja_id')->constrained('lojas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', array_column(LojaUserRole::cases(), 'value'));
            $table->timestamps();

            $table->unique(['loja_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loja_user');
    }
};
