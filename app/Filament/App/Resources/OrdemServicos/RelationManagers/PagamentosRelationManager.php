<?php

namespace App\Filament\App\Resources\OrdemServicos\RelationManagers;

use App\Enums\FormaPagamento;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagamentosRelationManager extends RelationManager
{
    protected static string $relationship = 'pagamentos';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('forma_pagamento')
                    ->label('Forma de pagamento')
                    ->options(collect(FormaPagamento::cases())->mapWithKeys(
                        fn (FormaPagamento $forma): array => [$forma->value => str($forma->value)->replace('_', ' ')->title()->toString()]
                    ))
                    ->required(),
                TextInput::make('valor')
                    ->numeric()
                    ->required()
                    ->prefix('R$'),
                DateTimePicker::make('data_pagamento')
                    ->default(now())
                    ->required(),
                Textarea::make('observacoes')
                    ->label('Observacoes')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('forma_pagamento')
                    ->badge()
                    ->formatStateUsing(fn (FormaPagamento $state): string => str($state->value)->replace('_', ' ')->title()->toString()),
                TextColumn::make('valor')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('data_pagamento')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(fn (array $data): array => [
                        ...$data,
                        'loja_id' => $this->getOwnerRecord()->loja_id,
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
