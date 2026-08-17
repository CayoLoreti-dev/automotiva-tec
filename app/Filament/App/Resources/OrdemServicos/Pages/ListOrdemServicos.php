<?php

namespace App\Filament\App\Resources\OrdemServicos\Pages;

use App\Filament\App\Resources\OrdemServicos\OrdemServicoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrdemServicos extends ListRecords
{
    protected static string $resource = OrdemServicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
