<?php

namespace App\Filament\Master\Resources\Lojas\Pages;

use App\Filament\Master\Resources\Lojas\LojaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoja extends EditRecord
{
    protected static string $resource = LojaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
