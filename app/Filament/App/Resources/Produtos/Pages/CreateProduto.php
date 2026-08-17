<?php

namespace App\Filament\App\Resources\Produtos\Pages;

use App\Filament\App\Resources\Produtos\ProdutoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduto extends CreateRecord
{
    protected static string $resource = ProdutoResource::class;
}
