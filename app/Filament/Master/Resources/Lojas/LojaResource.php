<?php

namespace App\Filament\Master\Resources\Lojas;

use App\Filament\Master\Resources\Lojas\Pages\CreateLoja;
use App\Filament\Master\Resources\Lojas\Pages\EditLoja;
use App\Filament\Master\Resources\Lojas\Pages\ListLojas;
use App\Filament\Master\Resources\Lojas\Schemas\LojaForm;
use App\Filament\Master\Resources\Lojas\Tables\LojasTable;
use App\Models\Loja;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LojaResource extends Resource
{
    protected static ?string $model = Loja::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nome';

    public static function form(Schema $schema): Schema
    {
        return LojaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LojasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLojas::route('/'),
            'create' => CreateLoja::route('/create'),
            'edit' => EditLoja::route('/{record}/edit'),
        ];
    }
}
