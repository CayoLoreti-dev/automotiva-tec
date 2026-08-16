<?php

namespace App\Filament\Master\Resources\Lojas\Schemas;

use App\Enums\LojaStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LojaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                TextInput::make('cnpj')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('plano')
                    ->maxLength(255),
                Select::make('status')
                    ->options(collect(LojaStatus::cases())->mapWithKeys(
                        fn (LojaStatus $status): array => [$status->value => ucfirst($status->value)]
                    ))
                    ->required(),
                DatePicker::make('data_vencimento'),
                TextInput::make('valor_mensalidade')
                    ->numeric()
                    ->prefix('R$'),
            ]);
    }
}
