<?php

namespace App\Filament\App\Resources\Funcionarios;

use App\Filament\App\Resources\Funcionarios\Pages\CreateFuncionario;
use App\Filament\App\Resources\Funcionarios\Pages\EditFuncionario;
use App\Filament\App\Resources\Funcionarios\Pages\ListFuncionarios;
use App\Filament\App\Resources\Funcionarios\Schemas\FuncionarioForm;
use App\Filament\App\Resources\Funcionarios\Tables\FuncionariosTable;
use App\Models\Funcionario;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FuncionarioResource extends Resource
{
    protected static ?string $model = Funcionario::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Operacao';

    protected static ?string $recordTitleAttribute = 'nome';

    public static function form(Schema $schema): Schema
    {
        return FuncionarioForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FuncionariosTable::configure($table);
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
            'index' => ListFuncionarios::route('/'),
            'create' => CreateFuncionario::route('/create'),
            'edit' => EditFuncionario::route('/{record}/edit'),
        ];
    }
}
