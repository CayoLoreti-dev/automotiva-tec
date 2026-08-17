<?php

namespace App\Filament\App\Resources\Clientes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                TextInput::make('telefone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                TextInput::make('cpf_cnpj')
                    ->label('CPF/CNPJ')
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Textarea::make('observacoes')
                    ->label('Observacoes')
                    ->columnSpanFull(),
            ]);
    }
}
