<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Pricing;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
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

                    Step::make('Customer Information')
                        ->schema([
                            Select::make('user_id')
                                ->relationship('student', 'email')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $user = User::find($state);
                                    $name = $user->name;
                                    $email = $user->email;
                                    $set('name', $name);
                                    $set('email', $email);
                                })
                                ->afterStateHydrated(function ($state, callable $set) {
                                    $userId = $state;
                                    if ($userId) {
                                        $user = User::find($userId);
                                        $name = $user->name;
                                        $email = $user->email;
                                        $set('name', $name);
                                        $set('email', $email);
                                    }
                                }),
                            TextInput::make('name')
                                ->required()
                                ->readOnly()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->required()
                                ->email()
                                ->readOnly()
                                ->maxLength(255),
                        ]),

                    Step::make('Payment Information')
                        ->schema([
                            ToggleButtons::make('is_paid')
                                ->label('Apakah Sudah Membayar?')
                                ->boolean()
                                ->grouped()
                                ->icons([
                                    'true' => 'heroicon-o-pencil',
                                    'false' => 'heroicon-o-clock',
                                ])
                                ->required(),

                            Select::make('payment_type')
                                ->options([
                                    'Midtrans' => 'Midtrans',
                                    'Manual' => 'Manual',
                                ])
                                ->required(),

                            FileUpload::make('proof')
                                ->label('Bukti Pembayaran')
                                ->image(),
                        ]),
                ])
                    ->columnSpan('full')
                    ->columns(1)
                    ->skippable(),
            ]);
    }
}
