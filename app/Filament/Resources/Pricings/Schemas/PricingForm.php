<?php

namespace App\Filament\Resources\Pricings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;

class PricingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Details')
                    ->schema([
                        TextInput::make('name')
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('IDR'),
                        TextInput::make('duration')
                            ->required()
                            ->numeric()
                            ->prefix('Month'),
                    ]),
            ])
            ->columns(1);
    }
}
