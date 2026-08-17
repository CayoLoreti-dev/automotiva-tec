<?php

namespace App\Filament\App\Resources\Funcionarios\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FuncionarioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                TextInput::make('cargo')
                    ->maxLength(255),
                TextInput::make('percentual_comissao')
                    ->label('Percentual de comissao')
                    ->numeric()
                    ->suffix('%')
                    ->minValue(0)
                    ->maxValue(100),
                Toggle::make('ativo')
                    ->default(true),
            ]);
    }
}
