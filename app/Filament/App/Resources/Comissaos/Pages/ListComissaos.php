<?php

namespace App\Filament\App\Resources\Comissaos\Pages;

use App\Filament\App\Resources\Comissaos\ComissaoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComissaos extends ListRecords
{
    protected static string $resource = ComissaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
