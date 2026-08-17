<?php

namespace App\Filament\App\Resources\Comissaos;

use App\Filament\App\Resources\Comissaos\Pages\EditComissao;
use App\Filament\App\Resources\Comissaos\Pages\ListComissaos;
use App\Filament\App\Resources\Comissaos\Schemas\ComissaoForm;
use App\Filament\App\Resources\Comissaos\Tables\ComissaosTable;
use App\Models\Comissao;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ComissaoResource extends Resource
{
    protected static ?string $model = Comissao::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Financeiro';

    protected static ?string $navigationLabel = 'Comissoes';

    protected static ?string $modelLabel = 'Comissao';

    protected static ?string $pluralModelLabel = 'Comissoes';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return ComissaoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComissaosTable::configure($table);
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
            'index' => ListComissaos::route('/'),
            'edit' => EditComissao::route('/{record}/edit'),
        ];
    }
}
