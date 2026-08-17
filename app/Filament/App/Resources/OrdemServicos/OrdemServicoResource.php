<?php

namespace App\Filament\App\Resources\OrdemServicos;

use App\Filament\App\Resources\OrdemServicos\Pages\CreateOrdemServico;
use App\Filament\App\Resources\OrdemServicos\Pages\EditOrdemServico;
use App\Filament\App\Resources\OrdemServicos\Pages\ListOrdemServicos;
use App\Filament\App\Resources\OrdemServicos\Schemas\OrdemServicoForm;
use App\Filament\App\Resources\OrdemServicos\Tables\OrdemServicosTable;
use App\Models\OrdemServico;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class OrdemServicoResource extends Resource
{
    protected static ?string $model = OrdemServico::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Operacao';

    protected static ?string $navigationLabel = 'Ordens de Servico';

    protected static ?string $modelLabel = 'Ordem de Servico';

    protected static ?string $pluralModelLabel = 'Ordens de Servico';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return OrdemServicoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrdemServicosTable::configure($table);
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
            'index' => ListOrdemServicos::route('/'),
            'create' => CreateOrdemServico::route('/create'),
            'edit' => EditOrdemServico::route('/{record}/edit'),
        ];
    }
}
