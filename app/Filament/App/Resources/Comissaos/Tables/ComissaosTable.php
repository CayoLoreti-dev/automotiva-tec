<?php

namespace App\Filament\App\Resources\Comissaos\Tables;

use App\Enums\ComissaoStatus;
use App\Models\Funcionario;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ComissaosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('funcionario.nome')
                    ->label('Funcionario')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ordemServico.cliente.nome')
                    ->label('Cliente')
                    ->searchable(),
                TextColumn::make('ordemServico.veiculo.placa')
                    ->label('Veiculo')
                    ->searchable(),
                TextColumn::make('percentual_aplicado')
                    ->label('Percentual')
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('valor_comissao')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (ComissaoStatus $state): string => ucfirst($state->value))
                    ->color(fn (ComissaoStatus $state): string => $state === ComissaoStatus::Paga ? 'success' : 'warning'),
                TextColumn::make('data_pagamento')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('funcionario_id')
                    ->label('Funcionario')
                    ->options(fn (): array => Funcionario::query()->orderBy('nome')->pluck('nome', 'id')->all()),
                SelectFilter::make('status')
                    ->options(collect(ComissaoStatus::cases())->mapWithKeys(
                        fn (ComissaoStatus $status): array => [$status->value => ucfirst($status->value)]
                    )),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('marcarComoPaga')
                    ->label('Marcar como paga')
                    ->visible(fn ($record): bool => $record->status === ComissaoStatus::Pendente)
                    ->action(fn ($record): mixed => $record->update([
                        'status' => ComissaoStatus::Paga,
                        'data_pagamento' => now(),
                    ])),
            ])
            ->toolbarActions([]);
    }
}
