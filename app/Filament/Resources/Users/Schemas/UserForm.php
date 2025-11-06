<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

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
                    ->required()
                    ->maxLength(255)
                    ->email(),
                TextInput::make('password')
                    ->helperText('At least 9 characters.')
                    ->required()
                    ->minLength(9)
                    ->maxLength(255)
                    ->password(),
                Select::make('occupation')
                    ->options([
                        'Developer' => 'Developer',
                        'Designer' => 'Designer',
                        'Project Manager' => 'Project Manager',
                    ])
                    ->required(),
                Select::make('roles')
                    ->label('Role')
                    ->preload()
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->required(),
                FileUpload::make('photo')
                    ->image()
                    ->required(),
            ]);
    }
}
