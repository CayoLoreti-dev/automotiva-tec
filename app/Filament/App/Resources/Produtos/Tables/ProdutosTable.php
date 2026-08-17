<?php

namespace App\Filament\App\Resources\Produtos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProdutosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unidade'),
                TextColumn::make('quantidade_atual')
                    ->label('Atual')
                    ->badge()
                    ->color(fn ($record): string => $record->isEstoqueBaixo() ? 'danger' : 'success')
                    ->sortable(),
                TextColumn::make('quantidade_minima')
                    ->label('Minima')
                    ->sortable(),
                TextColumn::make('preco_custo')
                    ->money('BRL')
                    ->sortable(),
                IconColumn::make('ativo')
                    ->boolean()
                    ->label('Ativo'),
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
