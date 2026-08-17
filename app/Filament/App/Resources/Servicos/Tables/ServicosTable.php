<?php

namespace App\Filament\App\Resources\Servicos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServicosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('preco')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('duracao_estimada_minutos')
                    ->label('Duracao')
                    ->suffix(' min')
                    ->sortable(),
                TextColumn::make('ativo')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Ativo' : 'Inativo')
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
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
