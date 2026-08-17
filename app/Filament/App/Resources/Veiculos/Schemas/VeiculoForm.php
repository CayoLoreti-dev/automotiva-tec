<?php

namespace App\Filament\App\Resources\Veiculos\Schemas;

use App\Models\Cliente;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VeiculoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('cliente_id')
                    ->label('Cliente')
                    ->options(fn (): array => Cliente::query()
                        ->where('loja_id', Filament::getTenant()?->getKey())
                        ->orderBy('nome')
                        ->pluck('nome', 'id')
                        ->all())
                    ->searchable()
                    ->required(),
                TextInput::make('placa')
                    ->required()
                    ->maxLength(255),
                TextInput::make('modelo')
                    ->required()
                    ->maxLength(255),
                TextInput::make('marca')
                    ->maxLength(255),
                TextInput::make('cor')
                    ->maxLength(255),
                TextInput::make('ano')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(2100),
                Textarea::make('observacoes')
                    ->label('Observacoes')
                    ->columnSpanFull(),
            ]);
    }
}
