<?php

namespace App\Filament\App\Resources\Servicos;

use App\Filament\App\Resources\Servicos\Pages\CreateServico;
use App\Filament\App\Resources\Servicos\Pages\EditServico;
use App\Filament\App\Resources\Servicos\Pages\ListServicos;
use App\Filament\App\Resources\Servicos\Schemas\ServicoForm;
use App\Filament\App\Resources\Servicos\Tables\ServicosTable;
use App\Models\Servico;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ServicoResource extends Resource
{
    protected static ?string $model = Servico::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Cadastros';

    protected static ?string $recordTitleAttribute = 'nome';

    public static function form(Schema $schema): Schema
    {
        return ServicoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicosTable::configure($table);
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
            'index' => ListServicos::route('/'),
            'create' => CreateServico::route('/create'),
            'edit' => EditServico::route('/{record}/edit'),
        ];
    }
}
