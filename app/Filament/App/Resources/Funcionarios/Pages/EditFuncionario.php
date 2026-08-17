<?php

namespace App\Filament\App\Resources\Funcionarios\Pages;

use App\Filament\App\Resources\Funcionarios\FuncionarioResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFuncionario extends EditRecord
{
    protected static string $resource = FuncionarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
