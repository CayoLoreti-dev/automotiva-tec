<?php

namespace App\Filament\App\Resources\Produtos\Pages;

use App\Filament\App\Resources\Produtos\ProdutoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduto extends EditRecord
{
    protected static string $resource = ProdutoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
