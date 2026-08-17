<?php

namespace App\Filament\App\Resources\Produtos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProdutoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                TextInput::make('unidade')
                    ->required()
                    ->default('un')
                    ->maxLength(255),
                TextInput::make('quantidade_atual')
                    ->numeric()
                    ->required()
                    ->default(0),
                TextInput::make('quantidade_minima')
                    ->numeric(),
                TextInput::make('preco_custo')
                    ->numeric()
                    ->prefix('R$'),
                Toggle::make('ativo')
                    ->default(true),
            ]);
    }
}
