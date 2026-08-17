<?php

namespace App\Filament\App\Resources\Clientes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('telefone')
                    ->searchable(),
                TextColumn::make('cpf_cnpj')
                    ->label('CPF/CNPJ')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('veiculos_count')
                    ->counts('veiculos')
                    ->label('Veiculos')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
