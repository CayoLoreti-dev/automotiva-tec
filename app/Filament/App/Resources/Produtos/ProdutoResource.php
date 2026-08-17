<?php

namespace App\Filament\App\Resources\Produtos;

use App\Filament\App\Resources\Produtos\Pages\CreateProduto;
use App\Filament\App\Resources\Produtos\Pages\EditProduto;
use App\Filament\App\Resources\Produtos\Pages\ListProdutos;
use App\Filament\App\Resources\Produtos\Schemas\ProdutoForm;
use App\Filament\App\Resources\Produtos\Tables\ProdutosTable;
use App\Models\Produto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProdutoResource extends Resource
{
    protected static ?string $model = Produto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Cadastros';

    protected static ?string $recordTitleAttribute = 'nome';

    public static function form(Schema $schema): Schema
    {
        return ProdutoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProdutosTable::configure($table);
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
            'index' => ListProdutos::route('/'),
            'create' => CreateProduto::route('/create'),
            'edit' => EditProduto::route('/{record}/edit'),
        ];
    }
}
