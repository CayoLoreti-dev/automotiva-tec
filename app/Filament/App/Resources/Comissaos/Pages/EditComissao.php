<?php

namespace App\Filament\App\Resources\Comissaos\Pages;

use App\Filament\App\Resources\Comissaos\ComissaoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComissao extends EditRecord
{
    protected static string $resource = ComissaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
