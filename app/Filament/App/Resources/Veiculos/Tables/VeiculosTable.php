<?php

namespace App\Filament\App\Resources\Veiculos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VeiculosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('placa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cliente.nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('modelo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('marca')
                    ->searchable(),
                TextColumn::make('cor'),
                TextColumn::make('ano')
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
