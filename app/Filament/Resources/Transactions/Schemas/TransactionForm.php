<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Pricing;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Product and Price')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Select::make('pricing_id')
                                        ->relationship('pricing', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $pricing = Pricing::find($state);

                                            $price = $pricing->price;
                                            $duration = $pricing->duration;

                                            $subtotal = $price * $state;
                                            $totalPpn = $subtotal * 0.11;
                                            $totalAmount = $subtotal + $totalPpn;

                                            $set('total_tax_amount', $totalPpn);
                                            $set('grand_total_amount', $totalAmount);
                                            $set('sub_total_amount', $price);
                                            $set('duration', $duration);
                                        })
                                        ->afterStateHydrated(function ($state, callable $set) {
                                            $pricingId = $state;
                                            if ($pricingId) {
                                                $pricing = Pricing::find($pricingId);

                                                $duration = $pricing->duration;
                                                $set('duration', $duration);
                                            }
                                        }),

                                    TextInput::make('duration')
                                        ->numeric()
                                        ->readOnly()
                                        ->required()
                                        ->prefix('Months'),
                                ]),

                            Grid::make(3)
                                ->schema([
                                    TextInput::make('sub_total_amount')
                                        ->numeric()
                                        ->readOnly()
                                        ->required()
                                        ->prefix('Rp'),

                                    TextInput::make('total_tax_amount')
                                        ->numeric()
                                        ->readOnly()
                                        ->required()
                                        ->prefix('Rp'),

                                    TextInput::make('grand_total_amount')
                                        ->numeric()
                                        ->readOnly()
                                        ->required()
                                        ->prefix('Rp')
                                        ->helperText('Harga sudah termasuk PPN 11%'),
                                ]),

                            Grid::make(2)
                                ->schema([
                                    DatePicker::make('started_at')
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            $duration = $get('duration');
                                            if ($state && $duration) {
                                                $endedAt = \Carbon\Carbon::parse($state)->addMonths($duration);
                                                $set('ended_at', $endedAt->format('Y-m-d'));
                                            }
                                        }),

                                    DatePicker::make('ended_at')
                                        ->required()
                                        ->readOnly(),
                                ]),
                        ]),

                    Step::make('Delivery')
                        ->schema([
                            // ...
                        ]),

                    Step::make('Billing')
                        ->schema([
                            // ...
                        ]),
                ])
                    ->columnSpan('full')
                    ->columns(1)
                    ->skippable(),
            ]);
    }
}
