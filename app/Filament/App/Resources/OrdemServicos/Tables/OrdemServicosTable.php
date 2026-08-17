<?php

namespace App\Filament\App\Resources\OrdemServicos\Tables;

use App\Enums\OrdemServicoStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdemServicosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cliente.nome')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('veiculo.placa')
                    ->label('Veiculo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('funcionario.nome')
                    ->label('Funcionario')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (OrdemServicoStatus $state): string => str($state->value)->replace('_', ' ')->title()->toString())
                    ->color(fn (OrdemServicoStatus $state): string => match ($state) {
                        OrdemServicoStatus::Aguardando => 'gray',
                        OrdemServicoStatus::EmAndamento => 'warning',
                        OrdemServicoStatus::Concluido,
                        OrdemServicoStatus::Entregue => 'success',
                        OrdemServicoStatus::Cancelado => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('valor_total')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('data_abertura')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(OrdemServicoStatus::cases())->mapWithKeys(
                        fn (OrdemServicoStatus $status): array => [$status->value => str($status->value)->replace('_', ' ')->title()->toString()]
                    )),
            ])
            ->defaultSort('data_abertura', 'desc')
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
