<?php

namespace App\Filament\Master\Resources\Users\Schemas;

use App\Enums\LojaUserRole;
use App\Models\Loja;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
                Toggle::make('is_super_admin')
                    ->label('Super admin'),
                Repeater::make('loja_assignments')
                    ->label('Lojas')
                    ->dehydrated(false)
                    ->afterStateHydrated(function (Repeater $component, ?Model $record): void {
                        if (! $record) {
                            return;
                        }

                        $component->state(
                            $record->lojas
                                ->map(fn ($loja): array => [
                                    'loja_id' => $loja->getKey(),
                                    'role' => $loja->pivot->role,
                                ])
                                ->all()
                        );
                    })
                    ->saveRelationshipsUsing(function (Model $record, ?array $state): void {
                        $record->lojas()->sync(
                            collect($state ?? [])
                                ->filter(fn (array $assignment): bool => filled($assignment['loja_id'] ?? null))
                                ->mapWithKeys(fn (array $assignment): array => [
                                    $assignment['loja_id'] => [
                                        'role' => $assignment['role'] ?? LojaUserRole::Funcionario->value,
                                    ],
                                ])
                                ->all()
                        );
                    })
                    ->schema([
                        Select::make('loja_id')
                            ->options(fn (): array => Loja::query()->orderBy('nome')->pluck('nome', 'id')->all())
                            ->searchable()
                            ->required(),
                        Select::make('role')
                            ->options(collect(LojaUserRole::cases())->mapWithKeys(
                                fn (LojaUserRole $role): array => [$role->value => ucfirst($role->value)]
                            ))
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
