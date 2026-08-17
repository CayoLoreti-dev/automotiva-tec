<?php

namespace App\Filament\App\Resources\Comissaos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ComissaoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('funcionario.nome')
                    ->label('Funcionario')
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('ordemServico.cliente.nome')
                    ->label('Cliente')
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('percentual_aplicado')
                    ->suffix('%')
                    ->disabled(),
                TextInput::make('valor_comissao')
                    ->prefix('R$')
                    ->disabled(),
                TextInput::make('status')
                    ->disabled(),
                DateTimePicker::make('data_pagamento')
                    ->disabled(),
            ]);
    }
}
