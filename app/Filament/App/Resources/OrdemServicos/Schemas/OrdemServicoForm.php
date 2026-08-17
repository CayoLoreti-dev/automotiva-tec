<?php

namespace App\Filament\App\Resources\OrdemServicos\Schemas;

use App\Enums\OrdemServicoStatus;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Servico;
use App\Models\Veiculo;
use Filament\Facades\Filament;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class OrdemServicoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('cliente_id')
                    ->label('Cliente')
                    ->options(fn (): array => Cliente::query()
                        ->where('loja_id', Filament::getTenant()?->getKey())
                        ->orderBy('nome')
                        ->pluck('nome', 'id')
                        ->all())
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn (Set $set): mixed => $set('veiculo_id', null))
                    ->required(),
                Select::make('veiculo_id')
                    ->label('Veiculo')
                    ->options(fn (Get $get): array => Veiculo::query()
                        ->where('loja_id', Filament::getTenant()?->getKey())
                        ->when($get('cliente_id'), fn ($query, $clienteId) => $query->where('cliente_id', $clienteId))
                        ->orderBy('placa')
                        ->get()
                        ->mapWithKeys(fn (Veiculo $veiculo): array => [
                            $veiculo->id => "{$veiculo->placa} - {$veiculo->modelo}",
                        ])
                        ->all())
                    ->searchable()
                    ->required(),
                Select::make('funcionario_id')
                    ->label('Funcionario')
                    ->options(fn (): array => Funcionario::query()
                        ->where('loja_id', Filament::getTenant()?->getKey())
                        ->where('ativo', true)
                        ->orderBy('nome')
                        ->pluck('nome', 'id')
                        ->all())
                    ->searchable(),
                Select::make('status')
                    ->options(collect(OrdemServicoStatus::cases())->mapWithKeys(
                        fn (OrdemServicoStatus $status): array => [$status->value => str($status->value)->replace('_', ' ')->title()->toString()]
                    ))
                    ->default(OrdemServicoStatus::Aguardando->value)
                    ->required(),
                TextInput::make('valor_total')
                    ->numeric()
                    ->prefix('R$')
                    ->disabled()
                    ->dehydrated(false),
                Repeater::make('itens')
                    ->relationship()
                    ->schema([
                        Select::make('servico_id')
                            ->label('Servico')
                            ->options(fn (): array => Servico::query()
                                ->where('loja_id', Filament::getTenant()?->getKey())
                                ->where('ativo', true)
                                ->orderBy('nome')
                                ->pluck('nome', 'id')
                                ->all())
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?int $state): void {
                                if (! $state) {
                                    return;
                                }

                                $preco = Servico::query()->whereKey($state)->value('preco');

                                if ($preco !== null) {
                                    $set('preco_unitario', $preco);
                                }
                            })
                            ->required(),
                        TextInput::make('preco_unitario')
                            ->numeric()
                            ->required()
                            ->prefix('R$'),
                        TextInput::make('quantidade')
                            ->numeric()
                            ->integer()
                            ->default(1)
                            ->minValue(1)
                            ->required(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Textarea::make('observacoes')
                    ->label('Observacoes')
                    ->columnSpanFull(),
            ]);
    }
}
