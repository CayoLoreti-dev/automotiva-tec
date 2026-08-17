<?php

namespace App\Filament\App\Resources\Servicos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServicoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                Textarea::make('descricao')
                    ->label('Descricao')
                    ->columnSpanFull(),
                TextInput::make('preco')
                    ->numeric()
                    ->required()
                    ->prefix('R$'),
                TextInput::make('duracao_estimada_minutos')
                    ->label('Duracao estimada')
                    ->numeric()
                    ->suffix('min'),
                Toggle::make('ativo')
                    ->default(true),
            ]);
    }
}
