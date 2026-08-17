<?php

namespace App\Filament\App\Resources\Clientes\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VeiculosRelationManager extends RelationManager
{
    protected static string $relationship = 'veiculos';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('placa')
            ->columns([
                TextColumn::make('placa')
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
