<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Fieldset;

class CourseForm
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
                        FileUpload::make('thumbnail')
                            ->image()
                            ->required(),
                    ]),
                Fieldset::make('Additional')
                    ->schema([
                        Repeater::make('benefits')
                            ->relationship('benefits')
                            ->schema([
                                TextInput::make('name')
                                    ->required(),
                                Textarea::make('about')
                                    ->required(),
                                Select::make('is_popular')
                                    ->options([
                                        true => 'Popular',
                                        false => 'Not Popular',
                                    ])
                                    ->required(),
                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ])
                    ]),
            ])
            ->columns(1);
    }
}
