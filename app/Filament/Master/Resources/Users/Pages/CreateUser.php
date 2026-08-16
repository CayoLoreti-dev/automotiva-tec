<?php

namespace App\Filament\Master\Resources\Users\Pages;

use App\Filament\Master\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
