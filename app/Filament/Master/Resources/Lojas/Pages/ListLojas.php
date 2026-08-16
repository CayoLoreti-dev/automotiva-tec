<?php

namespace App\Filament\Master\Resources\Lojas\Pages;

use App\Filament\Master\Resources\Lojas\LojaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLojas extends ListRecords
{
    protected static string $resource = LojaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
